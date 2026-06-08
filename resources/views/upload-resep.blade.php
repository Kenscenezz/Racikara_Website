@php
    $hideSidebar = true;
@endphp
@extends('layouts.app')

@section('title', 'Upload Resep - Racikara')

@section('content')

@php
    $topbarBack = route('home');
    $topbarBackLabel = 'Beranda';
    $topbarTitle = 'Upload Resep';
    $topbarActions = '<button type="button" class="btn btn-outline btn-sm" onclick="saveDraft()" id="draftBtn">💾 Simpan Draft</button>';
@endphp
@include('layouts.topbar', ['topbarBack' => $topbarBack, 'topbarBackLabel' => $topbarBackLabel, 'topbarTitle' => $topbarTitle, 'topbarActions' => $topbarActions])

<main style="max-width: 1100px; margin: 0 auto; padding: 28px 24px;">

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        <div class="upload-layout">

            <!-- LEFT: FORM -->
            <div>

                <!-- FOTO MAKANAN -->
                <div class="upload-form-card" style="margin-bottom:20px;">
                    <h2>📸 Foto Makanan</h2>
                    <div class="photo-upload-zone" id="uploadZone">
                        <input type="file" name="image" accept="image/png,image/jpg,image/jpeg,image/webp" id="photoInput" required>
                        <span class="photo-upload-icon">📷</span>
                        <div class="photo-upload-text">Klik atau seret foto makanan ke sini</div>
                        <div class="photo-upload-hint">PNG, JPG, WEBP — Maksimal 2MB</div>
                    </div>
                    <div class="photo-preview" id="photoPreview">
                        <img id="previewImg" src="" alt="Preview">
                        <button type="button" class="remove-photo" id="removePhoto" title="Hapus foto">✕</button>
                    </div>
                </div>

                <!-- INFO UTAMA -->
                <div class="upload-form-card" style="margin-bottom:20px;">
                    <h2>📝 Informasi Resep</h2>

                    <div class="form-grid-2">
                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Judul Resep <span style="color:var(--danger);">*</span></label>
                            <input
                                type="text"
                                name="title"
                                class="form-control"
                                placeholder="Contoh: Ayam Bakar Madu Spesial"
                                required
                                maxlength="100"
                                id="titleInput"
                                value="{{ old('title') }}"
                                oninput="updatePreview()"
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kategori <span style="color:var(--danger);">*</span></label>
                            <select name="category_id" class="form-control" required id="categorySelect" onchange="updatePreview()">
                                <option value="" disabled selected>Pilih kategori...</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tingkat Kesulitan</label>
                            <select name="difficulty" class="form-control" id="difficultySelect" onchange="updatePreview()">
                                <option value="Mudah" {{ old('difficulty') == 'Mudah' ? 'selected' : '' }}>😊 Mudah</option>
                                <option value="Sedang" {{ old('difficulty') == 'Sedang' ? 'selected' : '' }}>😐 Sedang</option>
                                <option value="Sulit" {{ old('difficulty') == 'Sulit' ? 'selected' : '' }}>😤 Sulit</option>
                            </select>
                        </div>

                        <div class="form-group" style="grid-column:1/-1;">
                            <label class="form-label">Deskripsi Singkat <span style="color:var(--danger);">*</span></label>
                            <textarea
                                name="description"
                                class="form-control"
                                placeholder="Ceritakan tentang resepmu secara singkat... (maks. 200 karakter)"
                                required
                                maxlength="200"
                                rows="3"
                                id="descInput"
                                oninput="updatePreview(); updateCharCount(this, 'descCount', 200)"
                            >{{ old('description') }}</textarea>
                            <div class="char-counter"><span id="descCount">0</span>/200</div>
                        </div>
                    </div>
                </div>

                <!-- BAHAN-BAHAN -->
                <div class="upload-form-card" style="margin-bottom:20px;">
                    <h2>🥕 Bahan-bahan</h2>
                    <p style="font-size:13px; color:var(--gray-400); margin-bottom:16px;">Tambahkan bahan satu per baris. Contoh: "500 gr dada ayam"</p>
                    <div class="ingredients-builder" id="ingredientsBuilder">
                        <div class="ingredient-row">
                            <input type="text" name="ingredients[]" placeholder="Contoh: 500 gr dada ayam" class="ingredient-input">
                            <button type="button" class="remove-row" onclick="removeRow(this)">✕</button>
                        </div>
                        <div class="ingredient-row">
                            <input type="text" name="ingredients[]" placeholder="Contoh: 3 sdm madu" class="ingredient-input">
                            <button type="button" class="remove-row" onclick="removeRow(this)">✕</button>
                        </div>
                    </div>
                    <button type="button" class="add-row-btn" onclick="addIngredient()" id="addIngredientBtn">
                        ➕ Tambah Bahan
                    </button>
                </div>

                <!-- LANGKAH MEMASAK -->
                <div class="upload-form-card" style="margin-bottom:20px;">
                    <h2>👨‍🍳 Langkah Memasak</h2>
                    <p style="font-size:13px; color:var(--gray-400); margin-bottom:16px;">Tuliskan langkah memasak secara berurutan dan jelas.</p>
                    <div class="steps-builder" id="stepsBuilder">
                        <div class="step-row">
                            <div class="step-num-badge">1</div>
                            <textarea name="steps[]" placeholder="Campurkan bumbu..." rows="2" class="step-input"></textarea>
                            <button type="button" class="remove-row" onclick="removeStepRow(this)">✕</button>
                        </div>
                        <div class="step-row">
                            <div class="step-num-badge">2</div>
                            <textarea name="steps[]" placeholder="Masak hingga matang..." rows="2" class="step-input"></textarea>
                            <button type="button" class="remove-row" onclick="removeStepRow(this)">✕</button>
                        </div>
                    </div>
                    <button type="button" class="add-row-btn" onclick="addStep()" id="addStepBtn">
                        ➕ Tambah Langkah
                    </button>
                </div>

                <!-- DETAIL MASAK -->
                <div class="upload-form-card" style="margin-bottom:20px;">
                    <h2>⏱️ Detail Memasak</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Waktu Memasak (menit) <span style="color:var(--danger);">*</span></label>
                            <div class="form-control-icon">
                                <span class="icon">⏱️</span>
                                <input
                                    type="number"
                                    name="cooking_time"
                                    class="form-control"
                                    placeholder="30"
                                    min="1"
                                    max="1440"
                                    value="{{ old('cooking_time') }}"
                                    required
                                    id="timeInput"
                                    oninput="updatePreview()"
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Porsi</label>
                            <div class="form-control-icon">
                                <span class="icon">🍽️</span>
                                <input
                                    type="number"
                                    name="portion"
                                    class="form-control"
                                    placeholder="2"
                                    min="1"
                                    max="100"
                                    value="{{ old('portion', 2) }}"
                                    id="portionInput"
                                    oninput="updatePreview()"
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Kalori (kkal) <span style="font-size:11px; color:var(--gray-400);">opsional</span></label>
                            <div class="form-control-icon">
                                <span class="icon">🔥</span>
                                <input
                                    type="number"
                                    name="calories"
                                    class="form-control"
                                    placeholder="Kosongkan jika tidak tahu"
                                    min="0"
                                    max="9999"
                                    value="{{ old('calories') }}"
                                    id="caloriesInput"
                                    oninput="updatePreview()"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="upload-actions">
                    <button
                        type="submit"
                        name="status"
                        value="published"
                        class="btn btn-primary btn-lg"
                        style="flex:1;"
                        id="publishBtn"
                    >
                        📤 Publish Resep
                    </button>
                </div>

            </div>

            <!-- RIGHT: PREVIEW -->
            <div>
                <div class="preview-card" id="previewCard">
                    <div class="preview-card-header">
                        👁️ Preview Resep
                    </div>

                    <div class="preview-recipe-img" id="previewImgWrapper">
                        <span>📷</span>
                    </div>

                    <div class="preview-body">
                        <div class="preview-title" id="previewTitle">Judul resep akan muncul di sini</div>
                        <div class="preview-desc" id="previewDesc">Deskripsi singkat resepmu...</div>

                        <div class="preview-info">
                            <div class="preview-info-row">
                                <span class="label">🗂️ Kategori</span>
                                <span class="value" id="previewCat">—</span>
                            </div>
                            <div class="preview-info-row">
                                <span class="label">⏱️ Waktu</span>
                                <span class="value" id="previewTime">—</span>
                            </div>
                            <div class="preview-info-row">
                                <span class="label">📊 Kesulitan</span>
                                <span class="value" id="previewDiff">Mudah</span>
                            </div>
                            <div class="preview-info-row">
                                <span class="label">🍽️ Porsi</span>
                                <span class="value" id="previewPortion">—</span>
                            </div>
                            <div class="preview-info-row" id="previewCalRow" style="display:none;">
                                <span class="label">🔥 Kalori</span>
                                <span class="value" id="previewCal">—</span>
                            </div>
                        </div>

                        <div class="tip-box">
                            💡 <span>Pastikan foto terang dan deskripsi singkat jelas agar resepmu lebih menarik bagi pembaca!</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</main>
