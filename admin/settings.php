<?php
// admin/settings.php
require_once __DIR__ . '/../includes/init.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) die("CSRF token inválido.");
    $key = trim($_POST['key'] ?? '');
    $value = trim($_POST['value'] ?? '');
    if ($key) {
        $stmt = $pdo->prepare("INSERT INTO tenant_settings (tenant_id, key_name, value, created_at) VALUES (NULL,:k,:v,NOW()) ON DUPLICATE KEY UPDATE value=:v2, updated_at=NOW()");
        $stmt->execute([':k'=>$key, ':v'=>$value, ':v2'=>$value]);
        $msg = "Guardado.";
    }
}
$rows = $pdo->prepare("SELECT key_name,value FROM tenant_settings WHERE tenant_id IS NULL");
$rows->execute();
$settings = $rows->fetchAll();
include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Configuración Global</h3>
  <?php if (!empty($msg)): ?><div class="alert"><?= sanitize($msg) ?></div><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <input name="key" placeholder="Clave" required>
    <input name="value" placeholder="Valor" required>
    <button type="submit">Guardar</button>
  </form>
  <ul><?php foreach ($settings as $s): ?><li><?= sanitize($s['key_name']) ?> = <?= sanitize($s['value']) ?></li><?php endforeach; ?></ul>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>