<header class="topbar">
    <a href="{{ route('home') }}" class="topbar-brand">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Racikara">
        <span class="topbar-brand-name">Racikara</span>
    </a>

    <a href="{{ $topbarBack ?? url()->previous() }}" class="topbar-back">
        ← {{ $topbarBackLabel ?? 'Kembali' }}
    </a>

    <div class="topbar-title">{{ $topbarTitle ?? '' }}</div>

    <div class="topbar-actions">
        {!! $topbarActions ?? '' !!}
    </div>
</header>
