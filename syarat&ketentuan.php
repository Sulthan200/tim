<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .hero-section {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            padding: 60px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 20%, transparent 80%);
            transform: translateX(-50%);
            animation: move 6s infinite linear;
            z-index: 0;
        }
        @keyframes move {
            0% { transform: translateX(-50%) rotate(0); }
            100% { transform: translateX(-50%) rotate(360deg); }
        }
        .hero-section h1 {
            font-size: 3rem;
            z-index: 1;
            position: relative;
        }
        .hero-section p {
            font-size: 1.2rem;
            margin-top: 10px;
            z-index: 1;
            position: relative;
        }
        .list-group-item {
            background-color: #ffffff;
            border: none;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .list-group-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .list-group-item i {
            font-size: 1.8rem;
            margin-right: 15px;
        }
        footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px 0;
            text-align: center;
        }
        footer a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s;
        }
        footer a:hover {
            color: #ffcc00;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">S2R Rental</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="syarat&ketentuan.php">Ketentuan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kontak.php">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="animate__animated animate__fadeInDown">Syarat & Ketentuan</h1>
            <p class="lead animate__animated animate__fadeInUp animate__delay-1s">Ketahui aturan sebelum menggunakan layanan kami.</p>
        </div>
    </div>

    <!-- Syarat & Ketentuan -->
    <div class="container my-5">
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
    <?php include('footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
