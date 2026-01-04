<?php
/**
 * Class Auth
 * Deskripsi: Class untuk handling authentication menggunakan password_hash.
 */
class Auth {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function login($username, $password) {
        $username = $this->db->escape_string($username);
      
        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $this->db->query($sql);
        
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
           
            if (password_verify($password, $user['password'])) {
             
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama'] = $user['nama']; 
                $_SESSION['login_time'] = time();
                $_SESSION['role'] = $user['role'];
          
                return true;
            }
        }
        return false;
    }

    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public function requireAdmin() {
        if (!$this->isAdmin()) {
            header("Location: " . BASE_URL . "/home/index");
            exit;
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['username']);
    }

    public function logout() {
        session_destroy();
        $_SESSION = array(); 
        header("Location: " . BASE_URL . "/auth/login");
        exit;
    }

    public function getUser() {
        return $_SESSION['username'] ?? null;
    }
    
    public function getUserName() {
        return $_SESSION['nama'] ?? null;
    }

    // --- Metode Baru untuk Profil ---
    public function getUserData() {
        if (!$this->isLoggedIn()) {
            return false;
        }
        $username = $this->db->escape_string($_SESSION['username']);
        return $this->db->get('users', "username = '$username'");
    }

    public function updatePassword($username, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $data = ['password' => $hashed_password];
        $username = $this->db->escape_string($username);
        $where = "username = '$username'";
        
        return $this->db->update('users', $data, $where);
    }
 
    public function checkAccess() {
        if (!$this->isLoggedIn()) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
    }
}
?>