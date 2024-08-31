<?php
session_start();
require_once '../fungsi/koneksi.php';

if ($_SESSION['kota'] !== 'ambon') {
    $kota_sesuaikan = htmlspecialchars($_SESSION['kota'], ENT_QUOTES, 'UTF-8');
	$profil = htmlspecialchars($_SESSION['profil'], ENT_QUOTES, 'UTF-8');
    header("Location: " . BASE_URL . $kota_sesuaikan . "/profile.php");
    exit();
}
include '../view/header.php';

?>

	<div class="mobile-menu-overlay"></div>
	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="page-header">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="title">
								<h4>Profil</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.html">Profil</a></li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
						<div class="pd-20 card-box height-10-p">
						<div class="profile-photo">
							<a href="modal" data-toggle="modal" data-target="#modal" class="edit-avatar"><i class="fa fa-pencil"></i></a>
							<img src="../vendors/images/<?php echo ($adminPhoto != '') ? htmlspecialchars($adminPhoto, ENT_QUOTES, 'UTF-8') : 'profil.png'; ?>" alt="" class="avatar-photo">
							<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered" role="document">
									<div class="modal-content">
										<form method="POST" action="../fungsi/upload_profil.php" enctype="multipart/form-data">
											<div class="modal-body pd-5">
												<div class="img-container">
													<center>
														<input type="file" id="fileInput" name="image" accept="image/*" required>
													</center>
													<img id="image" src="../vendors/images/<?php echo ($adminPhoto != '') ? htmlspecialchars($adminPhoto, ENT_QUOTES, 'UTF-8') : 'profil.png'; ?>" alt="Picture" style="margin-top: 10px; max-width: 100%; height: auto;">
												</div>
											</div>
											<div class="modal-footer">
												<input type="submit" value="Update" class="btn btn-primary">
												<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
							<h5 class="text-center h5 mb-0"><?php echo htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8'); ?></h5>
							<p class="text-center text-muted font-14">Lorem ipsum dolor sit amet</p>
							<div class="profile-info">
								<h5 class="mb-20 h5 text-blue">Informasi Profil</h5>
								<ul>
									<li>
										<span>Alamat Email:</span>
										<?php echo htmlspecialchars($adminEmail, ENT_QUOTES, 'UTF-8'); ?>
									</li>
									<li>
										<span>No Telp:</span>
										<?php echo htmlspecialchars($adminPhone, ENT_QUOTES, 'UTF-8'); ?>
									</li>
									<li>
										<span>Alamat:</span>
										<?php echo htmlspecialchars($adminCity, ENT_QUOTES, 'UTF-8'); ?>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
						<div class="card-box height-100-p overflow-hidden">
							<div class="profile-tab height-100-p">
								<div class="tab height-100-p">
									<ul class="nav nav-tabs customtab" role="tablist">
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#setting" role="tab">Pengaturan</a>
										</li>
									</ul>
									<div class="tab-content">
										<!-- Setting Tab start -->
										<div class="tab-pane fade show active" id="setting" role="tabpanel">
											<div class="profile-setting">
											<form action="edit_profil.php" method="POST">
												<ul class="profile-edit-list row">
													<li class="weight-500 col-md-12">
														<h4 class="text-blue h5 mb-20">Edit Profil</h4>
														<div class="form-group">
															<label>Username</label>
															<input class="form-control form-control-lg" type="text" name="username" value="<?php echo htmlspecialchars($adminUsername, ENT_QUOTES, 'UTF-8'); ?>" required>
														</div>
														<div class="form-group">
															<label>Nama</label>
															<input class="form-control form-control-lg" type="text" name="nama" value="<?php echo htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8'); ?>" required>
														</div>
														<div class="form-group">
															<label>No Telp</label>
															<input class="form-control form-control-lg" type="text" name="notlp" value="<?php echo htmlspecialchars($adminPhone, ENT_QUOTES, 'UTF-8'); ?>">
														</div>
														<div class="form-group">
															<label>Email</label>
															<input class="form-control form-control-lg" type="email" name="email" value="<?php echo htmlspecialchars($adminEmail, ENT_QUOTES, 'UTF-8'); ?>" required>
														</div>
														<div class="form-group">
															<label>Alamat</label>
															<textarea class="form-control" name="alamat" required><?php echo htmlspecialchars($adminCity, ENT_QUOTES, 'UTF-8'); ?></textarea>
														</div>
														<div class="form-group mb-0">
															<input type="submit" class="btn btn-primary" value="Update Profil">
														</div>
													</li>
												</ul>
											</form>
											<form action="ganti_password.php" method="POST">
												<ul class="profile-edit-list row">
													<li class="weight-500 col-md-12">
														<h4 class="text-blue h5 mb-20">Ganti Password</h4>
														<div class="form-group">
															<label>Password Lama :</label>
															<input class="form-control form-control-lg" type="password" name="old_password" placeholder="Masukkan password lama anda..." required>
														</div>
														<div class="form-group">
															<label>Password Baru :</label>
															<input class="form-control form-control-lg" type="password" name="new_password" placeholder="Masukkan password baru" required>
														</div>
														<div class="form-group">
															<label>Konfirmasi Password :</label>
															<input class="form-control form-control-lg" type="password" name="confirm_password" placeholder="Konfirmasi password anda" required>
														</div>
														<div class="form-group mb-0">
															<input type="submit" class="btn btn-primary" value="Ganti Password">
														</div>
													</li>
												</ul>
											</form>
										</div>
										<!-- Setting Tab End -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			

	<?php include '../view/footer.php'; ?>
	
<script>
    	window.addEventListener('DOMContentLoaded', function () {
        var image = document.getElementById('image');
        var fileInput = document.getElementById('fileInput');
        var cropBoxData;
        var canvasData;
        var cropper;

        $('#modal').on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                autoCropArea: 0.5,
                dragMode: 'move',
                aspectRatio: 1,
                restore: false,
                guides: false,
                center: false,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
                ready: function () {
                    cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
                }
            });
        }).on('hidden.bs.modal', function () {
            cropBoxData = cropper.getCropBoxData();
            canvasData = cropper.getCanvasData();
            cropper.destroy();
        });

        // Menangani perubahan file input dan memperbarui gambar
        fileInput.onchange = function (event) {
            const [file] = event.target.files;
            if (file) {
                image.src = URL.createObjectURL(file);
                if (cropper) {
                    cropper.replace(image.src);
                }
            }
        };

        // Ketika tombol Update ditekan
        document.querySelector('input[type="submit"]').addEventListener('click', function (e) {
            e.preventDefault();
            
            // Ambil data dari cropper
            var canvas = cropper.getCroppedCanvas({
                width: 300,  // Ukuran output yang diinginkan
                height: 300,
            });

            canvas.toBlob(function (blob) {
                var formData = new FormData();
                formData.append('croppedImage', blob, 'profil.png');

                fetch('../fungsi/upload_profil.php', {
                    method: 'POST',
                    body: formData,
                }).then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: data.status === 'success' ? 'success' : 'error',
                        title: data.status === 'success' ? 'Berhasil!' : 'Gagal!',
                        text: data.message,
                        showConfirmButton: true
                    }).then((result) => {
                        if (data.status === 'success' && result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                }).catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat mengunggah.',
                        showConfirmButton: true
                    });
                });
            });
        });
    });
</script>




