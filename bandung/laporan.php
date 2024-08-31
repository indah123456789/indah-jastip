<?php
session_start();
require_once '../fungsi/koneksi.php';

if ($_SESSION['kota'] !== 'bandung') {
    $kota_sesuaikan = htmlspecialchars($_SESSION['kota'], ENT_QUOTES, 'UTF-8');
    header("Location: " . BASE_URL . $kota_sesuaikan . "/laporan.php");
    exit();
}
include '../view/header.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'last_12_hours';
$whereClause = '';

switch ($filter) {
    case 'last_12_hours':
        $whereClause = "WHERE tgl_pengirim >= DATE_SUB(NOW(), INTERVAL 12 HOUR)";
        break;
    case 'last_7_days':
        $whereClause = "WHERE tgl_pengirim >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        break;
    case 'last_1_month':
        $whereClause = "WHERE tgl_pengirim >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        break;
    case 'last_6_months':
        $whereClause = "WHERE tgl_pengirim >= DATE_SUB(NOW(), INTERVAL 6 MONTH)";
        break;
    case 'last_12_months':
        $whereClause = "WHERE tgl_pengirim >= DATE_SUB(NOW(), INTERVAL 12 MONTH)";
        break;
    default:
        $whereClause = "WHERE tgl_pengirim >= DATE_SUB(NOW(), INTERVAL 12 HOUR)";
        break;
}

// Ambil data dari database dengan filter yang diterapkan
$sql = "SELECT id, tujuan, nama_pengirim, no_hp, jumlah_paket, berat, biaya_paket, total_biaya, tgl_pengirim FROM jastipbdg $whereClause";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                                <li class="breadcrumb-item active" aria-current="page">Laporan</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <div class="dropdown">
                            <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                <?php
                                switch ($filter) {
                                    case 'last_12_hours':
                                        echo "Data 12 Jam Terakhir";
                                        break;
                                    case 'last_7_days':
                                        echo "Data 7 Hari Terakhir";
                                        break;
                                    case 'last_1_month':
                                        echo "Data 1 Bulan Terakhir";
                                        break;
                                    case 'last_6_months':
                                        echo "Data 6 Bulan Terakhir";
                                        break;
                                    case 'last_12_months':
                                        echo "Data 12 Bulan Terakhir";
                                        break;
                                    default:
                                        echo "Pilih Waktu";
                                        break;
                                }
                                ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="laporan.php?filter=last_12_hours">Data 12 Jam Terakhir</a>
                                <a class="dropdown-item" href="laporan.php?filter=last_7_days">Data 7 Hari Terakhir</a>
                                <a class="dropdown-item" href="laporan.php?filter=last_1_month">Data 1 Bulan Terakhir</a>
                                <a class="dropdown-item" href="laporan.php?filter=last_6_months">Data 6 Bulan Terakhir</a>
                                <a class="dropdown-item" href="laporan.php?filter=last_12_months">Data 12 Bulan Terakhir</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-box mb-30">
                <div class="pd-20">
                    <a class="btn btn-primary" href="cetak_laporan.php?filter=<?php echo $filter; ?>">
                        <i class="fa fa-print mr-10" aria-hidden="true"></i>Cetak
                    </a>
                </div>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th class="table-plus datatable-nosort">Kode Resi</th>
                                <th>Tujuan</th>
                                <th>Nama Pengirim</th>
                                <th>No Telp</th>
                                <th>Jumlah Paket</th>
                                <th>Total Berat</th>
                                <th>Biaya per Kg</th>
                                <th>Total Biaya</th>
                                <th class="datatable-nosort">Tanggal Pengirim</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($data) {
                                foreach ($data as $row) {
                                    echo "<tr>";
                                    echo "<td class='table-plus'>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['tujuan'], ENT_QUOTES, 'UTF-8') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nama_pengirim'], ENT_QUOTES, 'UTF-8') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['no_hp'], ENT_QUOTES, 'UTF-8') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['jumlah_paket'], ENT_QUOTES, 'UTF-8') . "</td>";
                                    echo "<td>" . htmlspecialchars($row['berat'], ENT_QUOTES, 'UTF-8') . " Kg</td>";
                                    echo "<td>Rp " . number_format($row['biaya_paket'], 0, ',', '.') . ",-</td>";
                                    echo "<td>Rp " . number_format($row['total_biaya'], 0, ',', '.') . ",-</td>";
                                    echo "<td>" . htmlspecialchars($row['tgl_pengirim'], ENT_QUOTES, 'UTF-8') . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9' class='text-center'>Tidak ada data</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>
