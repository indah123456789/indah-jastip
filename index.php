<?php
ob_start();
require_once 'fungsi/koneksi.php';

// Cek apakah pengguna sudah login
if (isset($_SESSION['username'])) {
    // Regenerate session ID untuk keamanan
    session_regenerate_id(true);

    // Jika sudah login, arahkan ke halaman beranda sesuai dengan kota
    $kota = $_SESSION['kota'];
    header("Location: " . BASE_URL . $kota . "/beranda.php");
    exit();
}

// Mencegah caching halaman login sehingga pengguna tidak bisa kembali ke halaman ini setelah login
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Akhiri buffer output
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login Ayana Jastip</title>

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
    <link rel="stylesheet" type="text/css" href="src/plugins/sweetalert2/sweetalert2.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
    <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <?php // Jika tidak diperlukan, hapus script berikut ?>
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script> -->
    <!-- <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-119386393-1');
    </script> -->
</head>
<body class="login-page">
    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="vendors/images/login-page-img.png" alt="Login Image">
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-primary">Silahkan Masuk ke<br> Jastip Ayana</h2>
                        </div>
                        <form action="fungsi/login.php" method="POST">
                            <div class="input-group custom">
                                <input type="text" class="form-control form-control-lg" name="username_email" placeholder="Username atau Email" required autofocus>
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                            <div class="input-group custom">
                                <input type="password" class="form-control form-control-lg" name="password" placeholder="**********" required>
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember_me">
                                        <label class="custom-control-label" for="customCheck1">Ingatkan saya</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <input class="btn btn-primary btn-lg btn-block" type="submit" value="Masuk">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="vendors/scripts/core.js" defer></script>
    <script src="vendors/scripts/script.min.js" defer></script>
    <script src="vendors/scripts/process.js" defer></script>
    <script src="vendors/scripts/layout-settings.js" defer></script>

    <!-- add sweet alert js & css in footer -->
    <script src="src/plugins/sweetalert2/sweetalert2.all.js" defer></script>
    <script src="src/plugins/sweetalert2/sweet-alert.init.js" defer></script>

</body>
</html>
