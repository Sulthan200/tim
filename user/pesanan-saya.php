<?php
session_start(); // Memulai session

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login'); // Redirect jika belum login
    exit;
}
$is_logged_in = isset($_SESSION['user']);
$userId = $_SESSION['user']['id_user']; // Ambil ID user dari session

require_once '../koneksi.php'; // Koneksi ke database

// Ambil data pesanan user
$queryOrders = "SELECT p.*, k.nama_kendaraan 
                FROM tbl_pesanan p
                JOIN tbl_kendaraan k ON p.id_kendaraan = k.id_kendaraan
                WHERE p.id_user = ?
                ORDER BY p.created_at DESC";
$stmt = $conn->prepare($queryOrders);
$stmt->bind_param("i", $userId);
$stmt->execute();
$resultOrders = $stmt->get_result();

// Proses pembatalan pesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $orderId = $_POST['id_pesanan'];

    // Cek apakah pesanan masih berstatus pending
    $queryCheck = "SELECT * FROM tbl_pesanan WHERE id_pesanan = ? AND status_pesanan = 'pending' AND id_user = ?";
    $stmt = $conn->prepare($queryCheck);
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
    $resultCheck = $stmt->get_result();
    $order = $resultCheck->fetch_assoc();

    if ($order) {
        // Update stok kendaraan
        $updateStockQuery = "UPDATE tbl_kendaraan SET stok_kendaraan = stok_kendaraan + 1 WHERE id_kendaraan = ?";
        $stmt = $conn->prepare($updateStockQuery);
        $stmt->bind_param("i", $order['id_kendaraan']);
        $stmt->execute();

        // Ubah status pesanan menjadi batal
        $updateOrderQuery = "UPDATE tbl_pesanan SET status_pesanan = 'batal' WHERE id_pesanan = ?";
        $stmt = $conn->prepare($updateOrderQuery);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        $message = "Pesanan berhasil dibatalkan.";
    } else {
        $message = "Pesanan tidak dapat dibatalkan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        table {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<?php include('../partials/navbar.php'); ?>

<div class="container">
    <h1 class="text-center">Pesanan Saya</h1>
    <?php if (isset($message)): ?>
        <div class="alert alert-info text-center">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <table class="table table-bordered mt-4">
        <thead>
        <tr class="table-primary text-center">
            <th>ID Pesanan</th>
            <th>Kendaraan</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($order = $resultOrders->fetch_assoc()): ?>
            <tr class="text-center">
                <td><?php echo $order['id_pesanan']; ?></td>
                <td><?php echo htmlspecialchars($order['nama_kendaraan']); ?></td>
                <td><?php echo $order['tgl_pinjam']; ?></td>
                <td><?php echo $order['tgl_kembali']; ?></td>
                <td>Rp <?php echo number_format($order['total_pesanan'], 0, ',', '.'); ?></td>
                <td>
                    <?php if ($order['status_pesanan'] === 'pending'): ?>
                        <span class="badge bg-warning">Pending</span>
                    <?php elseif ($order['status_pesanan'] === 'selesai'): ?>
                        <span class="badge bg-success">Selesai</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Batal</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($order['status_pesanan'] === 'pending'): ?>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id_pesanan" value="<?php echo $order['id_pesanan']; ?>">
                            <button type="submit" name="cancel_order" class="btn btn-danger btn-sm">Cancel</button>
                        </form>
                    <?php else: ?>
                        <span class="text-muted">Tidak Ada Aksi</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
