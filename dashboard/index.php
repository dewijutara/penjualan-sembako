<?php
session_start();
// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "penjualan_sembako");

// 1. DATA STATISTIK DASAR
$totalProduk = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM produk"));
$totalKategori = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kategori"));
$totalTrans  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM transaksi"));
$totalUsers  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users"));

// 2. LOGIKA KEUANGAN
$qDuit = mysqli_query($koneksi, "SELECT SUM(total) as total_semua FROM transaksi");
$dDuit = mysqli_fetch_assoc($qDuit);
$pendapatan = $dDuit['total_semua'] ?? 0;

// 3. DATA GRAFIK
$grafik = [];
$labels = [];
for ($i = 5; $i >= 0; $i--) {
    $bulanNum = date('n', strtotime("-$i months"));
    $bulanName = date('M', strtotime("-$i months"));
    $qG = mysqli_query($koneksi, "SELECT SUM(total) as jml FROM transaksi WHERE MONTH(tanggal) = '$bulanNum'");
    $rG = mysqli_fetch_assoc($qG);
    $grafik[] = (int)($rG['jml'] ?? 0);
    $labels[] = $bulanName;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>SembakoStore | Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .card-shadow { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.03), 0 10px 10px -5px rgba(0, 0, 0, 0.01); }
        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="text-slate-800 overflow-x-hidden">

<div class="flex h-screen overflow-hidden relative">
    
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 z-40 hidden md:hidden"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 sidebar-gradient text-slate-300 transform -translate-x-full md:translate-x-0 md:relative transition-transform duration-300 flex flex-col">
        <div class="p-8 flex-1 overflow-y-auto">
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
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4">Menu Utama</p>
                    <div class="space-y-1">
                        <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-chart-pie"></i> Dashboard
                        </a>
                        <a href="../kategori/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-2xl transition-all group">
                            <i class="fas fa-tags text-slate-500 group-hover:text-indigo-400"></i> Kategori Produk
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
                        <a href="../pengaturan/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white rounded-2xl transition-all group">
                            <i class="fas fa-cog text-slate-500 group-hover:text-indigo-400"></i> Pengaturan Sistem
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        
        <div class="p-8 border-t border-slate-800">
            <a href="../auth/logout.php" onclick="return confirm('Keluar dari sistem?')" class="flex items-center gap-3 text-slate-500 hover:text-red-400 font-bold transition-all group">
                <i class="fas fa-power-off group-hover:rotate-90 transition-transform"></i> <span>Keluar Sistem</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-y-auto">
        <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 px-8 flex items-center justify-between border-b border-gray-100">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Dashboard Admin</h1>
            <div class="flex items-center gap-4">
                <div class="flex flex-col text-right">
                    <span class="text-xs font-black text-slate-800 uppercase"><?= date('l, d F Y') ?></span>
                    <span class="text-[9px] text-green-500 font-bold uppercase">● Sistem Aktif</span>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin&background=4F46E5&color=fff" class="w-10 h-10 rounded-2xl border-4 border-indigo-50">
            </div>
        </header>

        <div class="p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-[2rem] card-shadow border border-gray-50 flex items-center gap-5">
                    <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center flex-shrink-0"><i class="fas fa-tags text-xl"></i></div>
                    <div>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Kategori Produk</p>
                        <h2 class="text-2xl font-black text-slate-800 leading-none"><?= $totalKategori ?></h2>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] card-shadow border border-gray-50 flex items-center gap-5">
                    <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0"><i class="fas fa-box text-xl"></i></div>
                    <div>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Produk</p>
                        <h2 class="text-2xl font-black text-slate-800 leading-none"><?= $totalProduk ?></h2>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] card-shadow border border-gray-50 flex items-center gap-5">
                    <div class="w-14 h-14 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center flex-shrink-0"><i class="fas fa-users text-xl"></i></div>
                    <div>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Kelola Karyawan</p>
                        <h2 class="text-2xl font-black text-slate-800 leading-none"><?= $totalUsers ?></h2>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-[2.5rem] card-shadow border border-emerald-50 flex items-center gap-6">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center flex-shrink-0"><i class="fas fa-shopping-cart text-2xl"></i></div>
                    <div>
                        <p class="text-slate-400 text-[11px] font-black uppercase tracking-widest mb-1">Total Transaksi Selesai</p>
                        <h2 class="text-3xl font-black text-emerald-600 leading-none"><?= $totalTrans ?></h2>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2.5rem] card-shadow border border-blue-50 flex items-center gap-6">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center flex-shrink-0"><i class="fas fa-wallet text-2xl"></i></div>
                    <div class="flex-1">
                        <p class="text-slate-400 text-[11px] font-black uppercase tracking-widest mb-1">Total Pendapatan Toko</p>
                        <h2 class="text-3xl font-black text-blue-600 leading-none whitespace-nowrap">
                            Rp<?= number_format($pendapatan, 0, ',', '.') ?>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] card-shadow border border-gray-50">
                    <h3 class="text-lg font-black text-slate-800 mb-6">Tren Penjualan Bulanan</h3>
                    <div class="h-80"><canvas id="mainFinanceChart"></canvas></div>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] card-shadow border border-gray-50 overflow-hidden">
                    <h3 class="text-lg font-black text-slate-800 mb-6">Aktivitas Terbaru</h3>
                    <div class="space-y-6">
                        <?php
                        $queryRecent = mysqli_query($koneksi, "SELECT * FROM transaksi ORDER BY id DESC LIMIT 4");
                        while($dataRecent = mysqli_fetch_assoc($queryRecent)) {
                        ?>
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-all text-xs"><i class="fas fa-shopping-bag"></i></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate w-32"><?= $dataRecent['nama_produk'] ?></p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase"><?= date('H:i', strtotime($dataRecent['tanggal'])) ?></p>
                                </div>
                            </div>
                            <span class="text-xs font-black text-slate-900 italic">Rp<?= number_format($dataRecent['total'], 0, ',', '.') ?></span>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('mainFinanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels); ?>,
            datasets: [{
                label: 'Pendapatan',
                data: <?= json_encode($grafik); ?>,
                borderColor: '#4F46E5',
                borderWidth: 4,
                backgroundColor: 'rgba(79, 70, 225, 0.05)',
                fill: true,
                tension: 0.4,
                pointRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10, weight: 'bold' } } },
                x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' } } }
            }
        }
    });
</script>
</body>
</html>