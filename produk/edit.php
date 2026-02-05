<?php
include "../config/database.php"; 

// 1. Ambil ID dari URL dengan aman
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// 2. Jika tidak ada ID, langsung balikkan ke index agar tidak error
if (empty($id)) {
    header("Location: index.php");
    exit;
}

// 3. Ambil data produk
$query = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id'");
$p = mysqli_fetch_array($query);

// 4. PELINDUNG: Jika ID salah atau data tidak ada di database
if (!$p) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

// 5. Proses Update
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $id_kat = mysqli_real_escape_string($conn, $_POST['id_kategori']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    
    $simpan = mysqli_query($conn, "UPDATE produk SET nama_produk='$nama', id_kategori='$id_kat', harga='$harga', stok='$stok' WHERE id='$id'");
    if ($simpan) {
        echo "<script>alert('Berhasil Diubah!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal Mengubah Data: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Data | SembakoStore Admin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfd; }
        .bg-pattern {
            background-color: #0f172a;
            background-image: radial-gradient(#1e293b 1px, transparent 1px);
            background-size: 20px 20px;
        }
        /* Memastikan input tidak zoom otomatis di iPhone */
        @media screen and (max-width: 640px) {
            input, select { font-size: 16px !important; }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 md:p-6 bg-pattern">

<div class="w-full max-w-xl">
    <a href="index.php" class="inline-flex items-center text-slate-400 hover:text-white mb-6 transition-colors group">
        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center mr-3 group-hover:bg-indigo-600 transition-all">
            <i class="fas fa-arrow-left text-xs text-white"></i>
        </div>
        <span class="text-xs md:text-sm font-bold uppercase tracking-widest">Kembali ke Inventory</span>
    </a>

    <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-2xl shadow-indigo-500/10 overflow-hidden border border-white/20">
        <div class="p-6 md:p-10 pb-4">
            <div class="flex items-center justify-between mb-2">
                <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 text-[9px] md:text-[10px] font-black rounded-full uppercase tracking-[0.2em]">Update Module</span>
                <i class="fas fa-pen-nib text-slate-200 text-xl md:text-2xl"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Edit <span class="text-indigo-600">Produk</span></h2>
            <p class="text-slate-400 text-xs md:text-sm mt-2 font-medium">Perbarui informasi barang untuk akurasi data inventaris.</p>
        </div>

        <form action="" method="post" class="p-6 md:p-10 pt-4 md:pt-6 space-y-5 md:space-y-6">
            
            <div class="space-y-2">
                <label for="nama_produk" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Nama Barang</label>
                <div class="relative">
                    <input type="text" id="nama_produk" name="nama_produk" 
                           value="<?= htmlspecialchars($p['nama_produk']); ?>" 
                           autocomplete="off"
                           class="w-full bg-slate-50 border border-slate-100 rounded-xl md:rounded-2xl px-5 md:px-6 py-3 md:py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 text-sm md:text-base" 
                           required>
                    <i class="fas fa-box absolute right-5 md:right-6 top-1/2 -translate-y-1/2 text-slate-200"></i>
                </div>
            </div>

            <div class="space-y-2">
                <label for="id_kategori" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Kategori Produk</label>
                <div class="relative">
                    <select id="id_kategori" name="id_kategori" 
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl md:rounded-2xl px-5 md:px-6 py-3 md:py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 appearance-none cursor-pointer text-sm md:text-base" required>
                        <?php 
                        $kat = mysqli_query($conn, "SELECT * FROM kategori");
                        while($k = mysqli_fetch_array($kat)): 
                        ?>
                        <option value="<?= $k['id_kategori'] ?>" <?= ($k['id_kategori'] == $p['id_kategori']) ? 'selected' : ''; ?>>
                            <?= strtoupper($k['nama_kategori']) ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-5 md:right-6 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6">
                <div class="space-y-2">
                    <label for="harga" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Harga Jual (Rp)</label>
                    <input type="number" id="harga" name="harga" value="<?= $p['harga']; ?>" 
                           class="w-full bg-slate-50 border border-slate-100 rounded-xl md:rounded-2xl px-5 md:px-6 py-3 md:py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-indigo-600 text-sm md:text-base" 
                           required>
                </div>
                <div class="space-y-2">
                    <label for="stok" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Stok Saat Ini</label>
                    <input type="number" id="stok" name="stok" value="<?= $p['stok']; ?>" 
                           class="w-full bg-slate-50 border border-slate-100 rounded-xl md:rounded-2xl px-5 md:px-6 py-3 md:py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 text-sm md:text-base" 
                           required>
                </div>
            </div>

            <div class="pt-4 md:pt-6">
                <button type="submit" name="update" 
                        class="w-full bg-slate-900 text-white py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase tracking-[0.2em] md:tracking-[0.3em] text-[10px] md:text-xs shadow-xl shadow-indigo-500/10 hover:bg-indigo-600 hover:-translate-y-1 active:scale-95 transition-all duration-300">
                    Simpan Perubahan
                </button>
                <p class="text-center mt-6 text-[9px] md:text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">
                    <i class="fas fa-shield-halved mr-1"></i> Data Aman dalam Database
                </p>
            </div>
        </form>
    </div>
</div>

</body>
</html>