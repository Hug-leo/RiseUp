<?php
/**
 * Router for PHP built-in server — WordPress compatible.
 */
$uri = urldecode( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );

// Serve existing files directly
if ( $uri !== '/' && file_exists( __DIR__ . $uri ) ) {
    return false;
}

// Route everything else through index.php
$_SERVER['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
require_once __DIR__ . '/index.php';
