<?php
session_start();

// Jika pengguna sudah login, arahkan ke halaman berdasarkan role mereka
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] == 'admin') {
        header('Location: ../admin/dashboard.php'); // Arahkan ke dashboard admin
    } else {
        header('Location: ../index.php'); // Arahkan ke halaman utama user
    }
    exit;
}

// Konfigurasi database
$host = 'localhost';
$db_name = 's2r_db';
$db_user = 'root';
$db_pass = '';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Periksa koneksi
if ($conn->connect_error) {
    die('Koneksi ke database gagal: ' . $conn->connect_error);
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Query untuk mencari pengguna berdasarkan username
    $sql = "SELECT * FROM tbl_user WHERE username_user = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Periksa keberadaan pengguna dan validasi password
        if ($user && password_verify($password, $user['password_user'])) {
            // Menyimpan data pengguna dan role ke dalam session
            $_SESSION['user'] = [
                'id' => $user['id_user'],
                'username' => $user['username_user'],
                'role' => $user['role_user']
            ];

            // Arahkan ke halaman berdasarkan role
            if ($user['role_user'] === 'admin') {
                header('Location: ../admin/dashboard.php'); // Admin ke dashboard admin
            } else {
                header('Location: ../index.php'); // User ke halaman utama
            }
            exit;
        } else {
            $error = "Username atau password salah!";
        }

        $stmt->close();
    } else {
        $error = "Terjadi kesalahan pada sistem.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sewa Kendaraan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
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
                    <img src="../assets/img/Logo_S2R.png" class="foto text-center d-flex" width="200" alt="">
                </div>
                <h4 class="login mt-5">Login</h4>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <div class="mt-3">
                    <a href="register.php">Registrasi</a>
                    <br>
                    <a href="forgot-password.php">Lupa password?</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>