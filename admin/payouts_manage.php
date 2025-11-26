<?php
// admin/payouts_manage.php - request & process payouts
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/stripe_payouts.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) die("CSRF token inválido.");
    $action = $_POST['action'] ?? '';
    if ($action === 'request') {
        $tenant_id = intval($_POST['tenant_id']);
        $amount = floatval($_POST['amount']);
        if (!$tenant_id || $amount <= 0) $message = "Datos inválidos.";
        else {
            $stmt = $pdo->prepare("INSERT INTO payouts (tenant_id, requested_amount, amount_cents, currency, status, created_at) VALUES (:t,:amt,:cents,:cur,'requested',NOW())");
            $stmt->execute([':t'=>$tenant_id, ':amt'=>$amount, ':cents'=>intval(round($amount*100)), ':cur'=>strtoupper($_POST['currency'] ?? 'USD')]);
            $message = "Payout solicitado.";
        }
    } elseif ($action === 'process') {
        $payout_id = intval($_POST['payout_id']);
        $pstmt = $pdo->prepare("SELECT p.*, t.payment_account_id FROM payouts p LEFT JOIN tenants t ON t.id = p.tenant_id WHERE p.id = :id LIMIT 1");
        $pstmt->execute([':id'=>$payout_id]);
        $row = $pstmt->fetch();
        if (!$row) $message = "Payout no encontrado.";
        else {
            try {
                $pdo->beginTransaction();
                $pdo->prepare("UPDATE payouts SET status='processing', updated_at=NOW() WHERE id = :id")->execute([':id'=>$payout_id]);
                if (empty($row['payment_account_id'])) throw new Exception("Tenant sin cuenta conectada");
                $resp = stripe_create_transfer_to_connected($row['payment_account_id'], intval($row['amount_cents']), strtolower($row['currency']), "Payout {$payout_id}");
                if ($resp['status'] >=200 && $resp['status'] < 300 && !empty($resp['body']['id'])) {
                    $pdo->prepare("UPDATE payouts SET stripe_transfer_id = :tid, status='transferred', updated_at=NOW() WHERE id = :id")->execute([':tid'=>$resp['body']['id'], ':id'=>$payout_id]);
                    $pdo->commit();
                    $message = "Transfer creado: " . $resp['body']['id'];
                } else {
                    $pdo->rollBack();
                    $message = "Stripe error: " . json_encode($resp);
                }
            } catch (Exception $e) {
                if ($pdo->inTransaction()) $pdo->rollBack();
                $pdo->prepare("UPDATE payouts SET status='failed', notes = :n, updated_at=NOW() WHERE id = :id")->execute([':n'=>$e->getMessage(), ':id'=>$payout_id]);
                $message = "Error: " . $e->getMessage();
            }
        }
    }
}

$tenants = $pdo->query("SELECT id, name, slug, payment_account_id FROM tenants ORDER BY created_at DESC")->fetchAll();
$pending = $pdo->query("SELECT p.*, t.name AS tenant_name FROM payouts p LEFT JOIN tenants t ON t.id = p.tenant_id WHERE p.status IN ('requested','processing') ORDER BY p.created_at ASC")->fetchAll();

include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Gestionar Payouts</h3>
  <?php if ($message): ?><div class="alert"><?= sanitize($message) ?></div><?php endif; ?>
  <h4>Solicitar</h4>
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="request">
    <select name="tenant_id"><?php foreach ($tenants as $t): ?><option value="<?= (int)$t['id'] ?>"><?= sanitize($t['name']) ?></option><?php endforeach; ?></select>
    <input name="amount" type="number" step="0.01" required>
    <input name="currency" value="USD">
    <button type="submit">Solicitar</button>
  </form>

  <h4>Pendientes</h4>
  <?php foreach ($pending as $p): ?>
    <div style="border:1px solid #ddd;padding:8px;margin:6px 0">
      <strong><?= sanitize($p['tenant_name']) ?></strong> <?= number_format($p['requested_amount'],2) ?> <?= sanitize($p['currency']) ?> - <?= sanitize($p['status']) ?>
      <form method="post" style="display:inline">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="process">
        <input type="hidden" name="payout_id" value="<?= (int)$p['id'] ?>">
        <button type="submit">Procesar</button>
      </form>
    </div>
  <?php endforeach; ?>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>