<?php
// public/install.php - ejecutar UNA VEZ si no importaste el schema.sql
// Crea roles básicos y usuario superadmin a partir de config values
require_once __DIR__ . '/../includes/init.php';

if (file_exists(__DIR__ . '/.installed')) {
    echo "Install already executed.";
    exit;
}

try {
    // Roles
    $pdo->beginTransaction();
    $pdo->exec("INSERT IGNORE INTO roles (name, slug) VALUES ('Superadmin','superadmin'), ('Tenant Admin','tenant_admin'), ('Customer','customer')");

    // Superadmin user (cambiar credenciales en config.php o editar aquí)
    $cfg = require __DIR__ . '/../config.php';
    $sa_email = $cfg->site->support_email ?? 'admin@local';
    $sa_user = 'admin';
    $sa_pass = 'Admin123*';

    $hash = password_hash($sa_pass, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
    $stmt->execute([':u'=>$sa_user]);
    if (!$stmt->fetch()) {
        $ins = $pdo->prepare("INSERT INTO users (username, email, password_hash, is_active, created_at) VALUES (:u,:e,:h,1,NOW())");
        $ins->execute([':u'=>$sa_user, ':e'=>$sa_email, ':h'=>$hash]);
        $uid = $pdo->lastInsertId();
        $r = $pdo->prepare("SELECT id FROM roles WHERE slug='superadmin' LIMIT 1"); $r->execute(); $role = $r->fetch();
        if ($role) $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (:uid,:rid)")->execute([':uid'=>$uid, ':rid'=>$role['id']]);
    }

    $pdo->commit();
    touch(__DIR__ . '/.installed');
    echo "Install completed. Superadmin user: {$sa_user} (password visible in script). Delete install.php after verifying.";
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo "Install error: " . $e->getMessage();
}