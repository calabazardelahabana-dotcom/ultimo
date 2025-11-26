<?php
// includes/auth_middleware.php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: /public/login.php');
    exit;
}