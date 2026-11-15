<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Q'Cat - Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="customer/css/website.css">
</head>


<body>
    <header>
        <nav class="navbar fixed-top navbar-laman">
            <div class="container-fluid">
                <div class="logo">🌶️ Q'Cat</div>

                <div>
                    <nav>
                        <ul>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Registrasi</a></li>
                        </ul>
                    </nav>
                </div>

            </div>
        </nav>
    </header>

    <section>
        <div class="fade">
            <section class="py-5 overflow-hidden">
                <div class="container">
                    <div class="row flex-center">
                        <div class="col-md-5 col-lg-6 order-0 order-md-1 mt-8 mt-md-0"><img class="img-fluid"
                                src="customer/image/geprek.png" alt="geprek" /></a></div>
                                
                        <div class="col-md-7 col-lg-6 py-8 text-md-start text-center">
                            <h3 class="display-1 text-light"><b><b>Rasanya Gurih, Pedasnya Nagih</b></b></h3>
                            <h1 class="text-800 mb-5 fs-4">
                                <b>Mau catering pedas? Q'Cat-in aja!</b></h1>

                            <div class="d-flex justify-content-center justify-content-md-start gap-3 mt-4">
                                <form action="customer/menu.php" method="post">
                                    <button class="tombol">Buat Pesanan</button>
                                </form>
                            </div>
                        </div>
                    </div>
            </section>

            <div class="container mt-5">
        <div class="row g-4">
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

</body>
</html>
