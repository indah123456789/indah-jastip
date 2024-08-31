<?php
session_start();
require_once '../fungsi/koneksi.php';

if ($_SESSION['kota'] !== 'bandung') {
    $kota_sesuaikan = htmlspecialchars($_SESSION['kota'], ENT_QUOTES, 'UTF-8');
    header("Location: " . BASE_URL . $kota_sesuaikan . "/pengiriman.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mendapatkan data pengiriman berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM jastipbdg WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $pengiriman = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pengiriman) {
        echo "Data tidak ditemukan.";
        exit();
    }
} else {
    echo "ID tidak disertakan.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Tanda Pengiriman Barang</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            width: 210mm;
            height: 297mm;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            box-sizing: border-box;
        }

        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            color: #444;
        }

        .header p {
            margin: 2px 0;
            font-size: 14px;
            color: #777;
        }

        .content {
            width: 100%;
            margin-top: 20px;
        }

        .content .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .content .row div {
            width: 48%;
        }

        .content table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .content table td {
            padding: 8px;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
        }

        .content table td strong {
            font-weight: 600;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .footer .signature {
            width: 45%;
            text-align: center;
        }

        .footer .signature p {
            margin-top: 80px;
            font-size: 14px;
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .charges, .services {
            width: 48%;
        }

        .charges table, .services table {
            width: 100%;
            margin-top: 10px;
        }

        .charges table td, .services table td {
            padding: 5px;
            font-size: 14px;
        }

        .charges table td:last-child, .services table td:last-child {
            text-align: right;
        }

        .charges table, .services table {
            border: 1px solid #000;
        }

        .charges table td:first-child, .services table td:first-child {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Surat Tanda Ayana Jastip</h1>
        <p>PT. AYANA JASTIP</p>
        <p>Jl. Dr. Cipto Mangunkusumo No. 69, Paninggilan Utara, Ciledug, Tangerang, Banten 15151</p>
        <p>Telp: 021-7329680 | Website: www.jastipayana.com</p>
    </div>

    <div class="content">
        <table>
            <tr>
                <td><strong>No Resi:</strong> <?php echo $pengiriman['id']; ?></td>
                <td><strong>Tanggal:</strong> <?php echo date('d-m-Y', strtotime($pengiriman['tgl_pengirim'])); ?></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Pengirim:</strong> <?php echo $pengiriman['nama_pengirim']; ?>, <?php echo $pengiriman['no_hp']; ?></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Tujuan:</strong> <?php echo $pengiriman['tujuan']; ?></td>
            </tr>
            <tr>
                <td><strong>Total Berat:</strong> <?php echo $pengiriman['berat']; ?> kg</td>
                <td><strong>Jumlah Paket:</strong> <?php echo $pengiriman['jumlah_paket']; ?></td>
            </tr>
            <tr>
                <td><strong>Biaya Per Kg:</strong> Rp <?php echo number_format($pengiriman['biaya_paket'], 0, ',', '.'); ?></td>
                <td><strong>Total Biaya:</strong> Rp <?php echo number_format($pengiriman['total_biaya'], 0, ',', '.'); ?></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div class="signature">
            <p>Pengirim</p>
        </div>
        <div class="signature">
            <p>Penerima</p>
        </div>
    </div>
</div>

<script>
    window.print();
</script>

</body>
</html>
