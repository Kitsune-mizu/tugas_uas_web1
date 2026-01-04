<?php
$db = $GLOBALS['db'];

// LOGIKA SEARCH & PAGINATION
$q = "";
$whereSql = "";

// 1. Logika Search (Mencari di kolom Nama DAN Kategori)
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $q = $db->escape_string(trim($_GET['q']));
    $whereSql = " WHERE nama LIKE '%{$q}%' OR kategori LIKE '%{$q}%'";
}

// 2. Hitung Total Data
$sqlCount = "SELECT COUNT(*) as total FROM data_barang" . $whereSql;
$resCount = $db->query($sqlCount);
$totalData = 0;
if ($resCount) {
    $r_data = $resCount->fetch_assoc();
    $totalData = (int)$r_data['total'];
}

// 3. Konfigurasi Pagination
$perPage = 5; 
$totalPage = ceil($totalData / $perPage);
if ($totalPage < 1) $totalPage = 1; // Pastikan minimal ada 1 halaman

$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
if ($page > $totalPage) $page = $totalPage;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $perPage;

// 4. Ambil Data
$sqlData = "SELECT * FROM data_barang" . $whereSql . " ORDER BY id_barang ASC LIMIT {$offset}, {$perPage}";
$result = $db->query($sqlData);
$barang_list = ($result && $result->num_rows > 0) ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>

<div class="page-header">
    <div class="header-title">
        <h2><i class="fas fa-boxes"></i> Data Barang</h2>
         <?php if ($isAdmin): ?>
            <p>Kelola data barang dalam sistem inventory</p>
        <?php else: ?>
            <p>Daftar barang yang tersedia</p>
        <?php endif; ?>
    </div>
   <?php if (isAdmin()): ?>
    <a href="<?= BASE_URL ?>/barang/tambah" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Barang
    </a>
    <?php endif; ?>
</div>

<form action="" method="get" style="margin-bottom:1.5rem;">
    <div style="display:flex; gap:10px; align-items:center;">
        <input 
            type="text" 
            id="q" 
            name="q" 
            class="form-control" 
            placeholder="Cari nama atau kategori..." 
            value="<?php echo htmlspecialchars($q); ?>" 
            style="max-width:300px;"
        >
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="fas fa-search"></i> Cari
        </button>
        <?php if ($q): ?>
            <a href="<?php echo BASE_URL; ?>/barang" class="btn btn-secondary btn-sm">Reset</a>
        <?php endif; ?>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <?php if (count($barang_list) > 0): ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <?php if (isAdmin()): ?>
                                <th>Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($barang_list as $row): ?>
                            <tr>
                                <td><?php echo $row['id_barang']; ?></td>
                                <td class="product-image">
                                    <?php 
                                    $imagePath = GAMBAR_PATH . '/' . $row['gambar'];
                                    $imageUrl = $row['gambar'] ? GAMBAR_URL . '/' . $row['gambar'] : '';
                                    if (!empty($row['gambar']) && file_exists($imagePath)): 
                                    ?>
                                        <img src="<?php echo $imageUrl; ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;cursor:pointer;">
                                    <?php else: ?>
                                        <div class="no-image" style="width: 60px; height: 60px; background: #f7fafc; border: 2px dashed #cbd5e0; border-radius: 5px; display: flex; align-items: center; justify-content: center; color: #a0aec0;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><span class="category-badge"><?php echo htmlspecialchars($row['kategori']); ?></span></td>
                                <td>Rp <?php echo number_format($row['harga_beli'], 0, ',', '.'); ?></td>
                                <td>Rp <?php echo number_format($row['harga_jual'], 0, ',', '.'); ?></td>
                                <td><span class="stock-badge"><?php echo $row['stok']; ?></span></td>

                                <?php if (isAdmin()): ?>
                                    <td class="action-buttons">
                                        <a href="<?= BASE_URL ?>/barang/ubah/<?= $row['id_barang'] ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/barang/hapus/<?= $row['id_barang'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus data?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state" style="text-align:center; padding: 2rem;">
                <i class="fas fa-box-open" style="font-size: 3rem; color: #ccc;"></i>
                <h3>Data tidak ditemukan</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<nav aria-label="Page navigation" style="margin-top: 20px;">
    <ul class="pagination" style="display:flex; justify-content:center; gap:5px; list-style:none; padding:0;">
        <li>
            <a class="btn btn-sm btn-secondary" href="?page=<?php echo ($page > 1) ? $page - 1 : 1; ?><?php echo $q ? '&q='.urlencode($q) : ''; ?>">
                <i class="fas fa-chevron-left"></i>
            </a>
        </li>

        <?php for ($i = 1; $i <= $totalPage; $i++): ?>
            <li>
                <a class="btn btn-sm <?php echo ($page == $i) ? 'btn-primary' : 'btn-secondary'; ?>" 
                   href="?page=<?php echo $i; ?><?php echo $q ? '&q='.urlencode($q) : ''; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <li>
            <a class="btn btn-sm btn-secondary" href="?page=<?php echo ($page < $totalPage) ? $page + 1 : $totalPage; ?><?php echo $q ? '&q='.urlencode($q) : ''; ?>">
                <i class="fas fa-chevron-right"></i>
            </a>
        </li>
    </ul>
</nav>