<?php
session_start();
require_once 'koneksi.php';

if (isset($_SESSION['login'])) { header("Location: dashboard.php"); exit; }

$error = "";
if (isset($_POST['login'])) {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if (empty($u) || empty($p)) {
        $error = "Username dan password wajib diisi.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $u);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();
        if ($data && password_verify($p, $data['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['user']  = $data['username'];
            $_SESSION['role']  = $data['role'];
            $_SESSION['uid']   = $data['id'];
            header("Location: dashboard.php"); exit;
        }
        $error = "Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Pendataan Praktikum</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style> body { font-family: 'DM Sans', sans-serif; } </style>
</head>
<body class="min-h-screen bg-slate-100 dark:bg-slate-900 flex items-center justify-center transition-colors">
    <div class="w-full max-w-sm">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 border border-slate-200 dark:border-slate-700">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-xl font-semibold text-slate-800 dark:text-white">Portal Praktikum</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Masuk ke akun Anda</p>
                </div>
                <button id="toggleDark" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors" title="Toggle dark mode">
                    <svg id="iconSun" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg id="iconMoon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>
            </div>

            <?php if ($error): ?>
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required
                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2.5 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <button type="submit" name="login"
                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Masuk
                </button>
            </form>
        </div>
    </div>
    <script>
        const html = document.documentElement;
        const dark = localStorage.getItem('dark') !== 'false';
        if (!dark) html.classList.remove('dark');
        document.getElementById('toggleDark').addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('dark', html.classList.contains('dark'));
        });
    </script>
</body>
</html>