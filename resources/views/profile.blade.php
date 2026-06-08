@extends('layouts.app')

@section('title', 'Profil — Racikara')

@section('content')
@php
    $tab = request('tab', 'semua');
    
    $countAll = $recipes->count();
    $countPublished = $recipes->where('status', 'published')->count();
    $countDraft = $recipes->where('status', 'draft')->count();
    $countSaved = $favoriteCount;
@endphp

<!-- PROFILE COVER + CARD -->
<div class="profile-cover"></div>

<div class="profile-card">
    <div class="profile-avatar-wrap">
        <img
            src="{{ asset('assets/img/' . ($user->profile_photo ?? 'default-user.png')) }}"
            alt="{{ $user->full_name }}"
            class="profile-avatar"
            onerror="this.src='{{ asset('assets/img/default-user.png') }}'"
        >
    </div>

    <div class="profile-info-row">
        <div>
            <h1 class="profile-name">{{ $user->full_name }}</h1>
            <p class="profile-bio">
                {{ !empty($user->bio) ? $user->bio : '✨ Chef Racikara — pecinta masakan rumahan yang lezat dan sehat.' }}
            </p>
            <div style="margin-top:12px; display:flex; gap:10px;">
                <a href="{{ route('recipes.create') }}" class="btn btn-primary btn-sm">➕ Tambah Resep</a>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline btn-sm" style="padding: 0 16px;">✏️ Edit Profil</a>
            </div>
        </div>

        <div class="profile-stats">
            <div class="profile-stat">
                <span class="stat-number">{{ $countPublished }}</span>
                <span class="stat-label">Resep</span>
            </div>
            <div class="profile-stat">
                <span class="stat-number">{{ $countSaved }}</span>
                <span class="stat-label">Favorit</span>
            </div>
        </div>
    </div>
</div>

<!-- TABS -->
<div class="profile-tab-nav">
    <a href="{{ route('profile', ['tab' => 'semua']) }}" class="profile-tab {{ $tab === 'semua' ? 'active' : '' }}">
        📋 Semua Resep ({{ $countAll }})
    </a>
    <a href="{{ route('profile', ['tab' => 'published']) }}" class="profile-tab {{ $tab === 'published' ? 'active' : '' }}">
        ✅ Published ({{ $countPublished }})
    </a>
    <a href="{{ route('profile', ['tab' => 'draft']) }}" class="profile-tab {{ $tab === 'draft' ? 'active' : '' }}">
        📝 Draft ({{ $countDraft }})
    </a>
    <a href="{{ route('favorites') }}" class="profile-tab">
        ❤️ Favorit ({{ $countSaved }})
    </a>
</div>

<!-- RECIPE GRID -->
@php
    $filteredRecipes = $recipes;
    if ($tab === 'published' || $tab === 'draft') {
        $filteredRecipes = $recipes->where('status', $tab);
    }
@endphp

@if($filteredRecipes->isEmpty())
<div class="empty-state">
    <div class="empty-icon">🍽️</div>
    <h3>Belum ada resep di sini</h3>
    <p>Mulai bagikan resep pertamamu dan jadilah inspirasi!</p>
    <a href="{{ route('recipes.create') }}" class="btn btn-primary">📤 Upload Resep Pertama</a>
</div>
@else
<div class="recipe-grid" id="profileGrid" style="grid-template-columns: repeat(3, 1fr);">
    @foreach ($filteredRecipes as $r)
    <div class="recipe-card" style="position:relative;">
        <a href="{{ route('recipes.show', $r->id) }}" style="text-decoration:none; display:block;">
            <div class="card-img-wrapper">
                <img
                    class="card-img"
                    src="{{ asset('assets/img/' . $r->image) }}"
                    alt="{{ $r->title }}"
                    onerror="this.src='{{ asset('assets/img/placeholder-food.jpg') }}'"
                >
                <span class="card-badge">{{ $r->category->name ?? 'Kategori' }}</span>
                <!-- Status badge -->
                <span class="status-badge {{ $r->status === 'published' ? 'status-published' : 'status-draft' }}"
                      style="position:absolute; top:12px; right:12px; box-shadow: var(--shadow-sm);">
                    {{ $r->status === 'published' ? '✅ Live' : '📝 Draft' }}
                </span>
            </div>
            <div class="card-content">
                <div class="card-category">{{ $r->category->name ?? 'Kategori' }}</div>
                <div class="card-title">{{ $r->title }}</div>
                <div class="card-meta">
                    <div class="meta-item">⭐ {{ number_format($r->rating ?? 4.8, 1) }}</div>
                    <div class="meta-item">⏱ {{ $r->cooking_time }} mnt</div>
                    @if (!empty($r->calories) && $r->calories > 0)
                    <div class="meta-item">🔥 {{ $r->calories }} kkal</div>
                    @endif
                </div>
                <div class="card-footer" style="border-top: 1px solid var(--gray-100); padding-top: 10px;">
                    <span style="font-size:12px; color:var(--gray-400);">
                        📅 {{ $r->created_at ? $r->created_at->format('d M Y') : 'Beberapa waktu lalu' }}
                    </span>
                </div>
            </div>
        </a>
        <!-- Action buttons overlay -->
        <div style="position:absolute; bottom:16px; right:16px; display:flex; gap:6px;">
            <a href="{{ route('recipes.show', $r->id) }}" class="action-btn edit-btn" title="Lihat">👁️</a>
            <button
                class="action-btn delete-btn"
                onclick="openDeleteModal({{ $r->id }}, '{{ addslashes($r->title) }}')"
                title="Hapus"
            >🗑️</button>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- DELETE MODAL -->
<div class="modal-backdrop" id="deleteModal">
    <div class="modal">
        <div class="modal-icon">🗑️</div>
        <h3>Hapus resep ini?</h3>
        <p id="deleteModalText">Resep ini akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
        <div class="modal-actions">
            <button class="btn btn-outline" onclick="closeModal()">Batal</button>
            <form action="" method="POST" id="deleteForm" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Hapus</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeleteModal(id, title) {
    const form = document.getElementById('deleteForm');
    form.action = `/recipes/${id}`;
    
    document.getElementById('deleteModalText').innerHTML = `Resep "<strong>${title}</strong>" akan dihapus secara permanen dan tidak dapat dikembalikan.`;
    document.getElementById('deleteModal').classList.add('active');
}

function closeModal() {
    document.querySelectorAll('.modal-backdrop').forEach(m => m.classList.remove('active'));
}

document.querySelectorAll('.modal-backdrop').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) closeModal(); });
});

// Animate cards
document.querySelectorAll('.recipe-card').forEach((card, i) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = `opacity 0.4s ease ${i*60}ms, transform 0.4s ease ${i*60}ms`;
    setTimeout(() => { card.style.opacity='1'; card.style.transform='translateY(0)'; }, 100 + i*60);
});
</script>
@endpush
@endsection
