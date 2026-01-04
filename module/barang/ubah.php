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

if (!$data) {
    header('Location: ' . BASE_URL . '/barang/index?message=' . urlencode('Data barang tidak ditemukan!') . '&type=error');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateData = [
        'nama' => $db->escape_string($_POST['nama']),
        'kategori' => $db->escape_string($_POST['kategori']),
        'harga_beli' => $db->escape_string($_POST['harga_beli']),
        'harga_jual' => $db->escape_string($_POST['harga_jual']),
        'stok' => $db->escape_string($_POST['stok'])
    ];
    
    // Handle file upload
    if (isset($_FILES['file_gambar']) && $_FILES['file_gambar']['error'] === 0) {
        // Cek ukuran file (maks 2MB)
        if ($_FILES['file_gambar']['size'] > 2097152) {
            $error = "Ukuran gambar terlalu besar. Maksimal 2MB.";
        } else {
            $fileInfo = pathinfo($_FILES['file_gambar']['name']);
            $extension = strtolower($fileInfo['extension']);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (in_array($extension, $allowedExtensions)) {
                // Buat nama file unik
                $filename = time() . '_' . str_replace(' ', '_', $fileInfo['filename']) . '.' . $extension;
                $destination = GAMBAR_PATH . '/' . $filename;
                
                if (move_uploaded_file($_FILES['file_gambar']['tmp_name'], $destination)) {
                    // Delete old image if exists
                    if ($data['gambar'] && file_exists(GAMBAR_PATH . '/' . $data['gambar'])) {
                        unlink(GAMBAR_PATH . '/' . $data['gambar']);
                    }
                    $updateData['gambar'] = $filename;
                } else {
                    $error = "Gagal mengupload gambar.";
                }
            } else {
                $error = "Format gambar tidak didukung. Gunakan JPG, PNG, atau GIF.";
            }
        }
    }
    
    if (!isset($error)) {
        // Update data using Database class
        if ($db->update('data_barang', $updateData, "id_barang = '$id'")) {
            header('Location: ' . BASE_URL . '/barang/index?message=' . urlencode('Barang berhasil diupdate!') . '&type=success');
            exit;
        } else {
            $error = "Gagal mengupdate barang: " . $db->getConnection()->error;
        }
    }
}
?>

<div class="page-header">
    <div class="header-title">
        <h2><i class="fas fa-edit"></i> Edit Barang</h2>
        <p>Ubah data barang yang sudah ada</p>
    </div>
    <a href="<?php echo BASE_URL; ?>/barang/index" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php
        // Create form using Form class
        $form = new Form('', 'Update Barang', 'POST', 'multipart/form-data');
        
        $currentImage = $data['gambar'] ? GAMBAR_URL . '/' . $data['gambar'] : '';
        
        $form->addField('nama', 'Nama Barang', 'text', $data['nama']);
        $form->addField('kategori', 'Kategori', 'select', $data['kategori'], [
            '' => 'Pilih Kategori',
            'Elektronik' => 'Elektronik',
            'Komputer' => 'Komputer',
            'Hand Phone' => 'Hand Phone',
            'Aksesoris' => 'Aksesoris'
        ]);
        $form->addField('harga_beli', 'Harga Beli', 'number', $data['harga_beli'], ['min' => '0', 'step' => '100']);
        $form->addField('harga_jual', 'Harga Jual', 'number', $data['harga_jual'], ['min' => '0', 'step' => '100']);
        $form->addField('stok', 'Stok', 'number', $data['stok'], ['min' => '0']);
        $form->addField('file_gambar', 'Gambar Barang (Maks. 2MB)', 'file', '', ['current' => $currentImage]);
        
        $form->render();
        ?>
    </div>
</div>