<?php
// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "penjualan_sembako");

// Ambil data pengaturan (Asumsi ada tabel 'pengaturan')
// Jika belum ada tabelnya, sistem akan menggunakan nilai default
$query = mysqli_query($koneksi, "SELECT * FROM pengaturan LIMIT 1");
$data = mysqli_fetch_assoc($query);

// Nilai default jika database kosong
$nama_toko = $data['nama_toko'] ?? "SembakoStore";
$alamat = $data['alamat'] ?? "Jl. Raya Pasar Minggu No. 12";
$telepon = $data['telepon'] ?? "08123456789";

// Logika Simpan Perubahan
if (isset($_POST['simpan'])) {
    $nama_baru = mysqli_real_escape_string($koneksi, $_POST['nama_toko']);
    $alamat_baru = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $telp_baru = mysqli_real_escape_string($koneksi, $_POST['telepon']);

    // Cek apakah data sudah ada atau belum
    if ($data) {
        mysqli_query($koneksi, "UPDATE pengaturan SET nama_toko='$nama_baru', alamat='$alamat_baru', telepon='$telp_baru'");
    } else {
        mysqli_query($koneksi, "INSERT INTO pengaturan (nama_toko, alamat, telepon) VALUES ('$nama_baru', '$alamat_baru', '$telp_baru')");
    }
    header("Location: index.php?status=sukses");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pengaturan | SembakoStore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .card-shadow { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.03), 0 10px 10px -5px rgba(0, 0, 0, 0.01); }
    </style>
</head>
<body>

<div class="flex h-screen overflow-hidden">
    <aside class="hidden md:flex flex-col w-72 sidebar-gradient text-slate-300">
        <div class="p-8">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-bolt text-white"></i>
                </div>
                <span class="text-xl font-extrabold text-white tracking-tighter italic">Sembako<span class="text-indigo-500">Store.</span></span>
            </div>
            <nav class="space-y-6">
                <div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Navigasi Utama</p>
                    <div class="space-y-1">
                        <a href="../dashboard/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-2xl transition-all group">
                            <i class="fas fa-chart-pie text-slate-500 group-hover:text-indigo-400"></i> Dashboard
                        </a>
                        <a href="../produk/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-2xl transition-all group">
                            <i class="fas fa-box text-slate-500 group-hover:text-indigo-400"></i> Data Produk
                        </a>
                        <a href="../users/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-2xl transition-all group">
                            <i class="fas fa-users-gear text-slate-500 group-hover:text-indigo-400"></i> Kelola Karyawan
                        </a>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Keuangan & Sistem</p>
                    <div class="space-y-1">
                        <a href="../transaksi/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-2xl transition-all group">
                            <i class="fas fa-cash-register text-slate-500 group-hover:text-indigo-400"></i> Kasir POS
                        </a>
                        <a href="../laporan/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-2xl transition-all group">
                            <i class="fas fa-file-invoice-dollar text-slate-500 group-hover:text-indigo-400"></i> Laporan
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-cog"></i> Pengaturan
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-y-auto">
        <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 px-8 flex items-center justify-between border-b border-gray-100">
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">Konfigurasi Sistem</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Atur Identitas & Bisnis Anda</p>
            </div>
        </header>

        <div class="p-8">
            <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl font-bold text-sm flex items-center gap-3 animate-bounce">
                    <i class="fas fa-check-circle"></i> Pengaturan berhasil diperbarui!
                </div>
            <?php endif; ?>

            <div class="max-w-2xl bg-white p-10 rounded-[3rem] card-shadow border border-slate-50">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center text-2xl">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Profil Toko</h2>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Informasi ini akan muncul di struk</p>
                    </div>
                </div>

                <form method="POST" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Toko / Bisnis</label>
                        <input type="text" name="nama_toko" value="<?= $nama_toko ?>" required 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" required 
                                  class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all"><?= $alamat ?></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="telepon" value="<?= $telepon ?>" required 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                    </div>

                    <div class="pt-6">
                        <button type="submit" name="simpan" 
                                class="w-full bg-slate-900 text-white py-5 text-xs font-black uppercase tracking-[0.3em] rounded-2xl shadow-xl hover:bg-indigo-600 transition-all transform active:scale-95">
                            Simpan Konfigurasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

</body>
</html>