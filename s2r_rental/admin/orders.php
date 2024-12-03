<?php
session_start();
include('../koneksi.php');

// Periksa apakah pengguna sudah login dan memiliki hak admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Proses edit pesanan
if (isset($_POST['edit_pesanan'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $id_pemesan = $_POST['id_pemesan'];
    $id_kendaraan = $_POST['id_kendaraan'];
    $id_jenis_bayar = $_POST['id_jenis_bayar'];
    $total_pesanan = $_POST['total_pesanan'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_kembali = $_POST['tgl_kembali'];

    $query = "UPDATE tbl_pesanan SET total_pesanan = ?, tgl_pinjam = ?, tgl_kembali = ?, id_pemesan = ?, id_kendaraan = ?, id_jenis_bayar = ? WHERE id_pesanan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssiiii', $total_pesanan, $tgl_pinjam, $tgl_kembali, $id_pemesan, $id_kendaraan, $id_jenis_bayar, $id_pesanan);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Pesanan berhasil diupdate.";
    } else {
        $_SESSION['error'] = "Gagal mengupdate pesanan: " . $conn->error;
    }

    header('Location: orders.php');
    exit;
}

// Proses hapus pesanan
if (isset($_GET['hapus_pesanan'])) {
    $id_pesanan = $_GET['hapus_pesanan'];

    $query = "DELETE FROM tbl_pesanan WHERE id_pesanan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id_pesanan);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Pesanan berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus pesanan: " . $conn->error;
    }

    header('Location: orders.php');
    exit;
}

// Ambil data pesanan
$query = "SELECT * FROM tbl_pesanan";
$result = $conn->query($query);
$pesanan = [];
while ($row = $result->fetch_assoc()) {
    $pesanan[] = $row;
}

// Ambil data untuk dropdown (pemesan, kendaraan, jenis pembayaran)
$query_pemesan = "SELECT * FROM tbl_pemesan";
$result_pemesan = $conn->query($query_pemesan);

$query_kendaraan = "SELECT * FROM tbl_kendaraan";
$result_kendaraan = $conn->query($query_kendaraan);

$query_jenis_bayar = "SELECT * FROM tbl_jenis_bayar";
$result_jenis_bayar = $conn->query($query_jenis_bayar);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include('sidebar.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">Kelola Pesanan</h1>

        <!-- Daftar Pesanan -->
        <div class="mb-4">
            <h2>Daftar Pesanan</h2>
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Pemesan</th>
                        <th>Kendaraan</th>
                        <th>Total Pesanan</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pesanan)): ?>
                        <?php foreach ($pesanan as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['id_pesanan']); ?></td>
                                <td><?= htmlspecialchars($item['id_pemesan']); ?></td>
                                <td><?= htmlspecialchars($item['id_kendaraan']); ?></td>
                                <td><?= htmlspecialchars($item['total_pesanan']); ?></td>
                                <td><?= htmlspecialchars($item['tgl_pinjam']); ?></td>
                                <td><?= htmlspecialchars($item['tgl_kembali']); ?></td>
                                <td>
                                    <a href="?edit_pesanan=<?= $item['id_pesanan']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?hapus_pesanan=<?= $item['id_pesanan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?');">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada pesanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>