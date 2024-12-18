<div class="sidebar">
        <h2>Admin</h2>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="dashboard">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="user">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="orders">Data Pesanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="produk">Data Produk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../auth/logout">Logout</a>
            </li>
        </ul>
    </div>
    <style>
        body {
            display: flex;
        }
        .sidebar {
            min-width: 250px;
            max-width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            padding: 15px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            background: #495057;
            padding-left: 10px;
            transition: 0.3s;
        }
        </style>