<aside class="sidebar" id="sidebar">
    <a href="{{ route('home') }}" class="sidebar-brand">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Racikara">
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-name">Racikara</span>
            <span class="sidebar-brand-tagline">Resep andalan setiap hari</span>
        </div>
    </a>

    <nav class="sidebar-nav">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span>
            <span class="nav-label">Beranda</span>
        </a>
        <a href="{{ route('explore') }}" class="{{ request()->routeIs('explore') ? 'active' : '' }}">
            <span class="nav-icon">🔍</span>
            <span class="nav-label">Jelajahi</span>
        </a>
        <a href="{{ route('favorites') }}" class="{{ request()->routeIs('favorites') ? 'active' : '' }}">
            <span class="nav-icon">❤️</span>
            <span class="nav-label">Favorit</span>
        </a>
        <a href="{{ route('recipes.mine') }}" class="{{ request()->routeIs('recipes.mine') ? 'active' : '' }}">
            <span class="nav-icon">📋</span>
            <span class="nav-label">Resep Saya</span>
        </a>
        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
            <span class="nav-icon">👤</span>
            <span class="nav-label">Profil</span>
        </a>

        <div class="sidebar-divider"></div>

        <a href="{{ route('recipes.create') }}" class="{{ request()->routeIs('recipes.create') ? 'active' : '' }}" style="{{ request()->routeIs('recipes.create') ? '' : 'background: linear-gradient(135deg, #f3fbf5, #e0f7ea); border: 1.5px dashed #2eac5a; color: #1a7a3c;' }}">
            <span class="nav-icon">➕</span>
            <span class="nav-label">Upload Resep</span>
        </a>

        @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
            <span class="nav-icon">⚙️</span>
            <span class="nav-label">Admin Panel</span>
            <span class="nav-badge">Admin</span>
        </a>
        @endif
    </nav>

    <div class="sidebar-bottom">
        @auth
        <div class="sidebar-user">
            <img src="{{ asset('assets/img/' . auth()->user()->profile_photo) }}" alt="Avatar" class="sidebar-user-avatar" onerror="this.src='{{ asset('assets/img/default-user.png') }}'">
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->full_name }}</div>
                <div class="sidebar-user-role">{{ auth()->user()->role === 'admin' ? '⚙️ Admin' : '👨‍🍳 Member' }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
            @csrf
        </form>
        <a href="#" class="sidebar-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span>🚪</span>
            <span>Keluar</span>
        </a>
        @else
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="{{ route('login') }}" class="btn btn-outline" style="text-align: center; justify-content: center; width: 100%;">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary" style="text-align: center; justify-content: center; width: 100%;">Daftar</a>
        </div>
        @endauth
    </div>
</aside>
