<?php
// admin/campaigns.php
require_once __DIR__ . '/../includes/init.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) die("CSRF token inválido.");
    $title = trim($_POST['title'] ?? '');
    $html = trim($_POST['html'] ?? '');
    $s = $pdo->prepare("SELECT value FROM tenant_settings WHERE tenant_id IS NULL AND key_name='campaigns' LIMIT 1");
    $s->execute(); $row = $s->fetch();
    $campaigns = $row ? json_decode($row['value'], true) : [];
    $campaigns[] = ['title'=>$title, 'html'=>$html, 'created_at'=>date('c')];
    if ($row) {
        $pdo->prepare("UPDATE tenant_settings SET value=:v, updated_at=NOW() WHERE tenant_id IS NULL AND key_name='campaigns'")->execute([':v'=>json_encode($campaigns, JSON_UNESCAPED_UNICODE)]);
    } else {
        $pdo->prepare("INSERT INTO tenant_settings (tenant_id, key_name, value, created_at) VALUES (NULL,'campaigns',:v,NOW())")->execute([':v'=>json_encode($campaigns, JSON_UNESCAPED_UNICODE)]);
    }
    header('Location: /admin/campaigns.php'); exit;
}
$stmt = $pdo->prepare("SELECT value FROM tenant_settings WHERE tenant_id IS NULL AND key_name='campaigns' LIMIT 1");
$stmt->execute(); $row = $stmt->fetch();
$campaigns = $row ? json_decode($row['value'], true) : [];
include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Campañas</h3>
  <form method="post">
    <?= csrf_field() ?>
    <input name="title" placeholder="Título" required>
    <textarea name="html" placeholder="HTML" required></textarea>
    <button type="submit">Crear</button>
  </form>
  <hr>
  <?php foreach ($campaigns as $c): ?>
    <div><h4><?= sanitize($c['title']) ?></h4><div><?= $c['html'] ?></div><small><?= sanitize($c['created_at']) ?></small></div>
  <?php endforeach; ?>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>