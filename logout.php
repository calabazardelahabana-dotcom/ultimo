<?php
// logout.php - Cerrar sesión
session_start();

// Registrar logout si hay usuario
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/includes/logger.php';
    log_user_action('Usuario cerró sesión', [
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? 'unknown'
    ]);
}

// Destruir sesión
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirigir al login con mensaje
header('Location: /login.php?logout=1');
exit;
