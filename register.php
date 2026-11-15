<?php
include "config/koneksi.php";

if (isset($_POST['register'])) {

    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // cek email
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($cek) > 0) {

        echo "<script>
        alert('Email sudah terdaftar!');
        window.location='register.php';
        </script>";
    } elseif ($password != $konfirmasi) {

        echo "<script>
        alert('Konfirmasi password tidak sama!');
        </script>";
    } else {

        mysqli_query($conn, "INSERT INTO users
        (nama,email,password,role)
        VALUES
        ('$nama','$email','$password','customer')
        ");

        echo "<script>
        alert('Registrasi berhasil!');
        window.location='login.php';
        </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">

                        <h3>Daftar Customer</h3>

                    </div>

                    <div class="card-body">
                        <form method="POST">

                            <label>Nama Lengkap</label>
                            <input
                                type="text"
                                name="nama"
                                class="form-control mb-3"
                                required>

                            <label>Email</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control mb-3"
                                required>

                            <label>Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control mb-3"
                                required>

                            <label>Konfirmasi Password</label>
                            <input
                                type="password"
                                name="konfirmasi"
                                class="form-control mb-4"
                                required>

                            <button class="btn btn-success w-100" name="register">
                                Daftar</button>
                        </form>

                        <hr>

                        <div class="text-center">Sudah punya akun?
                            <a href="login.php">Login</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>