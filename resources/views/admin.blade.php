@extends('layouts.app')

@section('title', 'Admin Dashboard - Racikara')

@section('content')
<div class="content-header">
    <h1 style="font-size: 2rem; color: #1a1a1a; display: flex; align-items: center; gap: 10px;">
        <span style="background: #1a7a3c; color: white; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">⚙️</span>
        Admin Panel
    </h1>
    <p style="color: #666; margin-top: 5px; padding-left: 50px;">Kelola pengguna, resep, dan statistik platform.</p>
</div>

@if(session('success'))
    <div style="background: #e0f7ea; color: #2eac5a; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 500;">
        ✓ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fdf0f0; color: #e74c3c; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; font-weight: 500;">
        ⚠️ {{ session('error') }}
    </div>
@endif

<!-- Overview Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 20px;">
        <div style="width: 60px; height: 60px; background: #e0f7ea; color: #2eac5a; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">👥</div>
        <div>
            <div style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Total Pengguna</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: #1a1a1a;">{{ $stats['users'] }}</div>
        </div>
    </div>
    <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 20px;">
        <div style="width: 60px; height: 60px; background: #fff0eb; color: #ff6b35; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">🍲</div>
        <div>
            <div style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Total Resep</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: #1a1a1a;">{{ $stats['recipes'] }}</div>
        </div>
    </div>
    <div style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 20px;">
        <div style="width: 60px; height: 60px; background: #eef2ff; color: #4f46e5; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">👁️</div>
        <div>
            <div style="color: #666; font-size: 0.9rem; margin-bottom: 5px;">Total Views</div>
            <div style="font-size: 1.8rem; font-weight: 700; color: #1a1a1a;">{{ $stats['views'] }}</div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="profile-tabs" style="margin-bottom: 20px;">
    <div class="profile-tab active" onclick="switchAdminTab('users')" id="tab-users">Manajemen Pengguna</div>
    <div class="profile-tab" onclick="switchAdminTab('recipes')" id="tab-recipes">Manajemen Resep</div>
</div>

<!-- Users Table -->
<div id="content-users" style="background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                    <th style="padding: 15px 20px; color: #444; font-weight: 600;">ID</th>
                    <th style="padding: 15px 20px; color: #444; font-weight: 600;">Pengguna</th>
                    <th style="padding: 15px 20px; color: #444; font-weight: 600;">Email</th>
                    <th style="padding: 15px 20px; color: #444; font-weight: 600;">Role</th>
                    <th style="padding: 15px 20px; color: #444; font-weight: 600;">Mendaftar</th>
                    <th style="padding: 15px 20px; color: #444; font-weight: 600; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px 20px; color: #666;">#{{ $u->id }}</td>
                    <td style="padding: 15px 20px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="{{ asset('assets/img/' . $u->profile_photo) }}" alt="Avatar" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;" onerror="this.src='{{ asset('assets/img/default-user.png') }}'">
                            <span style="font-weight: 500; color: #1a1a1a;">{{ $u->full_name }}</span>
                        </div>
                    </td>
                    <td style="padding: 15px 20px; color: #666;">{{ $u->email }}</td>
                    <td style="padding: 15px 20px;">
                        <span class="badge {{ $u->role == 'admin' ? '' : 'badge-outline' }}" style="margin:0; font-size: 0.75rem;">
                            {{ $u->role == 'admin' ? '⚙️ Admin' : '👤 User' }}
                        </span>
                    </td>
                    <td style="padding: 15px 20px; color: #666; font-size: 0.9rem;">{{ $u->created_at ? $u->created_at->format('d M Y') : 'Beberapa waktu lalu' }}</td>
                    <td style="padding: 15px 20px; text-align: right;">
                        @if($u->id != auth()->id())
                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini dan seluruh resepnya?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #fee2e2; color: #ef4444; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 0.85rem;">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Recipes Table -->
<div id="content-recipes" style="background: white; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; display: none;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                    <th style="padding: 15px 20px; color: #444; font-weight: 600;">Resep</th>
                    <th style="padding: 15px 20px; color: #444; font-weight: 600;">Penulis</th>
                    <th style="padding: 15px 20px; color: #444; font-weight: 600;">Kategori</th>
                    <th style="padding: 15px 20px; color: #444; font-weight: 600;">Statistik</th>
                    <th style="padding: 15px 20px; color: #444; font-weight: 600; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recipes as $r)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px 20px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="{{ asset('assets/img/' . $r->image) }}" alt="Resep" style="width: 45px; height: 45px; border-radius: 8px; object-fit: cover;" onerror="this.src='{{ asset('assets/img/placeholder-food.jpg') }}'">
                            <div>
                                <a href="{{ route('recipes.show', $r->id) }}" style="font-weight: 500; color: #1a1a1a; text-decoration: none;" target="_blank">{{ $r->title }}</a>
                                <div style="font-size: 0.8rem; color: #888; margin-top: 2px;">{{ $r->created_at ? $r->created_at->format('d M Y') : 'Beberapa waktu lalu' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 15px 20px; color: #444;">{{ $r->user->full_name }}</td>
                    <td style="padding: 15px 20px;">
                        <span style="background: #f3fbf5; color: #1a7a3c; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 500;">
                            {{ $r->category->icon }} {{ $r->category->name }}
                        </span>
                    </td>
                    <td style="padding: 15px 20px; color: #666; font-size: 0.9rem;">
                        <div>👁️ {{ $r->views }}</div>
                        <div>⭐ {{ $r->rating }}</div>
                    </td>
                    <td style="padding: 15px 20px; text-align: right;">
                        <form action="{{ route('admin.recipes.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus resep ini?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #fee2e2; color: #ef4444; border: none; padding: 6px 12px; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 0.85rem;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function switchAdminTab(tab) {
    document.getElementById('tab-users').classList.remove('active');
    document.getElementById('tab-recipes').classList.remove('active');
    
    document.getElementById('content-users').style.display = 'none';
    document.getElementById('content-recipes').style.display = 'none';
    
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('content-' + tab).style.display = 'block';
}
</script>
@endpush
@endsection
