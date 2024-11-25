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
        /* css */
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h1, .form-container h3 {
            font-weight: bold;
            text-align: center;
            color: #4e54c8;
        }

        .form-container h3 {
            margin-top: -10px;
            margin-bottom: 30px;
            font-size: 1.25rem;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 8px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: #4e54c8;
            box-shadow: 0 0 8px rgba(78, 84, 200, 0.4);
        }

        button.btn-primary {
            background: #4e54c8;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s;
        }

        button.btn-primary:hover {
            background-color: #6366f1;
            transform: scale(1.02);
        }

        button.btn-primary:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(78, 84, 200, 0.5);
        }
        /* css */

    </style>
</head>
<body>
<div class="form-container">
    <h1>Pesan Kendaraan</h1>
    <h3><?php echo htmlspecialchars($vehicle['nama_kendaraan']); ?></h3>
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

</body>
</html>


