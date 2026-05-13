<?php
include 'auth.php';
include 'koneksi.php';

$is_admin = ($_SESSION['role'] === 'admin');

if ($is_admin) {
    $total_mhs   = $conn->query("SELECT COUNT(*) c FROM mahasiswa")->fetch_assoc()['c'];
    $total_kelas = $conn->query("SELECT COUNT(*) c FROM kelas")->fetch_assoc()['c'];
    $total_user  = $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c'];

    $search = trim($_GET['q'] ?? '');
    $base_sql = "
        SELECT m.id_mhs, m.nama, m.nim,
               k.nama_kelas, k.ruangan, k.jadwal,
               u.username
        FROM mahasiswa m
        LEFT JOIN kelas k ON m.id_kelas = k.id_kelas
        LEFT JOIN users u ON m.user_id = u.id
        WHERE 1=1
    ";

    if ($search != '') {
        $sql = $base_sql . " AND (m.nama LIKE ? OR m.nim LIKE ? OR k.nama_kelas LIKE ?) ORDER BY m.id_mhs DESC";
        $stmt = $conn->prepare($sql);
        $like = "%$search%";
        $stmt->bind_param("sss", $like, $like, $like);
        $stmt->execute();
        $mhs_res = $stmt->get_result();
    } else {
        $sql = $base_sql . " ORDER BY m.id_mhs DESC";
        $mhs_res = $conn->query($sql);
    }

    $kls_res = $conn->query("
        SELECT k.*, COUNT(m.id_mhs) jml
        FROM kelas k
        LEFT JOIN mahasiswa m ON k.id_kelas = m.id_kelas
        GROUP BY k.id_kelas
        ORDER BY k.id_kelas DESC
    ");
} else {
    $user = $_SESSION['user'];
    // FIX: Menggunakan prepared statement untuk keamanan ekstra
    $stmt_kls = $conn->prepare("
        SELECT k.*, COUNT(m2.id_mhs) jml
        FROM mahasiswa m
        JOIN users u ON m.user_id = u.id
        JOIN kelas k ON m.id_kelas = k.id_kelas
        LEFT JOIN mahasiswa m2 ON k.id_kelas = m2.id_kelas
        WHERE u.username = ?
        GROUP BY k.id_kelas
        ORDER BY k.id_kelas DESC
    ");
    $stmt_kls->bind_param("s", $user);
    $stmt_kls->execute();
    $kls_res = $stmt_kls->get_result();
}
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Pendataan Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={darkMode:'class'}</script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif}</style>
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-900 transition-colors duration-200">
<nav class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-6 py-3 flex items-center justify-between sticky top-0 z-10">
    <span class="font-semibold text-slate-800 dark:text-white text-sm"> Portal Praktikum</span>
    <div class="flex items-center gap-3">
        <span class="text-xs text-slate-500 dark:text-slate-400"><?= htmlspecialchars($_SESSION['user']) ?> <span class="px-1.5 py-0.5 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300"><?= htmlspecialchars($_SESSION['role']) ?></span></span>
        <button id="toggleDark" class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
            <svg class="w-4 h-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            <svg class="w-4 h-4 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
        </button>
        <a href="logout.php" class="text-xs px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors">Logout</a>
    </div>
</nav>

<main class="max-w-6xl mx-auto px-6 py-6 space-y-6">
    <?php if ($is_admin): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php foreach([['Mahasiswa',$total_mhs,'👤'],['Kelas',$total_kelas,'🏫'],['Pengguna',$total_user,'🔑']] as [$label,$val,$icon]): ?>
        <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
            <div class="text-2xl font-bold text-slate-800 dark:text-white"><?= $val ?></div>
            <div class="text-sm text-slate-500 dark:text-slate-400 mt-0.5"><?= $icon ?> <?= $label ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="tambah_kelas.php" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm rounded-lg transition-colors">+ Tambah Kelas</a>
        <a href="tambah_mahasiswa.php" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">+ Tambah Mahasiswa</a>
    </div>

    <?php if (isset($_GET['deleted'])): ?>
    <div class="px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 text-sm">Data berhasil dihapus.</div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between flex-wrap gap-3">
            <h2 class="font-semibold text-slate-800 dark:text-white text-sm">Data Mahasiswa <span class="text-slate-400">(<?= $mhs_res->num_rows ?>)</span></h2>
            <form method="GET" class="flex gap-2 w-full md:w-auto">
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama, NIM, kelas…"
                    class="w-full md:w-auto px-3 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button class="px-3 py-1.5 bg-slate-800 dark:bg-slate-200 text-white dark:text-slate-800 hover:bg-slate-700 dark:hover:bg-white text-sm rounded-lg transition-colors">Cari</button>
                <?php if($search): ?><a href="dashboard.php" class="px-3 py-1.5 text-sm rounded-lg border border-slate-300 dark:border-slate-600 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700">Reset</a><?php endif; ?>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm whitespace-nowrap">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">
                    <tr><?php foreach(['#','Nama','NIM','Kelas','Ruangan','Jadwal','Username'] as $h): ?><th class="px-4 py-3 text-left font-medium"><?=$h?></th><?php endforeach; ?>
                    <th class="px-4 py-3 text-left font-medium">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                <?php if($mhs_res->num_rows===0): ?>
                    <tr><td colspan="8" class="px-4 py-8 text-center text-slate-400">Tidak ada data<?=$search?' yang cocok.':'.';?></td></tr>
                <?php else: $no=1; while($r=$mhs_res->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-4 py-3 text-slate-400"><?=$no++?></td>
                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-white"><?=htmlspecialchars($r['nama'])?></td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><?=htmlspecialchars($r['nim'])?></td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><?=htmlspecialchars($r['nama_kelas']??'-')?></td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><?=htmlspecialchars($r['ruangan']??'-')?></td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><?=htmlspecialchars($r['jadwal']??'-')?></td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><?=htmlspecialchars($r['username']??'-')?></td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="edit_mahasiswa.php?id=<?=$r['id_mhs']?>" class="px-2.5 py-1 text-xs bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-lg border border-amber-200 dark:border-amber-800 hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-colors">Edit</a>
                            <a href="hapus_mahasiswa.php?id=<?=$r['id_mhs']?>" onclick="return confirm('Yakin hapus <?=htmlspecialchars(addslashes($r['nama']))?> ?')" class="px-2.5 py-1 text-xs bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg border border-red-200 dark:border-red-800 hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php else: ?>
    <div class="bg-blue-600 dark:bg-blue-800 rounded-xl p-6 text-white shadow-sm border border-blue-500 dark:border-blue-700">
        <h1 class="text-xl font-bold mb-1">Selamat datang, <?= htmlspecialchars($_SESSION['user']) ?>! 👋</h1>
        <p class="text-blue-100 dark:text-blue-200 text-sm">Ini adalah portal praktikum Anda. Berikut adalah informasi daftar kelas yang sudah tersedia saat ini.</p>
    </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700">
            <h2 class="font-semibold text-slate-800 dark:text-white text-sm">Daftar Kelas Praktikum</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm whitespace-nowrap">
                <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider">
                    <tr><?php foreach(['#','Nama Kelas','Ruangan','Jadwal','Mahasiswa','Aksi'] as $h): ?><th class="px-4 py-3 text-left font-medium"><?=$h?></th><?php endforeach; ?></tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                <?php if($kls_res->num_rows===0): ?>
                    <tr><td colspan="6" class="px-4 py-8 text-center text-slate-400">Belum ada kelas.</td></tr>
                <?php else: $no=1; while($k=$kls_res->fetch_assoc()): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-4 py-3 text-slate-400"><?=$no++?></td>
                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-white"><?=htmlspecialchars($k['nama_kelas'])?></td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><?=htmlspecialchars($k['ruangan'])?></td>
                        <td class="px-4 py-3 text-slate-600 dark:text-slate-300"><?=htmlspecialchars($k['jadwal'])?></td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-xs font-medium"><?=$k['jml']?> mhs</span></td>
                        <td class="px-4 py-3">
                            <a href="kelas_saya.php?id=<?=$k['id_kelas']?>" class="inline-block px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-lg border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">Lihat Detail →</a>
                        </td>
                    </tr>
                <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<script>
    const html=document.documentElement;
    if(localStorage.getItem('dark')==='false')html.classList.remove('dark');
    document.getElementById('toggleDark').addEventListener('click',()=>{html.classList.toggle('dark');localStorage.setItem('dark',html.classList.contains('dark'));});
</script>
</body></html>