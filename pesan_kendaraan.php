<?php
// Koneksi ke database
$host = 'localhost';
$db = 'rental_db';
$user = 'root';
$password = '';
$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Simulasi data kendaraan (seharusnya dari database)
$vehicle = ['nama_kendaraan' => 'Toyota Avanza'];

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_penyewa = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $vehicle_name = $vehicle['nama_kendaraan'];

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO orders (nama_penyewa, email, phone, start_date, end_date, vehicle_name) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama_penyewa, $email, $phone, $start_date, $end_date, $vehicle_name);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id; // Ambil ID terakhir
        $stmt->close();

        // Redirect ke halaman struk dengan ID pemesanan
        header("Location: struk.php?id=$order_id");
        exit();
    } else {
        $error = "Gagal menyimpan data. Silakan coba lagi.";
    }
}
?>
