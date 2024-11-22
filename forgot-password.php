<?php
// Konfigurasi database
$host = 'localhost';
$db_name = 's2r_db';
$db_username = 'root';
$db_pass = '';

$error = '';
$message = '';

// Proses form jika metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Membuat koneksi ke database
    $conn = new mysqli($host, $db_username, $db_pass, $db_name);

    // Periksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Query untuk memeriksa apakah email ada di database
    $sql = "SELECT * FROM tbl_user WHERE email_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email ditemukan, generate password baru
        $new_password = bin2hex(random_bytes(4)); // Membuat password 8 karakter acak
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Enkripsi password

        // Update password di database
        $update_sql = "UPDATE tbl_user SET password_user = ? WHERE email_user = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('ss', $hashed_password, $email);
        $update_stmt->execute();

        if ($update_stmt->affected_rows > 0) {
            // Kirimkan password baru ke email
            $message = "Password Anda berhasil di-reset. Silakan cek email Anda untuk password baru.";

            // Gunakan fungsi kirim email
            $to = $email;
            $subject = "Reset Password";
            $body = "Halo,\n\nPassword baru Anda adalah: $new_password\n\nSilakan login menggunakan password ini.";
            $headers = "From: admin@s2r.com";

            if (mail($to, $subject, $body, $headers)) {
                $message .= " Email berhasil dikirim.";
            } else {
                $error = "Password berhasil di-reset, tetapi email gagal dikirim.";
            }
        } else {
            $error = "Terjadi kesalahan saat mereset password.";
        }
        $update_stmt->close();
    } else {
        $error = "Email tidak ditemukan.";
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sewa Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="mt-5 text-center">Lupa Password</h1>

                <!-- Pesan error atau sukses -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php elseif (!empty($message)): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>

                <!-- Form lupa password -->
                <form method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">Reset Password</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
