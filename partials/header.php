<header class="header-container text-center text-white">
    <div class="container">
        <h1 class="display-4 text-uppercase fw-bold animated-header">Selamat Datang di S2R Rental</h1>
        <p class="lead mt-3 text-white-50 animated-subtext"><strong>Penyewaan Motor Terpercaya dan Terjangkau</strong></p>
        <a href="#vehicles" class="btn btn-primary mt-4 px-4 py-2 fw-bold animated-button">Lihat Kendaraan Kami</a>
    </div>
</header>

<style>
    /* Full Header Background */
    .header-container {
        background: linear-gradient(135deg, #4e54c8, #8f94fb);
        color: white;
        position: relative;
        overflow: hidden;
    }

    /* Animation for Background */
    .header-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(60deg, rgba(78, 84, 200, 0.3), rgba(143, 148, 251, 0.3));
        z-index: 0;
        animation: moveBackground 10s infinite linear;
    }

    .header-container .container {
        position: relative;
        z-index: 1;
    }

    @keyframes moveBackground {
        0% { transform: rotate(0deg) translateX(0); }
        50% { transform: rotate(20deg) translateX(50%); }
        100% { transform: rotate(0deg) translateX(0); }
    }

    /* Text Animation */
    .animated-header {
        animation: fadeInDown 1s ease-in-out;
    }

    .animated-subtext {
        animation: fadeInUp 1s ease-in-out;
    }

    .animated-button {
        animation: pulse 1.5s infinite;
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Button Animation */
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
</style>