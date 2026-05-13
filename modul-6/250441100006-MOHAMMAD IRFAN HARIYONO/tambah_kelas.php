<?php
include 'auth.php';
include 'koneksi.php';

if ($_SESSION['role'] !== 'admin') { 
    header("Location: dashboard.php"); 
    exit; 
}

$success = "";
$errors  = [];

if (isset($_POST['simpan'])) {
    $nama       = trim($_POST['kelas'] ?? '');
    $ruangan    = trim($_POST['ruangan'] ?? '');
    $jadwal     = trim($_POST['jadwal'] ?? '');
    $deskripsi  = trim($_POST['deskripsi'] ?? '');

    if (empty($nama))       $errors['kelas'] = "Nama kelas wajib diisi.";
    if (empty($ruangan))    $errors['ruangan'] = "Ruangan wajib diisi.";
    if (empty($jadwal))     $errors['jadwal'] = "Jadwal wajib diisi.";
    if (empty($deskripsi))  $errors['deskripsi'] = "Deskripsi wajib diisi.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO kelas(nama_kelas, ruangan, jadwal, deskripsi) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss", $nama, $ruangan, $jadwal, $deskripsi);
        $stmt->execute();

        $success = "Kelas <strong>" . htmlspecialchars($nama) . "</strong> berhasil ditambahkan!";
        $_POST = [];
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Kelas — Pendataan Praktikum</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={darkMode:'class'}</script>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>body{font-family:'DM Sans',sans-serif}</style>
</head>

<body class="min-h-screen bg-slate-100 dark:bg-slate-900 transition-colors">

<nav class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-6 py-3 flex items-center justify-between">
    <a href="dashboard.php" class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Dashboard
    </a>
    <button id="toggleDark" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
        <svg class="w-4 h-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        <svg class="w-4 h-4 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
    </button>
</nav>

<main class="max-w-xl mx-auto py-8 px-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-7 border border-slate-200 dark:border-slate-700 shadow-sm">
        <h1 class="text-xl font-bold text-slate-800 dark:text-white mb-6">🏫 Tambah Kelas Baru</h1>

        <?php if($success): ?>
        <div class="mb-5 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400 text-sm">
            <?= $success ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">Nama Kelas</label>
                <input type="text" name="kelas" value="<?= htmlspecialchars($_POST['kelas'] ?? '') ?>"
                    class="w-full px-3 py-2.5 rounded-lg border <?=isset($errors['kelas'])?'border-red-400 dark:border-red-600':'border-slate-300 dark:border-slate-600'?> bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                <?php if(isset($errors['kelas'])): ?><p class="mt-1 text-red-500 text-xs"><?= $errors['kelas'] ?></p><?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">Ruangan</label>
                <input type="text" name="ruangan" value="<?= htmlspecialchars($_POST['ruangan'] ?? '') ?>"
                    class="w-full px-3 py-2.5 rounded-lg border <?=isset($errors['ruangan'])?'border-red-400 dark:border-red-600':'border-slate-300 dark:border-slate-600'?> bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                <?php if(isset($errors['ruangan'])): ?><p class="mt-1 text-red-500 text-xs"><?= $errors['ruangan'] ?></p><?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">Jadwal</label>
                <input type="text" name="jadwal" value="<?= htmlspecialchars($_POST['jadwal'] ?? '') ?>"
                    class="w-full px-3 py-2.5 rounded-lg border <?=isset($errors['jadwal'])?'border-red-400 dark:border-red-600':'border-slate-300 dark:border-slate-600'?> bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                <?php if(isset($errors['jadwal'])): ?><p class="mt-1 text-red-500 text-xs"><?= $errors['jadwal'] ?></p><?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">Deskripsi Praktikum</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full px-3 py-2.5 rounded-lg border <?=isset($errors['deskripsi'])?'border-red-400 dark:border-red-600':'border-slate-300 dark:border-slate-600'?> bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                <?php if(isset($errors['deskripsi'])): ?><p class="mt-1 text-red-500 text-xs"><?= $errors['deskripsi'] ?></p><?php endif; ?>
            </div>

            <button type="submit" name="simpan" class="w-full mt-2 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-lg transition-colors">
                Simpan Kelas
            </button>
        </form>
    </div>
</main>

<script>
    const html=document.documentElement;
    if(localStorage.getItem('dark')==='false')html.classList.remove('dark');
    document.getElementById('toggleDark').addEventListener('click',()=>{html.classList.toggle('dark');localStorage.setItem('dark',html.classList.contains('dark'));});
</script>
</body>
</html>