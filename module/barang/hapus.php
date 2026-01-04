<?php
requireLogin();
if (!isAdmin()) {
    header("Location: " . BASE_URL . "/barang/index");
    exit;
}

$db = $GLOBALS['db'];

// Get ID from URL
$id = $_GET['id'] ?? 0;
$id = $db->escape_string($id);

// Get current data using Database class
$data = $db->get('data_barang', "id_barang = '$id'");

if ($data) {
    // Delete image file if exists
    if ($data['gambar'] && file_exists(GAMBAR_PATH . '/' . $data['gambar'])) {
        unlink(GAMBAR_PATH . '/' . $data['gambar']);
    }
    
    // Delete from database using Database class
    if ($db->delete('data_barang', "id_barang = '$id'")) {
        header('Location: ' . BASE_URL . '/barang/index?message=' . urlencode('Barang berhasil dihapus!') . '&type=success');
        exit;
    } else {
        header('Location: ' . BASE_URL . '/barang/index?message=' . urlencode('Gagal menghapus barang!') . '&type=error');
        exit;
    }
} else {
    header('Location: ' . BASE_URL . '/barang/index?message=' . urlencode('Data barang tidak ditemukan!') . '&type=error');
    exit;
}
?>