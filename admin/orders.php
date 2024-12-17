<?php
session_start();    

// Periksa apakah pengguna telah login
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login'); // Redirect ke halaman login jika belum login
    exit;
}

// Periksa apakah role user adalah admin
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index'); // Redirect ke halaman utama jika bukan admin
    exit;
}

require_once '../koneksi.php';

// Ambil data pesanan dari tabel tbl_pesanan
$queryOrders = "SELECT id_pesanan, nama_penyewa, nomor_telepon, tgl_pinjam, tgl_kembali, 
                       id_kendaraan, id_pembayaran, total_pesanan, status_pesanan, id_user 
                FROM tbl_pesanan";
$resultOrders = $conn->query($queryOrders);

// Periksa apakah query berhasil
if (!$resultOrders) {
    die("Query gagal: " . $conn->error);
}

$message = ""; // Variabel untuk menampilkan pesan, jika diperlukan
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
    <?php if (!empty($message)): ?>
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
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Total Pesanan</th>
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
                <td><?php echo htmlspecialchars($order['tgl_pinjam']); ?></td>
                <td><?php echo htmlspecialchars($order['tgl_kembali']); ?></td>
                <td>Rp <?php echo number_format($order['total_pesanan'], 0, ',', '.'); ?></td>
                <td>
                    <?php if ($order['status_pesanan'] === 'belum bayar'): ?>
                        <span class="badge bg-warning">Belum Bayar</span>
                    <?php elseif ($order['status_pesanan'] === 'pending'): ?>
                        <span class="badge bg-info">Pending</span>
                    <?php elseif ($order['status_pesanan'] === 'sukses'): ?>
                        <span class="badge bg-success">Sukses</span>
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
