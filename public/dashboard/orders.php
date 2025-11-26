<?php
// public/dashboard/orders.php - list orders for tenant
require_once __DIR__ . '/../../includes/init.php';
require_login();
$user = current_user($pdo);
if (empty($user['tenant_id'])) die("No administras una tienda.");
$tenant_id = $user['tenant_id'];

$stmt = $pdo->prepare("SELECT * FROM orders WHERE tenant_id = :t ORDER BY created_at DESC");
$stmt->execute([':t'=>$tenant_id]);
$orders = $stmt->fetchAll();

include_once __DIR__ . '/../../header.php';
?>
<div class="container">
  <h3>Pedidos</h3>
  <?php foreach ($orders as $o): ?>
    <div style="border:1px solid #ddd;padding:8px;margin:6px 0">
      <strong>Pedido #<?= (int)$o['id'] ?></strong> — <?= sanitize($o['status']) ?> — <?= sanitize($o['created_at']) ?>
      <div>Total: <?= number_format($o['total'],2) ?> <?= sanitize($o['currency']) ?></div>
      <a href="/public/invoice_generate.php?order=<?= (int)$o['id'] ?>">Generar factura</a>
    </div>
  <?php endforeach; ?>
</div>
<?php include_once __DIR__ . '/../../footer.php'; ?>