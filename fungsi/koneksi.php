<?php
define('BASE_URL', '/jastip/');

$host = 'localhost';
$dbname = 'jastip';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Mengatur mode error ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Debug untuk memastikan koneksi berhasil
    // echo "Koneksi berhasil"; 
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
