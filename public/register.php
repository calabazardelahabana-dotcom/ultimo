<?php
// public/register.php
require_once __DIR__ . '/../includes/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) { $error = "CSRF inválido."; }
    else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if (!$username || !$email || !$password) $error = "Completa usuario, email y contraseña.";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = "Email inválido.";
        else {
            $s = $pdo->prepare("SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1");
            $s->execute([':u'=>$username, ':e'=>$email]);
            if ($s->fetch()) $error = "Usuario o email ya registrado.";
            else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $ins = $pdo->prepare("INSERT INTO users (username, email, password_hash, is_active, created_at) VALUES (:u,:e,:h,1,NOW())");
                $ins->execute([':u'=>$username, ':e'=>$email, ':h'=>$hash]);
                $user_id = $pdo->lastInsertId();
                $r = $pdo->prepare("SELECT id FROM roles WHERE slug='customer' LIMIT 1"); $r->execute(); $role = $r->fetch();
                if ($role) $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:uid,:rid)")->execute([':uid'=>$user_id, ':rid'=>$role['id']]);
                // auto-login
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = 'customer';
                header('Location: /public/dashboard/index.php');
                exit;
            }
        }
    }
}

include_once __DIR__ . '/../header.php';
?>
<div class="container">
  <h3>Registrarse</h3>
  <?php if (!empty($error)): ?><div class="alert alert-danger"><?= sanitize($error) ?></div><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <input name="username" placeholder="Usuario" required>
    <input name="email" placeholder="Email" required type="email">
    <input name="password" type="password" placeholder="Contraseña" required>
    <button type="submit">Registrar</button>
  </form>
</div>
<?php include_once __DIR__ . '/../footer.php'; ?>