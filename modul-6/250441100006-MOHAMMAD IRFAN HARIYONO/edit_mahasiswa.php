<?php
include 'auth.php';
include 'koneksi.php';

if ($_SESSION['role'] !== 'admin') { header("Location: dashboard.php"); exit; }

$id_mhs = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare("SELECT m.*,u.username,u.id as id_user FROM mahasiswa m JOIN users u ON m.user_id=u.id WHERE m.id_mhs=?");
$stmt->bind_param("i", $id_mhs); $stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) { header("Location: dashboard.php"); exit; }
$mhs     = $res->fetch_assoc();
$kelas   = $conn->query("SELECT * FROM kelas");
$success = "";
$errors  = [];

if (isset($_POST['simpan'])) {
    $nama  = trim($_POST['nama']     ?? '');
    $nim   = trim($_POST['nim']      ?? '');
    $kls   = trim($_POST['kelas']    ?? '');
    $user  = trim($_POST['username'] ?? '');
    $pass  = $_POST['password']         ?? '';
    $conf  = $_POST['confirm_password'] ?? '';

    if (empty($nama))             $errors['nama']     = "Nama wajib diisi.";
    if (empty($nim))              $errors['nim']      = "NIM wajib diisi.";
    elseif (!ctype_digit($nim))   $errors['nim']      = "NIM hanya angka.";
    if (empty($kls))              $errors['kelas']    = "Kelas wajib dipilih.";
    if (empty($user))             $errors['username'] = "Username wajib diisi.";
    elseif (strlen($user) < 4)   $errors['username'] = "Username minimal 4 karakter.";
    if (!empty($pass)||!empty($conf)) {
        if (strlen($pass) < 6)   $errors['password'] = "Password minimal 6 karakter.";
        if ($pass !== $conf)      $errors['confirm']  = "Konfirmasi tidak cocok.";
    }

    if (empty($errors['username'])) {
        $cek = $conn->prepare("SELECT id FROM users WHERE username=? AND id!=?");
        $cek->bind_param("si", $user, $mhs['id_user']); $cek->execute(); $cek->store_result();
        if ($cek->num_rows > 0) $errors['username'] = "Username sudah digunakan.";
    }

    if (empty($errors)) {
        if (!empty($pass)) {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $s1 = $conn->prepare("UPDATE users SET username=?,password=? WHERE id=?");
            $s1->bind_param("ssi", $user, $hash, $mhs['id_user']);
        } else {
            $s1 = $conn->prepare("UPDATE users SET username=? WHERE id=?");
            $s1->bind_param("si", $user, $mhs['id_user']);
        }
        $s1->execute();
        $s2 = $conn->prepare("UPDATE mahasiswa SET nama=?,nim=?,id_kelas=? WHERE id_mhs=?");
        $s2->bind_param("ssii", $nama, $nim, $kls, $id_mhs); $s2->execute();
        $success = "Data <strong>".htmlspecialchars($nama)."</strong> berhasil diperbarui!";
        $mhs['nama']=$nama; $mhs['nim']=$nim; $mhs['id_kelas']=$kls; $mhs['username']=$user;
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa — Pendataan Praktikum</title>
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

<main class="max-w-lg mx-auto px-6 py-8">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-7">
        <h1 class="text-lg font-semibold text-slate-800 dark:text-white mb-1">✏️ Edit Mahasiswa</h1>
        <p class="text-xs text-slate-400 mb-6">ID: <?=$id_mhs?> · <?=htmlspecialchars($mhs['nama'])?></p>

        <?php if ($success): ?>
        <div class="mb-5 px-4 py-3 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 text-sm"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" class="space-y-4" novalidate>
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Data Mahasiswa</p>

            <?php foreach([['nama','Nama Lengkap','text'],['nim','NIM','text']] as [$n,$l,$t]):
                $val = htmlspecialchars($_POST[$n] ?? $mhs[$n]); $err = $errors[$n] ?? ''; ?>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"><?=$l?> <span class="text-red-500">*</span></label>
                <input type="<?=$t?>" name="<?=$n?>" value="<?=$val?>" required
                    class="w-full px-3 py-2.5 rounded-lg border <?=$err?'border-red-400 dark:border-red-600':'border-slate-300 dark:border-slate-600'?> bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                <?php if($err): ?><p class="mt-1 text-xs text-red-500"><?=$err?></p><?php endif; ?>
            </div>
            <?php endforeach; ?>

            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Kelas <span class="text-red-500">*</span></label>
                <select name="kelas" class="w-full px-3 py-2.5 rounded-lg border <?=isset($errors['kelas'])?'border-red-400 dark:border-red-600':'border-slate-300 dark:border-slate-600'?> bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">— Pilih Kelas —</option>
                    <?php $kelas->data_seek(0); while($k=$kelas->fetch_assoc()): $sel=(($_POST['kelas']??$mhs['id_kelas'])==$k['id_kelas'])?'selected':''; ?>
                    <option value="<?=$k['id_kelas']?>" <?=$sel?>><?=htmlspecialchars($k['nama_kelas'])?></option>
                    <?php endwhile; ?>
                </select>
                <?php if(isset($errors['kelas'])): ?><p class="mt-1 text-xs text-red-500"><?=$errors['kelas']?></p><?php endif; ?>
            </div>

            <div class="border-t border-slate-200 dark:border-slate-700 pt-4 space-y-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Akun Login</p>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="<?=htmlspecialchars($_POST['username']??$mhs['username'])?>"
                        class="w-full px-3 py-2.5 rounded-lg border <?=isset($errors['username'])?'border-red-400 dark:border-red-600':'border-slate-300 dark:border-slate-600'?> bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <?php if(isset($errors['username'])): ?><p class="mt-1 text-xs text-red-500"><?=$errors['username']?></p><?php endif; ?>
                </div>
                <?php foreach([['password','Password Baru'],['confirm_password','Konfirmasi Password Baru']] as [$n,$l]):
                    $ekey=$n==='confirm_password'?'confirm':$n; ?>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"><?=$l?> <span class="text-xs font-normal text-slate-400">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="<?=$n?>"
                        class="w-full px-3 py-2.5 rounded-lg border <?=isset($errors[$ekey])?'border-red-400 dark:border-red-600':'border-slate-300 dark:border-slate-600'?> bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <?php if(isset($errors[$ekey])): ?><p class="mt-1 text-xs text-red-500"><?=$errors[$ekey]?></p><?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" name="simpan" class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors">Simpan Perubahan</button>
        </form>
    </div>
</main>
<script>
    const html=document.documentElement;
    if(localStorage.getItem('dark')==='false')html.classList.remove('dark');
    document.getElementById('toggleDark').addEventListener('click',()=>{html.classList.toggle('dark');localStorage.setItem('dark',html.classList.contains('dark'));});
</script>
</body></html>