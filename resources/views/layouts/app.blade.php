<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Racikara')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ filemtime(public_path('assets/css/style.css')) }}">
    @stack('styles')
</head>
<body class="{{ $bodyClass ?? '' }}" style="{{ $bodyStyle ?? '' }}">
    
    @if(isset($hideSidebar) && $hideSidebar)
        @yield('content')
    @else
        <div class="app-layout">
            @include('layouts.sidebar')
            
            <main class="main-content">
                @if(isset($useTopbar) && $useTopbar)
                    @include('layouts.topbar')
                @endif
                
                @yield('content')
            </main>
        </div>
    @endif
    
    @stack('scripts')
</body>
</html>
