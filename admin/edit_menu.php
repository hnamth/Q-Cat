<?php

session_start();
if (!isset($_SESSION['admin'])) {
    header("location:../login.php");
    exit;
}


include "../config/koneksi.php";


$id = $_GET['id'];



$data = mysqli_query(
    $conn,

    "SELECT * FROM menu WHERE id_menu='$id'"

);


$row = mysqli_fetch_assoc($data);




if (isset($_POST['update'])) {


    $nama = $_POST['nama'];

    $harga = $_POST['harga'];

    $deskripsi = $_POST['deskripsi'];



    $gambar_lama = $row['gambar'];



    if ($_FILES['gambar']['name'] != "") {


        $gambar = $_FILES['gambar']['name'];

        $tmp = $_FILES['gambar']['tmp_name'];



        move_uploaded_file(

            $tmp,

            "../uploads/" . $gambar

        );



        $query = mysqli_query(
            $conn,


            "UPDATE menu SET

nama_menu='$nama',

harga='$harga',

deskripsi='$deskripsi',

gambar='$gambar'


WHERE id_menu='$id'

"

        );
    } else {


        $query = mysqli_query(
            $conn,


            "UPDATE menu SET

nama_menu='$nama',

harga='$harga',

deskripsi='$deskripsi'


WHERE id_menu='$id'

"

        );
    }



    header("location:menu.php");
}



?>



<!DOCTYPE html>

<html>

<head>


    <title>

        Edit Menu Catering

    </title>


    <link rel="stylesheet"

        href="../assets/css/bootstrap.min.css">


    <link rel="stylesheet"

        href="../assets/vendors/bootstrap-icons/bootstrap-icons.css">


    <link rel="stylesheet"

        href="../assets/css/style.css">


</head>




<body>



    <div class="admin-shell">



        <aside class="admin-sidebar">


            <div class="sidebar-header p-3">


                <h4>

                    Q'Cat Catering

                </h4>


            </div>



            <nav class="sidebar-nav">


                <a class="nav-link"

                    href="index.php">

                    Dashboard

                </a>


                <a class="nav-link active"

                    href="menu.php">

                    Menu Catering

                </a>


                <a class="nav-link"

                    href="pesanan.php">

                    Pesanan

                </a>


                <a class="nav-link"

                    href="../logout.php">

                    Logout

                </a>


            </nav>


        </aside>




        <div class="admin-main">


            <nav class="navbar admin-navbar bg-white">


                <div class="container-fluid">


                    <h4>

                        Edit Menu Catering

                    </h4>


                </div>


            </nav>





            <main class="dashboard-content">


                <div class="container-fluid p-4">


                    <div class="panel p-4">



                        <form method="POST"

                            enctype="multipart/form-data">



                            <label>

                                Nama Menu

                            </label>


                            <input type="text"

                                name="nama"

                                class="form-control mb-3"

                                value="<?= $row['nama_menu'] ?>"

                                required>




                            <label>

                                Harga

                            </label>


                            <input type="number"

                                name="harga"

                                class="form-control mb-3"

                                value="<?= $row['harga'] ?>"

                                required>





                            <label>

                                Deskripsi

                            </label>


                            <textarea

                                name="deskripsi"

                                class="form-control mb-3">


<?= $row['deskripsi'] ?>


</textarea>





                            <label>

                                Gambar Saat Ini

                            </label>


                            <br>


                            <img src="../uploads/<?= $row['gambar'] ?>"

                                width="150"

                                class="mb-3">





                            <label>

                                Ganti Gambar

                            </label>


                            <input type="file"

                                name="gambar"

                                class="form-control mb-3">





                            <button class="btn btn-primary"

                                name="update">

                                Update

                            </button>



                            <a href="menu.php"

                                class="btn btn-secondary">

                                Kembali
                            </a>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>


</html>