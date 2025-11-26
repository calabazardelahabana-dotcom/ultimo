<?php
// admin/tenants.php
require_once __DIR__ . '/../includes/init.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");
$tenants = $pdo->query("SELECT * FROM tenants ORDER BY created_at DESC")->fetchAll();
include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Tenants</h3>
  <ul>
    <?php foreach ($tenants as $t): ?>
      <li><?= sanitize($t['name']) ?> (<?= sanitize($t['slug']) ?>) - <?= sanitize($t['payment_provider'] ?? '') ?> <?= sanitize($t['payment_account_id'] ?? '') ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>