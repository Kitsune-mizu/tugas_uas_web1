<?php
$db = $GLOBALS['db'];
$auth = $GLOBALS['auth'];

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($auth->login($username, $password)) {
        header("Location: " . BASE_URL . "/home/index");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory System</title>
    <link href="<?php echo BASE_URL; ?>/assets/css/auth.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-boxes"></i>
                    <h2>Inventory System</h2>
                </div>
                <p>Silakan login untuk mengakses sistem</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <?php
                // Create login form using Form class
                $form = new Form('', 'Login', 'POST');
                $form->setResetButton(false);
                $form->addField('username', 'Username', 'text', $_POST['username'] ?? '');
                $form->addField('password', 'Password', 'password', '');
                
                $form->render();
                ?>
            </form>

            <div class="auth-footer">
                <p>Default login: <strong>admin</strong> / <strong>admin123</strong></p>
            </div>
        </div>
    </div>
</body>
</html>