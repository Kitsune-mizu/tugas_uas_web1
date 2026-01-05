<?php
requireLogin();
$isAdmin = isAdmin();
$db = $GLOBALS['db'];

// Inisialisasi variabel default
$total_barang = 0;
$total_stok = 0;
$categories = [];
$recent_items = [];
$total_categories = 0;

// ===== 1. QUERY UNTUK ADMIN (Data Statistik) =====
if ($isAdmin) {
    // Hitung Total Jenis Barang
    $total_barang_result = $db->query("SELECT COUNT(*) as total FROM data_barang");
    $total_barang = $total_barang_result ? $total_barang_result->fetch_assoc()['total'] : 0;

    // Hitung Total Stok Seluruhnya
    $total_stok_result = $db->query("SELECT SUM(stok) as total_stok FROM data_barang");
    $total_stok_data = $total_stok_result ? $total_stok_result->fetch_assoc() : ['total_stok' => 0];
    $total_stok = $total_stok_data['total_stok'] ?? 0;

    // Ambil Data Distribusi Kategori
    $categories_result = $db->query("SELECT kategori, COUNT(*) as count FROM data_barang GROUP BY kategori");
    $categories = $categories_result ? $categories_result->fetch_all(MYSQLI_ASSOC) : [];
    $total_categories = count($categories);
}

// ===== 2. QUERY UNTUK SEMUA USER (Daftar Barang Terbaru) =====
$recent_result = $db->query("SELECT * FROM data_barang ORDER BY id_barang DESC LIMIT 5");
$recent_items = $recent_result ? $recent_result->fetch_all(MYSQLI_ASSOC) : [];
?>

<div class="page-header">
    <div class="header-title">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
        <?php if ($isAdmin): ?>
            <p>Overview sistem manajemen inventory</p>
        <?php else: ?>
            <p>Selamat datang, silakan lihat data barang terbaru</p>
        <?php endif; ?>
    </div>
    <div class="header-actions">
        <span class="current-time" id="currentTime"></span>
    </div>
</div>

<?php if ($isAdmin): ?>
    <div class="dashboard-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-boxes"></i></div>
            <div class="stat-info">
                <h3><?php echo $total_barang; ?></h3>
                <p>Total Barang</p>
            </div>
            <div class="stat-trend"><i class="fas fa-chart-line"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stock"><i class="fas fa-layer-group"></i></div>
            <div class="stat-info">
                <h3><?php echo $total_stok; ?></h3>
                <p>Total Stok</p>
            </div>
            <div class="stat-trend"><i class="fas fa-warehouse"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon category"><i class="fas fa-tags"></i></div>
            <div class="stat-info">
                <h3><?php echo $total_categories; ?></h3>
                <p>Kategori</p>
            </div>
            <div class="stat-trend"><i class="fas fa-list"></i></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon revenue"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h3>100%</h3>
                <p>Sistem Ready</p>
            </div>
            <div class="stat-trend"><i class="fas fa-bolt"></i></div>
        </div>
    </div>
<?php endif; ?>

<div class="dashboard-content">
    
    <div class="recent-section <?php echo !$isAdmin ? 'full-width' : ''; ?>">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-clock"></i> Barang Terbaru</h3>
                <a href="<?php echo BASE_URL; ?>/barang/index" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_items)): ?>
                    <div class="recent-list recent-scroll">
                        <?php foreach ($recent_items as $item): ?>
                            <div class="recent-item">
                                <div class="item-image">
                                    <?php 
                                    $imagePath = GAMBAR_PATH . '/' . $item['gambar'];
                                    $imageUrl = $item['gambar'] ? GAMBAR_URL . '/' . $item['gambar'] : '';
                                    if (!empty($item['gambar']) && file_exists($imagePath)): 
                                    ?>
                                        <img src="<?php echo $imageUrl; ?>" 
                                             alt="<?php echo htmlspecialchars($item['nama']); ?>"
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    <?php else: ?>
                                        <div class="no-image-small"><i class="fas fa-image"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="item-info">
                                    <h4><?php echo htmlspecialchars($item['nama']); ?></h4>
                                    <p class="item-category"><?php echo htmlspecialchars($item['kategori']); ?></p>
                                    <p class="item-price">Rp <?php echo number_format($item['harga_jual'], 0, ',', '.'); ?></p>
                                </div>
                                <div class="item-stock">
                                    <span class="stock-badge <?php echo $item['stok'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                                        <?php echo $item['stok']; ?> stok
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>Belum ada data barang</p>
                        <?php if($isAdmin): ?>
                        <a href="<?php echo BASE_URL; ?>/barang/tambah" class="btn btn-primary">Tambah Barang Pertama</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($isAdmin): ?>
    <div class="chart-section">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-chart-bar"></i> Distribusi Kategori</h3>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <?php if (!empty($categories) && $total_barang > 0): ?>
                        <div class="category-chart">
                            <?php foreach ($categories as $category): ?>
                                <div class="chart-item">
                                    <div class="chart-label">
                                        <span class="category-name"><?php echo htmlspecialchars($category['kategori']); ?></span>
                                        <span class="category-count"><?php echo $category['count']; ?> items</span>
                                    </div>
                                    <div class="chart-bar">
                                        <div class="chart-fill" 
                                             style="width: <?php echo ($category['count'] / $total_barang) * 100; ?>%">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-chart">
                            <i class="fas fa-chart-pie"></i>
                            <p>Belum ada data kategori</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if ($isAdmin): ?>
    <div class="quick-actions">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="action-grid">
                    <a href="<?php echo BASE_URL; ?>/barang/tambah" class="action-card">
                        <div class="action-icon"><i class="fas fa-plus-circle"></i></div>
                        <div class="action-info">
                            <h4>Tambah Barang</h4>
                            <p>Tambahkan barang baru</p>
                        </div>
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>/barang/index" class="action-card">
                        <div class="action-icon"><i class="fas fa-list"></i></div>
                        <div class="action-info">
                            <h4>Lihat Semua</h4>
                            <p>Kelola data barang</p>
                        </div>
                    </a>
                    
                    <a href="#" class="action-card">
                        <div class="action-icon"><i class="fas fa-file-export"></i></div>
                        <div class="action-info">
                            <h4>Export Data</h4>
                            <p>Ekspor data barang</p>
                        </div>
                    </a>
                    
                    <a href="#" class="action-card">
                        <div class="action-icon"><i class="fas fa-cog"></i></div>
                        <div class="action-info">
                            <h4>Settings</h4>
                            <p>Pengaturan sistem</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    function updateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        const timeElement = document.getElementById('currentTime');
        if (timeElement) {
            timeElement.textContent = now.toLocaleDateString('id-ID', options);
        }
    }
    
    updateTime();
    setInterval(updateTime, 1000);
</script>