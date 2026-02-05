<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$conn = new mysqli("localhost", "root", "", "penjualan_sembako");

// 1. LOGIKA FILTER TANGGAL
$filter = $_GET['filter'] ?? 'harian';
$mulai = $_GET['mulai'] ?? date('Y-m-d');
$akhir = $_GET['akhir'] ?? date('Y-m-d');

if ($filter == 'mingguan') {
    $mulai = date('Y-m-d', strtotime('-1 week'));
    $akhir = date('Y-m-d');
} elseif ($filter == 'bulanan') {
    $mulai = date('Y-m-01');
    $akhir = date('Y-m-t');
}

$res = mysqli_query($conn, "SELECT * FROM transaksi WHERE DATE(tanggal) BETWEEN '$mulai' AND '$akhir' ORDER BY id DESC");
$q_duit = mysqli_query($conn, "SELECT SUM(total) as omzet FROM transaksi WHERE DATE(tanggal) BETWEEN '$mulai' AND '$akhir'");
$data_duit = mysqli_fetch_array($q_duit);
$q_item = mysqli_query($conn, "SELECT SUM(qty) as terjual FROM transaksi WHERE DATE(tanggal) BETWEEN '$mulai' AND '$akhir'");
$data_item = mysqli_fetch_array($q_item);

