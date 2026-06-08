<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke Racikara — temukan, simpan, dan bagikan resep lezat favoritmu.">
    <title>Masuk - Racikara</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body class="auth-body">

<div class="auth-wrapper">
    <!-- LEFT PANEL -->
    <div class="auth-panel-left">
        <div class="auth-logo-wrap">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Racikara" class="auth-logo-img">
            <span class="auth-logo-name">Racikara</span>
        </div>

        <img src="{{ asset('assets/img/ayam-panggang-madu.jpg') }}" alt="Ayam Panggang Madu" class="food-img">

        <h2 class="auth-tagline">Temukan resep terbaik<br>untuk setiap momen istimewa.</h2>
        <p class="auth-subtagline">Racikara hadir untuk membantu Anda menemukan, menyimpan, dan berbagi resep dengan mudah.</p>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-panel-right">
        <h1 class="auth-title">Selamat Datang Kembali!</h1>
        <p class="auth-subtitle">Masuk untuk melanjutkan ke Racikara</p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="auth-form" id="loginForm">
            @csrf

            <div class="form-group">
                <div class="form-control-icon" style="position: relative;">
                    <span class="icon">✉️</span>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Alamat Email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        id="emailInput"
                        oninput="checkAdminEmail(this.value)"
                    >
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: red; font-size: 0.8rem; margin-top: 4px;" />
            </div>

            <div class="form-group">
                <div class="password-wrapper form-control-icon" style="position: relative;">
                    <span class="icon">🔒</span>
                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Kata Sandi"
                        required
                        id="passwordInput"
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('passwordInput', this)" aria-label="Toggle password" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-500);">
                        👁️
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: red; font-size: 0.8rem; margin-top: 4px;" />
            </div>

            <!-- Admin Code Field (hidden by default) -->
            <div class="form-group admin-code-section" id="adminCodeSection" style="display: none;">
                <div class="form-control-icon" style="position: relative; width: 100%;">
                    <span class="icon">⚙️</span>
                    <input
                        type="password"
                        name="admin_code"
                        class="form-control"
                        placeholder="Kode Admin"
                        id="adminCodeInput"
                    >
                </div>
                <p style="font-size:12px; color: var(--gray-400); margin-top: 4px;">
                    Masukkan kode admin untuk login sebagai administrator.
                </p>
                <x-input-error :messages="$errors->get('admin_code')" class="mt-2" style="color: red; font-size: 0.8rem; margin-top: 4px;" />
            </div>

            <div class="block mt-4" style="margin-bottom: 15px;">
                <label for="remember_me" class="inline-flex items-center" style="display: flex; align-items: center; gap: 8px;">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span class="text-sm text-gray-600">Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-full" id="loginBtn">
                Masuk
            </button>
        </form>

        <div class="auth-footer" style="margin-top: 24px;">
            Belum punya akun? <a href="{{ route('register') }}" style="color: var(--green-600); font-weight: 600;">Daftar sekarang</a>
        </div>
    </div>
</div>

<style>
.admin-code-section.visible {
    display: flex !important;
    animation: slideDown 0.3s ease;
}
</style>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
    } else {
        input.type = 'password';
        btn.textContent = '👁️';
    }
}

function checkAdminEmail(email) {
    const adminSection = document.getElementById('adminCodeSection');
    const adminInput = document.getElementById('adminCodeInput');
    if (email.toLowerCase().includes('admin')) {
        adminSection.classList.add('visible');
        // Not making it strictly required to prevent HTML5 validation error if not needed, 
        // Backend handles validation anyway if the user is an admin.
    } else {
        adminSection.classList.remove('visible');
        adminInput.value = '';
    }
}

// Initial check on load
document.addEventListener('DOMContentLoaded', function() {
    checkAdminEmail(document.getElementById('emailInput').value);
});

document.getElementById('loginBtn').addEventListener('click', function(e) {
    this.style.transform = 'scale(0.97)';
    setTimeout(() => this.style.transform = '', 150);
});
</script>

</body>
</html>
