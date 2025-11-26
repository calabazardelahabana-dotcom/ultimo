<?php
// includes/theme.php
require_once __DIR__ . '/functions.php';

function get_tenant_theme($pdo, $tenant_id) {
    $stmt = $pdo->prepare("SELECT value FROM tenant_settings WHERE tenant_id = :tid AND key_name = 'theme' LIMIT 1");
    $stmt->execute([':tid'=>$tenant_id]);
    $row = $stmt->fetch();
    if ($row && !empty($row['value'])) return $row['value'];
    $stmt = $pdo->prepare("SELECT settings FROM tenants WHERE id = :id LIMIT 1");
    $stmt->execute([':id'=>$tenant_id]);
    $t = $stmt->fetch();
    if ($t && !empty($t['settings'])) {
        $s = json_decode($t['settings'], true);
        if (!empty($s['theme'])) return $s['theme'];
    }
    return 'default';
}

function get_available_themes() {
    $dir = __DIR__ . '/../themes';
    $out = [];
    foreach (glob($dir . '/*', GLOB_ONLYDIR) as $d) $out[] = basename($d);
    return $out;
}