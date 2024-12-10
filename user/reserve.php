<?php
require_once '../koneksi.php';

session_start(); // Memulai session

// Pastikan pengguna sudah login
if (!isset($_SESSION['user'])) {
    die("Anda harus login terlebih dahulu.");
}

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

if (!$vehicle || $vehicle['stok_kendaraan'] <= 0) {
    die("Kendaraan tidak tersedia.");
}

// Ambil data jenis pembayaran
$paymentQuery = "SELECT * FROM tbl_jenis_bayar";
$paymentResult = $conn->query($paymentQuery);
$paymentMethods = $paymentResult->fetch_all(MYSQLI_ASSOC);

// Proses pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $paymentMethod = $_POST['payment_method'];

    // Hitung total hari dan harga
    $diffTime = strtotime($endDate) - strtotime($startDate);
    $totalDays = ceil($diffTime / (60 * 60 * 24));
    $totalPrice = $vehicle['harga_kendaraan'] * $totalDays;

    // Ambil id_user dari session
    $userId = $_SESSION['user']['id'];

    // Simpan data pemesanan ke database
    $conn->begin_transaction();
    try {
        $insertQuery = "INSERT INTO tbl_pesanan (id_user, nama_penyewa, nomor_telepon, tgl_pinjam, tgl_kembali, id_kendaraan, id_jenis_bayar, total_pesanan, status_pesanan) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("issssiii", $userId, $name, $phone, $startDate, $endDate, $vehicleId, $paymentMethod, $totalPrice);
        $stmt->execute();

        // Kurangi stok kendaraan
        $updateStockQuery = "UPDATE tbl_kendaraan SET stok_kendaraan = stok_kendaraan - 1 WHERE id_kendaraan = ?";
        $stmt = $conn->prepare($updateStockQuery);
        $stmt->bind_param("i", $vehicleId);
        $stmt->execute();

        $conn->commit();

        echo "<h2>Pemesanan Berhasil</h2>";
        echo "<p>Silakan selesaikan pembayaran Anda dalam 1 jam untuk menghindari pembatalan.</p>";
    } catch (Exception $e) {
        $conn->rollback();
        echo "<h2>Pemesanan Gagal</h2>";
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
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
        .form-container {
            margin-top: 50px;
        }

        .form-card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #4e54c8;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #6c63ff;
        }

        h1, h3 {
            text-align: center;
            color: #4e54c8;
        }
    </style>
</head>
<body>
<div class="container form-container d-flex justify-content-center">
    <div class="form-card col-md-6">
        <h1>Pesan Kendaraan</h1>
        <h3><?php echo htmlspecialchars($vehicle['nama_kendaraan']); ?></h3>
        <p>Harga per hari: Rp <?php echo number_format($vehicle['harga_kendaraan'], 0, ',', '.'); ?></p>
        <p>Stok tersedia: <?php echo $vehicle['stok_kendaraan']; ?></p>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Penyewa</label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="text" name="phone" class="form-control" placeholder="Masukkan nomor telepon Anda" required>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai Sewa</label>
                <input type="date" name="start_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Akhir Sewa</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                <select name="payment_method" class="form-control" required>
                    <option value="">Pilih Metode Pembayaran</option>
                    <?php foreach ($paymentMethods as $method): ?>
                        <option value="<?php echo $method['id_jenis_bayar']; ?>">
                            <?php echo htmlspecialchars($method['jenis_bayar']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Pesan</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
