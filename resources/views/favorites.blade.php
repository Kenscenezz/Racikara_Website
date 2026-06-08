@extends('layouts.app')

@section('title', 'Resep Favorit - Racikara')

@section('content')
<div class="content-header">
    <h1 style="font-size: 2rem; color: #1a1a1a;">Resep Favorit</h1>
</div>

<section class="section">
    @if($recipes->isEmpty())
        <div style="text-align: center; padding: 50px 20px;">
            <div style="font-size: 4rem; margin-bottom: 20px;">❤️</div>
            <h3 style="color: #1a1a1a; margin-bottom: 10px;">Belum ada resep favorit</h3>
            <p style="color: #666; margin-bottom: 20px;">Jelajahi resep dan simpan yang kamu suka untuk dilihat lagi nanti.</p>
            <a href="{{ route('explore') }}" class="btn btn-primary" style="display: inline-block;">Jelajahi Resep</a>
        </div>
    @else
        <div class="recipe-grid">
            @foreach($recipes as $recipe)
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
                        <div class="card-rating">⭐ {{ number_format($recipe->rating ?? 4.8, 1) }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</section>
@endsection
