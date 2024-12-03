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
                    <a class="nav-link px-3" href="index.php#vehicles">Daftar Kendaraan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="index.php#syaratketentuan">Syarat & Ketentuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="index.php#contact">Kontak</a>
                </li>

                <!-- Dynamic Login/Logout button -->
                <?php if ($is_logged_in): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white ms-2" href="auth/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-2" href="auth/login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>