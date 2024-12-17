<?php
// Sertakan file koneksi
require_once '../koneksi.php';

// Inisialisasi variabel untuk pesan error atau sukses
$error = '';
$success = '';

// Proses form register
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Semua field harus diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        // Hash password untuk keamanan
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username atau email sudah digunakan
        $check_user_query = "SELECT * FROM tbl_user WHERE username_user = ? OR email_user = ?";
        $stmt = $conn->prepare($check_user_query);
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Username atau email sudah digunakan.';
        } else {
            // Simpan data pengguna ke database
            $insert_query = "INSERT INTO tbl_user (username_user, email_user, password_user, role_user) VALUES (?, ?, ?, 'user')";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param('sss', $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = 'Registrasi berhasil. Silakan login.';
            } else {
                $error = 'Terjadi kesalahan saat menyimpan data.';
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sewa Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    
        .form-control {
            background-color: white;
            color: black;
        }
        body{ 
            background-color: #133E87;
        }
        .login{
            color: aliceblue;
        }
        a{
            text-decoration: none;
            color: aliceblue;

        }
        .foto{
            filter: drop-shadow(0 0 10px rgba(0, 0, 0, 10));
        }
        label {
            color: white;
        }

    </style>
</head>
<body>
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="d-flex justify-content-center align-items-center">
                <img src="img/Logo_S2R.png" class="foto text-center d-flex" width="200" alt="">
            </div>
            <h4 class="login mt-5">Registerr</h4>


                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" class="mt-4">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
    </div>

    <button type="submit" class="btn btn-success w-100">Daftar</button>
</form>

    
                <div class="mt-3">
                    <a href="login">Login User</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>