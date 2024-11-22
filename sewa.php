<?php
session_start();

// if (!isset($_SESSION['loggedin'])) {
//     header('Location: login.php');
//     exit();
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Menu Sewa Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #6c63ff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 10px;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Pilih Kendaraan yang Ingin Disewa</h2>
    <div class="row">
        <?php foreach ($kendaraan as $item): ?>
        <div class="col-md-4 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?= $item['nama']; ?></h5>
                    <p class="card-text">Jenis: <?= $item['jenis']; ?></p>
                    <p class="card-text">Harga: Rp <?= number_format($item['harga'], 0, ',', '.'); ?></p>
                    <button class="btn btn-success">Sewa Sekarang</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>