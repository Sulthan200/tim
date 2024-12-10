<?php
require_once '../koneksi.php';

// Proses otomatis: batalkan pesanan pending lebih dari 1 jam
$queryAutoCancel = "SELECT * FROM tbl_pesanan WHERE status_pesanan = 'pending' AND TIMESTAMPDIFF(MINUTE, created_at, NOW()) > 60";
$resultAutoCancel = $conn->query($queryAutoCancel);

while ($order = $resultAutoCancel->fetch_assoc()) {
    $updateStockQuery = "UPDATE tbl_kendaraan SET stok_kendaraan = stok_kendaraan + 1 WHERE id_kendaraan = ?";
    $stmt = $conn->prepare($updateStockQuery);
    $stmt->bind_param("i", $order['id_kendaraan']);
    $stmt->execute();

    $updateOrderQuery = "UPDATE tbl_pesanan SET status_pesanan = 'batal' WHERE id_pesanan = ?";
    $stmt = $conn->prepare($updateOrderQuery);
    $stmt->bind_param("i", $order['id_pesanan']);
    $stmt->execute();
}

// Proses manual: aksi admin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $orderId = $_POST['id_pesanan'];

    if ($action === 'acc') {
        // Konfirmasi pembayaran
        $updateOrderQuery = "UPDATE tbl_pesanan SET status_pesanan = 'selesai' WHERE id_pesanan = ?";
        $stmt = $conn->prepare($updateOrderQuery);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $message = "Pesanan berhasil dikonfirmasi.";
    } elseif ($action === 'cancel') {
        // Batalkan pesanan manual
        $orderQuery = "SELECT id_kendaraan FROM tbl_pesanan WHERE id_pesanan = ?";
        $stmt = $conn->prepare($orderQuery);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();

        if ($order) {
            $updateStockQuery = "UPDATE tbl_kendaraan SET stok_kendaraan = stok_kendaraan + 1 WHERE id_kendaraan = ?";
            $stmt = $conn->prepare($updateStockQuery);
            $stmt->bind_param("i", $order['id_kendaraan']);
            $stmt->execute();

            $updateOrderQuery = "UPDATE tbl_pesanan SET status_pesanan = 'batal' WHERE id_pesanan = ?";
            $stmt = $conn->prepare($updateOrderQuery);
            $stmt->bind_param("i", $orderId);
            $stmt->execute();

            $message = "Pesanan berhasil dibatalkan.";
        }
    } elseif ($action === 'delete') {
        // Hapus pesanan
        $deleteQuery = "DELETE FROM tbl_pesanan WHERE id_pesanan = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $message = "Pesanan berhasil dihapus.";
    }
}

// Ambil semua data pesanan
$queryOrders = "SELECT p.*, k.nama_kendaraan, j.jenis_bayar 
                FROM tbl_pesanan p
                JOIN tbl_kendaraan k ON p.id_kendaraan = k.id_kendaraan
                JOIN tbl_jenis_bayar j ON p.id_jenis_bayar = j.id_jenis_bayar
                ORDER BY p.created_at DESC";
$resultOrders = $conn->query($queryOrders);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan</title>
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

<!-- Sidebar -->
<?php include('sidebar.php'); ?>

<div class="container">
    <h1 class="text-center">Kelola Pesanan</h1>
    <?php if (isset($message)): ?>
        <div class="alert alert-success text-center">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <table class="table table-bordered mt-4">
        <thead>
        <tr class="table-primary text-center">
            <th>ID Pesanan</th>
            <th>Nama Penyewa</th>
            <th>Nomor Telepon</th>
            <th>Kendaraan</th>
            <th>Metode Pembayaran</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($order = $resultOrders->fetch_assoc()): ?>
            <tr class="text-center">
                <td><?php echo $order['id_pesanan']; ?></td>
                <td><?php echo htmlspecialchars($order['nama_penyewa']); ?></td>
                <td><?php echo htmlspecialchars($order['nomor_telepon']); ?></td>
                <td><?php echo htmlspecialchars($order['nama_kendaraan']); ?></td>
                <td><?php echo htmlspecialchars($order['jenis_bayar']); ?></td>
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
                            <button type="submit" name="action" value="acc" class="btn btn-success btn-sm">ACC</button>
                        </form>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id_pesanan" value="<?php echo $order['id_pesanan']; ?>">
                            <button type="submit" name="action" value="cancel" class="btn btn-danger btn-sm">Cancel</button>
                        </form>
                    <?php endif; ?>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="id_pesanan" value="<?php echo $order['id_pesanan']; ?>">
                        <button type="submit" name="action" value="delete" class="btn btn-secondary btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
