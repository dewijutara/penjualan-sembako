<?php
include "../config/database.php"; 

// --- LOGIKA SIMPAN TRANSAKSI (Tetap Sama) ---
if (isset($_POST['simpan_transaksi'])) {
    $id_produk = $_POST['id_produk'];
    $qty_beli  = $_POST['qty'];
    $tanggal   = date('Y-m-d H:i:s');

    $query_p  = mysqli_query($conn, "SELECT * FROM produk WHERE id='$id_produk'");
    $data_p   = mysqli_fetch_array($query_p);
    
    if($data_p) {
        $nama_p   = $data_p['nama_produk'];
        $harga_p  = $data_p['harga'];
        $stok_p   = $data_p['stok'];
        $total    = $harga_p * $qty_beli;

        if ($stok_p < $qty_beli) {
            echo "<script>alert('Gagal! Stok tidak cukup'); window.location.href='index.php';</script>";
        } else {
            $stok_baru = $stok_p - $qty_beli;
            mysqli_query($conn, "UPDATE produk SET stok='$stok_baru' WHERE id='$id_produk'");
            
            $sql_t = "INSERT INTO transaksi (tanggal, nama_produk, harga, qty, total) 
                      VALUES ('$tanggal', '$nama_p', '$harga_p', '$qty_beli', '$total')";
            
            if (mysqli_query($conn, $sql_t)) {
                echo "<script>alert('Berhasil!'); window.location.href='index.php';</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kasir Digital | SembakoStore</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #fcfcfd; }
    .shadow-premium { box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.04), 0 10px 10px -5px rgba(79, 70, 229, 0.02); }
    .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
    #sidebar { transition: transform 0.3s ease-in-out; }
  </style>
</head>
<body class="text-slate-800 flex min-h-screen relative overflow-x-hidden">

  <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden"></div>

  <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 sidebar-gradient text-slate-300 shrink-0 transform -translate-x-full md:translate-x-0 md:relative transition-transform duration-300 flex flex-col">
    <div class="p-8 mb-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <i class="fas fa-cash-register text-white"></i>
            </div>
            <span class="text-xl font-bold text-white tracking-tight italic">Sembako<span class="text-indigo-500">Store.</span></span>
        </div>
        <button id="closeSidebar" class="md:hidden text-slate-400 hover:text-white">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <nav class="flex-1 px-6 space-y-2">
        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-4 px-2">Pos Terminal</p>
        <a href="../dashboard/index.php" class="flex items-center gap-4 px-4 py-3 hover:bg-slate-800 rounded-2xl transition-all group">
            <i class="fas fa-chart-pie text-slate-500 group-hover:text-indigo-400"></i>
            <span class="text-sm font-semibold group-hover:text-white">Dashboard</span>
        </a>
        <a href="index.php" class="flex items-center gap-4 px-4 py-3 bg-indigo-600/10 text-indigo-400 rounded-2xl border-l-4 border-indigo-600 shadow-sm">
            <i class="fas fa-shopping-cart text-sm"></i>
            <span class="text-sm font-bold">Kasir / POS</span>
        </a>
        <a href="../produk/index.php" class="flex items-center gap-4 px-4 py-3 hover:bg-slate-800 rounded-2xl transition-all group">
            <i class="fas fa-boxes-stacked text-slate-500 group-hover:text-indigo-400"></i>
            <span class="text-sm font-semibold group-hover:text-white">Gudang Stok</span>
        </a>
    </nav>
  </aside>

  <main class="flex-1 max-h-screen overflow-y-auto flex flex-col">
    <header class="h-20 bg-white/70 backdrop-blur-md sticky top-0 z-30 px-4 md:px-8 flex items-center justify-between border-b border-slate-100">
        <div class="flex items-center gap-4">
            <button id="menuBtn" class="md:hidden w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600">
                <i class="fas fa-bars"></i>
            </button>
            <h2 class="text-lg md:text-xl font-bold text-slate-900 tracking-tight">Digital Terminal</h2>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-[10px] text-green-500 font-black uppercase tracking-widest leading-none">Kasir Aktif</p>
                <p class="text-xs font-bold text-slate-800 mt-1"><?= date('d M Y') ?></p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600"><i class="fas fa-user-circle"></i></div>
        </div>
    </header>

    <div class="p-4 md:p-8 space-y-6 md:space-y-8">
      <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-premium border border-slate-50 overflow-hidden">
        <div class="p-6 md:p-8 pb-0">
            <h3 class="text-lg font-black text-slate-900 uppercase tracking-tighter italic">Input <span class="text-indigo-600">Pesanan Baru</span></h3>
        </div>
        <form action="" method="post" class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-12 gap-5 md:gap-6 items-end">
          <div class="md:col-span-5 space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Produk</label>
            <select name="id_produk" class="w-full bg-slate-50 border border-slate-100 rounded-xl md:rounded-2xl px-5 py-3 md:py-4 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all font-bold cursor-pointer text-sm" required>
              <option value="">-- PILIH BARANG --</option>
              <?php
              $produk = mysqli_query($conn, "SELECT * FROM produk WHERE stok > 0");
              while($row = mysqli_fetch_array($produk)):
              ?>
              <option value="<?= $row['id'] ?>"><?= strtoupper($row['nama_produk']) ?> (STOK: <?= $row['stok'] ?>)</option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="md:col-span-3 space-y-2">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jumlah (Qty)</label>
            <input type="number" name="qty" class="w-full bg-slate-50 border border-slate-100 rounded-xl md:rounded-2xl px-5 py-3 md:py-4 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all font-bold text-sm" min="1" required placeholder="0">
          </div>
          <div class="md:col-span-4">
            <button type="submit" name="simpan_transaksi" class="w-full bg-indigo-600 text-white py-3 md:py-4 rounded-xl md:rounded-2xl font-black uppercase tracking-[0.1em] md:tracking-[0.2em] text-[10px] md:text-[11px] shadow-xl shadow-indigo-500/20 hover:bg-indigo-700 transition-all">
              <i class="fas fa-save mr-2"></i> Simpan Transaksi
            </button>
          </div>
        </form>
      </div>

      <div class="bg-white rounded-[1.5rem] md:rounded-[2rem] shadow-premium border border-slate-50 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-50 flex justify-between items-center">
            <h3 class="text-base md:text-lg font-black text-slate-900 uppercase tracking-tighter italic">Riwayat  <span class="text-indigo-600">Transaksi</span></h3>
            <i class="fas fa-history text-slate-200 text-xl"></i>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left min-w-[650px]">
            <thead>
              <tr class="bg-slate-50/50 border-b border-slate-100">
                <th class="px-6 md:px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Waktu</th>
                <th class="px-4 md:px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Produk</th>
                <th class="px-4 md:px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Qty</th>
                <th class="px-4 md:px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center text-indigo-500">Total</th>
                <th class="px-6 md:px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 font-bold text-sm">
              <?php
              $riwayat = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY id DESC LIMIT 10");
              while($r = mysqli_fetch_array($riwayat)):
              ?>
              <tr class="hover:bg-slate-50/80 transition-all">
                <td class="px-6 md:px-8 py-5 text-center text-[10px] text-slate-400">
                    <?= date('H:i', strtotime($r['tanggal'])) ?><br><span class="text-[8px] opacity-50">WIB</span>
                </td>
                <td class="px-4 md:px-6 py-5 text-slate-800 uppercase text-xs md:text-sm"><?= $r['nama_produk'] ?></td>
                <td class="px-4 md:px-6 py-5 text-center text-slate-600"><?= $r['qty'] ?></td>
                <td class="px-4 md:px-6 py-5 text-center text-indigo-600 font-black italic">Rp<?= number_format($r['total'], 0, ',', '.') ?></td>
                <td class="px-6 md:px-8 py-5 text-right">
                  <div class="flex justify-end gap-2">
                    <button onclick="aksiStruk('print', '<?= $r['id'] ?>', '<?= $r['nama_produk'] ?>', '<?= $r['harga'] ?>', '<?= $r['qty'] ?>', '<?= $r['total'] ?>')" class="p-2 md:px-3 md:py-2 bg-slate-900 text-white rounded-lg text-[9px] md:text-[10px] font-black hover:bg-indigo-600 transition-all">
                      <i class="fas fa-print"></i> <span class="hidden md:inline">PRINT</span>
                    </button>
                    <button onclick="aksiStruk('pdf', '<?= $r['id'] ?>', '<?= $r['nama_produk'] ?>', '<?= $r['harga'] ?>', '<?= $r['qty'] ?>', '<?= $r['total'] ?>')" class="p-2 md:px-3 md:py-2 bg-red-50 text-red-600 rounded-lg text-[9px] md:text-[10px] font-black hover:bg-red-600 hover:text-white transition-all">
                      <i class="fas fa-file-pdf"></i> <span class="hidden md:inline">PDF</span>
                    </button>
                  </div>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
    <footer class="mt-auto p-6 text-center text-[9px] font-bold text-slate-400 uppercase tracking-widest bg-white/50">
        SembakoStore POS Terminal &copy; 2026
    </footer>
  </main>

  <script>
  // SIDEBAR LOGIC
  const menuBtn = document.getElementById('menuBtn');
  const closeSidebar = document.getElementById('closeSidebar');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');

  function toggleSidebar() {
      sidebar.classList.toggle('-translate-x-full');
      overlay.classList.toggle('hidden');
  }

  menuBtn.addEventListener('click', toggleSidebar);
  closeSidebar.addEventListener('click', toggleSidebar);
  overlay.addEventListener('click', toggleSidebar);

  // PRINT LOGIC
  function aksiStruk(tipe, id, nama, harga, qty, total) {
    const content = `
      <div style="font-family: 'Courier New', monospace; width: 280px; padding: 20px; border: 1px solid #eee;">
        <center>
          <strong style="font-size: 16px;">SEMBAKO STORE</strong><br>
          Jl. Raya Pasar Digital No. 1<br>
          ---------------------------<br>
          STRUK PEMBAYARAN #TRX-${id}<br>
          ---------------------------
        </center>
        <p style="font-size: 12px;">
          Tgl: ${new Date().toLocaleString()}<br>
          Item: ${nama.toUpperCase()}<br>
          Harga: Rp ${parseInt(harga).toLocaleString()}<br>
          Qty: ${qty}<br>
          ---------------------------<br>
          <strong style="font-size: 14px;">TOTAL: Rp ${parseInt(total).toLocaleString()}</strong>
        </p>
        ---------------------------<br>
        <center>Simpan Struk Sebagai Bukti<br>Terima Kasih!</center>
      </div>
    `;

    const win = window.open('', '', 'height=600,width=450');
    win.document.write('<html><head><title>Struk_' + id + '</title></head><body style="display:flex;justify-content:center;padding-top:20px;">');
    win.document.write(content);
    win.document.write('</body></html>');
    win.document.close();

    setTimeout(() => {
      win.print();
      win.close();
    }, 500);
  }
  </script>
</body>
</html>