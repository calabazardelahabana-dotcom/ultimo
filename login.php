<?php
// login.php - Inicio de sesión
require_once __DIR__ . '/includes/init.php';

$error = '';
$pageTitle = 'Iniciar Sesión - MassolaCommerce';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) {
        $error = "Token de seguridad inválido.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (!$username || !$password) {
            $error = "Completa todos los campos.";
        } else {
            $stmt = $pdo->prepare("
                SELECT * FROM users 
                WHERE (username = :u OR email = :u) 
                AND is_active = 1 
                AND deleted_at IS NULL 
                LIMIT 1
            ");
            $stmt->execute([':u' => $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                
                // Cargar rol
                $r = $pdo->prepare("
                    SELECT r.slug FROM roles r 
                    JOIN user_roles ur ON ur.role_id = r.id 
                    WHERE ur.user_id = :uid LIMIT 1
                ");
                $r->execute([':uid' => $user['id']]);
                $role = $r->fetch();
                $_SESSION['role'] = $role ? $role['slug'] : 'customer';
                
                // Actualizar last_login
                $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = :id")
                    ->execute([':id' => $user['id']]);
                
                log_login_attempt($username, true);
                
                header('Location: /dashboard.php');
                exit;
            } else {
                $error = "Credenciales incorrectas.";
                log_login_attempt($username, false, 'Invalid credentials');
            }
        }
    }
}

include_once __DIR__ . '/header.php';
?>

<style>
    .login-container {
        max-width: 450px;
        margin: 60px auto;
        padding: 40px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .login-title {
        text-align: center;
        margin-bottom: 30px;
        color: #2d3748;
        font-size: 2em;
    }
    
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .alert-danger {
        background: #fed7d7;
        color: #c53030;
        border-left: 4px solid #f56565;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #4a5568;
    }
    
    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .login-footer {
        margin-top: 30px;
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .login-footer p {
        color: #718096;
        margin-bottom: 15px;
    }
</style>

<div class="login-container">
    <h2 class="login-title">Iniciar Sesión</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= sanitize($error) ?>
        </div>
    <?php endif; ?>
    
    <form method="post" action="/login.php">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="username">
                <i class="fas fa-user"></i> Usuario o Email
            </label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                placeholder="tu_usuario o email@ejemplo.com"
                required 
                autofocus
                value="<?= isset($_POST['username']) ? sanitize($_POST['username']) : '' ?>"
            >
        </div>
        
        <div class="form-group">
            <label for="password">
                <i class="fas fa-lock"></i> Contraseña
            </label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                placeholder="Tu contraseña"
                required
            >
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 18px;">
            <i class="fas fa-sign-in-alt"></i> Entrar
        </button>
    </form>
    
    <div class="login-footer">
        <p>¿No tienes cuenta?</p>
        <a href="/register.php" class="btn btn-outline">
            <i class="fas fa-user-plus"></i> Crear Cuenta
        </a>
    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
