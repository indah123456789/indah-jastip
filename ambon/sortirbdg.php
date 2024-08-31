<?php
session_start();
require_once '../fungsi/koneksi.php';

if ($_SESSION['kota'] !== 'ambon') {
    $kota_sesuaikan = htmlspecialchars($_SESSION['kota'], ENT_QUOTES, 'UTF-8');
    header("Location: " . BASE_URL . $kota_sesuaikan . "/sortirbdg.php");
    exit();
}

include '../view/header.php';

// Inisialisasi variabel untuk filter
$selected_kota = isset($_POST['kota']) ? $_POST['kota'] : '';
$waktu_mulai = isset($_POST['waktu_mulai']) ? $_POST['waktu_mulai'] : '';
$waktu_akhir = isset($_POST['waktu_akhir']) ? $_POST['waktu_akhir'] : '';

// Query untuk mengambil data berdasarkan pilihan kota, rentang tanggal, dan ID yang dimulai dengan 'BDG'
$sql = "SELECT * FROM sortirambon WHERE id LIKE 'BDG%'";
$params = [];
if ($selected_kota) {
    $sql .= " AND tujuan = :tujuan";
    $params['tujuan'] = $selected_kota;
}
if ($waktu_mulai && $waktu_akhir) {
    $sql .= " AND tgl_pengirim BETWEEN :waktu_mulai AND :waktu_akhir";
    $params['waktu_mulai'] = $waktu_mulai;
    $params['waktu_akhir'] = $waktu_akhir;
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$dataJastip = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi variabel untuk menghitung total
$totalPengirim = 0;
$totalJumlahPaket = 0;
$totalBerat = 0.0;
$totalBiayaPaket = 0.0;
$totalTotalBiaya = 0.0;
$totalPendingBarang = 0;
$totalRusakBarang = 0;
$totalBaikBarang = 0;
$totalPendingTujuan = 0;
$totalSudahTiba = 0;
$totalBelumTiba = 0;
?>

<div class="pre-loader">
    <div class="pre-loader-box">
        <div class="loader-logo"></div>
        <div class='loader-progress' id="progress_div">
            <div class='bar' id='bar1'></div>
        </div>
        <div class='percent' id='percent1'>0%</div>
        <div class="loading-text">
            Loading...
        </div>
    </div>
</div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Laporan</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">Bandung</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Bagian Filter dan Cetak Data -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <h4 class="text-blue h4">Data Bandung</h4>
                    <form id="filterForm" method="POST" action="">
                        <div class="row mb-20">
                            <div class="col-md-6 col-sm-12">
                                <select class="selectpicker form-control" data-style="btn-outline-primary" data-size="4" name="kota" onchange="document.getElementById('filterForm').submit();">
                                <optgroup label="Pilih kota laporan anda" data-max-options="2">
                                    <option value="">Pilih kota laporan anda</option>
                                    <option value="Ambon" <?= $selected_kota === 'Ambon' ? 'selected' : ''; ?>>Ambon</option>
                                    <option value="Tulehu, Tial" <?= $selected_kota === 'Tulehu, Tial' ? 'selected' : ''; ?>>Tulehu, Tial</option>
                                    <option value="Gemba, Kairatu" <?= $selected_kota === 'Gemba, Kairatu' ? 'selected' : ''; ?>>Gemba, Kairatu</option>
                                    <option value="Waihatu, Waisarisa" <?= $selected_kota === 'Waihatu, Waisarisa' ? 'selected' : ''; ?>>Waihatu, Waisarisa</option>
                                    <option value="Piru" <?= $selected_kota === 'Piru' ? 'selected' : ''; ?>>Piru</option>
                                    <option value="Kawa" <?= $selected_kota === 'Kawa' ? 'selected' : ''; ?>>Kawa</option>
                                    <option value="Masohi" <?= $selected_kota === 'Masohi' ? 'selected' : ''; ?>>Masohi</option>
                                    <option value="Amdua, Rutah, Haruo, Tanjung, Iha, Lohi, Sepa" <?= $selected_kota === 'Amdua, Rutah, Haruo, Tanjung, Iha, Lohi, Sepa' ? 'selected' : ''; ?>>Amdua, Rutah, Haruo, Tanjung, Iha, Lohi, Sepa</option>
                                    <option value="Luhu, Iha" <?= $selected_kota === 'Luhu, Iha' ? 'selected' : ''; ?>>Luhu, Iha</option>
                                    <option value="Sirisori" <?= $selected_kota === 'Sirisori' ? 'selected' : ''; ?>>Sirisori</option>
                                    <option value="Katapang" <?= $selected_kota === 'Katapang' ? 'selected' : ''; ?>>Katapang</option>
                                    <option value="Olas" <?= $selected_kota === 'Olas' ? 'selected' : ''; ?>>Olas</option>
                                    <option value="Bula" <?= $selected_kota === 'Bula' ? 'selected' : ''; ?>>Bula</option>
                                    <option value="Malaku" <?= $selected_kota === 'Malaku' ? 'selected' : ''; ?>>Malaku</option>
                                    <option value="Wahai" <?= $selected_kota === 'Wahai' ? 'selected' : ''; ?>>Wahai</option>
                                    <option value="Hualoi" <?= $selected_kota === 'Hualoi' ? 'selected' : ''; ?>>Hualoi</option>
                                </optgroup>
                                </select>
                                <a class="btn btn-primary" style="margin-top:20px;" href="#"><i class="fa fa-print mr-10" aria-hidden="true"></i>Cetak</a>
                                <input type="submit" hidden>
                            </div>
                            <div class="col-md-3 col-sm-12 text-right">
                                <input type="datetime-local" class="form-control" style="width: 100%;" name="waktu_mulai" value="<?= $waktu_mulai; ?>">
                            </div>
                            <div class="col-md-3 col-sm-12 text-right">
                                <input type="datetime-local" class="form-control" style="width: 100%;" name="waktu_akhir" value="<?= $waktu_akhir; ?>">
                            </div>
                        </div>
                    </form>
                    <div class="pb-20">
                        <table class="data-table table stripe hover nowrap">
                            <thead>
                                <tr>
                                    <th class="table-plus">No Resi</th>
                                    <th>Nama Pengirim</th>
                                    <th>No Hp</th>
                                    <th>Jumlah Paket</th>
                                    <th>Berat</th>
                                    <th>Biaya /Paket</th>
                                    <th>Total Biaya</th>
                                    <th>Tanggal Pengirim</th>
                                    <th>Tujuan</th>
                                    <th>Status Barang</th>
                                    <th>Status Tujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($dataJastip as $row) {
                                $biaya_paket_rupiah = "Rp " . number_format($row['biaya_paket'], 0, ',', '.');
                                $total_biaya_rupiah = "Rp " . number_format($row['total_biaya'], 0, ',', '.');
                                
                                // Hitung total pengirim
                                $totalPengirim++;

                                // Hitung total jumlah paket
                                $totalJumlahPaket += $row['jumlah_paket'];

                                // Hitung total berat
                                $totalBerat += $row['berat'];

                                // Hitung total biaya paket dan total biaya
                                $totalBiayaPaket += $row['biaya_paket'];
                                $totalTotalBiaya += $row['total_biaya'];

                                // Hitung status barang
                                if ($row['sttsbrg'] === 'Pending..') {
                                    $totalPendingBarang++;
                                } elseif ($row['sttsbrg'] === 'Rusak') {
                                    $totalRusakBarang++;
                                } elseif ($row['sttsbrg'] === 'Baik') {
                                    $totalBaikBarang++;
                                }

                                // Hitung status tujuan
                                if ($row['status'] === 'Pending..') {
                                    $totalPendingTujuan++;
                                } elseif ($row['status'] === 'Sudah Tiba Di Sortir Ambon') {
                                    $totalSudahTiba++;
                                } elseif ($row['status'] === 'Belum Tiba Di Sortir Ambon') {
                                    $totalBelumTiba++;
                                }

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($row['nama_pengirim'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($row['no_hp'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($row['jumlah_paket'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($row['berat'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>{$biaya_paket_rupiah},-</td>";
                                echo "<td>{$total_biaya_rupiah},-</td>";
                                echo "<td>" . htmlspecialchars($row['tgl_pengirim'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($row['tujuan'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($row['sttsbrg'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "<td>" . htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8') . "</td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">Total Pengirim: <?= $totalPengirim; ?></td>
                                    <td><?= $totalJumlahPaket; ?></td>
                                    <td><?= number_format($totalBerat, 2, ',', '.'); ?></td>
                                    <td>Rp <?= number_format($totalBiayaPaket, 0, ',', '.'); ?>,-</td>
                                    <td>Rp <?= number_format($totalTotalBiaya, 0, ',', '.'); ?>,-</td>
                                    <td colspan="2"></td>
                                    <td>Pending: <?= $totalPendingBarang; ?> | Rusak: <?= $totalRusakBarang; ?> | Baik: <?= $totalBaikBarang; ?></td>
                                    <td>Pending: <?= $totalPendingTujuan; ?> | Sudah Tiba: <?= $totalSudahTiba; ?> | Belum Tiba: <?= $totalBelumTiba; ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>
