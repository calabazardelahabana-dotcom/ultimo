<?php
// includes/logger.php - simple file logger
function log_event($level, $message, $context = []) {
    $dir = __DIR__ . '/../storage/logs';
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $file = $dir . '/app.log';
    $entry = date('Y-m-d H:i:s') . " [{$level}] " . $message;
    if ($context) $entry .= ' ' . json_encode($context, JSON_UNESCAPED_UNICODE);
    file_put_contents($file, $entry . PHP_EOL, FILE_APPEND | LOCK_EX);
}