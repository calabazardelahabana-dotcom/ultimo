#!/usr/bin/env php
<?php
// cron/payouts_processor.php - process requested payouts (limit per run)
require __DIR__ . '/../includes/db.php';
require __DIR__ . '/../includes/stripe_payouts.php';
$limit = 10;
$stmt = $pdo->prepare("SELECT p.*, t.payment_account_id FROM payouts p LEFT JOIN tenants t ON t.id = p.tenant_id WHERE p.status = 'requested' ORDER BY p.created_at ASC LIMIT :n");
$stmt->bindValue(':n', $limit, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll();
foreach ($items as $it) {
    try {
        $pdo->beginTransaction();
        $pdo->prepare("UPDATE payouts SET status='processing', updated_at=NOW() WHERE id = :id")->execute([':id'=>$it['id']]);
        if (empty($it['payment_account_id'])) throw new Exception("Tenant {$it['tenant_id']} has no connected account");
        $resp = stripe_create_transfer_to_connected($it['payment_account_id'], intval($it['amount_cents']), strtolower($it['currency']), "Auto payout {$it['id']}");
        if ($resp['status'] >= 200 && $resp['status'] < 300 && !empty($resp['body']['id'])) {
            $pdo->prepare("UPDATE payouts SET stripe_transfer_id = :tid, status='transferred', updated_at=NOW() WHERE id = :id")->execute([':tid'=>$resp['body']['id'], ':id'=>$it['id']]);
            $pdo->commit();
        } else {
            $pdo->rollBack();
            $pdo->prepare("UPDATE payouts SET status='failed', notes = :n, updated_at=NOW() WHERE id = :id")->execute([':n'=>json_encode($resp), ':id'=>$it['id']]);
        }
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $pdo->prepare("UPDATE payouts SET status='failed', notes = :n, updated_at=NOW() WHERE id = :id")->execute([':n'=>$e->getMessage(), ':id'=>$it['id']]);
    }
}
echo "Processed " . count($items) . " payouts\n";