<?php
// includes/csrf.php - Protección CSRF

function csrf_token() {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="_csrf" value="' . csrf_token() . '">';
}

function csrf_verify($token) {
    return !empty($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
}
