<?php
// includes/permissions.php
require_once __DIR__ . '/db.php';

function user_has_role($pdo, $user_id, $role_slug, $tenant_id = null) {
    $sql = "SELECT 1 FROM user_roles ur JOIN roles r ON r.id = ur.role_id WHERE ur.user_id = :uid AND r.slug = :slug";
    $params = [':uid'=>$user_id, ':slug'=>$role_slug];
    if ($tenant_id !== null) {
        $sql .= " AND (ur.tenant_id = :tid OR ur.tenant_id IS NULL)";
        $params[':tid'] = $tenant_id;
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return (bool)$stmt->fetch();
}