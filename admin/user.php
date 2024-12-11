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
// Sertakan koneksi database
include('../koneksi.php');

// Query untuk mengambil data pengguna
$sql = "SELECT id_user, username_user, email_user, role_user FROM tbl_user";
$result = $conn->query($sql);

// Cek apakah query berhasil dijalankan
if (!$result) {
    die("Query gagal dijalankan: " . $conn->error);
}

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row; // Menyimpan data pengguna ke array $users
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <div class="container mt-4">
        <h1 class="mb-4">Daftar Pengguna</h1>
        <table class="table table-striped">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($user['username_user']); ?></td>
                            <td><?php echo htmlspecialchars($user['email_user']); ?></td>
                            <td><?php echo htmlspecialchars($user['role_user']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data pengguna.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>