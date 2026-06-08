@extends('layouts.app')

@section('title', 'Resep Saya - Racikara')

@section('content')
<div class="content-header" style="display: flex; justify-content: space-between; align-items: center;">
    <h1 style="font-size: 2rem; color: #1a1a1a;">Resep Saya</h1>
    <a href="{{ route('recipes.create') }}" class="btn btn-primary">➕ Buat Resep Baru</a>
</div>

@if(session('success'))
    <div style="background: #e0f7ea; color: #2eac5a; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 500;">
        ✓ {{ session('success') }}
    </div>
@endif

<section class="section">
    @if($recipes->isEmpty())
        <div style="text-align: center; padding: 50px 20px;">
            <div style="font-size: 4rem; margin-bottom: 20px;">📝</div>
            <h3 style="color: #1a1a1a; margin-bottom: 10px;">Belum ada resep yang dibuat</h3>
            <p style="color: #666; margin-bottom: 20px;">Bagikan resep andalanmu dengan komunitas Racikara!</p>
            <a href="{{ route('recipes.create') }}" class="btn btn-primary" style="display: inline-block;">Upload Resep Pertamamu</a>
        </div>
    @else
        <div class="recipe-grid">
            @foreach($recipes as $recipe)
            <div class="recipe-card" style="position: relative;">
                <!-- Hapus Button -->
                <div style="position: absolute; top: 15px; right: 15px; z-index: 10; display: flex; gap: 8px;">
                    <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus resep ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: white; border: none; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 5px rgba(0,0,0,0.1); color: #e74c3c;" title="Hapus Resep">
                            🗑️
                        </button>
                    </form>
                </div>
                
                <a href="{{ route('recipes.show', $recipe->id) }}" style="text-decoration:none;">
                    <div class="card-img-wrapper">
                        <img src="{{ asset('assets/img/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="card-img" onerror="this.src='{{ asset('assets/img/placeholder-food.jpg') }}'">
                        <span class="card-badge">{{ $recipe->category->name }}</span>
                    </div>
                    <div class="card-content">
                        <div class="card-category">{{ $recipe->category->name }}</div>
                        <div class="card-title">{{ $recipe->title }}</div>
                        <div class="card-meta">
                            <div class="meta-item">👁️ {{ $recipe->views }} Dilihat</div>
                            <span class="badge {{ $recipe->status == 'published' ? 'badge-green' : 'badge-gray' }}" style="font-size: 0.75rem; margin: 0;">
                                {{ $recipe->status == 'published' ? '✅ Publik' : '📝 Draft' }}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
