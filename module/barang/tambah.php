<?php
requireLogin();
if (!isAdmin()) {
    header("Location: " . BASE_URL . "/barang/index");
    exit;
}

$db = $GLOBALS['db'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
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
                
                // Pastikan folder gambar ada
                if (!is_dir(GAMBAR_PATH)) {
                    mkdir(GAMBAR_PATH, 0755, true);
                }
                
                if (move_uploaded_file($_FILES['file_gambar']['tmp_name'], $destination)) {
                    $data['gambar'] = $filename;
                } else {
                    $error = "Gagal mengupload gambar.";
                }
            } else {
                $error = "Format gambar tidak didukung. Gunakan JPG, PNG, atau GIF.";
            }
        }
    }
    
    if (!isset($error)) {
        // Insert data using Database class
        if ($db->insert('data_barang', $data)) {
            header('Location: ' . BASE_URL . '/barang/index?message=' . urlencode('Barang berhasil ditambahkan!') . '&type=success');
            exit;
        } else {
            $error = "Gagal menambahkan barang: " . $db->getConnection()->error;
        }
    }
}
?>

<div class="page-header">
    <div class="header-title">
        <h2><i class="fas fa-plus-circle"></i> Tambah Barang Baru</h2>
        <p>Tambahkan barang baru ke dalam sistem inventory</p>
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
        $form = new Form('', 'Simpan Barang', 'POST', 'multipart/form-data');
        
        $form->addField('nama', 'Nama Barang', 'text', '');
        $form->addField('kategori', 'Kategori', 'select', '', [
            '' => 'Pilih Kategori',
            'Elektronik' => 'Elektronik',
            'Komputer' => 'Komputer',
            'Hand Phone' => 'Hand Phone',
            'Aksesoris' => 'Aksesoris'
        ]);
        $form->addField('harga_beli', 'Harga Beli', 'number', '0', ['min' => '0', 'step' => '100']);
        $form->addField('harga_jual', 'Harga Jual', 'number', '0', ['min' => '0', 'step' => '100']);
        $form->addField('stok', 'Stok', 'number', '0', ['min' => '0']);
        $form->addField('file_gambar', 'Gambar Barang (Maks. 2MB)', 'file', '');
        
        $form->render();
        ?>
    </div>
</div>