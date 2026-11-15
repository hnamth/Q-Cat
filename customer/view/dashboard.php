<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';

$nama = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];

//total menu
$qMenu = mysqli_query($conn, "SELECT COUNT(*) AS total FROM menu");
$totalMenu = mysqli_fetch_assoc($qMenu)['total'];

//total pesanan
$qPesanan = mysqli_query($conn, "SELECT COUNT(*) AS total FROM pesanan WHERE id_user='$id_user'");
$totalPesanan = mysqli_fetch_assoc($qPesanan)['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Q'Cat - Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/website.css">
</head>


<body>
    <header>
        <nav class="navbar fixed-top navbar-laman">
            <div class="container-fluid">
                <div class="logo">🌶️ Q'Cat</div>

                <div>
                    <nav>
                        <ul>
                            <li><a href="menu.php">Menu</a></li>
                            <li><a href="pembayaran.php">Riwayat</a></li>
                            <li><a href="../logout.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>

            </div>
        </nav>
    </header>

    <style>
        .card-dashboard {
            border: none;
            border-radius: 15px;
            transition: .3s;
        }

        .card-dashboard:hover {
            transform: translateY(-8px);
        }

        .icon {
            font-size: 40px;
        }

        footer {
            margin-top: 70px;
        }
    </style>

    <section>
        <div class="fade">
            <section class="py-5 overflow-hidden">
                <div class="container">
                    <div class="row flex-center">
                        <div class="col-md-5 col-lg-6 order-0 order-md-1 mt-8 mt-md-0"><img class="img-fluid"
                                src="image/geprek.png" alt="geprek" /></a></div>
                                
                        <div class="col-md-7 col-lg-6 py-8 text-md-start text-center">
                            <h3 class="display-1 text-light"><b><b>Halo, <?= $nama ?> 👋</b></b></h3>
                            <h1 class="text-800 mb-5 fs-4"><b>Mau pesan apa hari ini?</b></h1>
                            <h6>Kami menyediakan berbagai menu catering rumahan dengan cita rasa terbaik, 
                                harga terjangkau, serta pelayanan cepat langsung ke lokasi Anda.</h6>

                            <div class="d-flex justify-content-center justify-content-md-start gap-3 mt-4">
                                <form action="menu.php" method="post">
                                    <button class="tombol">Buat Pesanan</button>
                                </form>
                            </div>
                        </div>
                    </div>
            </section>

            <div class="container mt-5">
        <div class="row g-4">
            
            <div class="col-md-4">
                <div class="card card-dashboard shadow">
                    <div class="card-body text-center">
                        <div class="icon">🍽️</div>
                        <h2><?= $totalMenu ?></h2>
                        <p>Total Menu Catering</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-dashboard shadow">
                    <div class="card-body text-center">
                        <div class="icon">🛒</div>
                        <h2><?= $totalPesanan ?></h2>
                        <p>Pesanan Saya</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-dashboard shadow">
                    <div class="card-body text-center">
                        <div class="icon">🚚</div>
                        <h2>24 Jam</h2>
                        <p>Layanan Customer Service</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
        </div>
    </section>


<footer class="py-5 px-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="lh-lg fw-bold text-white">LOCATION</h5>
                    <ul class="list-unstyled">
                        <li>Umban Sari</li>
                        <li>Arifin Ahmad</li>
                        <li>Soekarno Hatta</li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <h5 class="lh-lg fw-bold text-white">CONTACT</h5>
                    <ul class="list-unstyled">
                        <li>G-mail : email@gmail.com</li>
                        <li>+62 822 2222 2222</li>
                    </ul>
                </div>
            </div>

            <hr class="border border-light opacity-25 mt-5" />
            <div class="row">
                <div class="col-12 text-center pt-3 text-white-50">
                    &copy; Politeknik Caltex Riau, 2026
                </div>
            </div>
        </div>
    </footer>

    <script src="js/website.js"></script>

</body>
</html>
