<?php
session_start();
include "../config/database.php";

/* =====================
   TAMBAH KE KERANJANG
===================== */
if (isset($_POST['tambah'])) {

    $id = $_POST['produk'];
    $qty = $_POST['qty'];

    $p = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM produk WHERE id='$id'"
    ));

    $_SESSION['cart'][$id] = [
        'nama'  => $p['nama_produk'],
        'harga' => $p['harga'],
        'qty'   => ($_SESSION['cart'][$id]['qty'] ?? 0) + $qty
    ];
}

/* =====================
   HAPUS ITEM
===================== */
if (isset($_GET['hapus'])) {
    unset($_SESSION['cart'][$_GET['hapus']]);
}

/* =====================
   SIMPAN TRANSAKSI
===================== */
if (isset($_POST['simpan'])) {

    foreach ($_SESSION['cart'] as $id => $c) {
        $total = $c['harga'] * $c['qty'];

        mysqli_query($conn, "INSERT INTO transaksi VALUES (
            NULL, NOW(),
            '{$c['nama']}',
            '{$c['harga']}',
            '{$c['qty']}',
            '$total'
        )");

        mysqli_query($conn, "UPDATE produk 
            SET stok = stok - {$c['qty']} 
            WHERE id='$id'");
    }

    unset($_SESSION['cart']);
    echo "<script>alert('Transaksi berhasil');location='transaksi.php'</script>";
}

$produk = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0");
?>

<!DOCTYPE html>
<html>
<head>
<title>Keranjang Penjualan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{background:#eef1f5}
.card{border-radius:16px}
thead{background:#212529;color:white}
</style>
</head>

<body>
<div class="container mt-5">

<!-- FORM -->
<div class="card shadow mb-4">
<div class="card-header bg-dark text-white">
<h5><i class="fa fa-cart-plus"></i> Tambah Produk</h5>
</div>

<div class="card-body">
<form method="post" class="row g-3">

<div class="col-md-6">
<select name="produk" class="form-select" required>
<option value="">-- Pilih Produk --</option>
<?php while($p=mysqli_fetch_assoc($produk)): ?>
<option value="<?= $p['id'] ?>">
<?= $p['nama_produk'] ?> | Rp <?= number_format($p['harga']) ?>
</option>
<?php endwhile; ?>
</select>
</div>

<div class="col-md-3">
<input type="number" name="qty" class="form-control" placeholder="Qty" min="1" required>
</div>

<div class="col-md-3">
<button name="tambah" class="btn btn-primary w-100">
<i class="fa fa-plus"></i> Tambah
</button>
</div>

</form>
</div>
</div>

<!-- KERANJANG -->
<div class="card shadow">
<div class="card-header bg-secondary text-white">
<h6>Keranjang</h6>
</div>

<div class="card-body">
<table class="table table-hover text-center align-middle">
<thead>
<tr>
<th>Produk</th>
<th>Harga</th>
<th>Qty</th>
<th>Total</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>
<?php
$grand = 0;
if (!empty($_SESSION['cart'])):
foreach ($_SESSION['cart'] as $id=>$c):
$total = $c['harga'] * $c['qty'];
$grand += $total;
?>
<tr>
<td><?= $c['nama'] ?></td>
<td>Rp <?= number_format($c['harga']) ?></td>
<td><?= $c['qty'] ?></td>
<td class="fw-bold text-success">
Rp <?= number_format($total) ?>
</td>
<td>
<a href="?hapus=<?= $id ?>" class="btn btn-danger btn-sm">
<i class="fa fa-trash"></i>
</a>
</td>
</tr>
<?php endforeach; endif; ?>
</tbody>

<tfoot>
<tr>
<th colspan="3">TOTAL</th>
<th colspan="2" class="text-success">
Rp <?= number_format($grand) ?>
</th>
</tr>
</tfoot>

</table>

<form method="post">
<button name="simpan" class="btn btn-success w-100">
<i class="fa fa-check"></i> Simpan Transaksi
</button>
</form>

</div>
</div>

</div>
</body>
</html>