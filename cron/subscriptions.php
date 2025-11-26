#!/usr/bin/env php
<?php
// cron/subscriptions.php - run daily, update subscription statuses
require __DIR__ . '/../includes/db.php';
date_default_timezone_set('UTC');

$stmt = $pdo->query("SELECT * FROM subscriptions WHERE status IN ('trialing','active','past_due')");
$now = new DateTime();
foreach ($stmt->fetchAll() as $s) {
    if (!empty($s['current_period_end'])) {
        $end = new DateTime($s['current_period_end']);
        if ($end < $now && $s['status'] === 'active') {
            $pdo->prepare("UPDATE subscriptions SET status='past_due', updated_at=NOW() WHERE id = :id")->execute([':id'=>$s['id']]);
        } elseif ($end < $now && $s['status'] === 'trialing') {
            $pdo->prepare("UPDATE subscriptions SET status='cancelled', updated_at=NOW() WHERE id = :id")->execute([':id'=>$s['id']]);
        }
    }
}
echo "Subscriptions cron completed\n";