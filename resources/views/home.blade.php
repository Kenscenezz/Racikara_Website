@extends('layouts.app')

@section('title', 'Beranda - Racikara')

@section('content')
<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-content">
        <p class="hero-greeting">👋 Selamat datang, {{ auth()->check() ? explode(' ', auth()->user()->full_name)[0] : 'Sobat Masak' }}!</p>
        <h1 class="hero-title">
            Temukan<br>
            <span>Resep Favoritmu</span>
        </h1>
        <p class="hero-desc">Masak sehat, lezat, dan mudah setiap hari bersama komunitas Racikara. Lebih dari 500+ resep pilihan menanti kamu!</p>

        <div class="hero-cta">
            <a href="{{ route('explore') }}" class="btn btn-primary btn-lg" id="exploreBtn">
                🔍 Jelajahi Resep
            </a>
            <a href="{{ route('recipes.create') }}" class="btn btn-outline btn-lg" id="uploadBtn">
                ➕ Bagikan Resep
            </a>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <span class="hero-stat-number">500+</span>
                <span class="hero-stat-label">Resep</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-number">1.2K</span>
                <span class="hero-stat-label">Chef Aktif</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-number">4.9★</span>
                <span class="hero-stat-label">Rating</span>
            </div>
        </div>
    </div>

    <div class="hero-images">
        <img src="{{ asset('assets/img/ayam-panggang-madu.jpg') }}" alt="Ayam Panggang Madu" class="hero-img-main">
        <div class="hero-img-side">
            <img src="{{ asset('assets/img/nasi-goreng-seafood.jpg') }}" alt="Nasi Goreng" class="hero-img-sm">
            <img src="{{ asset('assets/img/pasta.jpg') }}" alt="Pasta" class="hero-img-sm">
        </div>
    </div>
</section>

<!-- CATEGORY PILLS -->
<section class="category-section">
    <div class="section-title">
        <h2>🗂️ Kategori Resep</h2>
    </div>
    <div class="category-pills">
        <a href="{{ route('explore') }}" class="category-pill active">
            <span class="pill-icon">🍽️</span> Semua
        </a>
        @foreach($categories as $category)
        <a href="{{ route('explore', ['category' => $category->id]) }}" class="category-pill">
            <span class="pill-icon">{{ $category->icon }}</span> {{ $category->name }}
        </a>
        @endforeach
    </div>
</section>

<!-- POPULAR RECIPES -->
<section>
    <div class="section-title">
        <h2>🔥 Resep Populer</h2>
        <a href="{{ route('explore') }}">Lihat semua →</a>
    </div>

    <div class="recipe-grid">
        @foreach($popularRecipes as $recipe)
        <a href="{{ route('recipes.show', $recipe->id) }}" class="recipe-card" style="text-decoration:none;">
            <div class="card-img-wrapper">
                <img src="{{ asset('assets/img/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="card-img" onerror="this.src='{{ asset('assets/img/placeholder-food.jpg') }}'">
                <span class="card-badge">{{ $recipe->category->name }}</span>
            </div>
            <div class="card-content">
                <div class="card-category">{{ $recipe->category->name }}</div>
                <div class="card-title">{{ $recipe->title }}</div>
                <div class="card-meta">
                    <div class="meta-item">⏱ {{ $recipe->cooking_time }} mnt</div>
                    <div class="meta-item">👥 {{ $recipe->portion }} porsi</div>
                    @if($recipe->calories)
                    <div class="meta-item">🔥 {{ $recipe->calories }} kkal</div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="card-author">
                        <img src="{{ asset('assets/img/' . $recipe->user->profile_photo) }}" alt="" class="author-avatar" onerror="this.src='{{ asset('assets/img/default-user.png') }}'">
                        {{ $recipe->user->full_name }}
                    </div>
                    <div class="card-rating">
                        ⭐ {{ number_format($recipe->rating ?? 4.8, 1) }}
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>

<!-- LATEST RECIPES -->
<section style="margin-top: 40px;">
    <div class="section-title">
        <h2>🕒 Resep Terbaru</h2>
        <a href="{{ route('explore') }}">Lihat semua →</a>
    </div>

    <div class="recipe-grid">
        @foreach($recentRecipes as $recipe)
        <a href="{{ route('recipes.show', $recipe->id) }}" class="recipe-card" style="text-decoration:none;">
            <div class="card-img-wrapper">
                <img src="{{ asset('assets/img/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="card-img" onerror="this.src='{{ asset('assets/img/placeholder-food.jpg') }}'">
                <span class="card-badge">{{ $recipe->category->name }}</span>
            </div>
            <div class="card-content">
                <div class="card-category">{{ $recipe->category->name }}</div>
                <div class="card-title">{{ $recipe->title }}</div>
                <div class="card-meta">
                    <div class="meta-item">⏱ {{ $recipe->cooking_time }} mnt</div>
                    <div class="meta-item">👥 {{ $recipe->portion }} porsi</div>
                </div>
                <div class="card-footer">
                    <div class="card-author">
                        <img src="{{ asset('assets/img/' . $recipe->user->profile_photo) }}" alt="" class="author-avatar" onerror="this.src='{{ asset('assets/img/default-user.png') }}'">
                        {{ $recipe->user->full_name }}
                    </div>
                    <div class="card-rating">
                        ⭐ {{ number_format($recipe->rating ?? 4.8, 1) }}
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>

<!-- CTA BANNER -->
<div class="cta-banner" style="margin-top: 36px;">
    <div class="cta-content">
        <h3>Punya Resep Andalan?</h3>
        <p>Bagikan resepmu dan jadilah inspirasi bagi ribuan pembaca Racikara!</p>
    </div>
    <a href="{{ route('recipes.create') }}" class="btn-cta">
        📤 Upload Resepmu Sekarang
    </a>
</div>
@endsection
