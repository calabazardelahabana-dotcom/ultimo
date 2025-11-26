<?php
require_once __DIR__ . '/../includes/init.php';
require_login();
$user = current_user($pdo);
if (empty($user['tenant_id'])) die("No administras una tienda.");
$tenant_id = $user['tenant_id'];

$msg = '';
$isEdit = !empty($_GET['id']);
$data = [];
if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id AND tenant_id = :tid LIMIT 1");
    $stmt->execute([':id'=>$_GET['id'], ':tid'=>$tenant_id]);
    $data = $stmt->fetch() ?: [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image_url = trim($_POST['image_url']);
    if ($isEdit) {
        $up = $pdo->prepare("UPDATE products SET title=:t, price=:p, stock=:s, image_url=:img WHERE id=:id AND tenant_id=:tid");
        $up->execute([':t'=>$title, ':p'=>$price, ':s'=>$stock, ':img'=>$image_url, ':id'=>$_GET['id'], ':tid'=>$tenant_id]);
        $msg = 'Producto actualizado!';
    } else {
        $in = $pdo->prepare("INSERT INTO products (tenant_id, title, price, stock, image_url, created_at) VALUES (:t, :title, :price, :stock, :img, NOW())");
        $in->execute([':t'=>$tenant_id, ':title'=>$title, ':price'=>$price, ':stock'=>$stock, ':img'=>$image_url]);
        $msg = 'Producto creado!';
    }
    header('Location: /public/dashboard/products.php');
    exit;
}
include_once __DIR__ . '/../header.php';
?>
<div class="container">
    <h3><?= $isEdit ? "Editar" : "Crear" ?> producto</h3>
    <form method="post">
        <?= csrf_field() ?>
        <input name="title" placeholder="Título" value="<?= sanitize($data['title'] ?? '') ?>" required>
        <input name="price" placeholder="Precio" type="number" step="0.01" value="<?= sanitize($data['price'] ?? '') ?>" required>
        <input name="stock" placeholder="Stock" type="number" value="<?= sanitize($data['stock'] ?? '') ?>" required>
        <input name="image_url" placeholder="URL Imagen" value="<?= sanitize($data['image_url'] ?? '') ?>">
        <button type="submit"><?= $isEdit ? "Actualizar" : "Crear" ?></button>
    </form>
    <a href="/public/dashboard/products.php">Volver</a>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>