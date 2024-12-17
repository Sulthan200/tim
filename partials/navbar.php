<?php
// session_start(); // Memulai session untuk mengakses variabel session

// // Memeriksa apakah pengguna sudah login
// $is_logged_in = isset($_SESSION['user']) && is_array($_SESSION['user']); // Validasi session sudah ada dan berbentuk array
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-sm">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand fw-bold text-uppercase" href="#">S2R Rental</a>
        
        <!-- Toggler Button for mobile view -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> 

        <!-- Navbar Menu Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link px-3" href="../index#vehicles">Daftar Kendaraan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="../index#syaratketentuan">Syarat & Ketentuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="pesanan-saya">Pesanan Saya</a>
                </li>

                <!-- Dynamic Login/Logout button -->
                <?php if ($is_logged_in): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white ms-2" href="../auth/logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-2" href="../auth/login">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
