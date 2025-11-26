<?php
// public/dashboard/products.php - tenant product CRUD list + link to edit/create
require_once __DIR__ . '/../../includes/init.php';
require_login();
$user = current_user($pdo);
if (empty($user['tenant_id'])) die("No administras una tienda.");
$tenant_id = $user['tenant_id'];

// list
$stmt = $pdo->prepare("SELECT * FROM products WHERE tenant_id = :t ORDER BY created_at DESC");
$stmt->execute([':t'=>$tenant_id]);
$products = $stmt->fetchAll();

include_once __DIR__ . '/../../header.php';
?>
<div class="container">
  <h3>Productos - <?= sanitize($tenant_id) ?></h3>
  <a href="/public/product_edit.php">Crear producto</a>
  <table>
    <tr><th>ID</th><th>Título</th><th>Precio</th><th>Inventario</th><th>Acciones</th></tr>
    <?php foreach ($products as $p): ?>
      <tr>
        <td><?= (int)$p['id'] ?></td>
        <td><?= sanitize($p['title']) ?></td>
        <td><?= number_format($p['price'],2) ?></td>
        <td><?= (int)$p['inventory'] ?></td>
        <td>
          <a href="/public/product_edit.php?id=<?= (int)$p['id'] ?>">Editar</a> |
          <form method="post" action="/public/product_delete.php" style="display:inline">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
            <button type="submit">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php include_once __DIR__ . '/../../footer.php'; ?>