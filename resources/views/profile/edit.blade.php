@php
    $hideSidebar = true;
@endphp
@extends('layouts.app')

@section('title', 'Edit Profil - Racikara')

@section('content')

@php
    $topbarBack = route('profile');
    $topbarBackLabel = 'Kembali ke Profil';
    $topbarTitle = 'Edit Profil';
    $topbarActions = '<button type="submit" form="editProfileForm" class="btn btn-primary">💾 Simpan Perubahan</button>';
@endphp
@include('layouts.topbar', ['topbarBack' => $topbarBack, 'topbarBackLabel' => $topbarBackLabel, 'topbarTitle' => $topbarTitle, 'topbarActions' => $topbarActions])

<main style="max-width: 800px; margin: 0 auto; padding: 40px 24px;">

    @if($errors->any())
        <div style="background: #fdf0f0; color: #e74c3c; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 500;">
            ⚠️ Terdapat kesalahan pengisian form.
            <ul style="margin-top: 10px; margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div style="background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden;">
        <div style="padding: 30px; border-bottom: 1px solid #eee;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #1a1a1a; margin-bottom: 5px;">Informasi Dasar</h2>
            <p style="color: #666; font-size: 0.9rem;">Perbarui foto profil, nama, dan bio untuk menunjukkan siapa dirimu.</p>
        </div>

        <form id="editProfileForm" action="{{ route('profile.update') }}" method="POST" style="padding: 30px;">
            @csrf
            @method('PATCH')

            <!-- Pilihan Avatar -->
            <div class="form-group" style="margin-bottom: 30px;">
                <label style="display: block; font-weight: 600; color: #1a1a1a; margin-bottom: 15px;">Pilih Foto Profil (Template)</label>
                
                <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                    <!-- Option 1 -->
                    <label class="avatar-option">
                        <input type="radio" name="profile_photo" value="avatar-1.png" {{ ($user->profile_photo == 'avatar-1.png' || empty($user->profile_photo)) ? 'checked' : '' }} style="display: none;">
                        <img src="{{ asset('assets/img/avatar-1.png') }}" onerror="this.src='{{ asset('assets/img/default-user.png') }}'" class="avatar-img" alt="Avatar 1">
                        <div class="avatar-check">✓</div>
                    </label>

                    <!-- Option 2 -->
                    <label class="avatar-option">
                        <input type="radio" name="profile_photo" value="avatar-2.png" {{ $user->profile_photo == 'avatar-2.png' ? 'checked' : '' }} style="display: none;">
                        <img src="{{ asset('assets/img/avatar-2.png') }}" onerror="this.src='{{ asset('assets/img/default-user.png') }}'" class="avatar-img" alt="Avatar 2">
                        <div class="avatar-check">✓</div>
                    </label>

                    <!-- Option 3 -->
                    <label class="avatar-option">
                        <input type="radio" name="profile_photo" value="avatar-3.png" {{ $user->profile_photo == 'avatar-3.png' ? 'checked' : '' }} style="display: none;">
                        <img src="{{ asset('assets/img/avatar-3.png') }}" onerror="this.src='{{ asset('assets/img/default-user.png') }}'" class="avatar-img" alt="Avatar 3">
                        <div class="avatar-check">✓</div>
                    </label>

                    <!-- Option 4 -->
                    <label class="avatar-option">
                        <input type="radio" name="profile_photo" value="avatar-4.png" {{ $user->profile_photo == 'avatar-4.png' ? 'checked' : '' }} style="display: none;">
                        <img src="{{ asset('assets/img/avatar-4.png') }}" onerror="this.src='{{ asset('assets/img/default-user.png') }}'" class="avatar-img" alt="Avatar 4">
                        <div class="avatar-check">✓</div>
                    </label>

                    <!-- Option 5 -->
                    <label class="avatar-option">
                        <input type="radio" name="profile_photo" value="avatar-5.png" {{ $user->profile_photo == 'avatar-5.png' ? 'checked' : '' }} style="display: none;">
                        <img src="{{ asset('assets/img/avatar-5.png') }}" onerror="this.src='{{ asset('assets/img/default-user.png') }}'" class="avatar-img" alt="Avatar 5">
                        <div class="avatar-check">✓</div>
                    </label>
                </div>
                <p style="font-size: 0.85rem; color: #888; margin-top: 15px;">Pilih salah satu dari 5 template foto profil di atas.</p>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="full_name" style="display: block; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">Nama Lengkap</label>
                <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $user->full_name) }}" class="form-control" required style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 10px; font-size: 1rem;">
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="bio" style="display: block; font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">Deskripsi Profil (Bio)</label>
                <textarea name="bio" id="bio" rows="4" class="form-control" placeholder="Ceritakan sedikit tentang dirimu dan hobi memasakmu..." style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 10px; font-size: 1rem; resize: vertical;">{{ old('bio', $user->bio) }}</textarea>
            </div>
            
        </form>
    </div>

</main>

@push('styles')
<style>
    body { background: var(--gray-50); }
    
    .avatar-option {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }
    
    .avatar-img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .avatar-check {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 24px;
        height: 24px;
        background: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        border: 2px solid white;
        opacity: 0;
        transform: scale(0.5);
        transition: all 0.3s ease;
    }
    
    .avatar-option input:checked + .avatar-img {
        border-color: var(--primary);
        box-shadow: 0 4px 15px rgba(46, 172, 90, 0.2);
    }
    
    .avatar-option input:checked ~ .avatar-check {
        opacity: 1;
        transform: scale(1);
    }
</style>
@endpush

@endsection
