<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != "customer") {
    header("Location: ../login.php");
    exit;
}

require_once __DIR__ . '/../config/koneksi.php';

$id_menu = $_GET['id'];
$id_user = $_SESSION['id_user'];

$menu = mysqli_fetch_assoc(mysqli_query($conn, "
SELECT * FROM menu
WHERE id_menu='$id_menu'
"));

if (isset($_POST['submit'])) {

    $jumlah = $_POST['jumlah'];
    $pengiriman = $_POST['pengiriman'];

    if ($pengiriman == "jemput") {
        $alamat = "-";
    } else {
        $alamat = trim($_POST['alamat']);
    }

    $tanggal = date("Y-m-d");
    $status = "Menunggu";

    mysqli_query($conn, "INSERT INTO pesanan (id_user,id_menu,jumlah,tanggal_pesan,alamat,pengiriman,status)
                        VALUES
    ('$id_user','$id_menu','$jumlah','$tanggal','$alamat','$pengiriman','$status')
    ");

    header("Location: transaksi.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/website.css">
</head>

<body class="bg-light">

    <header>
        <nav class="navbar fixed-top navbar-laman">
            <div class="container-fluid">
                <div class="logo">🌶️ Pesan</div>
                <nav>
                    <ul>
                        <li><a href="index.php">Utama</a></li>
                        <li><a href="menu.php">Menu</a></li>
                        <li><a href="transaksi.php">Riwayat</a></li>
                        <li><a href="../logout.php">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </nav>
    </header>

    <br><br><br>

    <div class="container mt-4">
        <div class="card shadow p-4">
            <h3>Pesan Menu</h3>

            <hr>

            <h5><?= $menu['nama_menu']; ?></h5>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">
                        Jumlah
                    </label>
                    <input
                        type="number" name="jumlah" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">

                    <label class="form-label">Metode Pengiriman</label>

                    <select name="pengiriman" id="pengiriman" class="form-control">
                        <option value="antar">Diantar</option>
                        <option value="jemput">Jemput</option>
                    </select>
                </div>

                <div id="alamatBox" class="mb-3">
                    <label class="form-label">Alamat</label>

                    <textarea
                        name="alamat"
                        id="alamat"
                        class="form-control"
                        rows="3"
                        required></textarea>

                </div>

                <button
                    type="submit"
                    name="submit"
                    class="btn btn-success w-100">

                    Pesan Sekarang

                </button>

            </form>

        </div>

    </div>

    <br><br>

    <footer class="py-5 px-4">

        <div class="container-fluid">

            <div class="row">

                <div class="col-md-6">

                    <h5 class="text-white">
                        LOCATION
                    </h5>

                    <ul class="list-unstyled">
                        <li>Umban Sari</li>
                        <li>Arifin Ahmad</li>
                        <li>Soekarno Hatta</li>
                    </ul>

                </div>

                <div class="col-md-6">

                    <h5 class="text-white">
                        CONTACT
                    </h5>

                    <ul class="list-unstyled">
                        <li>email@gmail.com</li>
                        <li>+62 822 2222 2222</li>
                    </ul>

                </div>

            </div>

            <hr>

            <div class="text-center text-white-50">

                &copy; Politeknik Caltex Riau, 2026

            </div>

        </div>

    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const pengiriman = document.getElementById("pengiriman");
            const alamatBox = document.getElementById("alamatBox");
            const alamat = document.getElementById("alamat");

            function cekPengiriman() {

                if (pengiriman.value === "jemput") {

                    alamatBox.style.display = "none";
                    alamat.required = false;
                    alamat.value = "";

                } else {

                    alamatBox.style.display = "block";
                    alamat.required = true;

                }

            }

            cekPengiriman();

            pengiriman.addEventListener("change", cekPengiriman);

        });
    </script>

</body>

</html>
