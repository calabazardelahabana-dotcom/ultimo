<?php
// register.php - Registro de usuarios
require_once __DIR__ . '/includes/init.php';

$error = '';
$success = '';
$pageTitle = 'Crear Cuenta - MassolaCommerce';
$selectedPlan = $_GET['plan'] ?? 'professional';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['_csrf'] ?? '')) {
        $error = "Token de seguridad inválido.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $plan = $_POST['plan'] ?? 'professional';
        
        // Validaciones
        if (!$username || !$email || !$password) {
            $error = "Completa todos los campos.";
        } elseif (strlen($username) < 3) {
            $error = "El usuario debe tener al menos 3 caracteres.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Email inválido.";
        } elseif (strlen($password) < 6) {
            $error = "La contraseña debe tener al menos 6 caracteres.";
        } elseif ($password !== $confirm) {
            $error = "Las contraseñas no coinciden.";
        } else {
            // Verificar si ya existe
            $check = $pdo->prepare("SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1");
            $check->execute([':u' => $username, ':e' => $email]);
            
            if ($check->fetch()) {
                $error = "El usuario o email ya existe.";
            } else {
                // Crear usuario
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password_hash, is_active, created_at) 
                    VALUES (:u, :e, :p, 1, NOW())
                ");
                $stmt->execute([
                    ':u' => $username,
                    ':e' => $email,
                    ':p' => $hash
                ]);
                
                $userId = $pdo->lastInsertId();
                
                // Asignar rol de tenant_admin
                $roleStmt = $pdo->prepare("SELECT id FROM roles WHERE slug = 'tenant_admin' LIMIT 1");
                $roleStmt->execute();
                $role = $roleStmt->fetch();
                
                if ($role) {
                    $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:uid, :rid)")
                        ->execute([':uid' => $userId, ':rid' => $role['id']]);
                }
                
                $success = "¡Cuenta creada! Ahora puedes iniciar sesión.";
                
                // Auto-login
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = 'tenant_admin';
                
                // Redirigir al dashboard
                header('Location: /dashboard.php?welcome=1');
                exit;
            }
        }
    }
}

include_once __DIR__ . '/header.php';
?>

<style>
    .register-container {
        max-width: 550px;
        margin: 60px auto;
        padding: 40px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    
    .register-title {
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
    
    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border-left: 4px solid #48bb78;
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
    
    .form-group input, .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
    }
    
    .form-group input:focus, .form-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .register-footer {
        margin-top: 30px;
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .register-footer p {
        color: #718096;
        margin-bottom: 15px;
    }
</style>

<div class="register-container">
    <h2 class="register-title">Crear Cuenta</h2>
    
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> <?= sanitize($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?= sanitize($success) ?>
        </div>
    <?php endif; ?>
    
    <form method="post" action="/register.php">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="username">
                <i class="fas fa-user"></i> Usuario
            </label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                placeholder="Elige un nombre de usuario"
                required 
                autofocus
                value="<?= isset($_POST['username']) ? sanitize($_POST['username']) : '' ?>"
            >
        </div>
        
        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i> Email
            </label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="tu@email.com"
                required
                value="<?= isset($_POST['email']) ? sanitize($_POST['email']) : '' ?>"
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
                placeholder="Mínimo 6 caracteres"
                required
            >
        </div>
        
        <div class="form-group">
            <label for="confirm_password">
                <i class="fas fa-lock"></i> Confirmar Contraseña
            </label>
            <input 
                type="password" 
                id="confirm_password" 
                name="confirm_password" 
                placeholder="Repite tu contraseña"
                required
            >
        </div>
        
        <div class="form-group">
            <label for="plan">
                <i class="fas fa-star"></i> Plan
            </label>
            <select id="plan" name="plan">
                <option value="basic" <?= $selectedPlan === 'basic' ? 'selected' : '' ?>>Básico - $550/mes</option>
                <option value="professional" <?= $selectedPlan === 'professional' ? 'selected' : '' ?>>Profesional - $750/mes</option>
                <option value="enterprise" <?= $selectedPlan === 'enterprise' ? 'selected' : '' ?>>Empresa - $1500/mes</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 18px;">
            <i class="fas fa-rocket"></i> Crear Cuenta
        </button>
    </form>
    
    <div class="register-footer">
        <p>¿Ya tienes cuenta?</p>
        <a href="/login.php" class="btn btn-outline">
            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
        </a>
    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
