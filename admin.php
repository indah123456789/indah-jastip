<?php
// Koneksi ke database menggunakan PDO
$dsn = 'mysql:host=localhost;dbname=jastip';
$username = 'root';
$password = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
];

$pdo = new PDO($dsn, $username, $password, $options);

// Hash password dengan password_hash()
$ambon1234 = password_hash('ambon1234', PASSWORD_BCRYPT);
$jakarta1234 = password_hash('jakarta1234', PASSWORD_BCRYPT);
$bandung1234 = password_hash('bandung1234', PASSWORD_BCRYPT);

// Query untuk menambahkan admin
$sql = "INSERT INTO admin (username, nama, alamat, notlp, email, password) VALUES 
        (:username_ambon, :nama_ambon, :alamat_ambon, :no_tlp_ambon, :email_ambon, :ambon1234),
        (:username_jakarta, :nama_jakarta, :alamat_jakarta, :no_tlp_jakarta, :email_jakarta, :jakarta1234),
        (:username_bandung, :nama_bandung, :alamat_bandung, :no_tlp_bandung, :email_bandung, :bandung1234)";

// Persiapkan statement
$stmt = $pdo->prepare($sql);

// Bind parameter
$stmt->execute([
    ':username_ambon' => 'admin_ambon',
    ':nama_ambon' => 'Admin Ambon',
    ':alamat_ambon' => 'Jl. Ambon No. 1',
    ':no_tlp_ambon' => '081234567890',
    ':email_ambon' => 'admin_ambon@example.com',
    ':ambon1234' => $ambon1234,
    ':username_jakarta' => 'admin_jakarta',
    ':nama_jakarta' => 'Admin Jakarta',
    ':alamat_jakarta' => 'Jl. Jakarta No. 2',
    ':no_tlp_jakarta' => '081234567891',
    ':email_jakarta' => 'admin_jakarta@example.com',
    ':jakarta1234' => $jakarta1234,
    ':username_bandung' => 'admin_bandung',
    ':nama_bandung' => 'Admin Bandung',
    ':alamat_bandung' => 'Jl. Bandung No. 3',
    ':no_tlp_bandung' => '081234567892',
    ':email_bandung' => 'admin_bandung@example.com',
    ':bandung1234' => $bandung1234,
]);

echo "Data admin berhasil ditambahkan.";
?>
