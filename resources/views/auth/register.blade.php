<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daftar di Racikara — mulai berbagi dan menyimpan resep lezat favoritmu.">
    <title>Daftar Akun - Racikara</title>
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

        <img src="{{ asset('assets/img/pasta.jpg') }}" alt="Pasta Lezat" class="food-img">

        <h2 class="auth-tagline">Temukan. Simpan. Bagikan.<br>Resep yang Menginspirasi.</h2>
        <p class="auth-subtagline">Racikara adalah teman terbaikmu untuk menemukan resep lezat, menyimpan favorit, dan berbagi kreativitas dengan komunitas pecinta masakan.</p>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-panel-right">
        <h1 class="auth-title">Daftar Akun Baru</h1>
        <p class="auth-subtitle">Buat akun untuk mulai berbagi resep favorit</p>

        <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
            @csrf

            <div class="form-group">
                <div class="form-control-icon" style="position: relative;">
                    <span class="icon">👤</span>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Nama Lengkap"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        id="fullNameInput"
                    >
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" style="color: red; font-size: 0.8rem; margin-top: 4px;" />
            </div>

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
                        id="emailInput"
                    >
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: red; font-size: 0.8rem; margin-top: 4px;" />
            </div>

            <div class="form-grid-2">
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

                <div class="form-group">
                    <div class="password-wrapper form-control-icon" style="position: relative;">
                        <span class="icon">🔒</span>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Konfirmasi Sandi"
                            required
                            id="confirmPasswordInput"
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('confirmPasswordInput', this)" aria-label="Toggle confirm password" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--gray-500);">
                            👁️
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" style="color: red; font-size: 0.8rem; margin-top: 4px;" />
                </div>
            </div>

            <!-- Admin Code (optional) -->
            <div class="form-group">
                <div class="form-control-icon" style="position: relative;">
                    <span class="icon">⚙️</span>
                    <input
                        type="password"
                        name="admin_code"
                        class="form-control"
                        placeholder="Kode Admin (opsional — kosongkan jika bukan admin)"
                        id="adminCodeInput"
                    >
                </div>
                <x-input-error :messages="$errors->get('admin_code')" class="mt-2" style="color: red; font-size: 0.8rem; margin-top: 4px;" />
            </div>

            <label class="terms-check" style="display: flex; align-items: flex-start; gap: 8px; font-size: 13px; color: var(--gray-600); margin-bottom: 20px; cursor: pointer;">
                <input type="checkbox" required id="termsCheck" style="margin-top: 3px;">
                <span>Saya menyetujui <a href="#" style="color:var(--green-600);font-weight:700;">Syarat &amp; Ketentuan</a> dan <a href="#" style="color:var(--green-600);font-weight:700;">Kebijakan Privasi</a> Racikara</span>
            </label>

            <button type="submit" class="btn btn-primary btn-lg btn-full" id="registerBtn">
                🎉 Daftar Sekarang
            </button>
        </form>

        <div class="auth-footer" style="margin-top: 24px;">
            Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--green-600); font-weight: 600;">Masuk di sini</a>
        </div>
    </div>
</div>

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

document.getElementById('registerForm').addEventListener('submit', function(e) {
    const pass = document.getElementById('passwordInput').value;
    const confirm = document.getElementById('confirmPasswordInput').value;
    if (pass !== confirm) {
        e.preventDefault();
        alert('Kata sandi tidak cocok! Pastikan kedua kata sandi sama.');
        document.getElementById('confirmPasswordInput').focus();
    }
});

// Animation on button click
document.getElementById('registerBtn').addEventListener('mousedown', function() {
    this.style.transform = 'scale(0.97)';
});
document.getElementById('registerBtn').addEventListener('mouseup', function() {
    this.style.transform = '';
});
</script>

</body>
</html>
