<?php
// admin/themes.php - upload/delete themes (superadmin)
require_once __DIR__ . '/../includes/init.php';
require_login();
if (!is_superadmin()) die("Acceso restringido");
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) die("CSRF token inválido.");
    if (!empty($_FILES['theme_zip']) && $_POST['action'] === 'upload') {
        $f = $_FILES['theme_zip'];
        if ($f['error'] === UPLOAD_ERR_OK && pathinfo($f['name'], PATHINFO_EXTENSION) === 'zip') {
            $name = pathinfo($f['name'], PATHINFO_FILENAME);
            $slug = preg_replace('/[^a-z0-9\-]/i','-', strtolower($name));
            $targetDir = __DIR__ . "/../themes/{$slug}";
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            $tmp = $f['tmp_name'];
            $za = new ZipArchive();
            if ($za->open($tmp) === true) {
                $za->extractTo($targetDir);
                $za->close();
                $message = "Theme {$slug} instalado.";
            } else $message = "No se pudo extraer zip.";
        } else $message = "Sube un .zip válido.";
    } elseif ($_POST['action'] === 'delete' && !empty($_POST['theme'])) {
        $theme = preg_replace('/[^a-z0-9\-]/i','', $_POST['theme']);
        if ($theme !== 'default') {
            $dir = __DIR__ . "/../themes/{$theme}";
            if (is_dir($dir)) {
                $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($files as $file) {
                    $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
                }
                rmdir($dir);
                $message = "Theme eliminado.";
            }
        } else $message = "No se puede eliminar default.";
    }
}
$themes = get_available_themes();
include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Gestión de Themes</h3>
  <?php if ($message): ?><div class="alert"><?= sanitize($message) ?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="upload">
    <input type="file" name="theme_zip" accept=".zip" required>
    <button type="submit">Subir</button>
  </form>
  <ul><?php foreach ($themes as $t): ?><li><?= sanitize($t) ?> <?php if ($t !== 'default'): ?><form method="post" style="display:inline"><?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="theme" value="<?= sanitize($t) ?>"><button type="submit">Eliminar</button></form><?php endif; ?></li><?php endforeach; ?></ul>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>