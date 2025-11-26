<?php
// register.php - Página de registro en raíz
require_once __DIR__ . '/includes/init.php';

$error = '';
$success = '';
$pageTitle = 'Crear Cuenta - MassolaCommerce';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) {
        $error = "Token de seguridad inválido. Por favor intenta de nuevo.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $plan = $_POST['plan'] ?? 'basic';
        
        // Validaciones
        if (!$username || !$email || !$password) {
            $error = "Por favor completa todos los campos requeridos.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email inválido. Por favor ingresa un email válido.";
        } elseif (strlen($password) < 8) {
            $error = "La contraseña debe tener al menos 8 caracteres.";
        } elseif ($password !== $password_confirm) {
            $error = "Las contraseñas no coinciden.";
        } else {
            // Verificar si usuario o email ya existen
            $s = $pdo->prepare("SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1");
            $s->execute([':u' => $username, ':e' => $email]);
            
            if ($s->fetch()) {
                $error = "El usuario o email ya está registrado. Por favor usa otro.";
            } else {
                try {
                    $pdo->beginTransaction();
                    
                    // Crear usuario
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $ins = $pdo->prepare("INSERT INTO users (username, email, password_hash, is_active, created_at) VALUES (:u, :e, :h, 1, NOW())");
                    $ins->execute([':u' => $username, ':e' => $email, ':h' => $hash]);
                    $user_id = $pdo->lastInsertId();
                    
                    // Asignar rol customer por defecto
                    $r = $pdo->prepare("SELECT id FROM roles WHERE slug = 'customer' LIMIT 1");
                    $r->execute();
                    $role = $r->fetch();
                    
                    if ($role) {
                        $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:uid, :rid)")
                            ->execute([':uid' => $user_id, ':rid' => $role['id']]);
                    }
                    
                    $pdo->commit();
                    
                    // Registrar acción
                    log_user_action('Usuario registrado', ['username' => $username, 'email' => $email]);
                    
                    // Enviar email de bienvenida (si está configurado)
                    if (function_exists('send_welcome_email')) {
                        send_welcome_email($email, $username);
                    }
                    
                    // Auto-login
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['role'] = 'customer';
                    
                    // Redirigir al dashboard
                    header('Location: /dashboard.php');
                    exit;
                    
                } catch (Exception $e) {
                    $pdo->rollBack();
                    $error = "Error al crear la cuenta. Por favor intenta de nuevo.";
                    log_error("Error en registro: " . $e->getMessage());
                }
            }
        }
    }
}

// Obtener plan desde URL
$selected_plan = $_GET['plan'] ?? 'professional';

include_once __DIR__ . '/header.php';
?>

<div class="container" style="max-width: 600px; margin: 60px auto; padding: 40px; background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; margin-bottom: 10px; color: #333;">Crear Cuenta</h2>
    <p style="text-align: center; color: #666; margin-bottom: 30px;">Únete a MassolaCommerce y comienza tu prueba gratuita</p>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
            <i class="fas fa-exclamation-circle"></i> <?= sanitize($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745;">
            <i class="fas fa-check-circle"></i> <?= sanitize($success) ?>
        </div>
    <?php endif; ?>
    
    <form method="post" action="/register.php" style="display: flex; flex-direction: column; gap: 20px;">
        <?= csrf_field() ?>
        <input type="hidden" name="plan" value="<?= sanitize($selected_plan) ?>">
        
        <div class="form-group">
            <label for="username" style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">
                <i class="fas fa-user"></i> Usuario <span style="color: #dc3545;">*</span>
            </label>
            <input type="text" 
                   id="username"
                   name="username" 
                   placeholder="Elige un nombre de usuario" 
                   required 
                   autofocus
                   minlength="3"
                   maxlength="50"
                   value="<?= isset($_POST['username']) ? sanitize($_POST['username']) : '' ?>"
                   style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;">
            <small style="color: #666; font-size: 13px;">Mínimo 3 caracteres, solo letras, números y guiones bajos</small>
        </div>
        
        <div class="form-group">
            <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">
                <i class="fas fa-envelope"></i> Email <span style="color: #dc3545;">*</span>
            </label>
            <input type="email" 
                   id="email"
                   name="email" 
                   placeholder="tu@email.com" 
                   required
                   value="<?= isset($_POST['email']) ? sanitize($_POST['email']) : '' ?>"
                   style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;">
        </div>
        
        <div class="form-group">
            <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">
                <i class="fas fa-lock"></i> Contraseña <span style="color: #dc3545;">*</span>
            </label>
            <input type="password" 
                   id="password"
                   name="password" 
                   placeholder="Mínimo 8 caracteres" 
                   required
                   minlength="8"
                   style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;">
            <small style="color: #666; font-size: 13px;">Mínimo 8 caracteres. Usa letras, números y símbolos para mayor seguridad</small>
        </div>
        
        <div class="form-group">
            <label for="password_confirm" style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">
                <i class="fas fa-lock"></i> Confirmar Contraseña <span style="color: #dc3545;">*</span>
            </label>
            <input type="password" 
                   id="password_confirm"
                   name="password_confirm" 
                   placeholder="Repite tu contraseña" 
                   required
                   minlength="8"
                   style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; transition: border-color 0.3s;">
        </div>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #667eea;">
            <p style="margin: 0; font-size: 14px; color: #555;">
                <i class="fas fa-info-circle" style="color: #667eea;"></i>
                Al crear tu cuenta, aceptas nuestros 
                <a href="/legal/terms.php" style="color: #667eea;">Términos de Servicio</a> y 
                <a href="/legal/privacy.php" style="color: #667eea;">Política de Privacidad</a>
            </p>
        </div>
        
        <button type="submit" 
                class="btn btn-primary" 
                style="width: 100%; padding: 15px; font-size: 18px; font-weight: 600; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; cursor: pointer; transition: transform 0.2s;">
            <i class="fas fa-user-plus"></i> Crear Mi Cuenta
        </button>
    </form>
    
    <div style="margin-top: 30px; text-align: center; padding-top: 20px; border-top: 1px solid #e0e0e0;">
        <p style="color: #666; margin-bottom: 10px;">¿Ya tienes cuenta?</p>
        <a href="/login.php" class="btn btn-outline" style="display: inline-block; padding: 10px 30px; border: 2px solid #667eea; color: #667eea; text-decoration: none; border-radius: 8px; transition: all 0.3s;">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
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
