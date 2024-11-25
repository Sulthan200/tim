<?php
session_start(); // Memulai session

// Periksa apakah pengguna sudah login
$is_logged_in = isset($_SESSION['user']); // True jika pengguna sudah login

// Koneksi ke database
$host = "localhost"; // Nama host atau alamat IP dari server database
$username = "root"; // Nama pengguna database
$password = ""; // Kata sandi database
$database = "s2r_db"; // Nama database yang ingin dihubungkan

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data kendaraan
$sql = "SELECT id_kendaraan, nama_kendaraan, harga_kendaraan, img_kendaraan, stok_kendaraan FROM tbl_kendaraan";

$result = $conn->query($sql);

// Cek apakah query berhasil dijalankan
if (!$result) {
    die("Query gagal dijalankan: " . $conn->error);
}

$vehicles = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row; // Menyimpan data kendaraan ke array $vehicles
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sewa Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            transition: background-color 0.3s ease;
        }

        .navbar.scrolled {
            background-color: #343a40;
        }

        .btn-gradient {
            background: linear-gradient(90deg, #4CAF50, #2196F3);
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            transition: all 0.3s ease-in-out;
            color: #fff;
            padding: 10px 20px;
        }

        .btn-gradient:hover {
            background: linear-gradient(90deg, #2196F3, #4CAF50);
            transform: scale(1.05);
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.03);
        }

        .card-img-top {
            object-fit: cover;
            height: 200px;
        }

        .header-container {
            background: linear-gradient(135deg, rgba(63, 94, 251, 0.8), rgba(70, 252, 167, 0.8)), url('https://via.placeholder.com/1200x400') no-repeat center center;
            background-size: cover;
            padding: 80px 0;
            color: white;
            text-align: center;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .header-container h1 {
            font-size: 2.8rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .header-container p {
            font-size: 1.1rem;
        }

        .card-body h5 {
            font-weight: bold;
        }

        .badge-stock {
            font-size: 0.9rem;
            border-radius: 10px;
        }

        footer {
            background-color: #222;
            color: white;
            padding: 20px 0;
        }

        footer a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #00ccff;
        }

        @media (max-width: 768px) {
            .header-container h1 {
                font-size: 2rem;
            }

            .header-container p {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Header -->
    <?php include('header.php') ?>

    <!-- Daftar Kendaraan -->
    <div class="container py-5 mt-5" id="vehicles">
        <h2 class="text-center mb-5 text-primary">Daftar Kendaraan</h2>
        <div class="row g-4">
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($vehicle['img_kendaraan']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($vehicle['nama_kendaraan']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($vehicle['nama_kendaraan']); ?></h5>
                            <p class="card-text">
                                <i class="fas fa-tag text-success"></i> Harga per hari: 
                                <span class="text-primary">Rp <?php echo number_format($vehicle['harga_kendaraan'], 0, ',', '.'); ?></span>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-car text-secondary"></i> Stok: 
                                <span class="badge badge-stock <?php echo $vehicle['stok_kendaraan'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $vehicle['stok_kendaraan'] > 0 ? $vehicle['stok_kendaraan'] . ' Tersedia' : 'Habis'; ?>
                                </span>
                            </p>
                            <?php if ($vehicle['stok_kendaraan'] > 0): ?>
                                <?php if ($is_logged_in): ?>
                                    <a href="reserve.php?id=<?php echo $vehicle['id_kendaraan']; ?>" class="btn btn-gradient">Pesan Sekarang</a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-warning">Login untuk Pesan</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="btn btn-secondary disabled">Tidak Tersedia</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>&copy; 2024 S2R Rental. All Rights Reserved.</p>
            <a href="#" class="text-decoration-none">Kembali ke atas</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
