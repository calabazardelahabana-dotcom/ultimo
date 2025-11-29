<?php
// includes/db.php - Conexión a base de datos
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$config = require __DIR__ . '/../config.php';

$host = $config->db->host;
$port = $config->db->port;
$name = $config->db->name;
$user = $config->db->user;
$pass = $config->db->pass;
$charset = $config->db->charset;

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";
    
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    
    $pdo->exec("SET NAMES 'utf8mb4'");
    $pdo->exec("SET CHARACTER SET utf8mb4");
    
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    die("Error de conexión a la base de datos. Verifica la configuración.");
}
