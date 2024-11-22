<?php
$host = "localhost"; // Nama host atau alamat IP dari server database
$username = "root"; // Nama pengguna database
$password = ""; // Kata sandi database
$db_name = "s2r_db"; // Nama database yang ingin dihubungkan

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $db_name);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
} 

?>