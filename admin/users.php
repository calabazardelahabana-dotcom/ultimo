<?php
// admin/users.php
require_once __DIR__ . '/../includes/init.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");
$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 200")->fetchAll();
include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Usuarios</h3>
  <table>
    <tr><th>ID</th><th>Usuario</th><th>Email</th><th>Tenant</th><th>Activo</th></tr>
    <?php foreach ($users as $u): ?>
    <tr>
      <td><?= (int)$u['id'] ?></td>
      <td><?= sanitize($u['username']) ?></td>
      <td><?= sanitize($u['email']) ?></td>
      <td><?= (int)$u['tenant_id'] ?></td>
      <td><?= $u['is_active'] ? 'Sí' : 'No' ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>