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

<!-- $host = "localhost"; // Nama host atau alamat IP dari server database
$username = "root"; // Nama pengguna database
$password = ""; // Kata sandi database
$database = "s2r_db"; // Nama database yang ingin dihubungkan

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil semua data dari tbl_user
$sql = "SELECT * FROM tbl_user";
$result = $conn->query($sql);

// Memeriksa apakah ada hasil
if ($result->num_rows > 0) {
    // Menampilkan data dalam bentuk tabel
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>";

    // Mengiterasi setiap baris hasil query
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id_user']) . "</td>";
        echo "<td>" . htmlspecialchars($row['username_user']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email_user']) . "</td>";
        echo "<td>" . htmlspecialchars($row['role_user']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "Tidak ada data di tabel user.";
}

// Menutup koneksi
$conn->close(); -->
