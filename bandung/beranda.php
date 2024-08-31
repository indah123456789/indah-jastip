<?php
session_start();
require_once '../fungsi/koneksi.php';

// Cek apakah pengguna sudah login dan kota yang sesuai
if (!isset($_SESSION['username'])) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}

if ($_SESSION['kota'] !== 'bandung') {
    $kota_sesuaikan = htmlspecialchars($_SESSION['kota'], ENT_QUOTES, 'UTF-8');
    header("Location: " . BASE_URL . $kota_sesuaikan . "/beranda.php");
    exit();
}

include '../view/header.php';

// Ambil data dari tabel sortirambon dengan tujuan Ambon dan id yang dimulai dengan 'BDG'
$stmt = $pdo->prepare("SELECT * FROM sortirambon WHERE tujuan = :tujuan AND id LIKE 'BDG%'");
$stmt->execute(['tujuan' => 'Ambon']);
$dataJastip = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <div class="pd-ltr-20">
        <div class="card-box pd-20 height-100-p mb-30">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <img src="../vendors/images/banner-img.png" alt="">
                </div>
                <div class="col-md-8">
                    <h4 class="font-20 weight-500 mb-10 text-capitalize">
                        Selamat datang kembali <div class="weight-600 font-30 text-blue"><?php echo htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8'); ?>!</div>
                    </h4>
                    <p class="font-18 max-width-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde
                        hic non repellendus debitis iure, doloremque assumenda. Autem modi, corrupti, nobis ea iure
                        fugiat, veniam non quaerat mollitia animi error corporis.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 mb-30">
                <div class="card-box height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="progress-data">
                            <div id="chart"></div>
                        </div>
                        <div class="widget-data">
                            <div class="h4 mb-0">2020</div>
                            <div class="weight-600 font-14">Contact</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mb-30">
                <div class="card-box height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="progress-data">
                            <div id="chart2"></div>
                        </div>
                        <div class="widget-data">
                            <div class="h4 mb-0">400</div>
                            <div class="weight-600 font-14">Deals</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mb-30">
                <div class="card-box height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="progress-data">
                            <div id="chart3"></div>
                        </div>
                        <div class="widget-data">
                            <div class="h4 mb-0">350</div>
                            <div class="weight-600 font-14">Campaign</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 mb-30">
                <div class="card-box height-100-p widget-style1">
                    <div class="d-flex flex-wrap align-items-center">
                        <div class="progress-data">
                            <div id="chart4"></div>
                        </div>
                        <div class="widget-data">
                            <div class="h4 mb-0">$6060</div>
                            <div class="weight-600 font-14">Worth</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-box mb-30">
            <div class="pd-20">
                <h4 class="text-blue h4">Data Sortir Ambon</h4>
            </div>
            <div class="pb-20">
                <table class="checkbox-datatable table nowrap">
                    <thead>
                        <tr>
                            <th>
                                <div class="dt-checkbox">
                                    <input type="checkbox" name="select_all" value="1" id="example-select-all">
                                    <span class="dt-checkbox-label"></span>
                                </div>
                            </th>
                            <th>No Resi</th>
                            <th>Nama Pengirim</th>
                            <th>No Hp</th>
                            <th>Jumlah Paket</th>
                            <th>Berat</th>
                            <th>Biaya /Paket</th>
                            <th>Total Biaya</th>
                            <th>Tanggal Pengirim</th>
                            <th>Tujuan</th>
                            <th>Status Barang</th>
                            <th>Status Pengiriman</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataJastip as $row): ?>
                        <tr>
                            <td></td>
                            <td><?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_pengirim'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row['no_hp'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row['jumlah_paket'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row['berat'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo "Rp " . number_format($row['biaya_paket'], 0, ',', '.'); ?>,-</td>
                            <td><?php echo "Rp " . number_format($row['total_biaya'], 0, ',', '.'); ?>,-</td>
                            <td><?php echo htmlspecialchars($row['tgl_pengirim'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($row['tujuan'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td>
                                <?php
                                if (isset($row['sttsbrg']) && !empty($row['sttsbrg'])) {
                                    if ($row['sttsbrg'] === 'Pending..') {
                                        echo '<span class="badge badge-secondary">Pending..</span>';
                                    } elseif ($row['sttsbrg'] === 'Bagus') {
                                        echo '<span class="badge badge-success">Bagus</span>';
                                    } elseif ($row['sttsbrg'] === 'Rusak') {
                                        echo '<span class="badge bg-danger">Rusak</span>';
                                    } else {
                                        echo '<span class="badge badge-warning">Status Tidak Dikenal</span>';
                                    }
                                } else {
                                    echo '<span class="badge badge-warning">Status Tidak Tersedia</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (isset($row['status']) && !empty($row['status'])) {
                                    if ($row['status'] === 'Pending..') {
                                        echo '<span class="badge badge-secondary">Pending..</span>';
                                    } elseif ($row['status'] === 'Sudah Tiba Di Sortir Ambon') {
                                        echo '<span class="badge badge-success">Sudah Tiba Di Sortir Ambon</span>';
                                    } elseif ($row['status'] === 'Belum Tiba Di Sortir Ambon') {
                                        echo '<span class="badge bg-danger">Belum Tiba Di Sortir Ambon</span>';
                                    } elseif ($row['status'] === 'Sudah Tiba Di Penerima') {
                                        echo '<span class="badge badge-success">Sudah Tiba Di Penerima</span>';
                                    } elseif ($row['status'] === 'Belum Tiba Di Penerima') {
                                        echo '<span class="badge bg-danger">Belum Tiba Di Penerima</span>';
                                    } else {
                                        echo '<span class="badge badge-warning">Status Tidak Dikenal</span>';
                                    }
                                } else {
                                    echo '<span class="badge badge-warning">Status Tidak Tersedia</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>
