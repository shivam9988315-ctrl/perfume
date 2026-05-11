<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Luxury perfume boutique — curated extrait, eau de parfum, and niche collections.')">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700|instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[#070608] text-zinc-100 antialiased selection:bg-[#c9a227]/30 selection:text-white">
    <div class="pointer-events-none fixed inset-0 -z-10 bg-[radial-gradient(ellipse_at_top,_rgba(201,162,39,0.12),_transparent_55%),radial-gradient(ellipse_at_bottom,_rgba(255,255,255,0.04),_transparent_50%)]"></div>
    <header class="border-b border-white/5 bg-black/40 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-5 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="font-[family-name:var(--font-display)] text-2xl tracking-[0.18em] text-[#c9a227] sm:text-3xl">
                {{ config('app.name') }}
            </a>
            <nav class="flex items-center gap-4 text-sm text-zinc-300">
                <a href="{{ route('shop.index') }}" class="transition hover:text-white">Shop</a>
                <a href="{{ route('shop.index', ['gender' => 'women']) }}" class="hidden transition hover:text-white sm:inline">Women</a>
                <a href="{{ route('shop.index', ['gender' => 'men']) }}" class="hidden transition hover:text-white sm:inline">Men</a>
                <a href="{{ url('/admin') }}" class="rounded-full border border-[#c9a227]/40 px-3 py-1 text-xs uppercase tracking-widest text-[#e8d48b] transition hover:border-[#c9a227] hover:text-white">
                    Admin
                </a>
            </nav>
        </div>
    </header>

    <main>
        @if (session('status'))
            <div class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                <p class="rounded-lg border border-[#c9a227]/30 bg-[#c9a227]/10 px-4 py-3 text-sm text-[#e8d48b]">{{ session('status') }}</p>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="mt-24 border-t border-white/5 bg-black/50">
        <div class="mx-auto flex max-w-7xl flex-col gap-8 px-4 py-14 sm:px-6 lg:flex-row lg:items-end lg:justify-between lg:px-8">
            <div>
                <p class="font-[family-name:var(--font-display)] text-xl text-[#c9a227]">{{ config('app.name') }}</p>
                <p class="mt-2 max-w-md text-sm leading-relaxed text-zinc-400">
                    Artisan compositions, small-batch maceration, and uncompromising ingredients — shipped with care worldwide.
                </p>
            </div>
            <p class="text-xs text-zinc-500">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
