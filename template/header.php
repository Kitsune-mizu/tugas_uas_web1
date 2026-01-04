<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Barang</title>
    <link href="<?php echo BASE_URL; ?>/assets/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="app-container">
    <header class="app-header">
        <div class="header-content">
            <div class="logo">
                <i class="fas fa-boxes"></i>
                <h1>Inventory System</h1>
            </div>

            <?php $isAdmin = isAdmin(); ?>

            <nav class="main-nav">
                <!-- Dashboard -->
                <a href="<?php echo BASE_URL; ?>/home/index" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Data Barang (Admin & User) -->
                <a href="<?php echo BASE_URL; ?>/barang/index" class="nav-link">
                    <i class="fas fa-list"></i>
                    <span>Data Barang</span>
                </a>

                <!-- Tambah Barang (ADMIN SAJA) -->
                <?php if ($isAdmin): ?>
                <a href="<?php echo BASE_URL; ?>/barang/tambah" class="nav-link">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Barang</span>
                </a>
                <?php endif; ?>

                <!-- Profil -->
                <a href="<?php echo BASE_URL; ?>/user/profile" class="nav-link">
                    <i class="fas fa-user-circle"></i>
                    <span>Profil</span>
                </a>

                <!-- Logout -->
                <a href="<?php echo BASE_URL; ?>/auth/logout" class="nav-link logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>(<?php echo htmlspecialchars($_SESSION['nama'] ?? 'User'); ?>)</span>
                </a>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="content-wrapper">
