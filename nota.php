<?php
session_start();
require_once 'config/database.php'; 
$koneksi = $conn; 

// Ambil data transaksi terakhir dari database
$query = mysqli_query($koneksi, "SELECT * FROM transaksi ORDER BY id DESC LIMIT 1");
$d = mysqli_fetch_array($query);

// Siapkan link WhatsApp menggunakan session dari proses_beli.php
$nomor_hp = "6285881601103"; 
$pesan_wa = $_SESSION['pesan_wa_final'] ?? "";
$link_final = "https://wa.me/" . $nomor_hp . "?text=" . $pesan_wa;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Belanja - Toko Sembako Jaya</title>
    <style>
        /* Desain khusus ukuran printer thermal 58mm */
        body { font-family: 'Courier New', Courier, monospace; display: flex; flex-direction: column; align-items: center; padding: 10px; background-color: #f4f4f4; }
        .nota-box { width: 280px; background: white; padding: 15px; border: 1px solid #ddd; box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        hr { border: none; border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; font-size: 12px; }
        .btn-wa { display: block; background: #25D366; color: white; text-align: center; padding: 10px; text-decoration: none; border-radius: 5px; margin-top: 15px; font-family: sans-serif; font-weight: bold; }
        .btn-print { display: block; text-align: center; margin-top: 10px; font-size: 11px; color: #888; cursor: pointer; text-decoration: underline; }
        
        /* Hilangkan tombol saat dicetak kertas */
        @media print { .btn-wa, .btn-print { display: none; } body { background: white; padding: 0; } .nota-box { border: none; box-shadow: none; } }
    </style>
</head>
<body>

<div class="nota-box">
    <div class="center bold" style="font-size: 16px;">TOKO SEMBAKO JAYA</div>
    <div class="center" style="font-size: 10px;">Terima Kasih Telah Berbelanja!</div>
    <hr>
    <table>
        <tr>
            <td>Tgl:</td>
            <td align="right"><?= $d['tanggal']; ?></td>
        </tr>
        <tr>
            <td>Item:</td>
            <td align="right"><?= $d['nama_produk']; ?></td>
        </tr>
        <tr>
            <td>Qty:</td>
            <td align="right"><?= $d['qty']; ?> x <?= number_format($d['harga'], 0, ',', '.'); ?></td>
        </tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr class="bold">
           <tr>
                <td>Pembeli:</td>
                <td align="right"><?= $_SESSION['nama_pembeli'] ?? 'Pelanggan'; ?></td>
            </tr>

            <tr>
                <td>Alamat:</td>
                <td align="right" style="font-size: 10px;"><?= $_SESSION['alamat_pembeli'] ?? '-'; ?></td>
            </tr>

            <tr class="bold">
                <td>TOTAL:</td>
                <td align="right">Rp <?= number_format($d['total'], 0, ',', '.'); ?></td>
            </tr>
    </table>
    <hr>
    <?php 
  $nomor_hp = "6285881601103"; 
  $link_final = "https://wa.me/" . $nomor_hp . "?text=" . $_SESSION['pesan_wa_final'];
?>
    <a href="<?= $link_final; ?>" class="btn-wa">Kirim ke WhatsApp</a>
    
    <span class="btn-print" onclick="window.print()">[ Cetak Struk ]</span>
</div>

</body>
</html>