// ================= EKPORT PDF (DOMPDF) =================
if (isset($_GET['pdf'])) {
    ob_start();
    ?>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #4f46e5; color: white; padding: 10px; font-size: 12px; }
        td { padding: 8px; border: 1px solid #eee; font-size: 11px; }
        .total-row { background: #f8fafc; font-weight: bold; }
    </style>
    <div class="header">
        <h2 style="margin:0;">LAPORAN PENJUALAN - SEMBAKO STORE</h2>
        <p style="font-size:12px; color:#666;">Periode: <?= $mulai ?> s/d <?= $akhir ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; $grand=0; while($d = mysqli_fetch_array($res)): $grand+=$d['total']; ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td><?= $d['tanggal'] ?></td>
                <td><?= strtoupper($d['nama_produk']) ?></td>
                <td align="center"><?= $d['qty'] ?></td>
                <td align="right">Rp <?= number_format($d['total'],0,',','.') ?></td>
            </tr>
            <?php endwhile; ?>
            <tr class="total-row">
                <td colspan="4" align="right">TOTAL PEMASUKAN</td>
                <td align="right">Rp <?= number_format($grand,0,',','.') ?></td>
            </tr>
        </tbody>
    </table>
    <?php
    $html = ob_get_clean();
    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('Laporan_Penjualan.pdf', ['Attachment' => 0]);
    exit;
}

// ================= EKPORT EXCEL =================
if (isset($_GET['excel'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    $sheet->fromArray(['No', 'Tanggal', 'Nama Produk', 'Qty', 'Total'], NULL, 'A1');
    $sheet->getStyle('A1:E1')->getFont()->setBold(true);
    
    $row_num = 2; 
    $no = 1;
    $grand_total_excel = 0;
    
    mysqli_data_seek($res, 0); 
    while ($d = mysqli_fetch_array($res)) {
        $sheet->setCellValue("A$row_num", $no++);
        $sheet->setCellValue("B$row_num", $d['tanggal']);
        $sheet->setCellValue("C$row_num", strtoupper($d['nama_produk']));
        $sheet->setCellValue("D$row_num", $d['qty']);
        $sheet->setCellValue("E$row_num", $d['total']);
        
        $grand_total_excel += $d['total'];
        $row_num++;
    }

    $sheet->setCellValue("A$row_num", "GRAND TOTAL PEMASUKAN");
    $sheet->mergeCells("A$row_num:D$row_num");
    $sheet->setCellValue("E$row_num", $grand_total_excel);
    
    $sheet->getStyle("A$row_num:E$row_num")->getFont()->setBold(true);
    $sheet->getStyle("A$row_num")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle("E2:E$row_num")->getNumberFormat()->setFormatCode('#,##0');

    foreach (range('A','E') as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Laporan_Penjualan.xlsx"');
    $writer->save('php://output');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan | SembakoStore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfd; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .shadow-premium { box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.04), 0 10px 10px -5px rgba(79, 70, 229, 0.02); }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="hidden md:flex flex-col w-72 sidebar-gradient text-slate-300 shrink-0">
        <div class="p-8 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-bolt text-white"></i>
                </div>
                <span class="text-xl font-extrabold text-white tracking-tighter italic">Sembako<span class="text-indigo-500">Store.</span></span>
            </div>
        </div>
        <nav class="flex-1 px-6 space-y-2">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 px-2">Menu Utama</p>
            <a href="../dashboard/index.php" class="flex items-center gap-4 px-4 py-3 hover:bg-slate-800 rounded-2xl transition-all group">
                <i class="fas fa-chart-pie text-slate-500 group-hover:text-indigo-400"></i>
                <span class="text-sm font-semibold group-hover:text-white">Dashboard</span>
            </a>
            <a href="../produk/index.php" class="flex items-center gap-4 px-4 py-3 hover:bg-slate-800 rounded-2xl transition-all group">
                <i class="fas fa-box text-slate-500 group-hover:text-indigo-400"></i>
                <span class="text-sm font-semibold group-hover:text-white">Produk</span>
            </a>
            <a href="../users/index.php" class="flex items-center gap-4 px-4 py-3 hover:bg-slate-800 rounded-2xl transition-all group">
                <i class="fas fa-users-gear text-slate-500 group-hover:text-indigo-400"></i>
                <span class="text-sm font-semibold group-hover:text-white">Kelola Karyawan</span>
            </a>

            <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mt-8 mb-4 px-2">Keuangan & Sistem</p>
            <a href="../transaksi/index.php" class="flex items-center gap-4 px-4 py-3 hover:bg-slate-800 rounded-2xl transition-all group">
                <i class="fas fa-cash-register text-slate-500 group-hover:text-indigo-400"></i>
                <span class="text-sm font-semibold group-hover:text-white">Kasir POS</span>
            </a>
            <a href="index.php" class="flex items-center gap-4 px-4 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/20">
                <i class="fas fa-file-invoice-dollar"></i>
                <span class="text-sm">Laporan Penjualan</span>
            </a>
            <a href="../pengaturan/index.php" class="flex items-center gap-4 px-4 py-3 hover:bg-slate-800 rounded-2xl transition-all group">
                <i class="fas fa-cog text-slate-500 group-hover:text-indigo-400"></i>
                <span class="text-sm font-semibold group-hover:text-white">Pengaturan</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1 max-h-screen overflow-y-auto">
        <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 px-8 flex items-center justify-between border-b border-slate-100">
            <div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight">Laporan Keuangan</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Analisis Transaksi Real-time</p>
            </div>
            <div class="flex items-center gap-4 italic text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                Status: <span class="text-green-500">● Terverifikasi Sistem</span>
            </div>
        </header>

        <div class="p-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-500/20">
                    <div class="relative z-10">
                        <p class="text-indigo-100 text-[10px] font-black uppercase tracking-widest mb-2">Total Omzet Periode Ini</p>
                        <h2 class="text-4xl font-black italic tracking-tighter text-white">Rp <?= number_format($data_duit['omzet'] ?? 0, 0, ',', '.'); ?></h2>
                    </div>
                    <i class="fas fa-wallet absolute -right-4 -bottom-4 text-white/10 text-9xl"></i>
                </div>
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl shadow-slate-900/10">
                    <div class="relative z-10">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-2">Total Barang Terjual</p>
                        <h2 class="text-4xl font-black italic tracking-tighter text-white"><?= $data_item['terjual'] ?? 0; ?> <span class="text-lg font-medium text-slate-500 italic uppercase">Produk</span></h2>
                    </div>
                    <i class="fas fa-box-open absolute -right-4 -bottom-4 text-white/10 text-9xl"></i>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 shadow-premium border border-slate-50">
                <form action="" method="get" class="flex flex-col lg:flex-row gap-4 items-end">
                    <div class="flex-1 grid grid-cols-2 gap-4 w-full">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Dari Tanggal</label>
                            <input type="date" name="mulai" value="<?= $mulai ?>" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 outline-none font-bold text-sm transition-all text-slate-700">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sampai Tanggal</label>
                            <input type="date" name="akhir" value="<?= $akhir ?>" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 outline-none font-bold text-sm transition-all text-slate-700">
                        </div>
                    </div>
                    <div class="flex gap-2 w-full lg:w-auto">
                        <button type="submit" class="flex-1 lg:flex-none px-8 py-4 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-lg active:scale-95">Filter</button>
                        <a href="?mulai=<?= $mulai ?>&akhir=<?= $akhir ?>&pdf=1" target="_blank" class="px-6 py-4 bg-rose-50 text-rose-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all"><i class="fas fa-file-pdf mr-1"></i> PDF</a>
                        <a href="?mulai=<?= $mulai ?>&akhir=<?= $akhir ?>&excel=1" class="px-6 py-4 bg-emerald-50 text-emerald-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all"><i class="fas fa-file-excel mr-1"></i> Excel</a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[3rem] shadow-premium border border-slate-50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">No</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Transaksi</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Produk</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Harga Satuan</th>
                                <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Qty</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $no=1; $grand=0; mysqli_data_seek($res, 0); while($d = mysqli_fetch_array($res)): $grand+=$d['total']; ?>
                            <tr class="hover:bg-slate-50 transition-all group">
                                <td class="px-8 py-6 text-center text-slate-300 font-bold"><?= $no++ ?></td>
                                <td class="px-6 py-6 text-[11px] font-black text-slate-400 uppercase italic leading-tight"><?= $d['tanggal'] ?></td>
                                <td class="px-6 py-6">
                                    <span class="block text-sm font-black text-slate-800 uppercase tracking-tight group-hover:text-indigo-600 transition-colors"><?= $d['nama_produk'] ?></span>
                                </td>
                                <td class="px-6 py-6 text-center text-slate-500 font-bold text-xs">Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
                                <td class="px-6 py-6 text-center">
                                    <span class="bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-tighter border border-indigo-100 italic"><?= $d['qty'] ?> Unit</span>
                                </td>
                                <td class="px-8 py-6 text-right font-black text-slate-800 tracking-tighter text-sm">Rp <?= number_format($d['total'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-indigo-600 border-t-2 border-indigo-700">
                                <td colspan="5" class="px-8 py-6 text-right text-[10px] font-black text-white uppercase tracking-[0.3em]">Grand Total Pemasukan</td>
                                <td class="px-8 py-6 text-right text-xl font-black text-white italic tracking-tighter">Rp <?= number_format($grand, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>