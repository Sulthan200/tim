<?php
include('../koneksi.php'); // Koneksi ke database

// Fungsi untuk menambah produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $nama_kendaraan = $_POST['nama_kendaraan'];
    $stok_kendaraan = $_POST['stok_kendaraan'];
    $img_kendaraan = $_FILES['img_kendaraan']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($img_kendaraan);

    // Upload gambar
    if (move_uploaded_file($_FILES['img_kendaraan']['tmp_name'], $target_file)) {
        // Insert data produk ke database
        $query = "INSERT INTO tbl_kendaraan (nama_kendaraan, img_kendaraan, stok_kendaraan) 
                  VALUES ('$nama_kendaraan', '$img_kendaraan', '$stok_kendaraan')";
        if ($conn->query($query) === TRUE) {
            header('Location: produk.php'); // Redirect ke halaman produk setelah berhasil menambah
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }
}

// Fungsi untuk menghapus produk
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Mengambil nama gambar produk
    $query = "SELECT img_kendaraan FROM tbl_kendaraan WHERE id_kendaraan = '$id'";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $img_kendaraan = $row['img_kendaraan'];

    // Hapus gambar dari folder uploads
    if ($img_kendaraan) {
        unlink('uploads/' . $img_kendaraan);
    }

    // Menghapus data produk dari database
    $delete_query = "DELETE FROM tbl_kendaraan WHERE id_kendaraan = '$id'";
    if ($conn->query($delete_query) === TRUE) {
        header('Location: produk.php'); // Redirect ke halaman produk setelah berhasil menghapus
    }
}

// Fungsi untuk mengedit produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product'])) {
    $id = $_POST['id_kendaraan'];
    $nama_kendaraan = $_POST['nama_kendaraan'];
    $stok_kendaraan = $_POST['stok_kendaraan'];
    $img_kendaraan = $_FILES['img_kendaraan']['name'] ? $_FILES['img_kendaraan']['name'] : $_POST['old_img_kendaraan'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($img_kendaraan);

    // Upload gambar jika ada yang baru
    if ($_FILES['img_kendaraan']['name']) {
        move_uploaded_file($_FILES['img_kendaraan']['tmp_name'], $target_file);
    }

    // Update data produk ke database
    $update_query = "UPDATE tbl_kendaraan SET nama_kendaraan = '$nama_kendaraan', img_kendaraan = '$img_kendaraan', stok_kendaraan = '$stok_kendaraan' WHERE id_kendaraan = '$id'";
    if ($conn->query($update_query) === TRUE) {
        header('Location: produk.php'); // Redirect ke halaman produk setelah berhasil update
    }
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
                        <form action="produk.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nama_kendaraan" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="nama_kendaraan" name="nama_kendaraan" required>
                            </div>
                            <div class="mb-3">
                                <label for="stok_kendaraan" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="stok_kendaraan" name="stok_kendaraan" required>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id_kendaraan']; ?></td>
                            <td><?php echo $row['nama_kendaraan']; ?></td>
                            <td><img src="uploads/<?php echo $row['img_kendaraan']; ?>" alt="<?php echo $row['nama_kendaraan']; ?>" width="100"></td>
                            <td><?php echo $row['stok_kendaraan']; ?></td>
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
                                                <form action="produk.php" method="POST" enctype="multipart/form-data">
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
                                                        <label for="img_kendaraan" class="form-label">Image</label>
                                                        <input type="file" class="form-control" id="img_kendaraan" name="img_kendaraan">
                                                        <img src="uploads/<?php echo $row['img_kendaraan']; ?>" width="100" alt="Current Image">
                                                    </div>
                                                    <button type="submit" name="edit_product" class="btn btn-warning">Save Changes</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Button untuk menghapus produk -->
                                <a href="produk.php?delete_id=<?php echo $row['id_kendaraan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">No products found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>