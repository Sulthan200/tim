<?php
include('../koneksi.php'); // Koneksi ke database

// Fungsi untuk menambah produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $nama_kendaraan = $_POST['nama_kendaraan'];
    $stok_kendaraan = $_POST['stok_kendaraan'];
    $harga_kendaraan = $_POST['harga_kendaraan'];
    $img_kendaraan = "assets/img/" . $_FILES['img_kendaraan']['name'];
    $target_dir = "../assets/img/"; // Path baru
    $target_file = $target_dir . basename($img_kendaraan);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi file gambar
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "<script>alert('Only JPG, JPEG, PNG, & GIF files are allowed.');</script>";
        exit;
    }

    // Upload gambar
    if (move_uploaded_file($_FILES['img_kendaraan']['tmp_name'], $target_file)) {
        // Prepared statement untuk memasukkan data ke database
        $stmt = $conn->prepare("INSERT INTO tbl_kendaraan (nama_kendaraan, img_kendaraan, stok_kendaraan, harga_kendaraan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $nama_kendaraan, $img_kendaraan, $stok_kendaraan, $harga_kendaraan);

        if ($stmt->execute()) {
            header('Location: produk');
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "<script>alert('Error uploading file.');</script>";
    }
}

// Fungsi untuk menghapus produk
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Mengambil nama gambar produk
    $stmt = $conn->prepare("SELECT img_kendaraan FROM tbl_kendaraan WHERE id_kendaraan = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $img_kendaraan = $row['img_kendaraan'];
    $stmt->close();

    // Hapus gambar dari folder uploads
    if ($img_kendaraan) {
        unlink('../assets/img/' . $img_kendaraan); // Path baru
    }

    // Menghapus data produk dari database
    $stmt = $conn->prepare("DELETE FROM tbl_kendaraan WHERE id_kendaraan = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: produk');
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fungsi untuk mengedit produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product'])) {
    $id = $_POST['id_kendaraan'];
    $nama_kendaraan = $_POST['nama_kendaraan'];
    $stok_kendaraan = $_POST['stok_kendaraan'];
    $harga_kendaraan = $_POST['harga_kendaraan'];
    $img_kendaraan = $_FILES['img_kendaraan']['name'] 
        ? "../assets/img/" . $_FILES['img_kendaraan']['name'] // Path baru jika gambar diubah
        : $_POST['old_img_kendaraan']; // Tetap gunakan gambar lama jika tidak diubah
    $target_dir = "../assets/img/"; // Path baru
    $target_file = $target_dir . basename($img_kendaraan);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Upload gambar baru jika ada
    if ($_FILES['img_kendaraan']['name']) {
        // Validasi file gambar baru
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "<script>alert('Only JPG, JPEG, PNG, & GIF files are allowed.');</script>";
            exit;
        }

        // Hapus gambar lama
        if ($_POST['old_img_kendaraan']) {
            unlink('../assets/img/' . $_POST['old_img_kendaraan']); // Path baru
        }

        move_uploaded_file($_FILES['img_kendaraan']['tmp_name'], $target_file);
    }

    // Update data produk ke database
    $stmt = $conn->prepare("UPDATE tbl_kendaraan SET nama_kendaraan = ?, img_kendaraan = ?, stok_kendaraan = ?, harga_kendaraan = ? WHERE id_kendaraan = ?");
    $stmt->bind_param("ssiii", $nama_kendaraan, $img_kendaraan, $stok_kendaraan, $harga_kendaraan, $id);

    if ($stmt->execute()) {
        header('Location: produk');
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Menampilkan produk
$query = "SELECT * FROM tbl_kendaraan";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <div class="container mt-4">
        <h1>Products</h1>

        <!-- Button untuk menambah produk -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

        <!-- Modal untuk tambah produk -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="produk" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nama_kendaraan" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="nama_kendaraan" name="nama_kendaraan" required>
                            </div>
                            <div class="mb-3">
                                <label for="stok_kendaraan" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stok_kendaraan" name="stok_kendaraan" required>
                            </div>
                            <div class="mb-3">
                                <label for="harga_kendaraan" class="form-label">Price</label>
                                <input type="number" class="form-control" id="harga_kendaraan" name="harga_kendaraan" required>
                            </div>
                            <div class="mb-3">
                                <label for="img_kendaraan" class="form-label">Image</label>
                                <input type="file" class="form-control" id="img_kendaraan" name="img_kendaraan" required>
                            </div>
                            <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel untuk menampilkan produk -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id_kendaraan']; ?></td>
                            <td><?php echo $row['nama_kendaraan']; ?></td>
                            <td><img src="uploads/<?php echo $row['img_kendaraan']; ?>" width="100"></td>
                            <td><?php echo $row['stok_kendaraan']; ?></td>
                            <td>Rp <?php echo number_format($row['harga_kendaraan'], 0, ',', '.'); ?></td>
                            <td>
                                <!-- Button untuk edit produk -->
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $row['id_kendaraan']; ?>">Edit</button>
                                
                                <!-- Modal untuk edit produk -->
                                <div class="modal fade" id="editProductModal<?php echo $row['id_kendaraan']; ?>" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="produk" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="id_kendaraan" value="<?php echo $row['id_kendaraan']; ?>">
                                                    <input type="hidden" name="old_img_kendaraan" value="<?php echo $row['img_kendaraan']; ?>">
                                                    <div class="mb-3">
                                                        <label for="nama_kendaraan" class="form-label">Product Name</label>
                                                        <input type="text" class="form-control" id="nama_kendaraan" name="nama_kendaraan" value="<?php echo $row['nama_kendaraan']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="stok_kendaraan" class="form-label">Stock</label>
                                                        <input type="number" class="form-control" id="stok_kendaraan" name="stok_kendaraan" value="<?php echo $row['stok_kendaraan']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga_kendaraan" class="form-label">Price</label>
                                                        <input type="number" class="form-control" id="harga_kendaraan" name="harga_kendaraan" value="<?php echo $row['harga_kendaraan']; ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="img_kendaraan" class="form-label">Image</label>
                                                        <input type="file" class="form-control" id="img_kendaraan" name="img_kendaraan">
                                                    </div>
                                                    <button type="submit" name="edit_product" class="btn btn-primary">Update Product</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Button untuk hapus produk -->
                                <a href="produk?delete_id=<?php echo $row['id_kendaraan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center">No products available</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