@endsection

@push('styles')
<style>
    body { background: var(--gray-50); }
</style>
@endpush

@push('scripts')
<script>
// ---- PHOTO PREVIEW ----
const photoInput = document.getElementById('photoInput');
const previewImgWrapper = document.getElementById('previewImgWrapper');
const photoPreview = document.getElementById('photoPreview');
const previewImg = document.getElementById('previewImg');
const uploadZone = document.getElementById('uploadZone');
const removePhoto = document.getElementById('removePhoto');

photoInput.addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            previewImg.src = e.target.result;
            photoPreview.style.display = 'block';
            uploadZone.style.display = 'none';
            previewImgWrapper.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width:100%;height:180px;object-fit:cover;">`;
        };
        reader.readAsDataURL(this.files[0]);
    }
});

removePhoto.addEventListener('click', function() {
    photoInput.value = '';
    photoPreview.style.display = 'none';
    uploadZone.style.display = 'block';
    previewImgWrapper.innerHTML = '<span>📷</span>';
});

// Drag & drop
uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('drag-over'); });
uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
uploadZone.addEventListener('drop', e => {
    e.preventDefault();
    uploadZone.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        photoInput.files = e.dataTransfer.files;
        const reader = new FileReader();
        reader.onload = ev => {
            previewImg.src = ev.target.result;
            photoPreview.style.display = 'block';
            uploadZone.style.display = 'none';
            previewImgWrapper.innerHTML = `<img src="${ev.target.result}" alt="Preview" style="width:100%;height:180px;object-fit:cover;">`;
        };
        reader.readAsDataURL(file);
    }
});

