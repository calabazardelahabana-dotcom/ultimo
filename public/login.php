<?php
// public/login.php
require_once __DIR__ . '/../includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) { $error = "CSRF inválido."; }
    else {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        if (!$u || !$p) $error = "Completa usuario y contraseña.";
        else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u OR email = :u LIMIT 1");
            $stmt->execute([':u'=>$u]);
            $user = $stmt->fetch();
            if ($user && !empty($user['password_hash']) && password_verify($p, $user['password_hash']) && $user['is_active']) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                // cargar rol
                $r = $pdo->prepare("SELECT r.slug FROM roles r JOIN user_roles ur ON ur.role_id=r.id WHERE ur.user_id=:uid LIMIT 1");
                $r->execute([':uid'=>$user['id']]);
                $role = $r->fetch();
                $_SESSION['role'] = $role ? $role['slug'] : 'customer';
                header('Location: /public/dashboard/index.php');
                exit;
            } else $error = "Credenciales inválidas.";
        }
    }
}

include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Iniciar sesión</h3>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
  <form method="post" action="/public/login.php">
    <?= csrf_field() ?>
    <input name="username" placeholder="Usuario o email" required>
    <input name="password" type="password" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
  </form>
  <p><a href="/public/password_reset.php">Olvidé mi contraseña</a></p>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>