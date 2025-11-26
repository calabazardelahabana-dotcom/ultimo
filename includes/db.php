<?php
// includes/db.php
// PDO connection using config.php (config returns object)
if (session_status() === PHP_SESSION_NONE) session_start();

$config = require __DIR__ . '/../config.php';

try {
    $host = $config->db->host ?? getenv('DB_HOST') ?? '127.0.0.1';
    $port = $config->db->port ?? getenv('DB_PORT') ?? 3306;
    $name = $config->db->name ?? getenv('DB_NAME') ?? 'massolag_negocios';
    $user = $config->db->user ?? getenv('DB_USER') ?? 'root';
    $pass = $config->db->pass ?? getenv('DB_PASS') ?? '';
    $charset = $config->db->charset ?? 'utf8mb4';

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Ensure utf8mb4
    $pdo->exec("SET NAMES 'utf8mb4'");
    $pdo->exec("SET CHARACTER SET utf8mb4");
} catch (Exception $e) {
    // In production, log and show friendly message.
    error_log("DB connect error: " . $e->getMessage());
    die("Error connecting to database. Check configuration.");
}