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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if ($id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM sortirambon WHERE id = :id");
            $stmt->execute(['id' => $id]);

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data pengiriman berhasil dihapus!'
                }).then(() => {
                    window.location.href = 'dtajakarta.php';
                });
            </script>";
        } catch (PDOException $e) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menghapus data: " . $e->getMessage() . "'
                }).then(() => {
                    window.location.href = 'dtajakarta.php';
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'ID tidak ditemukan!'
            }).then(() => {
                window.location.href = 'dtajakarta.php';
            });
        </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'update') {
        $sttsbrg = isset($_POST['sttsbrg']) ? $_POST['sttsbrg'] : 'Pending..'; 
        $status = isset($_POST['status']) ? $_POST['status'] : 'Pending..';

        try {
            $stmt = $pdo->prepare("
                UPDATE sortirambon SET 
                    nama_pengirim = :nama_pengirim,
                    no_hp = :no_hp,
                    jumlah_paket = :jumlah_paket,
                    berat = :berat,
                    biaya_paket = :biaya_paket,
                    total_biaya = :total_biaya,
                    tgl_pengirim = :tgl_pengirim,
                    sttsbrg = :sttsbrg,
                    status = :status
                WHERE id = :id
            ");
            $stmt->execute([
                'id' => $_POST['id'],
                'nama_pengirim' => $_POST['nama_pengirim'],
                'no_hp' => $_POST['no_hp'],
                'jumlah_paket' => $_POST['jumlah_paket'],
                'berat' => $_POST['berat'],
                'biaya_paket' => $_POST['biaya_paket'],
                'total_biaya' => $_POST['total_biaya'],
                'tgl_pengirim' => $_POST['tgl_pengirim'],
                'sttsbrg' => $sttsbrg,  
                'status' => $status      
            ]);

            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data pengiriman berhasil diperbarui!'
                }).then(() => {
                    window.location.href = 'dtajakarta.php';
                });
            </script>";
        } catch (PDOException $e) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat memperbarui data: " . $e->getMessage() . "'
                }).then(() => {
                    window.location.href = 'dtajakarta.php';
                });
            </script>";
        }

    } elseif ($action === 'sortir') {
        $ids = isset($_POST['id']) ? $_POST['id'] : [];

        if (is_array($ids) && !empty($ids)) {
            foreach ($ids as $id) {
                try {
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM sortirambon WHERE id = :id");
                    $stmt->execute(['id' => $id]);
                    $count = $stmt->fetchColumn();

                    if ($count > 0) {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Data pengiriman dengan Resi $id sudah masuk di sortir Ambon!'
                            }).then(() => {
                                window.location.href = 'dtajakarta.php';
                            });
                        </script>";
                        exit();
                    } else {
                        $stmt = $pdo->prepare("SELECT * FROM jastipjkt WHERE id = :id");
                        $stmt->execute(['id' => $id]);
                        $data = $stmt->fetch(PDO::FETCH_ASSOC);

                        if (!$data) {
                            $stmt = $pdo->prepare("SELECT * FROM jastipbdg WHERE id = :id");
                            $stmt->execute(['id' => $id]);
                            $data = $stmt->fetch(PDO::FETCH_ASSOC);
                        }

                        if ($data) {
                            $insertStmt = $pdo->prepare("
                                INSERT INTO sortirambon (id, tujuan, nama_pengirim, no_hp, jumlah_paket, berat, biaya_paket, total_biaya, tgl_pengirim, sttsbrg, status)
                                VALUES (:id, :tujuan, :nama_pengirim, :no_hp, :jumlah_paket, :berat, :biaya_paket, :total_biaya, :tgl_pengirim, 'Pending..', 'Pending..')
                            ");
                            $insertStmt->execute([
                                'id' => $data['id'],
                                'tujuan' => $data['tujuan'],
                                'nama_pengirim' => $data['nama_pengirim'],
                                'no_hp' => $data['no_hp'],
                                'jumlah_paket' => $data['jumlah_paket'],
                                'berat' => $data['berat'],
                                'biaya_paket' => $data['biaya_paket'],
                                'total_biaya' => $data['total_biaya'],
                                'tgl_pengirim' => $data['tgl_pengirim'],
                            ]);

                            echo "<script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data pengiriman dengan Resi $id berhasil ditambahkan ke sortir Ambon!'
                                }).then(() => {
                                    window.location.href = 'dtajakarta.php';
                                });
                            </script>";
                        } else {
                            echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Data pengiriman dengan Resi $id tidak ditemukan di database!'
                                }).then(() => {
                                    window.location.href = 'dtajakarta.php';
                                });
                            </script>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menyortir data: " . $e->getMessage() . "'
                        }).then(() => {
                            window.location.href = 'dtajakarta.php';
                        });
                    </script>";
                }
            }
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak ada Resi yang dipilih!'
                }).then(() => {
                    window.location.href = 'dtajakarta.php';
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Aksi tidak dikenal!'
            }).then(() => {
                window.location.href = 'dtajakarta.php';
            });
        </script>";
    }
} else {
    header("Location: dtajakarta.php");
    exit();
}
?>
