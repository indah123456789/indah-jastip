<?php
require_once '../fungsi/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['username']) || !isset($_SESSION['kota'])) {
    // Jika tidak ada sesi, arahkan ke halaman login
    header("Location: " . BASE_URL . "index.php");
    exit();
}

// Dapatkan nama kota dari sesi
$kota = $_SESSION['kota'];
$username = $_SESSION['username']; // Ambil username dari sesi

// Query untuk mendapatkan informasi admin dari tabel `admin`
$stmt = $pdo->prepare("SELECT * FROM admin WHERE username = :username");
$stmt->execute(['username' => $username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika admin tidak ditemukan, logout dan arahkan ke halaman login
if (!$admin) {
    session_destroy();
    header("Location: " . BASE_URL . "index.php");
    exit();
}

// Variabel yang dapat digunakan di seluruh halaman
$adminUsername = htmlspecialchars($admin['username'], ENT_QUOTES, 'UTF-8');
$adminName = htmlspecialchars($admin['nama'], ENT_QUOTES, 'UTF-8');
$adminEmail = htmlspecialchars($admin['email'], ENT_QUOTES, 'UTF-8');
$adminPhone = htmlspecialchars($admin['notlp'], ENT_QUOTES, 'UTF-8');
$adminCity = htmlspecialchars($admin['alamat'], ENT_QUOTES, 'UTF-8');
$adminPhoto = htmlspecialchars($admin['profil'], ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html>
<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8">
	<title>Jastip Ayana</title>

	<!-- Site favicon -->
	<!-- <link rel="apple-touch-icon" sizes="180x180" href="../vendors/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="../vendors/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="../vendors/images/favicon-16x16.png"> -->

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="../vendors/styles/core.css">
	<link rel="stylesheet" type="text/css" href="../vendors/styles/icon-font.min.css">
	<link rel="stylesheet" type="text/css" href="../src/plugins/datatables/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="../src/plugins/datatables/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" type="text/css" href="../vendors/styles/style.css">
	<link rel="stylesheet" type="text/css" href="../src/plugins/sweetalert2/sweetalert2.css">
	<link rel="stylesheet" type="text/css" href="../src/plugins/cropperjs/dist/cropper.css">
	<link rel="stylesheet" type="text/css" href="src/plugins/fullcalendar/fullcalendar.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
	

	<div class="header">
		<div class="header-left">
			<div class="menu-icon dw dw-menu"></div>
			<div class="search-toggle-icon dw dw-search2" data-toggle="header_search"></div>
			<div class="header-search">
				<form>
					<div class="form-group mb-0">
						<i class="dw dw-search2 search-icon"></i>
						<input type="text" class="form-control search-input" placeholder="Pencarian">
						<div class="dropdown">
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="header-right">
			<div class="dashboard-setting user-notification">
				<div class="dropdown">
					<a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
						<i class="dw dw-settings2"></i>
					</a>
				</div>
			</div>
			<div class="user-info-dropdown">
				<div class="dropdown">
					<a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						<span class="user-icon">
							<img src="../vendors/images/<?php echo ($adminPhoto != '') ? htmlspecialchars($adminPhoto, ENT_QUOTES, 'UTF-8') : 'profil.png'; ?>" alt="">
						</span>
						<span class="user-name"><?php echo $_SESSION['nama']; ?></span>
					</a>
					<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
						<a class="dropdown-item" href="<?php echo BASE_URL . $kota; ?>/profile.php"><i class="dw dw-user1"></i> Profile</a>
						<a class="dropdown-item" href="#" id="logoutButton"><i class="dw dw-logout"></i> Keluar</a>

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="right-sidebar">
		<div class="sidebar-title">
			<h3 class="weight-600 font-16 text-blue">
				Pengaturan Tata Letak
				<span class="btn-block font-weight-400 font-12">Antarmuka Pengguna</span>
			</h3>
			<div class="close-sidebar" data-toggle="right-sidebar-close">
				<i class="icon-copy ion-close-round"></i>
			</div>
		</div>
		<div class="right-sidebar-body customscroll">
			<div class="right-sidebar-body-content">
				<h4 class="weight-600 font-18 pb-10">Latar Belakang Header</h4>
				<div class="sidebar-btn-group pb-30 mb-10">
					<a href="javascript:void(0);" class="btn btn-outline-primary header-white active">Putih</a>
					<a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Hitam</a>
				</div>

				<h4 class="weight-600 font-18 pb-10">Latar Belakang Sidebar</h4>
				<div class="sidebar-btn-group pb-30 mb-10">
					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light">Putih</a>
					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Hitam</a>
				</div>

				<h4 class="weight-600 font-18 pb-10">Menu Dropdown Ikon</h4>
				<div class="sidebar-radio-group pb-10 mb-10">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-1" checked="">
						<label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-2" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-2">
						<label class="custom-control-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-3" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-3">
						<label class="custom-control-label" for="sidebaricon-3"><i class="fa fa-angle-double-right"></i></label>
					</div>
				</div>

				<h4 class="weight-600 font-18 pb-10">Menu List Ikon</h4>
				<div class="sidebar-radio-group pb-30 mb-10">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-1" name="menu-list-icon" class="custom-control-input" value="icon-list-style-1" checked="">
						<label class="custom-control-label" for="sidebariconlist-1"><i class="ion-minus-round"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-2" name="menu-list-icon" class="custom-control-input" value="icon-list-style-2">
						<label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o" aria-hidden="true"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-3" name="menu-list-icon" class="custom-control-input" value="icon-list-style-3">
						<label class="custom-control-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-4" name="menu-list-icon" class="custom-control-input" value="icon-list-style-4" checked="">
						<label class="custom-control-label" for="sidebariconlist-4"><i class="icon-copy dw dw-next-2"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-5" name="menu-list-icon" class="custom-control-input" value="icon-list-style-5">
						<label class="custom-control-label" for="sidebariconlist-5"><i class="dw dw-fast-forward-1"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-6" name="menu-list-icon" class="custom-control-input" value="icon-list-style-6">
						<label class="custom-control-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
					</div>
				</div>

				<div class="reset-options pt-30 text-center">
					<button class="btn btn-danger" id="reset-settings">Atur Ulang Pengaturan</button>
				</div>
			</div>
		</div>
	</div>

	<div class="left-side-bar">
		<div class="brand-logo">
			<a href="beranda.php">
				<!-- <img src="../vendors/images/deskapp-logo.svg" alt="" class="dark-logo"> -->
				<!-- <img src="../vendors/images/deskapp-logo-white.svg" alt="" class="light-logo"> -->
			</a>
			<div class="close-sidebar" data-toggle="left-sidebar-close">
				<i class="ion-close-round"></i>
			</div>
		</div>
		<div class="menu-block customscroll">
			<div class="sidebar-menu">
            <ul id="accordion-menu">
            <li class="dropdown">
                <a href="<?php echo BASE_URL . $kota; ?>/beranda.php" class="dropdown-toggle no-arrow">
                    <span class="micon dw dw-house-1"></span><span class="mtext">Beranda</span>
                </a>
            </li>
            <?php if ($kota === 'ambon'): ?>
			<li class="dropdown">
				<a href="javascript:;" class="dropdown-toggle">
					<span class="micon dw dw-layers"></span><span class="mtext">Data Sortir</span>
				</a>
				<ul class="submenu">
					<li><a href="<?php echo BASE_URL . $kota; ?>/dtajakarta.php">Dari Jakarta</a></li>
					<li><a href="<?php echo BASE_URL . $kota; ?>/dtabandung.php">Dari Bandung</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="javascript:;" class="dropdown-toggle">
					<span class="micon dw dw-invoice"></span><span class="mtext">Laporan</span>
				</a>
				<ul class="submenu">
					<li><a href="<?php echo BASE_URL . $kota; ?>/sortirjkt.php">Jakarta Sortir</a></li>
					<li><a href="<?php echo BASE_URL . $kota; ?>/sortirbdg.php">Bandung Sortir</a></li>
				</ul>
			</li>
		<?php else: ?>
			<li class="dropdown">
				<a href="<?php echo BASE_URL . $kota; ?>/pengiriman.php" class="dropdown-toggle no-arrow">
					<span class="micon dw dw-right-arrow1"></span><span class="mtext">Pengiriman Barang</span>
				</a>
			</li>
			<li>
				<a href="<?php echo BASE_URL . $kota; ?>/laporan.php" class="dropdown-toggle no-arrow">
					<span class="micon dw dw-invoice"></span><span class="mtext">Laporan</span>
				</a>
			</li>
		<?php endif; ?>
			<li>
				<a href="<?php echo BASE_URL . $kota; ?>/dtatujuan.php" class="dropdown-toggle no-arrow">
					<span class="micon dw dw-calendar1"></span><span class="mtext">Data Harga Tujuan</span>
				</a>
			</li>
            </ul>
			</div>
		</div>
	</div>
	<div class="mobile-menu-overlay"></div>

	

