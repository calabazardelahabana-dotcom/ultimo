<?php
// admin/orders_view.php
require_once __DIR__ . '/../includes/init.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");
$page = max(1,intval($_GET['page']??1));
$per = 30; $start = ($page-1)*$per;
$stmt = $pdo->prepare("SELECT o.*, t.name AS tenant_name, u.username AS user_name FROM orders o LEFT JOIN tenants t ON t.id=o.tenant_id LEFT JOIN users u ON u.id=o.user_id ORDER BY o.created_at DESC LIMIT :s,:n");
$stmt->bindValue(':s',$start,PDO::PARAM_INT);
$stmt->bindValue(':n',$per,PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll();
include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Pedidos</h3>
  <table>
    <tr><th>ID</th><th>Tenant</th><th>User</th><th>Total</th><th>Status</th><th>Created</th><th>Acciones</th></tr>
    <?php foreach ($orders as $o): ?>
      <tr>
        <td><?= (int)$o['id'] ?></td>
        <td><?= sanitize($o['tenant_name']) ?></td>
        <td><?= sanitize($o['user_name']) ?></td>
        <td><?= number_format($o['total'],2) ?></td>
        <td><?= sanitize($o['status']) ?></td>
        <td><?= sanitize($o['created_at']) ?></td>
        <td><a href="/admin/orders_view.php?view=<?= (int)$o['id'] ?>">Ver</a> | <a href="/public/invoice_generate.php?order=<?= (int)$o['id'] ?>" target="_blank">Factura</a></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>