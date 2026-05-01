function handleLogin() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const errorMsg = document.getElementById('errorMsg');
    const btnLogin = document.getElementById('btnLogin');

    errorMsg.classList.add('hidden');
    errorMsg.textContent = '';
    document.getElementById('email').classList.remove('border-red-400', 'border-2');
    document.getElementById('password').classList.remove('border-red-400', 'border-2');

    if (!email) {
        errorMsg.textContent = '⚠️ Email tidak boleh kosong.';
        errorMsg.classList.remove('hidden');
        document.getElementById('email').classList.add('border-red-400', 'border-2');
        return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errorMsg.textContent = '⚠️ Format email tidak valid. Contoh: user@email.com';
        errorMsg.classList.remove('hidden');
        document.getElementById('email').classList.add('border-red-400', 'border-2');
        return;
    }

    if (!password) {
        errorMsg.textContent = '⚠️ Password tidak boleh kosong.';
        errorMsg.classList.remove('hidden');
        document.getElementById('password').classList.add('border-red-400', 'border-2');
        return;
    }

    if (password.length < 6) {
        errorMsg.textContent = '⚠️ Password minimal 6 karakter.';
        errorMsg.classList.remove('hidden');
        document.getElementById('password').classList.add('border-red-400', 'border-2');
        return;
    }

    btnLogin.innerText = 'Processing...';
    btnLogin.disabled = true;

    setTimeout(() => {
        alert("Berhasil Login. Selamat datang!");
        window.location.href = 'homepage.html';
    }, 1000);
}

var isDarkMode = false;
function toggleDarkMode() {
    isDarkMode = !isDarkMode;
    var tombol = document.getElementById('btnToggle');
    if (isDarkMode) {
        tombol.innerHTML = 'Light Mode';
    } else {
        tombol.innerHTML = 'Dark Mode';
    }
    document.getElementById('black').style.backgroundColor = isDarkMode ? '#16213e' : '';
    document.getElementById('dark').style.color = isDarkMode ? '#e0e0e0' : '';
}