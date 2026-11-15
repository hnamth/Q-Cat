<?php
session_start();
include "config/koneksi.php";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role'] = $data['role'];

        if ($data['role'] == "admin") {
            $_SESSION['admin'] = true;
            header("Location: admin/index.php");
            exit;
        } elseif ($data['role'] == "customer") {

            $_SESSION['customer'] = true;
            header("Location: customer/index.php");
            exit;
        } else {
            echo "<script>alert('Role tidak dikenali');</script>";
        }
    } else {

        echo "<script>alert('Email atau Password salah');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Q'Cat Catering</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="container mt-5">

        <div class="card p-4 mx-auto" style="max-width:400px">

            <h3 class="text-center">Login Q'Cat Catering</h3>

            <form method="POST">

                <label>Email</label>
                <input type="email" name="email" class="form-control mb-3" required>

                <label>Password</label>
                <input type="password" name="password" class="form-control mb-3" required>

                <button type="submit" name="login" class="btn btn-danger w-100 mt-3">
                    Login
                </button>

                <p class="text-center mt-3 mb-0">
                    Belum punya akun?
                    <a href="register.php" class="fw-semibold text-danger">
                        Register sekarang
                    </a>
                </p>
            </form>

        </div>

    </div>

</body>

</html>