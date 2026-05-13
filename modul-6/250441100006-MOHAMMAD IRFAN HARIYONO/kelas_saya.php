<?php
include 'auth.php';
include 'koneksi.php';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM kelas WHERE id_kelas = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Kelas tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Kelas — Pendataan Praktikum</title>
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

<main class="max-w-2xl mx-auto px-6 py-10">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-8">
        
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">
            <?= htmlspecialchars($data['nama_kelas']) ?>
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-sm">
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700">
                <span class="block font-semibold text-slate-500 dark:text-slate-400 mb-1 flex items-center gap-2">
                    📅 Jadwal
                </span>
                <span class="text-slate-800 dark:text-white font-medium text-base"><?= htmlspecialchars($data['jadwal'] ?? '-') ?></span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-100 dark:border-slate-700">
                <span class="block font-semibold text-slate-500 dark:text-slate-400 mb-1 flex items-center gap-2">
                    🏫 Ruangan
                </span>
                <span class="text-slate-800 dark:text-white font-medium text-base"><?= htmlspecialchars($data['ruangan'] ?? '-') ?></span>
            </div>
        </div>

        <div>
            <span class="block font-semibold text-slate-700 dark:text-slate-300 mb-2">📘 Deskripsi Praktikum</span>
            <div class="p-5 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                <?= nl2br(htmlspecialchars($data['deskripsi'] ?? 'Belum ada deskripsi praktikum untuk kelas ini.')) ?>
            </div>
        </div>

    </div>
</main>

<script>
    const html=document.documentElement;
    if(localStorage.getItem('dark')==='false')html.classList.remove('dark');
    document.getElementById('toggleDark').addEventListener('click',()=>{html.classList.toggle('dark');localStorage.setItem('dark',html.classList.contains('dark'));});
</script>
</body>
</html>