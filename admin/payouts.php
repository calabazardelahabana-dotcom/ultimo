<?php
// admin/payouts.php - overview
require_once __DIR__ . '/../includes/init.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");
$payouts = $pdo->query("SELECT p.*, t.name AS tenant_name FROM payouts p LEFT JOIN tenants t ON t.id = p.tenant_id ORDER BY p.created_at DESC LIMIT 200")->fetchAll();
include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Payouts</h3>
  <table>
    <tr><th>ID</th><th>Tenant</th><th>Amount</th><th>Status</th><th>Stripe Transfer</th><th>Created</th></tr>
    <?php foreach ($payouts as $p): ?>
      <tr>
        <td><?= (int)$p['id'] ?></td>
        <td><?= sanitize($p['tenant_name']) ?></td>
        <td><?= number_format($p['requested_amount'],2) ?> <?= sanitize($p['currency']) ?></td>
        <td><?= sanitize($p['status']) ?></td>
        <td><?= sanitize($p['stripe_transfer_id'] ?? '') ?></td>
        <td><?= sanitize($p['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php include_once __DIR__ . '/../footer.php']; ?>