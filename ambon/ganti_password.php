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

// Ambil data dari formulir ganti password
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

$username_session = $_SESSION['username'];

// Cek apakah password lama cocok dengan yang ada di database
$sql_password_check = "SELECT password FROM admin WHERE username = ?";
$stmt_check = $pdo->prepare($sql_password_check);
$stmt_check->execute([$username_session]);
$hashed_password = $stmt_check->fetchColumn();

if (password_verify($old_password, $hashed_password)) {
    if ($new_password === $confirm_password) {
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql_update_password = "UPDATE admin SET password = ? WHERE username = ?";
        $stmt_update_password = $pdo->prepare($sql_update_password);
        $stmt_update_password->execute([$new_hashed_password, $username_session]);
        
        if ($stmt_update_password->rowCount() > 0) {
            echo '<script>
                Swal.fire("Sukses", "Password berhasil diupdate!", "success").then(() => {
                    window.location.href="profile.php";
                });
            </script>';
        } else {
            echo '<script>
                Swal.fire("Error", "Gagal mengupdate password!", "error").then(() => {
                    window.location.href="profile.php";
                });
            </script>';
        }
    } else {
        echo '<script>
            Swal.fire("Error", "Password baru dan konfirmasi tidak sesuai!", "error").then(() => {
                window.location.href="profile.php";
            });
        </script>';
    }
} else {
    echo '<script>
        Swal.fire("Error", "Password lama tidak sesuai!", "error").then(() => {
            window.location.href="profile.php";
        });
    </script>';
}
?>
