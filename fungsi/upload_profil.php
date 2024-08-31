<?php
session_start();
require_once 'koneksi.php';

header('Content-Type: application/json');

$response = array('status' => 'error', 'message' => 'Tidak ada file yang diunggah atau terjadi error.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['croppedImage']) && $_FILES['croppedImage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['croppedImage']['tmp_name'];
        $fileName = $_SESSION['username'] . '_profil.png';
        $uploadPath = '../vendors/images/' . $fileName;

        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            
            $stmt = $pdo->prepare("UPDATE admin SET profil = :profil WHERE username = :username");
            if ($stmt->execute([':profil' => $fileName, ':username' => $_SESSION['username']])) {
                $response['status'] = 'success';
                $response['message'] = 'Gambar berhasil diupload.';
            } else {
                $response['message'] = 'Gagal memperbarui profil di database.';
            }
        } else {
            $response['message'] = 'Gagal menyimpan file gambar.';
        }
    } else {
        $response['message'] = 'Tidak ada file yang diunggah atau terjadi error.';
    }
} else {
    $response['message'] = 'Metode permintaan tidak valid.';
}

echo json_encode($response);
exit();
