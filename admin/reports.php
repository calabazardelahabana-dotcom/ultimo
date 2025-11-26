<?php
// admin/reports.php
require_once __DIR__ . '/../includes/init.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");
$totalSales = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status='paid'")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$tenantsCount = $pdo->query("SELECT COUNT(*) FROM tenants")->fetchColumn();
include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Reportes</h3>
  <p>Ventas totales: <?= number_format($totalSales,2) ?></p>
  <p>Pedidos: <?= (int)$totalOrders ?></p>
  <p>Tiendas: <?= (int)$tenantsCount ?></p>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>