<?php
session_start();
if (isset($_SESSION['login'])) {
    header("Location: ../dashboard/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | SembakoStore Admin</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body { 
      font-family: 'Plus Jakarta Sans', sans-serif; 
      background-color: #0f172a; /* Slate 900 */
      background-image: radial-gradient(circle at 2px 2px, #1e293b 1px, transparent 0);
      background-size: 40px 40px;
    }
    .glass-effect {
      background: rgba(255, 255, 255, 0.02);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.05);
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

<div class="w-full max-w-md">
  <div class="text-center mb-10">
    <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-[2rem] shadow-2xl shadow-indigo-500/20 mb-4 transform hover:rotate-12 transition-transform duration-300">
      <i class="fas fa-box-archive text-2xl text-white"></i>
    </div>
    <h1 class="text-3xl font-black text-white tracking-tighter italic">Sembako<span class="text-indigo-500">Store.</span></h1>
    <p class="text-slate-400 text-xs font-bold uppercase tracking-[0.3em] mt-2">Inventory Management System</p>
  </div>

  <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden shadow-indigo-500/10">
    <div class="p-10">
      <div class="mb-8 text-center">
        <h2 class="text-xl font-black text-slate-900 tracking-tight">Selamat Datang Kembali</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Silakan masuk untuk mengelola gudang.</p>
      </div>

      <?php if (isset($_GET['error'])): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-600 animate-bounce">
          <i class="fas fa-triangle-exclamation"></i>
          <span class="text-xs font-bold uppercase tracking-wider">Email atau password salah!</span>
        </div>
      <?php endif; ?>

      <form action="login_process.php" method="POST" class="space-y-6">
        <div class="space-y-2">
          <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Email</label>
          <div class="relative group">
            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors">
              <i class="fas fa-envelope text-sm"></i>
            </span>
            <input type="email" name="email" 
                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-14 pr-6 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 placeholder:text-slate-300" 
                   placeholder="admin@sembako.com" required>
          </div>
        </div>

        <div class="space-y-2">
          <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kata Sandi</label>
          <div class="relative group">
            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors">
              <i class="fas fa-lock text-sm"></i>
            </span>
            <input type="password" name="password" 
                   class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 pl-14 pr-6 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-slate-700 placeholder:text-slate-300" 
                   placeholder="••••••••" required>
          </div>
        </div>

        <div class="pt-4">
          <button type="submit" 
                  class="w-full bg-slate-900 text-white py-5 rounded-2xl font-black uppercase tracking-[0.3em] text-xs shadow-xl shadow-indigo-500/10 hover:bg-indigo-600 hover:-translate-y-1 transition-all duration-300 active:scale-95">
            Masuk ke System
          </button>
        </div>
      </form>
    </div>

    <div class="p-6 bg-slate-50 text-center border-t border-slate-100">
      <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">
        <i class="fas fa-shield-halved mr-1 text-indigo-400"></i> Secure Connection & Encryption Active
      </p>
    </div>
  </div>

  <p class="text-center mt-8 text-slate-500 text-[10px] font-bold uppercase tracking-widest">
    &copy; 2026 SembakoStore Portfolio Project
  </p>
</div>

</body>
</html>