// ---- LIVE PREVIEW ----
function updatePreview() {
    const title = document.getElementById('titleInput').value;
    const desc = document.getElementById('descInput').value;
    const catEl = document.getElementById('categorySelect');
    const catText = catEl.options[catEl.selectedIndex]?.text || '—';
    const time = document.getElementById('timeInput').value;
    const diff = document.getElementById('difficultySelect').value;
    const portion = document.getElementById('portionInput').value;
    const cal = document.getElementById('caloriesInput').value;

    document.getElementById('previewTitle').textContent = title || 'Judul resep akan muncul di sini';
    document.getElementById('previewDesc').textContent = desc || 'Deskripsi singkat resepmu...';
    document.getElementById('previewCat').textContent = catText || '—';
    document.getElementById('previewTime').textContent = time ? time + ' menit' : '—';
    document.getElementById('previewDiff').textContent = diff || 'Mudah';
    document.getElementById('previewPortion').textContent = portion ? portion + ' porsi' : '—';

    const calRow = document.getElementById('previewCalRow');
    if (cal && parseInt(cal) > 0) {
        calRow.style.display = 'flex';
        document.getElementById('previewCal').textContent = cal + ' kkal';
    } else {
        calRow.style.display = 'none';
    }
}

function updateCharCount(el, countId, max) {
    document.getElementById(countId).textContent = el.value.length;
}

// ---- INGREDIENTS ----
function addIngredient() {
    const builder = document.getElementById('ingredientsBuilder');
    const row = document.createElement('div');
    row.className = 'ingredient-row';
    row.innerHTML = `<input type="text" name="ingredients[]" placeholder="Tambahkan bahan..." class="ingredient-input"><button type="button" class="remove-row" onclick="removeRow(this)">✕</button>`;
    builder.appendChild(row);
    row.querySelector('input').focus();
}

function removeRow(btn) {
    const row = btn.closest('.ingredient-row');
    if (document.querySelectorAll('.ingredient-row').length > 1) {
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        row.style.transition = 'all 0.2s ease';
        setTimeout(() => row.remove(), 200);
    }
}

// ---- STEPS ----
function addStep() {
    const builder = document.getElementById('stepsBuilder');
    const num = document.querySelectorAll('.step-row').length + 1;
    const row = document.createElement('div');
    row.className = 'step-row';
    row.innerHTML = `<div class="step-num-badge">${num}</div><textarea name="steps[]" placeholder="Langkah memasak..." rows="2" class="step-input"></textarea><button type="button" class="remove-row" onclick="removeStepRow(this)">✕</button>`;
    builder.appendChild(row);
    row.querySelector('textarea').focus();
}

function removeStepRow(btn) {
    const rows = document.querySelectorAll('.step-row');
    if (rows.length > 1) {
        const row = btn.closest('.step-row');
        row.style.opacity = '0';
        row.style.transform = 'translateX(-20px)';
        row.style.transition = 'all 0.2s ease';
        setTimeout(() => {
            row.remove();
            document.querySelectorAll('.step-num-badge').forEach((badge, i) => badge.textContent = i + 1);
        }, 200);
    }
}

function saveDraft() {
    alert('Fungsi simpan draft dapat diimplementasikan nanti!');
}

document.getElementById('uploadForm').addEventListener('submit', function(e) {
    const ingInputs = [...document.querySelectorAll('.ingredient-input')].filter(i => i.value.trim());
    const stepInputs = [...document.querySelectorAll('.step-input')].filter(s => s.value.trim());

    if (ingInputs.length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal satu bahan!');
        return;
    }
    if (stepInputs.length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal satu langkah memasak!');
        return;
    }
});
</script>
@endpush
