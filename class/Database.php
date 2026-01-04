<?php
class Database {
    protected $host;
    protected $user;
    protected $password;
    protected $db_name;
    protected $conn;

    // Tambahkan parameter $config ke __construct
    public function __construct($config) { 
        $this->host = $config['host'];
        $this->user = $config['username'];
        $this->password = $config['password'];
        $this->db_name = $config['db_name'];
       
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->db_name);
        
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }
    
    public function getConnection() {
        return $this->conn;
    }

    public function escape_string($string) {
        return $this->conn->real_escape_string($string);
    }

    public function insert($table, $data) {
        if (is_array($data)) {
            $columns = implode(",", array_keys($data));
            $values = "'" . implode("','", array_values($data)) . "'";
            $sql = "INSERT INTO $table ($columns) VALUES ($values)";
            return $this->conn->query($sql);
        }
        return false;
    }

    public function update($table, $data, $where) {
        if (is_array($data)) {
            $set = "";
            foreach ($data as $key => $value) {
                $set .= "$key='$value', ";
            }
            $set = rtrim($set, ", ");
            $sql = "UPDATE $table SET $set WHERE $where";
            return $this->conn->query($sql);
        }
        return false;
    }

    public function delete($table, $where) {
        $sql = "DELETE FROM $table WHERE $where";
        return $this->conn->query($sql);
    }

    public function getAll($table, $where = null, $order = null) {
        $sql = "SELECT * FROM $table";
        if ($where) $sql .= " WHERE $where";
        if ($order) $sql .= " ORDER BY $order";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) return $result->fetch_all(MYSQLI_ASSOC);
        return [];
    }
    
    public function get($table, $where = null) {
        $sql = "SELECT * FROM $table";
        if ($where) $sql .= " WHERE $where";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) return $result->fetch_assoc();
        return false;
    }
}
?>