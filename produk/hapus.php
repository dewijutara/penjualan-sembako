<?php
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $_SESSION['pesan'] = "Produk dengan ID #$id berhasil dihapus!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Produk - Penjualan Sembako</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="Check5 13l4 4L19 7" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Proses Berhasil!</h2>
            <p class="text-slate-500 mb-8">
                <?php echo isset($_SESSION['pesan']) ? $_SESSION['pesan'] : "Data telah diperbarui."; ?>
            </p>

            <a href="../index.php" class="inline-flex items-center justify-center w-full px-6 py-3 text-sm font-semibold text-white transition-all bg-slate-900 rounded-xl hover:bg-slate-800 focus:ring-4 focus:ring-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
        
        <div class="bg-slate-50 py-4 px-8 border-t border-slate-100 text-center">
            <span class="text-xs text-slate-400 font-medium tracking-wider uppercase">Penjualan Sembako Admin</span>
        </div>
    </div>

    <?php 
    // Bersihkan pesan setelah ditampilkan
    unset($_SESSION['pesan']); 
    ?>
</body>
</html>