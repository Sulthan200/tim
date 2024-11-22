<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - S2R Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            background: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .hero-section {
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
            color: white;
            padding: 70px 0;
            text-align: center;
            clip-path: ellipse(100% 75% at 50% 0%);
        }
        .hero-section h1 {
            font-size: 2.8rem;
            font-weight: bold;
        }
        .hero-section p {
            font-size: 1.2rem;
            margin-top: 15px;
        }
        .contact-card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .contact-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .contact-card h2 {
            color: #4e54c8;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .contact-card ul {
            padding: 0;
            list-style: none;
        }
        .contact-card ul li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .contact-card ul li i {
            font-size: 1.5rem;
            margin-right: 15px;
            color: #4e54c8;
        }
        footer {
            background-color: #4e54c8;
            color: #ffffff;
            padding: 20px 0;
            text-align: center;
            margin-top: 40px;
        }
        footer a {
            color: #ffffff;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
        .btn-gradient {
            background: linear-gradient(90deg, #4e54c8, #8f94fb);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            text-transform: uppercase;
            transition: background 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(90deg, #8f94fb, #4e54c8);
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
                        <a class="nav-link active" href="index.php">Layanan</a>
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
        <h1 class="animate__animated animate__fadeInDown">Hubungi Kami</h1>
        <p class="animate__animated animate__fadeInUp animate__delay-1s">Kami selalu siap membantu kebutuhan Anda!</p>
        <a href="#contact" class="btn btn-gradient mt-3">Hubungi Sekarang</a>
    </div>

    <!-- Kontak Kami -->
    <div class="container mt-5" id="contact">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="contact-card animate__animated animate__fadeInUp">
                    <h2>Informasi Kontak</h2>
                    <ul>
                        <li><i class="bi bi-envelope-fill"></i><strong>Email:</strong> S2Rrental@gmail.com</li>
                        <li><i class="bi bi-telephone-fill"></i><strong>Telepon:</strong> +6289629833504</li>
                        <li><i class="bi bi-geo-alt-fill"></i><strong>Alamat:</strong> Jalan Paledang No.5</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 S2R Rental. All Rights Reserved.</p>
        <a href="#" class="text-white">Kembali ke atas</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"></script>
</body>
</html>
