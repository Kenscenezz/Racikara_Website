@php
    $hideSidebar = true;
@endphp
@extends('layouts.app')

@section('title', $recipe->title . ' - Racikara')

@section('content')

@php
    $topbarBack = route('explore');
    $topbarBackLabel = 'Kembali ke Resep';
    $topbarTitle = '';
    
    $isOwner = Auth::check() && Auth::id() == $recipe->user_id;
    $isAdmin = Auth::check() && Auth::user()->role === 'admin';
    
    $editBtn = '';
    $deleteBtn = '';
    if ($isOwner) {
        $editBtn = '<a href="'.route('recipes.edit', $recipe->id).'" class="btn btn-outline btn-sm">✏️ Edit</a> ';
    }
    if ($isOwner || $isAdmin) {
        $deleteBtn = '<button type="button" onclick="openDeleteModal()" class="btn btn-danger btn-sm">🗑️ Hapus</button>';
    }
    
    $topbarActions = '<button class="btn btn-ghost" onclick="shareRecipe()" id="shareBtn" title="Bagikan">📤 Bagikan</button> ' . $editBtn . $deleteBtn;
@endphp
@include('layouts.topbar', ['topbarBack' => $topbarBack, 'topbarBackLabel' => $topbarBackLabel, 'topbarTitle' => $topbarTitle, 'topbarActions' => $topbarActions])

