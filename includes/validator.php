<?php
// includes/validator.php
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
function validate_password_strength($pw) {
    return is_string($pw) && strlen($pw) >= 8;
}
function validate_amount($v) {
    return is_numeric($v) && floatval($v) > 0;
}