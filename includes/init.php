<?php
// includes/init.php - bootstrap (include this at top of pages)
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
require_once __DIR__ . '/mailer.php';
require_once __DIR__ . '/logger.php';

// [CORRECCIÓN] cache.php no existe y causa un error fatal. Se comenta para que la web cargue.
// require_once __DIR__ . '/cache.php'; 

require_once __DIR__ . '/image_utils.php';

// Aquí podrías definir constantes o variables de entorno si fuera necesario.
// Por ejemplo:
// $pdo = connect_db(); // Asumiendo que 'db.php' tiene la función connect_db()
// ...