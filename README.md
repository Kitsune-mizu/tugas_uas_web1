# Penjelasan Program web atau Aplikasi sederhana (Mobile First) untuk Tugas UAS
```
PRIYA FAWAZ ZAIDAN KHOIR (TI.24.A2)
NIM 			: 312410216
Matkul 		    : Pemrograman Web 1 UAS
Dosen Pengampu 	: Agung Nugroho, S.Kom., M.Kom.

```
## - Link Revositoty GitHub (https://github.com/Kitsune-mizu/tugas_uas_web1)

[![Tonton Video](https://img.youtube.com/vi/l4vPtvKf5o8/hqdefault.jpg)](https://youtu.be/l4vPtvKf5o8)
## - Link Youtube (https://youtu.be/l4vPtvKf5o8)
## - PDF [Pemrograman Web 1- UAS.pdf](https://github.com/user-attachments/files/24445431/Pemrograman.Web.1-.UAS.pdf)
# Struktur Program
```
TUGAS_UAS_WEB1/
├── .htaccess            (Konfigurasi Rewrite URL)
├── config.php           (Konfigurasi Database)
├── index.php            (Routing / Gerbang Utama)
├── assets/              (Css & Gambar)
│   ├── css/
|   |   ├── auth.css
|   |   └── style.css
│   └── gambar/
├── helpers/             (Helpers)
|   └── auth_helper.php
├── class/               (Library Class)
│   ├── Database.php
│   ├── Form.php
│   └── Auth.php         
├── template/            (Layout)
│   ├── header.php
│   └── footer.php
└── module/              (Modul Website)
    ├── home/            (Modul Dashboard)
    │   └── index.php    
    ├── user/            (Modul Profile)
    |   └── profile.php
    ├── barang/          (Modul Barang)
    │   ├── index.php    
    │   ├── tambah.php   
    │   ├── ubah.php     
    │   └── hapus.php    
    └── auth/            (Modul Login)
        ├── login.php
        └── logout.php
```
# Untuk bagian kode pada SQL
```sql
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100),
    role ENUM('admin','user') DEFAULT 'user'
);

-- password_hash("admin123", PASSWORD_DEFAULT)
INSERT INTO users (username, password, nama, role)
VALUES (
    'admin',
    '$2y$10$3mrGAyI5PCQhIl7cVWObgOm6s/q9W8wdvKAJUcT0qEmb6xF/IN2KO',
    'Administrator',
    'admin'
);

-- password: user123
INSERT INTO users (username, password, nama, role)
VALUES (
    'user',
    '$2y$10$bsJh0jmEGC8iaZMXO71SjeYcjPj4DnMatNcqmR7QK.IvfNnLwWpyy',
    'User Biasa',
    'user'
);
```
# Pejelasan dan Screenshot Program
## Tampilan dekstop/Web

![alt text](image.png)

* Tampilan form login untuk (admin dan user) (http://localhost/tugas_uas_web1/auth/login)
* Berisi tampilan input username dan password yang sudah di tentukan.

![alt text](image-1.png)

* Halaman antarmuka (admin) (http://localhost/tugas_uas_web1/home/index) berisi waktu, informasi seperti stock barang, preview barang baru, jumlah dan jenis distribusi kategori barang, dan quick actions.
* Menu header berisi button dashboard, data barang, tambah barang, profile dan logout role.

![alt text](image-2.png)

* Sedangkan halaman antarmuka (user) (http://localhost/tugas_uas_web1/home/index) hanya berisi waktu, dan preview barang baru.
* Menu header hanya berisi button dashboard, data barang, profile dan logout role.

![alt text](image-4.png)

* Halaman antarmuka (admin) (http://localhost/tugas_uas_web1/barang/index) tampilan data barang, admin bisa menambahkan barang dan melakukan aksi CRUD.
* Pada tampilan katalog barang berisi percarian barang (Search), katalog barang berisi (id barang, gambar, nama barang, kategori, harga beli/jual, stok, aksi) dan (Pagination).

![alt text](image-5.png)

* Tampilan Edit Barang khusus role (admin) (http://localhost/tugas_uas_web1/barang/ubah/1).
* Pada bagian edit barang berisi nama, kategori, harga beli, harga jual, stok dan upload gambar barang/produk dengan ukuran maks.2mb.

![alt text](image-13.png)

* Saat melakukan aksi hapus Barang khusus role (admin) (http://localhost/tugas_uas_web1/barang/ubah/1) akan muncul popup konfirmasi "Hapus data?" lalu tekan "yes" untuk menhapus dan "cancel" untuk membatalkan.

![alt text](image-3.png)

* Sedangkan halaman antarmuka (user) tidak bisa melakukan tambah barang maupun melakukan aksi CRUD.
* Sama hanya menampilkan katalog barang berisi percarian barang (Search), katalog barang berisi (id barang, gambar, nama barang, kategori, harga beli/jual, stok, aksi) dan (Pagination).

![alt text](image-6.png)

* Halaman antarmuka khusus role (admin) (http://localhost/tugas_uas_web1/barang/tambah) untuk menambah data barang dengan mengisi nama, kategori, harga beli, harga jual, stok dan upload gambar barang/produk dengan ukuran maks.2mb.
* Sedangkan untuk role (user) tidak ada.

![alt text](image-7.png)

* Dan untuk halaman antarmuka dari profile role (admin dan user) (http://localhost/tugas_uas_web1/user/profile) sama menampilkan data akun sederhana berisi: nama dan username, dan ubah password dengan mengisi password lama dan baru lalu konfirmasi password.

# Tampilan mobile first

![alt text](image-8.png)

* Untuk halaman antarmuka pada mobile yang membedakan dari versi Desktop hanya bagian menu nav header menggunakan menu burger, selebihnya sama fungsinya dengan versi web/dekstop nya dan hanya bagian tata letak dan layout yang disesuaikan.

# Tampilan lainnya untuk Mobile First

![alt text](image-12.png)    ![alt text](image-9.png)    ![alt text](image-10.png)   ![alt text](image-11.png)
