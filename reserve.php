<?php
require_once 'koneksi.php';

// Ambil data kendaraan berdasarkan ID
$vehicleId = $_GET['id'] ?? null;

if (!$vehicleId) {
    die("Kendaraan tidak ditemukan.");
}

$sql = "SELECT * FROM tbl_kendaraan WHERE id_kendaraan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicleId);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();

if (!$vehicle) {
    die("Kendaraan tidak ditemukan.");
}

// Proses pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    // Hitung total hari dan harga
    $diffTime = strtotime($endDate) - strtotime($startDate);
    $totalDays = ceil($diffTime / (60 * 60 * 24));
    $totalPrice = $vehicle['harga_kendaraan'] * $totalDays;

    // Tampilkan konfirmasi pemesanan
    echo "<h2>Pemesanan Berhasil</h2>";
    echo "<p>Nama Penyewa: $name</p>";
    echo "<p>Email: $email</p>";
    echo "<p>Nomor Telepon: $phone</p>";
    echo "<p>Kendaraan: " . $vehicle['nama_kendaraan'] . "</p>";
    echo "<p>Harga Total: Rp " . number_format($totalPrice, 0, ',', '.') . "</p>";
    echo "<p>Tanggal Sewa: $startDate hingga $endDate</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Effect untuk form dan input agar terlihat 3D */
        .form-container {
            perspective: 1000px;
        }

        .form-card {
            transform-style: preserve-3d;
            transform: rotateX(0deg);
            transition: transform 0.3s ease-in-out;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 30px;
            background-color: #fff;
        }

        .form-card:hover {
            transform: rotateX(10deg);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
        }

        .form-control {
            transition: all 0.3s ease;
            border-radius: 10px;
            padding: 10px;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.2), 0 0 8px rgba(78, 84, 200, 0.7);
            border-color: #8f94fb;
        }

        button.btn-primary {
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 50px;
            transition: transform 0.2s ease-in-out;
        }

        button.btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-weight: bold;
        }

        h3 {
            color: #4e54c8;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container mt-5 form-container d-flex justify-content-center">
    <div class="form-card">
        <h1 class="text-center">Pesan Kendaraan</h1>
        <h3 class="text-center"><?php echo htmlspecialchars($vehicle['nama_kendaraan']); ?></h3>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Penyewa</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai Sewa</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Akhir Sewa</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Pesan</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

