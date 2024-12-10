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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            transition: background-color 0.3s ease;
        }

        .navbar.scrolled {
            background-color: #343a40;
        }

        .btn-gradient-blue {
            background: linear-gradient(90deg, #4e54c8, #8f94fb);
            border: none;
            border-radius: 50px;
            font-size: 1.2rem;
            transition: all 0.3s ease-in-out;
        }

        .btn-gradient-blue:hover {
            background: linear-gradient(90deg, #8f94fb, #4e54c8);
            transform: scale(1.05);
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-img-top {
            object-fit: cover;
            height: 200px;
        }

        .header-container {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.7), rgba(33, 150, 243, 0.7)), url('https://via.placeholder.com/1200x400') no-repeat center center;
            background-size: cover;
            padding: 80px 0;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }

        .header-container h1 {
            font-size: 3rem;
        }

        .header-container p {
            font-size: 1.2rem;
        }

        .container {
            max-width: 1200px;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn-success, .btn-warning, .btn-secondary {
            transition: all 0.3s ease-in-out;
        }

        .btn-success:hover, .btn-warning:hover, .btn-secondary:hover {
            transform: scale(1.05);
        }

        footer {
            background-color: #343a40;
            color: white;
        }

        footer p {
            margin: 0;
        }

        @media (max-width: 768px) {
            .header-container h1 {
                font-size: 2.2rem;
            }

            .header-container p {
                font-size: 1rem;
            }

            .btn-gradient-blue {
                font-size: 1rem;
            }

            .card-img-top {
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-sm">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand fw-bold text-uppercase" href="#">S2R Rental</a>
        
        <!-- Toggler Button for mobile view -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> 

        <!-- Navbar Menu Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link px-3" href="index#vehicles">Daftar Kendaraan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="index#syaratketentuan">Syarat & Ketentuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="user/pesanan-saya">Pesanan Saya</a>
                </li>

                <!-- Dynamic Login/Logout button -->
                <?php if ($is_logged_in): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white ms-2" href="auth/logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-2" href="auth/login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

    <!-- Header -->
    <?php include('partials/header.php') ?>

    <!-- Daftar Kendaraan -->
    <div class="container py-5 mt-5" id="vehicles">
        <h2 class="text-center mb-4">Daftar Kendaraan</h2>
        <div class="row g-4">
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <img src="<?php echo htmlspecialchars($vehicle['img_kendaraan']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($vehicle['nama_kendaraan']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($vehicle['nama_kendaraan']); ?></h5>
                            <p class="card-text">Harga per hari: Rp <?php echo number_format($vehicle['harga_kendaraan'], 0, ',', '.'); ?></p>
                            <p class="card-text">Stok tersedia: <?php echo $vehicle['stok_kendaraan']; ?></p>
                            <?php if ($vehicle['stok_kendaraan'] > 0): ?>
                                <?php if ($is_logged_in): ?>
                                    <a href="user/reserve?id=<?php echo $vehicle['id_kendaraan']; ?>" class="btn btn-success">Pesan Sekarang</a>
                                <?php else: ?>
                                    <a href="auth/login" class="btn btn-warning">Pesan Sekarang</a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="btn btn-secondary disabled">Tidak Tersedia</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- syarat$ketentuan -->
    <div class="container my-5" id="syaratketentuan">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-center text-primary mb-4">Peraturan Penggunaan</h2>
                <p class="text-center text-muted mb-4">
                    Dengan menggunakan layanan ini, Anda dianggap telah membaca dan menyetujui semua ketentuan berikut.
                </p>
                <ul class="list-group">
                    <li class="list-group-item">
                        <i class="bi bi-person-check-fill text-primary"></i>
                        Pengguna harus berusia minimal 18 tahun.
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-info-circle-fill text-success"></i>
                        Informasi yang diberikan harus akurat dan benar.
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-shield-fill-exclamation text-danger"></i>
                        Website tidak bertanggung jawab atas kerugian akibat kesalahan pengguna.
                    </li>
                </ul>
            </div>
        </div>
    </div>

    
    <!-- Footer -->
    <?php include('partials/footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>