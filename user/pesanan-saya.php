<?php
include '../koneksi.php'; // Koneksi ke database
session_start(); // Memulai session untuk mengakses variabel session

// Memeriksa apakah pengguna sudah login
$is_logged_in = isset($_SESSION['user']) && is_array($_SESSION['user']); // Validasi session sudah ada dan berbentuk array

$username = $_SESSION['user']['username']; // Ambil username dari sesi pengguna

// Pesan untuk ditampilkan di antarmuka
$successMessage = $errorMessage = "";

// Jika form di-submit, proses penyimpanan pesanan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_kendaraan'], $_POST['tgl_pinjam'], $_POST['tgl_kembali'], $_POST['nomor_telepon'])) {
        $id_kendaraan = filter_var($_POST['id_kendaraan'], FILTER_SANITIZE_NUMBER_INT);
        $tgl_pinjam = $_POST['tgl_pinjam'];
        $tgl_kembali = $_POST['tgl_kembali'];
        $nomor_telepon = filter_var($_POST['nomor_telepon'], FILTER_SANITIZE_STRING);

        // Validasi data input
        if (empty($id_kendaraan) || empty($tgl_pinjam) || empty($tgl_kembali) || empty($nomor_telepon)) {
            $errorMessage = "Semua kolom wajib diisi.";
        } else {
            // Total pesanan (ganti logika sesuai kebutuhan)
            $total_pesanan = 100000; // Nilai tetap untuk contoh

            // Query untuk menyimpan pesanan baru
            $queryInsertOrder = "
                INSERT INTO tbl_pesanan (username, id_kendaraan, nama_penyewa, nomor_telepon, tgl_pinjam, tgl_kembali, total_pesanan, status_pembayaran)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'belum bayar')
            ";

            $stmtInsert = $conn->prepare($queryInsertOrder);
            if (!$stmtInsert) {
                $errorMessage = "Error dalam query: " . $conn->error;
            } else {
                $nama_penyewa = $_SESSION['user']['nama']; // Ambil nama penyewa dari sesi
                $stmtInsert->bind_param('sissssi', $username, $id_kendaraan, $nama_penyewa, $nomor_telepon, $tgl_pinjam, $tgl_kembali, $total_pesanan);

                // Eksekusi query
                if ($stmtInsert->execute()) {
                    $successMessage = "Pesanan berhasil disimpan!";
                } else {
                    $errorMessage = "Gagal menyimpan pesanan: " . $stmtInsert->error;
                }
            }
        }
    }

    // Proses pembatalan pesanan (hapus pesanan dari tabel)
    if (isset($_POST['cancel_order'], $_POST['id_pesanan'])) {
        $id_pesanan = filter_var($_POST['id_pesanan'], FILTER_SANITIZE_NUMBER_INT);

        try {
            // Query untuk memeriksa apakah status pesanan adalah 'belum bayar'
            $queryCancel = "
                DELETE FROM tbl_pesanan 
                WHERE id_pesanan = ? 
                  AND username = ? 
                  AND status_pembayaran = 'belum bayar'
            ";

            $stmtCancel = $conn->prepare($queryCancel);
            $stmtCancel->bind_param('is', $id_pesanan, $username);

            // Eksekusi query
            if ($stmtCancel->execute() && $stmtCancel->affected_rows > 0) {
                $successMessage = "Pesanan berhasil dibatalkan dan dihapus.";
            } else {
                $errorMessage = "Pesanan tidak ditemukan atau status bukan 'belum bayar'.";
            }
        } catch (Exception $e) {
            $errorMessage = "Gagal membatalkan pesanan: " . $e->getMessage();
        }
    }
}

// Query untuk mengambil data pesanan berdasarkan username yang login
$queryOrders = "
    SELECT p.id_pesanan, p.username, p.nama_penyewa, p.nomor_telepon, p.tgl_pinjam, p.tgl_kembali, 
           p.id_kendaraan, p.id_pembayaran, p.total_pesanan, p.created_at, p.id_user, p.status_pembayaran, k.nama_kendaraan
    FROM tbl_pesanan p
    JOIN tbl_kendaraan k ON p.id_kendaraan = k.id_kendaraan
    WHERE p.username = ?
";

$stmt = $conn->prepare($queryOrders);
if (!$stmt) {
    die("Error dalam query: " . $conn->error);
}
$stmt->bind_param('s', $username);
$stmt->execute();
$resultOrders = $stmt->get_result();

$message = $resultOrders->num_rows === 0 ? "Tidak ada pesanan ditemukan." : "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .container { margin-top: 50px; }
        table { background-color: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
<?php include('../partials/navbar.php'); ?>

<div class="container">
    <h1 class="text-center">Pesanan Saya</h1>
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success text-center"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php endif; ?>
    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <table class="table table-bordered mt-4">
        <thead>
        <tr class="table-primary text-center">
            <th>ID Pesanan</th>
            <th>Nama Penyewa</th>
            <th>Nomor Telepon</th>
            <th>Kendaraan</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Total Harga</th>
            <th>Status Pembayaran</th>
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
                <td><?php echo htmlspecialchars($order['tgl_pinjam']); ?></td>
                <td><?php echo htmlspecialchars($order['tgl_kembali']); ?></td>
                <td>Rp <?php echo number_format($order['total_pesanan'], 0, ',', '.'); ?></td>
                <td>
                    <?php if ($order['status_pembayaran'] === 'belum_bayar'): ?>
                        <span class="badge bg-danger">Belum Bayar</span>
                    <?php elseif ($order['status_pembayaran'] === 'sudah_bayar'): ?>
                        <span class="badge bg-success">Sudah Bayar</span>
                    <?php else: ?>
                        <span class="badge bg-warning">Status Tidak Diketahui</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($order['status_pembayaran'] === 'belum bayar'): ?>
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
