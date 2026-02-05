<?php
session_start();
date_default_timezone_set('Asia/Jakarta'); 
require_once dirname(__FILE__) . '/config/database.php';
$koneksi = $conn;

if (empty($_SESSION['keranjang'])) {
    die("Error: Keranjang belanja kosong di sistem!");
}

foreach ($_SESSION['keranjang'] as $id => $item) {
    $qty = $item['jumlah'];
    $id_bersih = trim($id); 
    $nama_produk = $item['nama']; // Mengambil nama produk dari session
    $harga = $item['harga']; 
    $total = $harga * $qty; // Menghitung total
    $tanggal = date('Y-m-d H:i:s');

    // 1. Potong Stok (Sesuai ID yang sudah kamu samakan)
    $query_stok = "UPDATE produk SET stok = stok - $qty WHERE id = '$id_bersih'";
    mysqli_query($koneksi, $query_stok);

    // 2. Simpan ke tabel Transaksi (Sesuai kolom di fotomu)
    // Nama kolom: tanggal, nama_produk, harga, qty, total
    $query_transaksi = "INSERT INTO transaksi (tanggal, nama_produk, harga, qty, total) 
                        VALUES ('$tanggal', '$nama_produk', '$harga', '$qty', '$total')";
    
    if (!mysqli_query($koneksi, $query_transaksi)) {
        echo "Gagal simpan transaksi: " . mysqli_error($koneksi);
    }   
}
$daftar_barang = "";
foreach ($_SESSION['keranjang'] as $id => $item) {
    $daftar_barang .= "- " . $item['nama'] . " (" . $item['jumlah'] . "x)\n";
}


// Ambil data dari form
$nama = $_POST['nama_pembeli'];
$alamat = $_POST['alamat_pembeli'];

// Susun teks pesan WhatsApp
$isi_pesan = "Halo Admin, saya mau beli:\n\n";
$isi_pesan .= "*IDENTITAS PEMESAN*\n";
$isi_pesan .= "Nama: $nama\n";
$isi_pesan .= "Alamat: $alamat\n\n";
$isi_pesan .= "*DAFTAR BARANG*\n$daftar_barang";

$pesan_encode = urlencode($isi_pesan);
$_SESSION['pesan_wa_final'] = $pesan_encode;
$_SESSION['nama_pembeli'] = $_POST['nama_pembeli'];
$_SESSION['alamat_pembeli'] = $_POST['alamat_pembeli'];
unset($_SESSION['keranjang']);
header("Location: nota.php");
exit();