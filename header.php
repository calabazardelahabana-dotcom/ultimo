<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'MassolaCommerce - Tu Plataforma de E-commerce en Cuba'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Estilos principales -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    
    <!-- Burbujas decorativas de fondo -->
    <div class="bubble-container">
        <div class="bubble bubble-1"></div>
        <div class="bubble bubble-2"></div>
        <div class="bubble bubble-3"></div>
    </div>
    
    <!-- Header principal -->
    <header class="main-header">
        <div class="container">
            <div class="logo">
                <a href="/">
                    <img src="/assets/images/logo-massolagroup.png" alt="Massola Group Business">
                </a>
            </div>
            
            <!-- Menú de navegación -->
            <nav class="main-nav">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id']): ?>
                    <!-- Usuario autenticado -->
                    <a href="/dashboard.php" class="btn-dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="/logout.php" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                <?php else: ?>
                    <!-- Usuario no autenticado -->
                    <a href="/login.php" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                    <a href="/register.php" class="btn-register">
                        <i class="fas fa-user-plus"></i> Registrarse
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    
    <main>
