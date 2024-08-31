<?php
session_start();
require_once '../fungsi/koneksi.php';

if ($_SESSION['kota'] !== 'bandung') {
    $kota_sesuaikan = htmlspecialchars($_SESSION['kota'], ENT_QUOTES, 'UTF-8');
    header("Location: " . BASE_URL . $kota_sesuaikan . "/dtatujuan.php");
    exit();
}

$message = '';
$message_type = ''; // success or error

// Tambah Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['update'])) {
    $tujuan = $_POST['tujuan'];
    $harga = $_POST['harga'];

    $sql = "INSERT INTO bdgtujuan (tujuan, harga) VALUES (:tujuan, :harga)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':tujuan' => $tujuan, ':harga' => $harga])) {
        $message = 'Data berhasil ditambahkan';
        $message_type = 'success';
    } else {
        $message = 'Gagal menambahkan data';
        $message_type = 'error';
    }
}

// Update Data
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id_tujuan = $_POST['id_tujuan'];
    $tujuan = $_POST['edit_tujuan'];
    $harga = $_POST['edit_harga'];

    $sql = "UPDATE bdgtujuan SET tujuan = :tujuan, harga = :harga WHERE id_tujuan = :id_tujuan";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':tujuan' => $tujuan, ':harga' => $harga, ':id_tujuan' => $id_tujuan])) {
        $message = 'Data berhasil diupdate';
        $message_type = 'success';
    } else {
        $message = 'Gagal mengupdate data';
        $message_type = 'error';
    }
}

// Hapus Data
if (isset($_GET['delete'])) {
    $id_tujuan = $_GET['delete'];

    $sql = "DELETE FROM bdgtujuan WHERE id_tujuan = :id_tujuan";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([':id_tujuan' => $id_tujuan])) {
        $message = 'Data berhasil dihapus';
        $message_type = 'success';
    } else {
        $message = 'Gagal menghapus data';
        $message_type = 'error';
    }
}

// Fetch Data untuk ditampilkan
$sql = "SELECT id_tujuan, tujuan, harga FROM bdgtujuan";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$tujuan_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                            <h4>Data Harga Tujuan</h4>
                        </div>
                        <nav aria-label="breadcrumb" role="navigation">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">Data Harga Tujuan</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Button to Open Modal -->
            <div class="card-box mb-30">
                <div class="pd-20">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#tujuanModal">
                        <i class="fa fa-plus-square mr-10" aria-hidden="true"></i>Tambah Data
                    </button>
                </div>

                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th class="table-plus datatable-nosort">Id Tujuan</th>
                                <th>Tujuan</th>
                                <th>Harga</th>
                                <th class="datatable-nosort">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tujuan_data as $row) { 
                                $harga_rupiah = "Rp " . number_format($row['harga'], 0, ',', '.'); ?>
                                <tr>
                                    <td class='table-plus'><?php echo htmlspecialchars($row['id_tujuan'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($row['tujuan'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo "$harga_rupiah"; ?>,-</td>
                                    <td>
                                        <div class='dropdown'>
                                            <a class='btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle' href='#' role='button' data-toggle='dropdown'>
                                                <i class='dw dw-more'></i>
                                            </a>
                                            <div class='dropdown-menu dropdown-menu-right dropdown-menu-icon-list'>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editModal<?php echo $row['id_tujuan']; ?>"><i class="dw dw-edit2"></i> Edit</a>
                                                <a class="dropdown-item" href="?delete=<?php echo $row['id_tujuan']; ?>" id="deleteBtn"><i class="dw dw-delete-3"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal Edit -->
                                <div class="modal fade" id="editModal<?php echo $row['id_tujuan']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $row['id_tujuan']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-md" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $row['id_tujuan']; ?>">Edit Data Tujuan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="dtatujuan.php">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="edit_id<?php echo $row['id_tujuan']; ?>">ID Tujuan</label>
                                                                <input type="text" class="form-control" id="edit_id<?php echo $row['id_tujuan']; ?>" name="id_tujuan" value="<?php echo htmlspecialchars($row['id_tujuan'], ENT_QUOTES, 'UTF-8'); ?>" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="edit_harga<?php echo $row['id_tujuan']; ?>">Harga</label>
                                                                <input type="text" class="form-control" id="edit_harga<?php echo $row['id_tujuan']; ?>" name="edit_harga" value="<?php echo htmlspecialchars($row['harga'], ENT_QUOTES, 'UTF-8'); ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="edit_tujuan<?php echo $row['id_tujuan']; ?>">Tujuan</label>
                                                                <input type="text" class="form-control" id="edit_tujuan<?php echo $row['id_tujuan']; ?>" name="edit_tujuan" value="<?php echo htmlspecialchars($row['tujuan'], ENT_QUOTES, 'UTF-8'); ?>" required>
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
                                <!-- End of Modal Edit -->

                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Tambah Data -->
            <div class="modal fade" id="tujuanModal" tabindex="-1" role="dialog" aria-labelledby="tujuanModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tujuanModalLabel">Tambah Data Tujuan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="pengirimanForm" method="POST" action="dtatujuan.php">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tujuan">Tujuan</label>
                                            <input type="text" class="form-control" id="tujuan" name="tujuan" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="harga">Harga</label>
                                            <input type="text" class="form-control" id="harga" name="harga" required maxlength="15">
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

        </div>
    </div>
</div>

<?php include '../view/footer.php'; ?>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    <?php if (!empty($message)): ?>
    Swal.fire({
        icon: '<?php echo $message_type; ?>',
        title: '<?php echo $message_type === 'success' ? 'Berhasil' : 'Gagal'; ?>',
        text: '<?php echo $message; ?>',
    });
    <?php endif; ?>

    // Konfirmasi penghapusan dengan SweetAlert
    document.querySelectorAll('.dropdown-item[href*="delete"]').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault();
            const href = this.getAttribute('href');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
