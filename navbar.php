<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #343a40;">
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
                    <a class="nav-link px-3" href="index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="syarat&ketentuan.php">Ketentuan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="kontak.php">Kontak</a>
                </li>

                <!-- Dynamic Login/Logout button -->
                <?php if ($is_logged_in): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white ms-2" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white ms-2" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<style>
    /* Navbar Styles */
    .navbar {
        transition: background-color 0.3s ease;
    }

    .navbar.scrolled {
        background-color: #007bff !important; /* Warna berubah saat scroll */
    }

    .navbar-brand {
        font-family: 'Arial', sans-serif;
        font-weight: bold;
        font-size: 1.5rem;
        text-transform: uppercase;
    }

    /* Navbar links styling */
    .nav-link {
        color: #ffffff;
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
        transition: color 0.3s ease, background-color 0.3s ease;
    }

    .nav-link:hover, .nav-link:focus {
        background-color: #007bff;
        color: white !important;
        border-radius: 5px;
    }

    /* Button hover effect */
    .btn {
        transition: transform 0.3s ease, background-color 0.3s ease;
    }

    .btn:hover {
        transform: scale(1.05);
    }

    /* For mobile responsiveness */
    @media (max-width: 768px) {
        .navbar-brand {
            font-size: 1.3rem;
        }

        .navbar-nav .nav-item .nav-link {
            padding: 0.5rem 1rem;
        }
    }
</style>

<!-- JavaScript untuk efek scroll pada navbar -->
<script>
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>