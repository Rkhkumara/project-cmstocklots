<?php

$host = 'localhost';
$user = 'root'; 
$pass = '';    
$db_name = 'db_fashion';

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $script_name = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $project_folder = preg_replace('/(admin|includes)\/?$/', '', $script_name);
    define('BASE_URL', $protocol . $host . $project_folder);
}

if (!defined('PROJECT_ROOT_PATH')) {
    $current_dir = __DIR__;
    $project_root = preg_replace('/(admin|includes)$/', '', $current_dir);
    $project_root = rtrim($project_root, '/\\'); 
    define('PROJECT_ROOT_PATH', $project_root);
}
// ?>