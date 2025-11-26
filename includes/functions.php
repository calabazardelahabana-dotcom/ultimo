<?php
// includes/functions.php - common helpers
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/db.php';

function sanitize($s) {
    return htmlspecialchars(trim($s ?? ''), ENT_QUOTES, 'UTF-8');
}

function create_slug($s) {
    $s = iconv('UTF-8', 'ASCII//TRANSLIT', $s);
    $s = preg_replace('/[^a-z0-9]+/i','-', strtolower($s));
    return trim($s, '-');
}

function getRequestHost() {
    return $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? '');
}

function getTenantFromRequest($pdo = null) {
    $host = getRequestHost();
    $siteRoot = parse_url((require __DIR__ . '/../config.php')->site->url, PHP_URL_HOST) ?: 'negocios.massolagroup.com';
    $tenantSlug = null;

    if (stripos($host, $siteRoot) !== false) {
        $parts = explode('.', $host);
        if (count($parts) > 3) $tenantSlug = $parts[0];
        elseif (count($parts) == 3 && !in_array($parts[0], ['www','negocios'])) $tenantSlug = $parts[0];
    }

    if (!$tenantSlug && !empty($_GET['t'])) {
        $tenantSlug = preg_replace('/[^a-z0-9-_]/i','', $_GET['t']);
    }

    if ($tenantSlug && $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM tenants WHERE slug = :slug AND deleted_at IS NULL LIMIT 1");
        $stmt->execute([':slug'=>$tenantSlug]);
        return $stmt->fetch() ?: null;
    }
    return null;
}

function require_login() {
    if (empty($_SESSION['user_id'])) {
        header('Location: /public/login.php');
        exit;
    }
}

function is_superadmin() {
    return (!empty($_SESSION['role']) && $_SESSION['role'] === 'superadmin');
}

function current_user($pdo) {
    if (empty($_SESSION['user_id'])) return null;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
    $stmt->execute([':id'=>$_SESSION['user_id']]);
    return $stmt->fetch();
}

function hash_password($plain) {
    return password_hash($plain, PASSWORD_BCRYPT);
}

function verify_password($plain, $hash) {
    return password_verify($plain, $hash);
}