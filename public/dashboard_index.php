<?php
// public/dashboard/index.php - tenant / user dashboard
require_once __DIR__ . '/../../includes/init.php';
require_login();
$user = current_user($pdo);
$tenant = null;
if (!empty($user['tenant_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tenants WHERE id = :id LIMIT 1"); $stmt->execute([':id'=>$user['tenant_id']]); $tenant = $stmt->fetch();
}

include_once __DIR__ . '/../../header.php';
?>
<div class="container">
  <h3>Dashboard</h3>
  <p>Bienvenido, <?= sanitize($user['username'] ?? $user['email']) ?></p>
  <?php if ($tenant): ?>
    <p>Administrando tienda: <?= sanitize($tenant['name']) ?></p>
    <ul>
      <li><a href="/public/dashboard/products.php">Productos</a></li>
      <li><a href="/public/dashboard/orders.php">Pedidos</a></li>
      <li><a href="/public/dashboard/settings.php">Configuración</a></li>
      <li><a href="/public/dashboard/tickets.php">Soporte</a></li>
    </ul>
  <?php else: ?>
    <p>No administras una tienda. <a href="/public/create_store.php">Crear tienda</a></p>
  <?php endif; ?>
</div>
<?php include_once __DIR__ . '/../../footer.php'; ?>