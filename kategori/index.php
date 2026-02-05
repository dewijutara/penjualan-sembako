<?php
// 1. KONEKSI & LOGIKA PROSES
include "../config/database.php"; 

if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
    header("Location: index.php"); exit;
}

if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    mysqli_query($conn, "UPDATE kategori SET nama_kategori='$nama' WHERE id_kategori='$id'");
    header("Location: index.php"); exit;
}

if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($conn, $_GET['hapus']);
    mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori='$id'");
    header("Location: index.php"); exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>SembakoStore | Kategori Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .card-shadow { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.03); }
        .animate-pop { animation: pop 0.3s ease-out; }
        @keyframes pop { 0% { transform: scale(0.95); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    </style>
</head>
<body class="text-slate-800">

<div class="flex h-screen overflow-hidden relative">
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 z-40 hidden md:hidden backdrop-blur-sm"></div>
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 sidebar-gradient text-slate-300 transform -translate-x-full md:translate-x-0 md:relative transition-transform duration-300 flex flex-col">
        <div class="p-8">
            <div class="flex items-center gap-3 mb-10">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-bolt text-white"></i>
                </div>
                <span class="text-xl font-extrabold text-white tracking-tighter italic">Sembako<span class="text-indigo-500">Store.</span></span>
            </div>
            <nav class="space-y-2">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 px-4">Menu Utama</p>
                <a href="../dashboard/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-2xl transition-all"><i class="fas fa-chart-pie"></i> Dashboard</a>
                <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/20"><i class="fas fa-tags"></i> Kategori Produk</a>
                <a href="../produk/index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 rounded-2xl transition-all"><i class="fas fa-box"></i> Data Produk</a>
                <div class="pt-10">
                    <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-3 text-red-400 font-bold hover:bg-red-500/10 rounded-2xl transition-all"><i class="fas fa-power-off"></i> Keluar</a>
                </div>
            </nav>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-y-auto">
        <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 px-6 md:px-8 flex items-center justify-between border-b border-slate-100">
            <div class="flex items-center gap-4">
                <button id="menuBtn" class="md:hidden w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 active:scale-95 transition-all">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-lg font-black text-slate-800 tracking-tight leading-none">Manajemen <span class="text-indigo-600 italic">Kategori</span></h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden sm:block text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= date('d F Y') ?></span>
                <img src="https://ui-avatars.com/api/?name=Admin&background=4F46E5&color=fff" class="w-10 h-10 rounded-xl border-2 border-indigo-50" alt="Avatar">
            </div>
        </header>

        <div class="p-4 md:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4 order-2 lg:order-1">
                    <div class="bg-white rounded-[2.5rem] card-shadow border border-slate-50 overflow-hidden sticky top-24">
                        <div class="p-8 pb-2">
                            <h3 class="text-xl font-black text-slate-900 italic">Tambah <span class="text-indigo-600">Baru</span></h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 italic">Input Nama Kategori Sembako</p>
                        </div>
                        <form action="" method="post" class="p-8 space-y-6">
                            <div class="space-y-2">
                                <label for="nama_kategori_tambah" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kategori</label>
                                <input type="text" name="nama_kategori" id="nama_kategori_tambah" required
                                       class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-sm" 
                                       placeholder="Contoh: Bahan Pokok">
                            </div>
                            <button type="submit" name="tambah" 
                                    class="w-full bg-slate-900 text-white py-4.5 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] shadow-xl shadow-slate-200 hover:bg-indigo-600 active:scale-95 transition-all duration-300">
                                Simpan Kategori
                            </button>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-8 order-1 lg:order-2">
                    <div class="bg-white rounded-[2.5rem] card-shadow border border-slate-50 overflow-hidden">
                        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
                            <h3 class="text-lg font-black text-slate-800 italic uppercase tracking-tighter">List <span class="text-indigo-600">Kategori</span></h3>
                            <span class="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 text-xs"><i class="fas fa-layer-group"></i></span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/50">
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Label</th>
                                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kategori</th>
                                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php
                                    $res = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori DESC");
                                    while($k = mysqli_fetch_array($res)) :
                                    ?>
                                    <tr class="hover:bg-slate-50/80 transition-all group">
                                        <td class="px-8 py-6 text-slate-400 text-xs font-bold tracking-tighter">#ID-<?= $k['id_kategori']; ?></td>
                                        <td class="px-6 py-6 text-slate-800 font-bold uppercase text-sm group-hover:text-indigo-600 transition-colors"><?= $k['nama_kategori']; ?></td>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex justify-end gap-2">
                                                <button onclick="openModal('edit<?= $k['id_kategori']; ?>')" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition-all active:scale-90"><i class="fas fa-edit text-xs"></i></button>
                                                <a href="index.php?hapus=<?= $k['id_kategori']; ?>" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-100 text-slate-400 hover:text-red-500 hover:border-red-500 transition-all active:scale-90" onclick="return confirm('Hapus kategori ini?')"><i class="fas fa-trash text-xs"></i></a>
                                            </div>
                                        </td>
                                    </tr>

                                    <div id="edit<?= $k['id_kategori']; ?>" class="fixed inset-0 z-[100] flex items-center justify-center p-4 hidden animate-pop">
                                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('edit<?= $k['id_kategori']; ?>')"></div>
                                        <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden">
                                            <div class="p-8 pb-4 flex justify-between items-center border-b border-slate-50">
                                                <h5 class="text-xl font-black text-slate-900 italic">Edit <span class="text-indigo-600">Kategori</span></h5>
                                                <button onclick="closeModal('edit<?= $k['id_kategori']; ?>')" class="text-slate-300 hover:text-red-500 transition-colors"><i class="fas fa-times-circle text-2xl"></i></button>
                                            </div>
                                            <form action="" method="post" class="p-8 space-y-6">
                                                <input type="hidden" name="id" value="<?= $k['id_kategori']; ?>">
                                                <div class="space-y-2">
                                                    <label for="edit_nama_<?= $k['id_kategori']; ?>" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Kategori Baru</label>
                                                    <input type="text" name="nama_kategori" id="edit_nama_<?= $k['id_kategori']; ?>" value="<?= $k['nama_kategori']; ?>" required
                                                           class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-sm">
                                                </div>
                                                <button type="submit" name="edit" 
                                                        class="w-full bg-slate-900 text-white py-4.5 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] hover:bg-indigo-600 transition-all shadow-xl active:scale-95">
                                                    Update Perubahan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuBtn = document.getElementById('menuBtn');

    menuBtn.onclick = () => { sidebar.classList.toggle('-translate-x-full'); overlay.classList.toggle('hidden'); };
    overlay.onclick = () => { sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); };

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
</body>
</html>