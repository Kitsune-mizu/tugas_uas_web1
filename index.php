<?php
session_start();

define('BASE_PATH', dirname(__FILE__));

define('BASE_URL', 'http://localhost/lab_UAS'); 
define('GAMBAR_PATH', BASE_PATH . '/assets/gambar');
define('GAMBAR_URL', BASE_URL . '/assets/gambar');

include "config.php";
include "class/Database.php"; 
include "class/Form.php";     
include "class/Auth.php";    

// Instance Objek Utama
$db = new Database($config);
$auth = new Auth($db);

include "helpers/auth_helper.php";

$path = $_SERVER['PATH_INFO'] ?? '/home/index';
$segments = explode('/', trim($path, '/'));

$mod = $segments[0] ?? 'home';
$page = $segments[1] ?? 'index';
$id = $segments[2] ?? null;

// Cek Login (Kecuali halaman login)
if (!$auth->isLoggedIn() && $mod != 'auth') {
    header("Location: " . BASE_URL . "/auth/login");
    exit;
}

// Tentukan file modul
$file = "module/{$mod}/{$page}.php";

// Load Template & Konten
if ($mod != 'auth') {
    include "template/header.php";
}

if (file_exists($file)) {
    if ($id) $_GET['id'] = $id; 
    include $file;
} else {
    echo '<div class="alert alert-danger">Modul tidak ditemukan: ' . htmlspecialchars($mod . '/' . $page) . '</div>';
}

if ($mod != 'auth') {
    include "template/footer.php";
}
?>