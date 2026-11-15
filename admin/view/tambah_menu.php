<?php

session_start();

if (!isset($_SESSION['admin'])) {

    header("location:../login.php");

    exit;
}


require_once __DIR__ . '/../config/koneksi.php';


if (isset($_POST['simpan'])) {


    $nama = $_POST['nama'];

    $harga = $_POST['harga'];

    $deskripsi = $_POST['deskripsi'];



    // upload gambar

    $gambar = $_FILES['gambar']['name'];

    $tmp = $_FILES['gambar']['tmp_name'];



    move_uploaded_file(

        $tmp,

        "../uploads/" . $gambar

    );



    $query = "INSERT INTO menu (nama_menu, deskripsi, harga, gambar) VALUES ('$nama_menu', '$deskripsi', '$harga', '$gambar')";
    mysqli_query($conn, $query);



    header("location:menu.php");
}



?>



<!DOCTYPE html>
<html>

<head>
    <title>

        Tambah Menu

    </title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h3>

            Tambah Menu Catering

        </h3>
        <form method="POST" enctype="multipart/form-data">
            <label>

                Nama Menu

            </label>


            <input type="text" name="nama"

                class="form-control mb-3" required>

            <label>

                Harga

            </label>
            <input type="number"

                name="harga"

                class="form-control mb-3" required>

            <label>

                Deskripsi

            </label>


            <textarea

                name="deskripsi"

                class="form-control mb-3">

</textarea>
            <label>

                Gambar Menu

            </label>


            <input type="file"

                name="gambar"

                class="form-control mb-3" required>

            <button class="btn btn-success"

                name="simpan">

                Simpan

            </button>

            <a href="menu.php"

                class="btn btn-secondary">

                Kembali

            </a>
        </form>
    </div>
</body>

</html>
