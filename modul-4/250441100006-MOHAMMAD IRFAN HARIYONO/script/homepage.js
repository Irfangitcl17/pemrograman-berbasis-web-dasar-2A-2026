document.addEventListener('DOMContentLoaded', () => {

    const heroButtons = document.querySelectorAll('#hero-buttons button');

    heroButtons.forEach(el => {
        el.style.transition = "all 0.3s ease";
        el.addEventListener('mouseenter', () => {
            el.style.transform = "translateY(-5px) scale(1.05)";
            el.style.filter = "brightness(1.1)";
        });
        el.addEventListener('mouseleave', () => {
            el.style.transform = "translateY(0) scale(1)";
            el.style.filter = "brightness(1)";
        });
        el.addEventListener('mousedown', () => el.style.transform = "scale(0.95)");
        el.addEventListener('mouseup', () => el.style.transform = "translateY(-5px) scale(1.05)");
    });

    const contactBtn = document.querySelector('header .contact-btn');
    if (contactBtn) {
        contactBtn.addEventListener('click', () => {
            alert("Terima kasih! Tim kami akan segera menghubungi Anda.");
        });
    }
});

const toggleBtn = document.getElementById('themeToggle');
const thumbIcon = document.getElementById('thumbIcon');
const themeLabel = document.getElementById('themeLabel');
const html = document.documentElement;

const savedTheme = localStorage.getItem('theme') || 'light';
applyTheme(savedTheme);

toggleBtn.addEventListener('click', () => {
    const isDark = html.classList.contains('dark');
    const next = isDark ? 'light' : 'dark';
    applyTheme(next);
    localStorage.setItem('theme', next);
});

function applyTheme(theme) {
    if (theme === 'dark') {
        html.classList.add('dark');
        thumbIcon.textContent = '🌙';
        themeLabel.textContent = 'DARK';
        themeLabel.style.color = '#ffffff';
        toggleBtn.style.background = '#3852B4';
    } else {
        html.classList.remove('dark');
        thumbIcon.textContent = '☀️';
        themeLabel.textContent = 'LIGHT';
        themeLabel.style.color = '#323232';
        toggleBtn.style.background = '#e2e8f0';
    }
}

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