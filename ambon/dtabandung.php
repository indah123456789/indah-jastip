<?php
session_start();
require_once '../fungsi/koneksi.php';

// Cek apakah pengguna sudah login dan kota yang sesuai
if (!isset($_SESSION['username']) || $_SESSION['kota'] !== 'ambon') {
    $kota_sesuaikan = $_SESSION['kota'];
    header("Location: " . BASE_URL . $kota_sesuaikan . "/dtabandung.php");
    exit();
}
include '../view/header.php';

// Query untuk mengambil data jastip dengan tujuan Ambon yang belum ada di sortirambon
$stmt = $pdo->prepare("
    SELECT jastipbdg.id, jastipbdg.nama_pengirim
    FROM jastipbdg
    LEFT JOIN sortirambon ON jastipbdg.id = sortirambon.id
    WHERE sortirambon.id IS NULL
");
$stmt->execute();
$dataJastip = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi variabel untuk filter
$selected_kota = isset($_POST['kota']) ? $_POST['kota'] : '';
$waktu_mulai = isset($_POST['waktu_mulai']) ? $_POST['waktu_mulai'] : '';
$waktu_akhir = isset($_POST['waktu_akhir']) ? $_POST['waktu_akhir'] : '';

// Query untuk mengambil data berdasarkan pilihan kota, rentang tanggal, dan ID yang dimulai dengan 'bdg'
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
$stmtt = $pdo->prepare($sql);
$stmtt->execute($params);
$dataJastipp = $stmtt->fetchAll(PDO::FETCH_ASSOC);

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
                            <h4>Data sortir</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">Sortir Data Bandung</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Button to Open Modal -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <form method="POST" action="dtasortirr.php">
                        <input type="hidden" name="action" value="sortir">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="id[]" class="custom-select2 form-control" multiple="multiple" style="width: 100%;">
                                        <optgroup label="Jastip bandung tujuan Ambon">
                                            <?php foreach ($dataJastip as $data) : ?>
                                                <option value="<?php echo htmlspecialchars($data['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php echo htmlspecialchars($data['nama_pengirim'], ENT_QUOTES, 'UTF-8'); ?> (Resi: <?php echo htmlspecialchars($data['id'], ENT_QUOTES, 'UTF-8'); ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary" style="height: 45px;">
                                    <i class="fa fa-plus-square mr-10" aria-hidden="true"></i>Sortir Ke Ambon
                                </button>
                            </div>
                        </div>
                    </form>
                    <h4 class="text-blue h4">Data Sortir Ambon</h4>
                    <p class="mb-0">Jika Belum ada data pengiriman di bawah ini tolong tambahkan data sortir ke ambon dengan masukkan no resi dan tambahkan</p>
                    <form id="filterForm" method="POST" action="">
                        <div class="row mb-20" style="margin-top:20px;">
                            <div class="col-md-6 col-sm-12">
                                <select class="selectpicker form-control" data-style="btn-outline-primary" data-size="4" name="kota" onchange="document.getElementById('filterForm').submit();">
                                    <optgroup label="Pilih kota" data-max-options="2">
                                        <option value="">Pilih kota</option>
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
                                    <th class="datatable-nosort">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($dataJastipp as $row) {
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
                                        echo "<td>
                                                <div class='dropdown'>
                                                    <a class='btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
                                                        <i class='dw dw-more'></i>
                                                    </a>
                                                    <div class='dropdown-menu dropdown-menu-right dropdown-menu-icon-list'>
                                                        <a class='dropdown-item' data-toggle='modal' data-target='#sortirModal' data-id='".htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8')."' data-nama='".htmlspecialchars($row['nama_pengirim'], ENT_QUOTES, 'UTF-8')."' data-nohp='".htmlspecialchars($row['no_hp'], ENT_QUOTES, 'UTF-8')."' data-tujuan='".htmlspecialchars($row['tujuan'], ENT_QUOTES, 'UTF-8')."' data-berat='".htmlspecialchars($row['berat'], ENT_QUOTES, 'UTF-8')."' data-jumlah='".htmlspecialchars($row['jumlah_paket'], ENT_QUOTES, 'UTF-8')."' data-tanggal='".htmlspecialchars($row['tgl_pengirim'], ENT_QUOTES, 'UTF-8')."' data-biaya='".htmlspecialchars($row['biaya_paket'], ENT_QUOTES, 'UTF-8')."' data-total='".htmlspecialchars($row['total_biaya'], ENT_QUOTES, 'UTF-8')."'><i class='dw dw-eye'></i> Sortir</a>
                                                        <a class='dropdown-item' href='javascript:void(0);' onclick='confirmDelete(\"".htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8')."\")'><i class='dw dw-delete-3'></i> Hapus </a>
                                                    </div>
                                                </div>
                                            </td>";
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
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HTML for Modal Sortir Data -->
<div class="modal fade" id="sortirModal" tabindex="-1" role="dialog" aria-labelledby="sortirModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sortirModalLabel">Data Sortir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sortirForm" method="POST" action="dtasortirr.php">
                    <input type="hidden" name="action" value="update">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_id">No Resi</label>
                                <input type="text" class="form-control" id="edit_id" name="id" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_nama_pengirim">Nama Pengirim</label>
                                <input type="text" class="form-control" id="edit_nama_pengirim" name="nama_pengirim" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_no_hp">No Telp</label>
                                <input type="text" class="form-control" id="edit_no_hp" name="no_hp" readonly maxlength="15">
                            </div>
                            <div class="form-group">
                                <label for="edit_tujuan">Tujuan</label>
                                <input type="text" class="form-control" id="edit_tujuan" name="tujuan" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_berat">Total Berat (kg)</label>
                                <input type="number" class="form-control" id="edit_berat" name="berat" step="0.01" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_jumlah_paket">Jumlah Paket</label>
                                <input type="number" class="form-control" id="edit_jumlah_paket" name="jumlah_paket" readonly>
                            </div>
                        </div>
                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_tgl_pengirim">Tanggal Pengirim</label>
                                <input type="datetime-local" class="form-control" id="edit_tgl_pengirim" name="tgl_pengirim" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_biaya_paket">Biaya per Kg (Rp)</label>
                                <input type="text" class="form-control" id="edit_biaya_paket" name="biaya_paket" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_total_biaya">Total Biaya (Rp)</label>
                                <input type="text" class="form-control" id="edit_total_biaya" name="total_biaya" readonly>
                            </div>
                            <div class="form-group">
                                <label for="edit_sttsbrg">Status Barang</label>
                                <select class="custom-select2 form-control" id="edit_sttsbrg" name="sttsbrg" style="width: 100%; height: 38px;">
                                    <option>Bagus</option>
                                    <option>Rusak</option>
                                </select>
                            </div>
                            <div class="form-group">2
                                <label for="edit_status">Status Tujuan</label>
                                <select class="custom-select2 form-control" id="edit_status" name="status" style="width: 100%; height: 38px;">
                                    <option>Pending..</option>
                                    <option>Sudah Tiba Di Sortir Ambon</option>
                                    <option>Belum Tiba Di Sortir Ambon</option>
                                    <option>Sudah Tiba Di Penerima</option>
                                    <option>Belum Tiba Di Penerima</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>
