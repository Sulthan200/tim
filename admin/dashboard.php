<?php
session_start();



// Periksa apakah pengguna telah login
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php'); // Redirect ke halaman login jika belum login
    exit;
}

// Periksa apakah role user adalah admin
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php'); // Redirect ke halaman utama jika bukan admin
    exit;
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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #eef2f7;
            color: #333;
        }

        .sidebar {
            min-width: 260px;
            max-width: 260px;
            height: 100vh;
            background-color: #1d3557;
            color: white;
            padding: 30px 10px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            text-align: center;
            font-weight: 700;
            font-size: 22px;
            margin-bottom: 30px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 600;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .sidebar a:hover {
            background-color: #457b9d;
            transform: translateX(5px);
        }

        .sidebar i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
        }

        .navbar {
            background-color: #1d3557;
            padding: 10px 20px;
            border-radius: 8px;
        }

        .navbar-brand {
            color: white;
            font-weight: 700;
        }

        .navbar .nav-link {
            color: white;
            margin-left: 10px;
            font-weight: 600;
        }

        .navbar .nav-link:hover {
            color: #a8dadc;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-body {
            text-align: center;
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #1d3557;
        }

        .card-text {
            font-size: 2rem;
            font-weight: bold;
            color: #457b9d;
        }

        .table th {
            background-color: #1d3557;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f5f9;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #1d3557;
            color: white;
            margin-top: 30px;
            border-radius: 8px;
        }

        .btn-logout {
            background-color: #e63946;
            border: none;
            padding: 8px 15px;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #d62828;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</a>
        <a href="detail_pesanan.php"><i class="fas fa-users"></i> Users</a>
        <a href="orders.php"><i class="fas fa-box"></i> Orders</a>
        <a href="produk.php"><i class="fas fa-tags"></i> Products</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Dashboard Admin</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../logout.php">
                                <button class="btn-logout">Logout</button>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Cards Section -->
        <div class="container mt-4">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text">150</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <p class="card-text">320</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Total Products</h5>
                            <p class="card-text">50</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="row mt-5">
                <div class="col-md-12">
                    <h3>Recent Activity</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Activity</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>Logged In</td>
                                <td>10:00 AM</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>Placed an Order</td>
                                <td>9:45 AM</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Bob Johnson</td>
                                <td>Updated Profile</td>
                                <td>9:30 AM</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2024 Admin Dashboard. All rights reserved.</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
