<?php
include "../config/database.php"; 

// ==========================================
// 1. LOGIKA HAPUS PRODUK (Satu File)
// ==========================================
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_hapus = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query_hapus = mysqli_query($conn, "DELETE FROM produk WHERE id = '$id_hapus'");
    
    if ($query_hapus) {
        echo "<script>alert('Produk Berhasil Dihapus!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal Hapus: " . mysqli_error($conn) . "');</script>";
    }
}

// ==========================================
// 2. PROSES TAMBAH PRODUK
// ==========================================
if (isset($_POST['simpan_produk'])) {
    $nama  = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $stok  = mysqli_real_escape_string($conn, $_POST['stok']);
    
    $insert = mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok, id_kategori) 
                                   VALUES ('$nama', '$harga', '$stok', '1')");
    
    if ($insert) {
        echo "<script>alert('Produk Berhasil Ditambah!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Gagal Simpan: " . mysqli_error($conn) . "');</script>";
    }
}

// ==========================================
// 3. LOGIKA PENCARIAN (SEARCH)
// ==========================================
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';
$whereClause = $cari != '' ? "WHERE nama_produk LIKE '%$cari%'" : "";

// 4. AMBIL DATA PRODUK
$query = mysqli_query($conn, "SELECT * FROM produk $whereClause ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Produk | SembakoStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .card-shadow { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.03), 0 10px 10px -5px rgba(0, 0, 0, 0.01); }
        /* Style icon hapus pencarian */
        #btnResetSearch { display: <?= $cari != '' ? 'flex' : 'none' ?>; }
    </style>
</head>
<body class="text-slate-800 overflow-x-hidden">

<div class="flex h-screen overflow-hidden relative">
    
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 sidebar-gradient text-slate-300 transform -translate-x-full md:translate-x-0 md:relative transition-transform duration-300 flex flex-col">
        <div class="p-8">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    <span class="text-xl font-extrabold text-white tracking-tighter italic">Sembako<span class="text-indigo-500">Store.</span></span>
                </div>
            </div>
            <nav class="space-y-6">
                <div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Navigasi Utama</p>
                    <div class="space-y-1">
                        <a href="../dashboard/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-2xl transition-all group">
                            <i class="fas fa-chart-pie text-slate-500 group-hover:text-indigo-400"></i> Dashboard
                        </a>
                        <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-box"></i> Data Produk
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-y-auto">
        <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 px-8 flex items-center justify-between border-b border-gray-100">
            <h1 class="text-xl font-black text-slate-800 italic">Stok <span class="text-indigo-600">Gudang.</span></h1>
        </header>

        <div class="p-8 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Daftar Inventaris</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">SembakoStore Management System</p>
                </div>
                <button id="openModal" class="flex items-center justify-center gap-3 bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-xl hover:bg-indigo-600 transition-all group">
                    <i class="fas fa-plus-circle"></i>
                    <span class="text-xs font-black uppercase tracking-widest">Barang Baru</span>
                </button>
            </div>

            <div class="bg-white p-6 rounded-[2rem] card-shadow border border-slate-50">
                <form action="" method="get" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari nama barang..." autocomplete="off"
                               class="w-full bg-slate-50 border border-slate-100 rounded-xl px-12 py-3 outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-bold">
                        
                        <?php if ($cari != ''): ?>
                        <a href="index.php" id="btnResetSearch" class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all">
                            <i class="fas fa-times text-xs"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-xs font-black uppercase hover:bg-slate-900 transition-all shadow-lg shadow-indigo-500/20">Filter</button>
                </form>
            </div>

            <div class="bg-white rounded-[2rem] card-shadow border border-slate-50 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">
                            <th class="px-8 py-5">Produk</th>
                            <th class="px-6 py-5 text-center">Stok</th>
                            <th class="px-6 py-5 text-center">Harga</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php if(mysqli_num_rows($query) > 0): ?>
                            <?php while($row = mysqli_fetch_array($query)): ?>
                            <tr class="hover:bg-slate-50 transition-all group">
                                <td class="px-8 py-4">
                                    <div class="font-bold text-slate-800 uppercase text-sm"><?= $row['nama_produk'] ?></div>
                                    <div class="text-[9px] text-slate-400 font-bold italic tracking-widest uppercase">Kode: SBK-<?= $row['id'] ?></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black <?= $row['stok'] > 5 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' ?>">
                                        <?= $row['stok'] ?> UNIT
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-600">
                                    Rp<?= number_format($row['harga'], 0, ',', '.') ?>
                                </td>
                                <td class="px-8 py-4 text-right flex justify-end gap-2">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition-all">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <a href="index.php?aksi=hapus&id=<?= $row['id'] ?>" onclick="return confirm('Hapus produk ini?')" class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-red-600 hover:border-red-600 transition-all">
                                        <i class="fas fa-trash text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center italic text-slate-400 font-bold text-sm">Barang "<?= htmlspecialchars($cari) ?>" tidak ditemukan...</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div id="modalTambah" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div id="modalBg" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="relative bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl p-8">
        <h3 class="text-xl font-black text-slate-800 mb-6">Tambah Barang</h3>
        <form action="" method="post" class="space-y-5">
            <input type="text" name="nama_produk" placeholder="Nama Produk" required class="w-full bg-slate-50 border p-3 rounded-xl font-bold text-sm outline-none focus:ring-2 focus:ring-indigo-500">
            <div class="grid grid-cols-2 gap-4">
                <input type="number" name="harga" placeholder="Harga" required class="w-full bg-slate-50 border p-3 rounded-xl font-bold text-sm outline-none focus:ring-2 focus:ring-indigo-500">
                <input type="number" name="stok" placeholder="Stok" required class="w-full bg-slate-50 border p-3 rounded-xl font-bold text-sm outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <button type="submit" name="simpan_produk" class="w-full bg-slate-900 text-white py-4 rounded-xl font-black uppercase text-xs tracking-widest hover:bg-indigo-600 transition-all">Simpan Barang</button>
            <button type="button" id="closeModal" class="w-full text-slate-400 font-bold uppercase text-[10px] mt-2">Batal</button>
        </form>
    </div>
</div>

<script>
    const modalTambah = document.getElementById('modalTambah');
    const openModalBtn = document.getElementById('openModal');
    const closeModalBtn = document.getElementById('closeModal');
    const modalBg = document.getElementById('modalBg');

    function toggleModal() { modalTambah.classList.toggle('hidden'); }
    openModalBtn.addEventListener('click', toggleModal);
    closeModalBtn.addEventListener('click', toggleModal);
    modalBg.addEventListener('click', toggleModal);
</script>

</body>
</html>