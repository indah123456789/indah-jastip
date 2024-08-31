<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
session_start();
require_once 'koneksi.php';

// Ambil data dari form dan sanitasi
$username_email = filter_input(INPUT_POST, 'username_email', FILTER_SANITIZE_STRING);
$password = $_POST['password'];
$remember_me = isset($_POST['remember_me']);

// Cari pengguna berdasarkan username atau email
$sql = "SELECT * FROM admin WHERE username = :username_email OR email = :username_email";
$stmt = $pdo->prepare($sql);
$stmt->execute([':username_email' => $username_email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Regenerasi sesi ID untuk keamanan
        session_regenerate_id(true);

        // Set sesi
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];

        // Bersihkan nama kota dari nama admin
        $kota = strtolower(str_replace('Admin ', '', $user['nama'])); // Hapus "Admin " dari nama
        
        $_SESSION['kota'] = $kota;

        // Jika "ingatkan saya" dipilih, simpan sesi dalam cookie yang aman
        if ($remember_me) {
            setcookie('username', $user['username'], time() + (86400 * 30), "/", "", false, true);
            setcookie('nama', $user['nama'], time() + (86400 * 30), "/", "", false, true);
            setcookie('kota', $kota, time() + (86400 * 30), "/", "", false, true);
        }

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: 'Anda akan diarahkan ke halaman beranda.',
                showConfirmButton: false,
                timer: 2000
            }).then(function() {
                window.location.href = '" . BASE_URL . $kota . "/beranda.php';
            });
        </script>";
        exit();
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Password Salah!',
                text: 'Silahkan cek kembali password Anda.',
                showConfirmButton: true
            }).then(function() {
                window.location.href = '" . BASE_URL . "index.php';
            });
        </script>";
        exit();
    }
} else {
    // Tampilkan SweetAlert untuk username/email tidak ditemukan
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Username/Email Tidak Ditemukan!',
            text: 'Silahkan cek kembali data Anda.',
            showConfirmButton: true
        }).then(function() {
            window.location.href = '" . BASE_URL . "index.php';
        });
    </script>";
    exit();
}
