<?php
// login.php - Página de inicio de sesión en raíz
require_once __DIR__ . '/includes/init.php';

$error = '';
$pageTitle = 'Iniciar Sesión - MassolaCommerce';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) { 
        $error = "Token de seguridad inválido. Por favor intenta de nuevo."; 
    } else {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        
        if (!$u || !$p) {
            $error = "Por favor completa usuario y contraseña.";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE (username = :u OR email = :u) AND is_active = 1 AND deleted_at IS NULL LIMIT 1");
            $stmt->execute([':u' => $u]);
            $user = $stmt->fetch();
            
            if ($user && !empty($user['password_hash']) && password_verify($p, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                // Cargar rol
                $r = $pdo->prepare("SELECT r.slug FROM roles r JOIN user_roles ur ON ur.role_id = r.id WHERE ur.user_id = :uid LIMIT 1");
                $r->execute([':uid' => $user['id']]);
                $role = $r->fetch();
                $_SESSION['role'] = $role ? $role['slug'] : 'customer';
                
                // Actualizar last_login
                $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = :id")->execute([':id' => $user['id']]);
                
                // Registrar login exitoso
                log_login_attempt($u, true);
                
                // Redirigir al dashboard
                header('Location: /dashboard.php');
                exit;
            } else {
                $error = "Credenciales inválidas. Por favor verifica tu usuario y contraseña.";
                log_login_attempt($u, false, 'Credenciales inválidas');
            }
        }
    }
}

include_once __DIR__ . '/header.php';
?>

<div class="container" style="max-width: 500px; margin: 60px auto; padding: 40px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 30px; color: #333;">Iniciar Sesión</h2>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
            <i class="fas fa-exclamation-circle"></i> <?= sanitize($error) ?>
        </div>
    <?php endif; ?>
    
    <form method="post" action="/login.php" style="display: flex; flex-direction: column; gap: 20px;">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="username" style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">
                <i class="fas fa-user"></i> Usuario o Email
            </label>
            <input type="text" 
                   id="username"
                   name="username" 
                   placeholder="Ingresa tu usuario o email" 
                   required 
                   autofocus
                   value="<?= isset($_POST['username']) ? sanitize($_POST['username']) : '' ?>"
                   style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;">
        </div>
        
        <div class="form-group">
            <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">
                <i class="fas fa-lock"></i> Contraseña
            </label>
            <input type="password" 
                   id="password"
                   name="password" 
                   placeholder="Ingresa tu contraseña" 
                   required
                   style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;">
        </div>
        
        <button type="submit" 
                class="btn btn-primary" 
                style="width: 100%; padding: 15px; font-size: 18px; font-weight: 600; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: transform 0.2s;">
            <i class="fas fa-sign-in-alt"></i> Entrar
        </button>
    </form>
    
    <div style="margin-top: 30px; text-align: center; padding-top: 20px; border-top: 1px solid #e0e0e0;">
        <p style="color: #666; margin-bottom: 10px;">¿No tienes cuenta?</p>
        <a href="/register.php" class="btn btn-outline" style="display: inline-block; padding: 10px 30px; border: 2px solid #667eea; color: #667eea; text-decoration: none; border-radius: 8px; transition: all 0.3s;">
            <i class="fas fa-user-plus"></i> Crear Cuenta
        </a>
    </div>
    
    <div style="margin-top: 20px; text-align: center;">
        <a href="/password_reset.php" style="color: #667eea; text-decoration: none; font-size: 14px;">
            <i class="fas fa-question-circle"></i> ¿Olvidaste tu contraseña?
        </a>
    </div>
</div>

<style>
    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .btn-outline:hover {
        background: #667eea;
        color: white;
    }
</style>

<?php include_once __DIR__ . '/footer.php'; ?>
