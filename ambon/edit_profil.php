<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
session_start();
require_once '../fungsi/koneksi.php';

if ($_SESSION['kota'] !== 'ambon') {
    $kota_sesuaikan = htmlspecialchars($_SESSION['kota'], ENT_QUOTES, 'UTF-8');
    header("Location: " . BASE_URL . $kota_sesuaikan . "/profile.php");
    exit();
}

include '../view/header.php';
// Ambil data dari formulir edit profil
$username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
$nama = htmlspecialchars($_POST['nama'], ENT_QUOTES, 'UTF-8');
$notlp = htmlspecialchars($_POST['notlp'], ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
$alamat = htmlspecialchars($_POST['alamat'], ENT_QUOTES, 'UTF-8');

$username_session = $_SESSION['username'];

// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo '<script>
        Swal.fire("Error", "Email tidak valid!", "error").then(() => {
            window.location.href="profile.php";
        });
    </script>';
    exit();
}

// Update profil pengguna
$sql_update = "UPDATE admin SET username = ?, nama = ?, notlp = ?, email = ?, alamat = ? WHERE username = ?";
$stmt = $pdo->prepare($sql_update);
$stmt->execute([$username, $nama, $notlp, $email, $alamat, $username_session]);

if ($stmt->rowCount() > 0) {
    echo '<script>
        Swal.fire("Sukses", "Profil berhasil diupdate!", "success").then(() => {
            window.location.href="profile.php";
        });
    </script>';
} else {
    echo '<script>
        Swal.fire("Error", "Gagal mengupdate profil!", "error").then(() => {
            window.location.href="profile.php";
        });
    </script>';
}
?>
