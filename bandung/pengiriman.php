<?php
session_start();
require_once '../fungsi/koneksi.php';

// Cek apakah pengguna sudah login dan kota yang sesuai
if (!isset($_SESSION['username']) || $_SESSION['kota'] !== 'bandung') {
    $kota_sesuaikan = $_SESSION['kota'];
    header("Location: " . BASE_URL . $kota_sesuaikan . "/pengiriman.php");
    exit();
}
include '../view/header.php';
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
                            <h4>Pengiriman</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">Pengiriman</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Button to Open Modal -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#pengirimanModal">
                        <i class="fa fa-plus-square mr-10" aria-hidden="true"></i>Tambah Data
                    </button>
                </div>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th class="table-plus datatable-nosort">Kode</th>
                                <th>Tujuan</th>
                                <th>Nama Pengirim</th>
                                <th>No Telp</th>
                                <th>Jumlah Paket</th>
                                <th>Tanggal Pengirim</th>
                                <th>Total Berat</th>
                                <th>Biaya per Kg</th>
                                <th>Total Biaya</th>
                                <th class="datatable-nosort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include 'pengiriman_data.php'; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- HTML for Modal Tambah Data -->
            <div class="modal fade" id="pengirimanModal" tabindex="-1" role="dialog" aria-labelledby="pengirimanModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pengirimanModalLabel">Tambah Pengiriman</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="pengirimanForm" method="POST" action="pengiriman_data.php">
                                <div class="row">
                                    <!-- Kolom Kiri -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_pengirim">Nama Pengirim</label>
                                            <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim" required autofocus>
                                        </div>
                                        <div class="form-group">
                                            <label for="no_hp">No Telp</label>
                                            <input type="text" class="form-control" id="no_hp" name="no_hp" required maxlength="15">
                                        </div>
                                        <div class="form-group">
                                            <label for="tujuan">Tujuan</label>
                                            <select class="form-control" id="tujuan" name="tujuan" required>
                                                <?php
                                                $sql = "SELECT id_tujuan, tujuan, harga FROM bdgtujuan";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute();
                                                $tujuan_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                                foreach ($tujuan_list as $tujuan) {
                                                    echo "<option value='{$tujuan['tujuan']}' data-harga='{$tujuan['harga']}'>{$tujuan['tujuan']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="berat">Total Berat (kg)</label>
                                            <input type="number" class="form-control" id="berat" name="berat" step="0.01" required>
                                        </div>
                                    </div>
                                    <!-- Kolom Kanan -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jumlah_paket">Jumlah Paket</label>
                                            <input type="number" class="form-control" id="jumlah_paket" name="jumlah_paket" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tgl_pengirim">Tanggal Pengirim</label>
                                            <input type="datetime-local" class="form-control" id="tgl_pengirim" name="tgl_pengirim" min="<?php echo date('Y-m-d'); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="biaya_paket">Biaya per Kg (Rp)</label>
                                            <input type="text" class="form-control" id="biaya_paket" name="biaya_paket" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="total_biaya">Total Biaya (Rp)</label>
                                            <input type="text" class="form-control" id="total_biaya" name="total_biaya" readonly>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modals for Edit Data -->
            <?php
                $sql = "SELECT id, tujuan, nama_pengirim, no_hp, jumlah_paket, tgl_pengirim, berat, biaya_paket, total_biaya FROM jastipbdg";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <!-- Modal Edit -->
                <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Pengiriman</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="pengiriman_data.php">
                                    <div class="row">
                                        <!-- Kolom Kiri -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_id<?php echo $row['id']; ?>">ID Resi</label>
                                                <input type="text" class="form-control" id="edit_id<?php echo $row['id']; ?>" name="id" value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_nama_pengirim<?php echo $row['id']; ?>">Nama Pengirim</label>
                                                <input type="text" class="form-control" id="edit_nama_pengirim<?php echo $row['id']; ?>" name="nama_pengirim" value="<?php echo htmlspecialchars($row['nama_pengirim'], ENT_QUOTES, 'UTF-8'); ?>" required autofocus>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_no_hp<?php echo $row['id']; ?>">No Telp</label>
                                                <input type="text" class="form-control" id="edit_no_hp<?php echo $row['id']; ?>" name="no_hp" value="<?php echo htmlspecialchars($row['no_hp'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_tujuan<?php echo $row['id']; ?>">Tujuan</label>
                                                <select class="form-control" id="edit_tujuan<?php echo $row['id']; ?>" name="tujuan" required>
                                                    <?php
                                                    $sqlTujuan = "SELECT tujuan, harga FROM bdgtujuan";
                                                    $stmtTujuan = $pdo->prepare($sqlTujuan);
                                                    $stmtTujuan->execute();
                                                    $tujuan_list = $stmtTujuan->fetchAll(PDO::FETCH_ASSOC);

                                                    foreach ($tujuan_list as $tujuan) {
                                                        $selected = ($tujuan['tujuan'] == $row['tujuan']) ? 'selected' : '';
                                                        echo "<option value='" . htmlspecialchars($tujuan['tujuan'], ENT_QUOTES, 'UTF-8') . "' data-harga='" . htmlspecialchars($tujuan['harga'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($tujuan['tujuan'], ENT_QUOTES, 'UTF-8') . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Kolom Kanan -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="edit_jumlah_paket<?php echo $row['id']; ?>">Jumlah Paket</label>
                                                <input type="number" class="form-control" id="edit_jumlah_paket<?php echo $row['id']; ?>" name="jumlah_paket" value="<?php echo htmlspecialchars($row['jumlah_paket'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_berat<?php echo $row['id']; ?>">Total Berat (kg)</label>
                                                <input type="number" class="form-control" id="edit_berat<?php echo $row['id']; ?>" name="berat" value="<?php echo htmlspecialchars($row['berat'], ENT_QUOTES, 'UTF-8'); ?>" step="0.01" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_tgl_pengirim<?php echo $row['id']; ?>">Tanggal Pengirim</label>
                                                <input type="datetime-local" class="form-control" id="edit_tgl_pengirim<?php echo $row['id']; ?>" name="tgl_pengirim" value="<?php echo htmlspecialchars($row['tgl_pengirim'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_biaya_paket<?php echo $row['id']; ?>">Biaya per Kg (Rp)</label>
                                                <input type="text" class="form-control" id="edit_biaya_paket<?php echo $row['id']; ?>" name="biaya_paket" value="<?php echo htmlspecialchars($row['biaya_paket'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_total_biaya<?php echo $row['id']; ?>">Total Biaya (Rp)</label>
                                                <input type="text" class="form-control" id="edit_total_biaya<?php echo $row['id']; ?>" name="total_biaya" value="<?php echo htmlspecialchars($row['total_biaya'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="update">Simpan</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateBiayaPaketAndTotalBiaya(modalId) {
            const tujuanSelect = document.querySelector(`#${modalId} select[name="tujuan"]`);
            const beratInput = document.querySelector(`#${modalId} input[name="berat"]`);
            const jumlahPaketInput = document.querySelector(`#${modalId} input[name="jumlah_paket"]`);
            const biayaPaketInput = document.querySelector(`#${modalId} input[name="biaya_paket"]`);
            const totalBiayaInput = document.querySelector(`#${modalId} input[name="total_biaya"]`);

            const harga = parseFloat(tujuanSelect.selectedOptions[0].getAttribute('data-harga')) || 0;
            const berat = parseFloat(beratInput.value) || 0;
            const jumlahPaket = parseFloat(jumlahPaketInput.value) || 0;

            const biayaPaket = harga;
            const totalBiaya = harga * berat;

            biayaPaketInput.value = biayaPaket.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
            totalBiayaInput.value = totalBiaya.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
        }

        document.querySelectorAll('.modal.fade').forEach(modal => {
            const modalId = modal.id;

            const tujuanSelect = document.querySelector(`#${modalId} select[name="tujuan"]`);
            const beratInput = document.querySelector(`#${modalId} input[name="berat"]`);
            const jumlahPaketInput = document.querySelector(`#${modalId} input[name="jumlah_paket"]`);

            tujuanSelect.addEventListener('change', () => updateBiayaPaketAndTotalBiaya(modalId));
            beratInput.addEventListener('input', () => updateBiayaPaketAndTotalBiaya(modalId));
            jumlahPaketInput.addEventListener('input', () => updateBiayaPaketAndTotalBiaya(modalId));

            modal.addEventListener('shown.bs.modal', () => updateBiayaPaketAndTotalBiaya(modalId));
        });
    });
</script>

<?php include '../view/footer.php'; ?>
