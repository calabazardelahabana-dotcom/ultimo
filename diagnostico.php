<?php
// Diagnóstico elemental y fuerte — MassolaGroup

header('Content-Type: text/plain; charset=utf-8');

// --- CONFIG ---
$db_host = "127.0.0.1";
$db_name = "massola";
$db_user = "massola_user";
$db_pass = "change_me";
$logFile = __DIR__."/storage/logs/app.log";

// --- ENTORNO ---
echo "=== ENTORNO ===\n";
echo "PHP: ".PHP_VERSION."\n";
echo "Memoria: ".ini_get('memory_limit')."\n";
echo "Tiempo ejecución: ".ini_get('max_execution_time')."s\n";
echo "Extensiones críticas:\n";
foreach (['pdo_mysql','openssl','mbstring','curl','gd'] as $ext) {
    echo " - $ext: ".(extension_loaded($ext) ? "OK" : "FALTA")."\n";
}
echo "\n";

// --- BASE DE DATOS ---
echo "=== BASE DE DATOS ===\n";
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",$db_user,$db_pass,
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    echo "Conexión OK\n";

    // Tablas
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tablas: ".implode(", ",$tables)."\n";

    // Usuarios
    if (in_array("users",$tables)) {
        $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo "Usuarios: $count\n";
        $sample = $pdo->query("SELECT email,password FROM users LIMIT 3")->fetchAll();
        foreach ($sample as $row) {
            $hash = $row['password'];
            $type = str_starts_with($hash,'$2y$') ? "bcrypt" : (str_starts_with($hash,'$argon2') ? "argon2" : "PLANO/OTRO");
            echo " - ".$row['email']." => ".$type."\n";
        }
    }
} catch (Throwable $e) {
    echo "Error DB: ".$e->getMessage()."\n";
}
echo "\n";

// --- SESIONES ---
echo "=== SESIONES ===\n";
session_start();
echo "Session ID: ".session_id()."\n";
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(8));
echo "CSRF: ".$_SESSION['csrf']."\n\n";

// --- SEGURIDAD BÁSICA ---
echo "=== SEGURIDAD ===\n";
echo "HTTPS: ".((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') ? "OK" : "NO")."\n";
echo "Headers esperados:\n";
foreach (['Strict-Transport-Security','Content-Security-Policy','X-Frame-Options','X-Content-Type-Options'] as $h) {
    echo " - $h: ".(isset($_SERVER[$h]) ? "OK" : "NO")."\n";
}
echo "\n";

// --- LOGS ---
echo "=== LOGS ===\n";
if (file_exists($logFile)) {
    $lines = file($logFile);
    $last = array_slice($lines,-10);
    echo "Últimos errores:\n".implode("",$last)."\n";
} else {
    echo "No se encontró log en $logFile\n";
}
echo "\n";

// --- TRAFICO SIMPLE ---
echo "=== TRÁFICO ===\n";
$metricFile = __DIR__."/storage/traffic.txt";
$now = time();
$data = @json_decode(@file_get_contents($metricFile),true) ?: [];
$data['hits'][] = $now;
file_put_contents($metricFile,json_encode($data));
$hitsLastMin = array_filter($data['hits'], fn($t)=>$t>$now-60);
echo "Visitas último minuto: ".count($hitsLastMin)."\n";
echo "Visitas totales: ".count($data['hits'])."\n";
