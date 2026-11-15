<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != "customer") {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Menu</title>
    <link rel="stylesheet" href="css/website.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <nav class="navbar fixed-top navbar-laman">
            <div class="container-fluid">
                <div class="logo">🌶️ Menu</div>

                <div>
                    <nav>
                        <ul>
                            <li><a href="index.php">Utama</a></li>
                            <li><a href="pembayaran.php">Riwayat</a></li>
                            <li><a href="../logout.php">Logout</a></li>
                        </ul>
                    </nav>
                </div>

            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <div class="row">
            <?php while($m = mysqli_fetch_assoc($query)) { ?>
            <div class="col-md-4 mt-3">
                <div class="card shadow-sm">
                    <img src="../uploads/<?= $m['gambar']; ?>" class="card-img-top"
                        style="height:200px; object-fit:cover;">
                    <div class="card-body">
                        <h5>
                            <?= $m['nama_menu']; ?>
                        </h5>
                        <p>
                            <?= $m['deskripsi']; ?>
                        </p>
                        <b>Rp
                            <?= number_format($m['harga']); ?>
                        </b>
                        <a href="pesan.php?id=<?= $m['id_menu']; ?>" class="btn btn-success w-100 mt-2">Pesan</a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>



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

</body>

</html>