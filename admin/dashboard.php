<?php
session_start();

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login'); // Redirect ke halaman login jika belum login
    exit;
}

// Periksa apakah role user adalah admin
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index'); // Redirect ke halaman utama jika bukan admin
    exit;
}

// Koneksi ke database
include('../koneksi.php'); // Pastikan file ini mendefinisikan variabel $conn

// Periksa apakah koneksi berhasil
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

try {
    // Query untuk menghitung total data
    $totalUsers = $conn->query("SELECT COUNT(*) AS total FROM tbl_user")->fetch_assoc()['total'];
    $totalOrders = $conn->query("SELECT COUNT(*) AS total FROM tbl_pesanan")->fetch_assoc()['total'];
    $totalProducts = $conn->query("SELECT COUNT(*) AS total FROM tbl_kendaraan")->fetch_assoc()['total'];

    // Query untuk data aktivitas terbaru berdasarkan ID
    $query = "SELECT id_user, username_user, role_user FROM tbl_user ORDER BY id_user DESC LIMIT 10";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception("Query gagal: " . $conn->error);
    }
} catch (Exception $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <p class="card-text"><?= htmlspecialchars($totalUsers) ?></p>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <p class="card-text"><?= htmlspecialchars($totalOrders) ?></p>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Products</h5>
                        <p class="card-text"><?= htmlspecialchars($totalProducts) ?></p>
                    </div>
                </div>
            </div>
        </div>

    <?php
    // Tutup koneksi
    $conn->close();
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>