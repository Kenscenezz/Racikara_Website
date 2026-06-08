@extends('layouts.app')

@section('title', 'Jelajahi Resep - Racikara')

@section('content')
<!-- EXPLORE HERO & SEARCH -->
<section class="explore-hero">
    <h1 class="explore-hero-title">🔍 Jelajahi Resep</h1>
    <p style="color: var(--green-700); font-size:15px; margin-bottom: 18px;">Temukan inspirasi masak dari ribuan resep pilihan komunitas Racikara</p>
    <form action="{{ route('explore') }}" method="GET" class="explore-search">
        <div class="search-container" style="flex:1;">
            <span class="search-icon">🔍</span>
            <input
                type="text"
                name="q"
                placeholder="Cari resep, bahan, atau kategori..."
                value="{{ request('q') }}"
                id="searchInput"
            >
        </div>
        <button type="submit" class="search-btn btn-primary btn" id="searchBtn">Cari</button>
    </form>
</section>

<!-- FILTER TABS -->
<div class="filter-tabs" id="filterTabs">
    <a href="{{ route('explore') }}" class="filter-tab {{ !request('category') ? 'active' : '' }}">✨ Semua</a>
    @foreach($categories as $category)
    <a href="{{ route('explore', ['category' => $category->id, 'q' => request('q')]) }}" class="filter-tab {{ request('category') == $category->id ? 'active' : '' }}">
        {{ $category->name }}
    </a>
    @endforeach
</div>

<!-- ALL RECIPES GRID -->
<section>
    <div class="section-title">
        <h2>
            @if(request('q'))
                🔍 Hasil pencarian "{{ request('q') }}"
            @elseif(request('category'))
                📂 Resep Kategori
            @else
                📋 Semua Resep
            @endif
        </h2>
        <span style="font-size:13px; color:var(--gray-400);">
            {{ $recipes->count() }} resep ditemukan
        </span>
    </div>

    <div class="recipe-list-grid" id="recipeListGrid">
        @if($recipes->isEmpty())
        <div class="empty-state" style="grid-column: 1/-1;">
            <div class="empty-icon">🍽️</div>
            <h3>Resep tidak ditemukan</h3>
            <p>Coba kata kunci atau filter yang berbeda</p>
            <a href="{{ route('explore') }}" class="btn btn-primary">Reset Pencarian</a>
        </div>
        @else
            @foreach($recipes as $recipe)
            <a href="{{ route('recipes.show', $recipe->id) }}" class="recipe-card" style="text-decoration:none;">
                <div class="card-img-wrapper">
                    <img
                        class="card-img"
                        src="{{ asset('assets/img/' . $recipe->image) }}"
                        alt="{{ $recipe->title }}"
                        onerror="this.src='{{ asset('assets/img/placeholder-food.jpg') }}'"
                    >
                    <span class="card-badge">{{ $recipe->category->name }}</span>
                </div>
                <div class="card-content">
                    <div class="card-category">{{ $recipe->category->name }}</div>
                    <div class="card-title">{{ $recipe->title }}</div>
                    <div class="card-meta">
                        <div class="meta-item">⏱ {{ $recipe->cooking_time }} mnt</div>
                        @if($recipe->calories)
                        <div class="meta-item">🔥 {{ $recipe->calories }} kkal</div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="card-author">
                            <img src="{{ asset('assets/img/' . $recipe->user->profile_photo) }}" alt="" class="author-avatar" onerror="this.src='{{ asset('assets/img/default-user.png') }}'">
                            {{ $recipe->user->full_name }}
                        </div>
                        <div class="card-rating">⭐ {{ number_format($recipe->rating ?? 4.8, 1) }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        @endif
    </div>
</section>
@endsection
