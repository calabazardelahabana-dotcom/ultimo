<?php
// includes/init.php - Inicialización de la aplicación
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar archivos esenciales
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';

// Configuración global
$config = require __DIR__ . '/../config.php';

// Zona horaria
date_default_timezone_set('America/Havana');

// Variables globales útiles
define('SITE_URL', $config->site->url);
define('SITE_NAME', $config->site->name);
