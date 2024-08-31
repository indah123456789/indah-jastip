<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php
date_default_timezone_set('Asia/Jakarta');
require_once '../fungsi/koneksi.php';

// Handle Insert
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['update']) && !isset($_POST['delete'])) {
    // Ambil data dari form dan sanitasi
    $nama_pengirim = htmlspecialchars($_POST['nama_pengirim'], ENT_QUOTES, 'UTF-8');
    $no_hp = htmlspecialchars($_POST['no_hp'], ENT_QUOTES, 'UTF-8');
    $tujuan = htmlspecialchars($_POST['tujuan'], ENT_QUOTES, 'UTF-8');
    $jumlah_paket = filter_var($_POST['jumlah_paket'], FILTER_VALIDATE_INT);
    $berat = filter_var($_POST['berat'], FILTER_VALIDATE_FLOAT);
    $tgl_pengirim = $_POST['tgl_pengirim']; // Mengambil langsung dari input pengguna

    // Konversi ke format datetime untuk penyimpanan di database
    $tgl_pengirim = date('Y-m-d H:i:s', strtotime($tgl_pengirim));

    // Cek apakah data valid
    if ($jumlah_paket === false || $berat === false) {
        echo "<script>
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Data yang Anda masukkan tidak valid.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'pengiriman.php';
                    }
                });
              </script>";
        exit();
    }

    // Dapatkan harga dari tabel bdgtujuan
    $stmt = $pdo->prepare("SELECT harga FROM bdgtujuan WHERE tujuan = :tujuan");
    $stmt->execute([':tujuan' => $tujuan]);
    $tujuanData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($tujuanData) {
        $biaya_paket = $tujuanData['harga'];
        $total_biaya = $biaya_paket * $berat;

        // Insert data ke tabel jastipbdg
        $stmt = $pdo->prepare("INSERT INTO jastipbdg (tujuan, nama_pengirim, no_hp, jumlah_paket, tgl_pengirim, berat, biaya_paket, total_biaya) 
                               VALUES (:tujuan, :nama_pengirim, :no_hp, :jumlah_paket, :tgl_pengirim, :berat, :biaya_paket, :total_biaya)");
        $stmt->execute([
            ':tujuan' => $tujuan,
            ':nama_pengirim' => $nama_pengirim,
            ':no_hp' => $no_hp,
            ':jumlah_paket' => $jumlah_paket,
            ':tgl_pengirim' => $tgl_pengirim,
            ':berat' => $berat,
            ':biaya_paket' => $biaya_paket,
            ':total_biaya' => $total_biaya
        ]);

        echo "<script>
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data pengiriman berhasil disimpan.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'pengiriman.php';
                    }
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal mendapatkan harga dari tujuan.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'pengiriman.php';
                    }
                });
              </script>";
    }
    exit();
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_pengirim = htmlspecialchars($_POST['nama_pengirim'], ENT_QUOTES, 'UTF-8');
    $no_hp = htmlspecialchars($_POST['no_hp'], ENT_QUOTES, 'UTF-8');
    $tujuan = htmlspecialchars($_POST['tujuan'], ENT_QUOTES, 'UTF-8');
    $jumlah_paket = filter_var($_POST['jumlah_paket'], FILTER_VALIDATE_INT);
    $berat = filter_var($_POST['berat'], FILTER_VALIDATE_FLOAT);
    $tgl_pengirim = $_POST['tgl_pengirim']; // Mengambil langsung dari input pengguna

    // Konversi ke format datetime untuk penyimpanan di database
    $tgl_pengirim = date('Y-m-d H:i:s', strtotime($tgl_pengirim));

    // Dapatkan harga dari tabel bdgtujuan untuk menghitung ulang biaya_paket dan total_biaya
    $stmt = $pdo->prepare("SELECT harga FROM bdgtujuan WHERE tujuan = :tujuan");
    $stmt->execute([':tujuan' => $tujuan]);
    $tujuanData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($tujuanData) {
        $biaya_paket = $tujuanData['harga'];
        $total_biaya = $biaya_paket * $berat;

        // Lakukan update pada database
        if ($jumlah_paket !== false && $berat !== false) {
            $stmt = $pdo->prepare("UPDATE jastipbdg SET 
                                    nama_pengirim = :nama_pengirim, 
                                    no_hp = :no_hp, 
                                    tujuan = :tujuan, 
                                    jumlah_paket = :jumlah_paket, 
                                    berat = :berat, 
                                    tgl_pengirim = :tgl_pengirim,
                                    biaya_paket = :biaya_paket,
                                    total_biaya = :total_biaya
                                   WHERE id = :id");
            $stmt->execute([
                ':nama_pengirim' => $nama_pengirim,
                ':no_hp' => $no_hp,
                ':tujuan' => $tujuan,
                ':jumlah_paket' => $jumlah_paket,
                ':berat' => $berat,
                ':tgl_pengirim' => $tgl_pengirim,
                ':biaya_paket' => $biaya_paket,
                ':total_biaya' => $total_biaya,
                ':id' => $id
            ]);

            echo "<script>
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Data pengiriman berhasil diperbarui.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'pengiriman.php';
                        }
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Data yang dimasukkan tidak valid.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'pengiriman.php';
                        }
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Gagal mendapatkan harga dari tujuan.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'pengiriman.php';
                    }
                });
              </script>";
    }
    exit();
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];

    echo "<script>
            Swal.fire({
                title: 'Apakah Anda yakin ingin menghapus data ini?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'pengiriman_data.php?confirm_delete&id={$id}';
                } else {
                    window.location.href = 'pengiriman.php';
                }
            });
          </script>";
    exit();
}

