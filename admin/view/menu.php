<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

require_once __DIR__ . '/../config/koneksi.php';


$data = mysqli_query(
    $conn,

    "SELECT * FROM menu"

);


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Menu Catering</title>


    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="../assets/vendors/bootstrap-icons/bootstrap-icons.css">

    <link rel="stylesheet" href="../assets/css/admin.css">

</head>

<body>
    <div class="admin-shell">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <div class="sidebar-header p-3">
                <h4>
                    Q'Cat Catering
                </h4>
            </div>

            <nav class="sidebar-nav">
                <a class="nav-link" href="index.php">
                    Dashboard
                </a>
                <a class="nav-link active" href="menu.php">
                    Menu Catering
                </a>
                <a class="nav-link" href="pesanan.php">
                    Pesanan
                </a>
                <a class="nav-link" href="pembayaran.php">
                    Pembayaran
                </a>
                <a class="nav-link" href="../logout.php">
                    Logout
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>
            </nav>
        </aside>

        <div class="admin-main">
            <nav class="navbar admin-navbar bg-white">
                <div class="container-fluid">
                    <h4>
                        Data Menu Catering
                    </h4>
                </div>
            </nav>

            <main class="dashboard-content">
                <div class="container-fluid p-4">
                    <a href="tambah_menu.php"

                        class="btn btn-primary mb-3">

                        <i class="bi bi-plus"></i>

                        Tambah Menu

                    </a>
                    <div class="panel p-3">
                        <table class="table table-bordered">
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Menu</th>
                                <th>Harga</th>
                                <th>Deskripsi</th>
                            </tr>

                            <?php

                            $no = 1;

                            while ($row = mysqli_fetch_assoc($data)) {

                            ?>

                                <tr>
                                    <td>
                                        <?= $no++ ?>
                                    </td>
                                    <td>


                                        <?php

                                        if ($row['gambar'] != "") {


                                        ?>


                                            <img src="../uploads/<?= $row['gambar'] ?>"

                                                width="100">


                                        <?php

                                        } else {


                                            echo "Tidak ada gambar";
                                        }


                                        ?>

                                    </td>
                                    <td>

                                        <?= $row['nama_menu'] ?>

                                    </td>
                                    <td>

                                        Rp <?= number_format($row['harga']) ?>

                                    </td>
                                    <td>

                                        <?= $row['deskripsi'] ?>

                                    </td>
                                    <td>
                                        <a href="edit_menu.php?id=<?= $row['id_menu'] ?>"

                                            class="btn btn-warning btn-sm">

                                            Edit

                                        </a>
                                        <a href="hapus_menu.php?id=<?= $row['id_menu'] ?>"

                                            class="btn btn-danger btn-sm"

                                            onclick="return confirm('Hapus menu?')">

                                            Hapus

                                        </a>

                                    </td>
                                </tr>

                            <?php } ?>

                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
