<?php
session_start();
require_once '../fungsi/koneksi.php';

if ($_SESSION['kota'] !== 'ambon') {
    $kota_sesuaikan = htmlspecialchars($_SESSION['kota'], ENT_QUOTES, 'UTF-8');
    header("Location: " . BASE_URL . $kota_sesuaikan . "/sortirjkt.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengiriman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            margin-bottom: 5px;
            font-size: 28px;
            color: #333;
        }
        .header p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
            font-size: 14px;
            color: #333;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .text-right-wrapper {
            width: 300px;
            float: right;
            margin-top: 40px;
        }
        .text-right p {
            margin: 0;
            text-align: center;
        }
        .signature {
            margin-top: 60px;
            text-align: center;
            position: relative;
        }
        .no {
            text-align: center;
            width: 40px;
        }
        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body onload="window.print(); window.close();">

<div class="header">
    <h1>Laporan Data Pengiriman (<?php echo $filterDescription; ?>)</h1>
    <p>PT. AYANA JASTIP</p>
    <p>Jl. Dr. Cipto Mangunkusumo No. 69, Paninggilan Utara, Ciledug, Tangerang, Banten 15151</p>
    <p>Telp: 021-7329680 | Website: www.jastipayana.com</p>
    <p><strong>Periode :</strong> <?php echo date('d F Y'); ?></p>
</div>

<table>
    <thead>
        <tr>
            <th class="no">No</th>
            <th>Kode Resi</th>
            <th>Tujuan</th>
            <th>Nama Pengirim</th>
            <th>No Telp</th>
            <th>Jumlah Paket</th>
            <th>Total Berat</th>
            <th>Biaya per Kg</th>
            <th>Total Biaya</th>
            <th>Tanggal Pengirim</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($data) {
            $no = 1;
            foreach ($data as $row) {
                echo "<tr>";
                echo "<td class='no'>" . $no++ . "</td>";
                echo "<td>" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['tujuan'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['nama_pengirim'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['no_hp'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['jumlah_paket'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . htmlspecialchars($row['berat'], ENT_QUOTES, 'UTF-8') . " Kg</td>";
                echo "<td>Rp " . number_format($row['biaya_paket'], 0, ',', '.') . "</td>";
                echo "<td>Rp " . number_format($row['total_biaya'], 0, ',', '.') . "</td>";
                echo "<td>" . htmlspecialchars($row['tgl_pengirim'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10' class='text-center'>Tidak ada data</td></tr>";
        }
        ?>
    </tbody>
</table>

<div class="text-right-wrapper">
    <div class="text-right">
        <p>Bandung, <?php echo date('d F Y'); ?></p>
        <p>Hormat Kami</p>
        <div class="signature">
            <p>_______________________</p>
        </div>
    </div>
</div>

</body>
</html>
