<?php
session_start();
require_once 'koneksi.php';

// Hapus semua data sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Hapus cookie sesi jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hapus cookie tambahan yang dibuat saat login
setcookie('username', '', time() - 42000, '/');
setcookie('nama', '', time() - 42000, '/');
setcookie('kota', '', time() - 42000, '/');

// Arahkan pengguna kembali ke halaman login
header("Location: " . BASE_URL . "index.php?logout=success");
exit();
