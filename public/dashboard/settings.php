<?php
// public/dashboard/settings.php - tenant settings: theme selector and logo upload
require_once __DIR__ . '/../../includes/init.php';
require_login();
$user = current_user($pdo);
if (empty($user['tenant_id'])) die("No administras una tienda.");
$tenant_id = $user['tenant_id'];

$tenantStmt = $pdo->prepare("SELECT * FROM tenants WHERE id = :id LIMIT 1"); $tenantStmt->execute([':id'=>$tenant_id]); $tenant = $tenantStmt->fetch();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) die("CSRF inválido.");
    $theme = preg_replace('/[^a-z0-9\-]/i','', $_POST['theme'] ?? 'default');
    $up = $pdo->prepare("INSERT INTO tenant_settings (tenant_id, key_name, value, created_at) VALUES (:tid,'theme',:v,NOW()) ON DUPLICATE KEY UPDATE value=:v2, updated_at=NOW()");
    $up->execute([':tid'=>$tenant_id, ':v'=>$theme, ':v2'=>$theme]);
    // logo upload
    if (!empty($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        require_once __DIR__ . '/../../includes/image_utils.php';
        $res = image_save_upload('logo', $tenant_id);
        if ($res) {
            $url = '/public/serve_image.php?tenant='.intval($tenant_id).'&file='.urlencode($res['name']);
            $s = $pdo->prepare("INSERT INTO tenant_settings (tenant_id, key_name, value, created_at) VALUES (:tid,'logo',:v,NOW()) ON DUPLICATE KEY UPDATE value=:v2, updated_at=NOW()");
            $s->execute([':tid'=>$tenant_id, ':v'=>$url, ':v2'=>$url]);
            $message = "Logo subido.";
        } else $message = "Error subiendo logo.";
    } else $message = "Tema guardado.";
}

$themes = get_available_themes();
$current = get_tenant_theme($pdo, $tenant_id);
include_once __DIR__ . '/../../header.php';
?>
<div class="container">
  <h3>Configuración de tienda</h3>
  <?php if ($message): ?><div class="alert"><?= sanitize($message) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <label>Tema</label>
    <select name="theme">
      <?php foreach ($themes as $t): ?>
        <option value="<?= sanitize($t) ?>" <?= $t === $current ? 'selected' : '' ?>><?= sanitize($t) ?></option>
      <?php endforeach; ?>
    </select>
    <div>
      <label>Logo</label>
      <input type="file" name="logo" accept="image/*">
    </div>
    <button type="submit">Guardar</button>
  </form>
  <hr>
  <p>Previsualizar tema: <a href="/themes/<?= urlencode($current) ?>/">Abrir</a></p>
</div>
<?php include_once __DIR__ . '/../../footer.php'; ?>