<?php
$db = $GLOBALS['db'];
$auth = $GLOBALS['auth'];
$auth->checkAccess();

$error = null;
$message = null;
$userData = $auth->getUserData();

if (!$userData) {
    header('Location: ' . BASE_URL . '/auth/login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi input
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Semua field password harus diisi.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Password baru dan konfirmasi password tidak cocok.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password baru minimal 6 karakter.";
    } elseif (!password_verify($current_password, $userData['password'])) {
        $error = "Password lama salah.";
    } else {
        if ($auth->updatePassword($userData['username'], $new_password)) {
            $message = "Password berhasil diubah!";
            $userData = $auth->getUserData();
        } else {
            $error = "Gagal mengubah password: " . $db->getConnection()->error;
        }
    }
}
?>

<div class="page-header">
    <div class="header-title">
        <h2><i class="fas fa-user-circle"></i> Profil Pengguna</h2>
        <p>Lihat data profil dan ubah password Anda</p>
    </div>
</div>

<div class="dashboard-content profile-layout">
    <div class="card profile-card">
        <div class="card-header">
            <h3><i class="fas fa-id-badge"></i> Data Akun</h3>
        </div>
        <div class="card-body">
            <p><strong>Nama:</strong> <?= htmlspecialchars($userData['nama']); ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($userData['username']); ?></p>
        </div>
    </div>

    <div class="card password-card">
        <div class="card-header">
            <h3><i class="fas fa-key"></i> Ubah Password</h3>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($message): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= $message; ?>
                </div>
            <?php endif; ?>

            <?php
            $form = new Form('', 'Ubah Password', 'POST');
            $form->addField('current_password', 'Password Lama', 'password', '');
            $form->addField('new_password', 'Password Baru', 'password', '');
            $form->addField('confirm_password', 'Konfirmasi Password Baru', 'password', '');
            $form->setResetButton(false);
            $form->render();
            ?>
        </div>
    </div>
</div>