<main style="max-width: 1160px; margin: 0 auto; padding: 28px 24px;">

    <div class="detail-page" style="border-radius: var(--border-radius-lg); overflow: hidden; box-shadow: var(--shadow-lg);">

        <!-- HERO IMAGE -->
        <div style="position:relative; overflow:hidden;">
            <img
                src="{{ asset('assets/img/' . $recipe->image) }}"
                alt="{{ $recipe->title }}"
                class="detail-hero-img"
                onerror="this.src='{{ asset('assets/img/placeholder-food.jpg') }}'"
            >
            <div style="position:absolute; bottom:0; left:0; right:0; background:linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 100%); padding:24px 40px;">
                <span class="badge badge-green" style="margin-bottom:8px;">{{ $recipe->category->name ?? 'Makanan' }}</span>
                <h1 style="font-size:38px; font-weight:800; color:white; text-shadow:0 2px 8px rgba(0,0,0,0.3); line-height:1.2;">
                    {{ $recipe->title }}
                </h1>
            </div>
        </div>

        <!-- BODY -->
        <div class="detail-body">

            <!-- MAIN CONTENT -->
            <div class="detail-main">

                <!-- Meta bar -->
                <div class="detail-meta" style="margin-bottom:18px;">
                    <span class="detail-meta-rating" style="display:flex;align-items:center;gap:4px;font-weight:700;color:var(--gray-700);">
                        ⭐ {{ number_format($recipe->rating ?? 4.8, 1) }}
                        <span style="font-weight:400; color:var(--gray-400); font-size:13px;">({{ $recipe->review_count ?? 0 }} ulasan)</span>
                    </span>
                    <span class="meta-dot"></span>
                    <span style="display:flex;align-items:center;gap:6px; color:var(--gray-500);">
                        👤 {{ $recipe->user->full_name ?? 'Chef' }}
                    </span>
                    <span class="meta-dot"></span>
                    <span class="meta-category">{{ $recipe->category->name ?? 'Kategori' }}</span>
                </div>

                <!-- Description -->
                <p class="detail-desc">{{ $recipe->description }}</p>

                <!-- Info Grid -->
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-icon">📊</span>
                        <span class="info-value" style="color:var(--green-600);">{{ $recipe->difficulty }}</span>
                        <span class="info-label">Tingkat Kesulitan</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">⏱️</span>
                        <span class="info-value" style="color:var(--green-600);">{{ $recipe->cooking_time }} mnt</span>
                        <span class="info-label">Waktu Memasak</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">🍽️</span>
                        <span class="info-value" style="color:var(--green-600);">{{ $recipe->portion ?? 2 }} porsi</span>
                        <span class="info-label">Porsi</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">🔥</span>
                        <span class="info-value" style="color:var(--green-600);">{{ $recipe->calories > 0 ? $recipe->calories.' kkal' : '—' }}</span>
                        <span class="info-label">Kalori</span>
                    </div>
                </div>

                <!-- Ingredients -->
                <div class="detail-section">
                    <h2>🥄 Bahan-bahan</h2>
                    <div class="ingredients-list">
                        @foreach ($ingredients as $item)
                            @if (!empty(trim($item)))
                            <div class="ingredient-item">{{ trim($item) }}</div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Steps -->
                <div class="detail-section">
                    <h2>👨‍🍳 Langkah Memasak</h2>
                    <div class="steps-list">
                        @php $stepNum = 1; @endphp
                        @foreach ($steps as $step)
                            @if (!empty(trim($step)))
                            <div class="step-item">
                                <div class="step-number">{{ $stepNum++ }}</div>
                                <div class="step-text">{{ trim($step) }}</div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Tags -->
                @php
                    $tags = !empty($recipe->tags) ? array_filter(array_map('trim', explode(',', $recipe->tags))) : [];
                @endphp
                @if (!empty($tags))
                <div class="detail-section">
                    <h2>🏷️ Tag</h2>
                    <div class="tags-list">
                        @foreach ($tags as $tag)
                            @if (!empty(trim($tag)))
                            <a href="{{ route('explore', ['q' => trim($tag)]) }}" class="tag">#{{ trim($tag) }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <!-- SIDEBAR CARD -->
            <div class="detail-sidebar">
                <div class="detail-side-card">
                    <img
                        src="{{ asset('assets/img/' . $recipe->image) }}"
                        alt=""
                        class="detail-side-img"
                        onerror="this.src='{{ asset('assets/img/placeholder-food.jpg') }}'"
                    >
                    <div class="detail-side-body">
                        <div class="detail-side-title">{{ $recipe->title }}</div>
                        <div class="detail-side-meta">
                            <span>⏱ {{ $recipe->cooking_time }} mnt</span>
                            <span>🍽️ {{ $recipe->portion ?? 2 }} porsi</span>
                            @if ($recipe->calories > 0)
                            <span>🔥 {{ $recipe->calories }} kkal</span>
                            @endif
                        </div>

                        <!-- Save Button -->
                        @if (Auth::check())
                            <button
                                type="button"
                                class="btn btn-primary btn-full"
                                id="saveBtn"
                                onclick="handleSave({{ $recipe->id }}, this)"
                                style="margin-bottom: 12px; {{ $isSaved ? 'background: var(--danger);' : '' }}"
                            >
                                {{ $isSaved ? '❤️ Tersimpan' : '🔖 Simpan Resep' }}
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-full" style="margin-bottom: 12px;">🔖 Masuk untuk Menyimpan</a>
                        @endif

                        <!-- About recipe -->
                        <div class="detail-side-section">
                            <h4>Tentang Resep</h4>
                            <p>{{ Str::limit($recipe->description, 120) }}</p>
                        </div>

                        <!-- Chef -->
                        <div class="detail-side-section">
                            <h4>Dibuat oleh</h4>
                            <div class="chef-card">
                                <img src="{{ asset('assets/img/' . ($recipe->user->profile_photo ?? 'default-user.png')) }}" alt="" class="chef-avatar" onerror="this.src='{{ asset('assets/img/default-user.png') }}'">
                                <div class="chef-info">
                                    <div class="chef-name">{{ $recipe->user->full_name ?? 'Chef' }}</div>
                                    <div class="chef-since">Bergabung sejak {{ $recipe->user->created_at ? $recipe->user->created_at->format('Y') : '2023' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="detail-side-section">
                            <h4>Kategori</h4>
                            <a href="{{ route('explore', ['category' => $recipe->category_id]) }}" class="tag">
                                🍽️ {{ $recipe->category->name ?? 'Kategori' }}
                            </a>
                        </div>

                        <!-- Tags sidebar -->
                        @if (!empty($tags))
                        <div class="detail-side-section">
                            <h4>Tag</h4>
                            <div class="tags-list" style="flex-wrap:wrap;">
                                @foreach (array_slice($tags, 0, 4) as $tag)
                                    @if (!empty(trim($tag)))
                                    <a href="{{ route('explore', ['q' => trim($tag)]) }}" class="tag" style="font-size:12px; padding:4px 10px;">#{{ trim($tag) }}</a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<!-- DELETE MODAL -->
@if ($isOwner || $isAdmin)
<div class="modal-backdrop" id="deleteModal">
    <div class="modal">
        <div class="modal-icon">🗑️</div>
        <h3>Hapus resep ini?</h3>
        <p>Resep "<strong>{{ $recipe->title }}</strong>" akan dihapus secara permanen dan tidak dapat dikembalikan.</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-outline" onclick="closeModal()">Batal</button>
            <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Hapus</button>
            </form>
        </div>
    </div>
</div>
@endif

<!-- TOAST -->
<div class="toast-container" id="toastContainer"></div>

@endsection

@push('styles')
<style>
    body { background: var(--gray-50); }
</style>
@endpush

@push('scripts')
<script>
function handleSave(recipeId, btn) {
    const original = btn.innerHTML;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    btn.disabled = true;
    btn.innerHTML = '⏳ Menyimpan...';

    fetch(`/recipes/${recipeId}/favorite`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        if (data.status === 'saved') {
            btn.innerHTML = '❤️ Tersimpan';
            btn.style.background = 'var(--danger)';
            showToast('Resep disimpan ke favorit! ❤️');
        } else {
            btn.innerHTML = '🔖 Simpan Resep';
            btn.style.background = '';
            showToast('Resep dihapus dari favorit');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = original;
        showToast('Gagal menyimpan. Coba lagi.', 'error');
    });
}

function shareRecipe() {
    if (navigator.share) {
        navigator.share({
            title: '{{ addslashes($recipe->title) }}',
            text: 'Lihat resep lezat ini di Racikara!',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href).then(() => {
            showToast('Link resep disalin! 📋');
        });
    }
}

function openDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.add('active');
    }
}

function closeModal() {
    document.querySelectorAll('.modal-backdrop').forEach(m => m.classList.remove('active'));
}

document.querySelectorAll('.modal-backdrop').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) closeModal(); });
});

function showToast(msg, type = '') {
    const c = document.getElementById('toastContainer');
    const t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' error' : '');
    t.innerHTML = `<span class="toast-icon">${type==='error' ? '❌' : '✅'}</span> ${msg}`;
    c.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; t.style.transform='translateX(120px)'; t.style.transition='all 0.3s'; setTimeout(()=>t.remove(),300); }, 3000);
}

// Animate step items
document.querySelectorAll('.step-item').forEach((el, i) => {
    el.style.opacity = '0';
    el.style.transform = 'translateX(-20px)';
    el.style.transition = 'all 0.4s ease';
    setTimeout(() => { el.style.opacity='1'; el.style.transform='translateX(0)'; }, 100 + i * 80);
});
</script>
@endpush
