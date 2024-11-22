<?php
// Koneksi database
$connection = new mysqli("localhost", "username", "password", "database_name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = $_POST['site_name'];
    $admin_email = $_POST['admin_email'];

    // Update settings ke database
    $sql = "UPDATE settings SET site_name = '$site_name', admin_email = '$admin_email' WHERE id = 1";
    if ($connection->query($sql) === TRUE) {
        echo "Settings updated successfully!";
    } else {
        echo "Error updating settings: " . $connection->error;
    }
}

// Ambil data settings
$settings = $connection->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Settings</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="siteName" class="form-label">Site Name</label>
                <input type="text" class="form-control" id="siteName" name="site_name" value="<?= $settings['site_name'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="adminEmail" class="form-label">Admin Email</label>
                <input type="email" class="form-control" id="adminEmail" name="admin_email" value="<?= $settings['admin_email'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </form>
    </div>
</body>
</html>
