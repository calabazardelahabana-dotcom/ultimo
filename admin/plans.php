<?php
// admin/plans.php - plans management (full)
require_once __DIR__ . '/../includes/init.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) die("CSRF token inválido.");
    $name = trim($_POST['name'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $interval = $_POST['interval'] ?? 'monthly';
    $stripe_price_id = trim($_POST['stripe_price_id'] ?? '');
    $slug = create_slug($name);
    $stmt = $pdo->prepare("INSERT INTO plans (name, slug, price, interval, features, created_at, stripe_price_id) VALUES (:n,:s,:p,:i,NULL,NOW(),:sp)");
    $stmt->execute([':n'=>$name, ':s'=>$slug, ':p'=>$price, ':i'=>$interval, ':sp'=>$stripe_price_id]);
    header('Location: /admin/plans.php'); exit;
}

$plans = $pdo->query("SELECT * FROM plans ORDER BY price ASC")->fetchAll();
include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Planes</h3>
  <form method="post">
    <?= csrf_field() ?>
    <input name="name" placeholder="Nombre" required>
    <input name="price" placeholder="Precio" type="number" step="0.01" required>
    <select name="interval"><option value="monthly">Mensual</option><option value="yearly">Anual</option></select>
    <input name="stripe_price_id" placeholder="Stripe Price ID (opcional)">
    <button type="submit">Crear plan</button>
  </form>
  <hr>
  <ul>
    <?php foreach ($plans as $p): ?>
      <li><?= sanitize($p['name']) ?> - <?= number_format($p['price'],2) ?> / <?= sanitize($p['interval']) ?> - Stripe: <?= sanitize($p['stripe_price_id'] ?? '') ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>