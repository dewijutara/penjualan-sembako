<?php
// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "penjualan_sembako");

// --- LOGIKA PROSES USERS ---

// A. Tambah User
if (isset($_POST['tambah'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role     = $_POST['role'];

    mysqli_query($koneksi, "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', '$role')");
    header("Location: index.php");
    exit;
}

// B. Edit User
if (isset($_POST['ubah'])) {
    $id       = $_POST['id_user'];
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role     = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', email='$email', password='$password', role='$role' WHERE id_user='$id'");
    } else {
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id_user='$id'");
    }
    header("Location: index.php");
    exit;
}

// C. Hapus User
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM users WHERE id_user='$id'");
    header("Location: index.php");
    exit;
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $res = mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id'");
    $edit_data = mysqli_fetch_array($res);
}

$query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id_user DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Karyawan | SembakoStore</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .card-shadow { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.03), 0 10px 10px -5px rgba(0, 0, 0, 0.01); }
    .sidebar-gradient { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
    #sidebar { transition: transform 0.3s ease-in-out; }
  </style>
</head>
<body class="text-slate-800 overflow-x-hidden">

<div class="flex h-screen overflow-hidden relative">
  
  <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden md:hidden"></div>

  <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 sidebar-gradient text-slate-300 transform -translate-x-full md:translate-x-0 md:relative transition-transform duration-300 flex flex-col">
    <div class="p-8">
        <div class="flex items-center justify-between mb-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-bolt text-white"></i>
                </div>
                <span class="text-xl font-extrabold text-white tracking-tighter italic">Sembako<span class="text-indigo-500">Store.</span></span>
            </div>
            <button id="closeSidebar" class="md:hidden text-slate-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
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
                    <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-indigo-600 text-white font-bold rounded-2xl shadow-lg shadow-indigo-500/20">
                        <i class="fas fa-users-gear"></i> Kelola Karyawan
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <div class="mt-auto p-8 border-t border-slate-800">
        <a href="../auth/logout.php" class="flex items-center gap-3 text-slate-500 hover:text-red-400 font-bold transition-all">
            <i class="fas fa-power-off"></i> <span>Keluar Sistem</span>
        </a>
    </div>
  </aside>

  <main class="flex-1 flex flex-col overflow-y-auto">
    <header class="h-20 bg-white/80 backdrop-blur-md sticky top-0 z-30 px-4 md:px-8 flex items-center justify-between border-b border-gray-100">
        <div class="flex items-center gap-4">
            <button id="menuBtn" class="md:hidden w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <h1 class="text-lg md:text-xl font-black text-slate-800 tracking-tight leading-none">Manajemen Karyawan</h1>
                <p class="hidden sm:block text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Akses & Hak Pengguna Sistem</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="hidden lg:flex flex-col text-right">
                <span class="text-xs font-black text-slate-800"><?= date('d F Y') ?></span>
                <span class="text-[9px] text-green-500 font-bold uppercase tracking-tighter">● Online</span>
            </div>
            <img src="https://ui-avatars.com/api/?name=Admin&background=4F46E5&color=fff" class="w-10 h-10 rounded-2xl ring-4 ring-indigo-50" alt="Avatar Admin">
        </div>
    </header>

    <div class="p-4 md:p-8 space-y-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
            
            <div class="lg:col-span-4 order-2 lg:order-1">
                <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] card-shadow border border-slate-50 lg:sticky lg:top-24">
                    <div class="mb-6 md:mb-8">
                        <h3 class="text-lg font-black text-slate-800 tracking-tight italic uppercase leading-none">
                            <?= $edit_data ? 'Ubah <span class="text-emerald-500">Data</span>' : 'Entri <span class="text-indigo-600">Karyawan</span>' ?>
                        </h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2">Lengkapi informasi akun</p>
                    </div>
                    
                    <form method="POST" class="space-y-4 md:space-y-5">
                        <?php if ($edit_data): ?>
                            <input type="hidden" name="id_user" value="<?= $edit_data['id_user'] ?>">
                        <?php endif; ?>
                        
                        <div class="space-y-1">
                            <label for="inputNama" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" id="inputNama" name="nama" value="<?= $edit_data ? $edit_data['nama'] : '' ?>" required 
                                   autocomplete="name"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-xl md:rounded-2xl px-5 md:px-6 py-3 md:py-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm">
                        </div>

                        <div class="space-y-1">
                            <label for="inputEmail" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Alamat Email</label>
                            <input type="email" id="inputEmail" name="email" value="<?= $edit_data ? $edit_data['email'] : '' ?>" required 
                                   autocomplete="email"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-xl md:rounded-2xl px-5 md:px-6 py-3 md:py-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm">
                        </div>

                        <div class="space-y-1">
                            <label for="inputPassword" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kata Sandi</label>
                            <input type="password" id="inputPassword" name="password" <?= $edit_data ? '' : 'required' ?>
                                   autocomplete="<?= $edit_data ? 'current-password' : 'new-password' ?>"
                                   class="w-full bg-slate-50 border border-slate-100 rounded-xl md:rounded-2xl px-5 md:px-6 py-3 md:py-4 focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm"
                                   placeholder="<?= $edit_data ? 'Kosongkan jika tetap' : 'Min. 6 karakter' ?>">
                        </div>

                        <div class="space-y-1">
                            <label for="inputRole" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Level Akses</label>
                            <select id="inputRole" name="role" class="w-full bg-slate-50 border border-slate-100 rounded-xl md:rounded-2xl px-5 md:px-6 py-3 md:py-4 focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer text-sm font-bold">
                                <option value="admin" <?= ($edit_data && $edit_data['role'] == 'admin') ? 'selected' : '' ?>>Administrator</option>
                                <option value="kasir" <?= ($edit_data && $edit_data['role'] == 'kasir') ? 'selected' : '' ?>>Staff Kasir</option>
                            </select>
                        </div>

                        <div class="pt-4">
                            <button type="submit" name="<?= $edit_data ? 'ubah' : 'tambah' ?>" 
                                    class="w-full <?= $edit_data ? 'bg-emerald-500' : 'bg-slate-900' ?> text-white py-4 md:py-5 text-[10px] md:text-xs font-black uppercase tracking-[0.2em] md:tracking-[0.3em] rounded-xl md:rounded-2xl shadow-xl hover:opacity-90 transition-all active:scale-95">
                                <?= $edit_data ? 'Simpan Perubahan' : 'Daftarkan Karyawan' ?>
                            </button>
                            <?php if ($edit_data): ?>
                                <a href="index.php" class="block text-center mt-4 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 underline">Batal Edit</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-8 order-1 lg:order-2">
                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] card-shadow border border-slate-50 overflow-hidden">
                    <div class="p-6 md:p-8 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="text-base md:text-lg font-black text-slate-800 tracking-tight">Daftar Akun Aktif</h3>
                        <i class="fas fa-users text-slate-300"></i>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[500px]">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    <th class="px-6 md:px-8 py-5">Karyawan</th>
                                    <th class="px-4 py-5 text-center">Akses</th>
                                    <th class="px-6 md:px-8 py-5 text-right">Opsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php while($row = mysqli_fetch_array($query)): ?>
                                <tr class="hover:bg-slate-50/80 transition-all group">
                                    <td class="px-6 md:px-8 py-4">
                                        <div class="flex items-center gap-3 md:gap-4">
                                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-[10px] md:text-xs border border-indigo-100 uppercase shrink-0">
                                                <?= substr($row['nama'], 0, 2) ?>
                                            </div>
                                            <div class="truncate">
                                                <div class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors uppercase text-xs md:text-sm truncate max-w-[120px] md:max-w-none"><?= $row['nama'] ?></div>
                                                <div class="text-[10px] md:text-[11px] font-medium text-slate-400 truncate"><?= $row['email'] ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-block px-3 py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-tighter border <?= $row['role'] == 'admin' ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-blue-50 text-blue-600 border-blue-100' ?>">
                                            <?= $row['role'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 md:px-8 py-4 text-right">
                                        <div class="flex justify-end gap-2 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="index.php?edit=<?= $row['id_user'] ?>" class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 transition-all shadow-sm"><i class="fas fa-edit text-[10px]"></i></a>
                                            <a href="index.php?hapus=<?= $row['id_user'] ?>" onclick="return confirm('Yakin hapus user ini?')" class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-red-600 transition-all shadow-sm"><i class="fas fa-trash text-[10px]"></i></a>
                                        </div>
                                    </td>
                                </tr>
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
</script>

</body>
</html>