// Handle Confirmed Delete
if (isset($_GET['confirm_delete']) && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data dari database berdasarkan ID
    $stmt = $pdo->prepare("DELETE FROM jastipbdg WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo "<script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data pengiriman berhasil dihapus.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'pengiriman.php';
                }
            });
          </script>";
    exit();
}

// Query untuk mengambil semua data dari tabel jastipbdg
$sql = "SELECT id, tujuan, nama_pengirim, no_hp, jumlah_paket, tgl_pengirim, berat, biaya_paket, total_biaya FROM jastipbdg";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Mengambil hasil query dan menampilkannya dalam bentuk tabel
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $biaya_paket_rupiah = "Rp " . number_format($row['biaya_paket'], 0, ',', '.');
    $total_biaya_rupiah = "Rp " . number_format($row['total_biaya'], 0, ',', '.');

    echo "<tr>";
    echo "<td class='table-plus'>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['tujuan'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['nama_pengirim'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['no_hp'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['jumlah_paket'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['tgl_pengirim'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['berat'], ENT_QUOTES, 'UTF-8') . " Kg</td>";
    echo "<td>{$biaya_paket_rupiah},-</td>";
    echo "<td>{$total_biaya_rupiah},-</td>";
    echo "<td>
            <div class='dropdown'>
                <a class='btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
                    <i class='dw dw-more'></i>
                </a>
                <div class='dropdown-menu dropdown-menu-right dropdown-menu-icon-list'>
                    <a class='dropdown-item' href='print_surat.php?id={$row['id']}' target='_blank'><i class='fa fa-print' aria-hidden='true'></i> Cetak</a>
                    <a class='dropdown-item' href='#' data-toggle='modal' data-target='#editModal{$row['id']}'><i class='dw dw-edit2'></i> Edit</a>
                    <form method='POST' action='pengiriman_data.php' style='display:inline;'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <input type='hidden' name='delete' value='true'>
                        <button type='submit' class='dropdown-item'><i class='dw dw-delete-3'></i> Hapus</button>
                    </form>
                </div>
            </div>
          </td>";
    echo "</tr>";
}
?>
