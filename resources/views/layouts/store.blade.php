<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Luxury perfumes, attars, and oud — a curated house for Arabic fragrances and rare compositions.')">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=amiri:400,700|montserrat:400,500,600,700|playfair+display:400,500,600,700|poppins:300,400,500,600" rel="stylesheet" />
    <script>
        window.__NAV_CATEGORIES__ = @json($navCategories->map(fn ($c) => ['name' => $c->name, 'slug' => $c->slug])->values());
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[#050506] text-[#f4f1ea] antialiased selection:bg-[#c9a227]/30 selection:text-white">
    <div
        id="luxury-shell"
        class="min-h-full pb-[5.75rem] lg:pb-0"
        x-data="luxuryShell()"
        @keydown.escape.window="closeOverlays()"
    >
        {{-- Ambient luxury gradients --}}
        <div class="pointer-events-none fixed inset-0 -z-10">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_100%_70%_at_50%_-10%,rgba(201,162,39,0.11),transparent_52%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_70%_50%_at_100%_0%,rgba(255,255,255,0.035),transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(180deg,#0e1016_0%,#050506_45%,#030304_100%)]"></div>
        </div>

        <header
            class="fixed inset-x-0 top-0 z-[100] transition-all duration-500 ease-out"
            :class="scrolled ? 'border-b border-white/[0.07] bg-[#050506]/92 shadow-[0_8px_40px_-12px_rgba(0,0,0,0.85)] backdrop-blur-2xl' : 'border-b border-transparent bg-gradient-to-b from-black/70 via-black/25 to-transparent'"
        >
            <div class="mx-auto flex max-w-[1680px] items-center justify-between gap-3 px-4 py-3.5 sm:px-6 lg:gap-6 lg:px-10 lg:py-4">
                <div class="flex items-center gap-2 lg:gap-4">
                    <button
                        type="button"
                        class="inline-flex rounded-full border border-white/10 p-2.5 text-zinc-300 transition hover:border-[#c9a227]/35 hover:text-white lg:hidden"
                        @click="mobileMenu = true; searchOpen = false"
                        aria-label="Open menu"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <a href="{{ route('home') }}" class="group flex flex-col leading-none">
                        <span class="font-display text-lg tracking-[0.22em] text-white transition duration-300 group-hover:text-[#e6cf5c] sm:text-xl md:text-2xl">{{ config('app.name') }}</span>
                        <span class="mt-0.5 hidden font-ui text-[9px] font-medium uppercase tracking-[0.5em] text-zinc-500 md:block">Parfum · Attar · Oud</span>
                    </a>
                </div>

                {{-- Desktop mega --}}
                <nav class="hidden items-center gap-1 xl:flex">
                    <a href="{{ route('home') }}" class="lux-underline px-3 py-2 font-ui text-[12px] font-medium uppercase tracking-[0.18em] text-zinc-300 hover:text-white">Home</a>
                    <div class="relative" @mouseleave="mega = false">
                        <button
                            type="button"
                            class="flex items-center gap-1 px-3 py-2 font-ui text-[12px] font-medium uppercase tracking-[0.18em] text-zinc-300 transition hover:text-white"
                            @mouseenter="mega = true"
                            @click.prevent="mega = !mega"
                        >
                            Collections
                            <svg class="h-3.5 w-3.5 opacity-60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div
                            x-show="mega"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute left-1/2 top-full z-[110] w-[min(92vw,720px)] -translate-x-1/2 pt-3"
                            x-cloak
                        >
                            <div class="lux-glass-strong grid grid-cols-2 gap-2 rounded-2xl p-5 shadow-2xl sm:grid-cols-3 md:grid-cols-4">
                                <template x-for="(c, idx) in categories" :key="idx">
                                    <a
                                        :href="`{{ route('shop.index') }}?category=${c.slug}`"
                                        class="rounded-xl border border-white/[0.06] bg-white/[0.02] px-4 py-3 font-ui text-[11px] font-medium uppercase tracking-wider text-zinc-300 transition hover:border-[#c9a227]/30 hover:text-white"
                                        x-text="c.name"
                                    ></a>
                                </template>
                                <a href="{{ route('shop.index', ['type' => 'attar']) }}" class="rounded-xl border border-[#c9a227]/25 bg-[#c9a227]/5 px-4 py-3 font-ui text-[11px] font-semibold uppercase tracking-wider text-[#e6cf5c] transition hover:bg-[#c9a227]/10">Exclusive attars</a>
                                <a href="{{ route('shop.index', ['limited' => 1]) }}" class="rounded-xl border border-white/[0.06] bg-white/[0.02] px-4 py-3 font-ui text-[11px] font-medium uppercase tracking-wider text-zinc-300 transition hover:border-[#c9a227]/30 hover:text-white">Limited edition</a>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('shop.index') }}" class="lux-underline px-3 py-2 font-ui text-[12px] font-medium uppercase tracking-[0.18em] text-zinc-300 hover:text-white">Shop all</a>
                </nav>

                <div class="flex items-center gap-1.5 sm:gap-2">
                    <button
                        type="button"
                        class="rounded-full border border-white/10 p-2.5 text-zinc-300 transition hover:border-[#c9a227]/35 hover:text-white"
                        @click="searchOpen = true; mega = false; accountOpen = false"
                        aria-label="Search"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.34-4.34M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z"/></svg>
                    </button>
                    <a href="{{ route('wishlist') }}" class="hidden rounded-full border border-white/10 p-2.5 text-zinc-400 transition hover:border-[#c9a227]/35 hover:text-[#e6cf5c] sm:inline-flex" aria-label="Wishlist">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                    </a>
                    <button
                        type="button"
                        class="relative rounded-full border border-[#c9a227]/35 bg-[#c9a227]/10 p-2.5 text-[#e6cf5c] transition hover:bg-[#c9a227]/18"
                        @click="cartOpen = !cartOpen; searchOpen = false; accountOpen = false; mega = false"
                        aria-label="Shopping bag"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l-1 12H6L5 9z"/></svg>
                    </button>
                    <div class="relative hidden sm:block">
                        <button
                            type="button"
                            class="flex items-center gap-2 rounded-full border border-white/10 py-1.5 pl-3 pr-2 font-ui text-[10px] font-semibold uppercase tracking-[0.2em] text-zinc-400 transition hover:border-[#c9a227]/30 hover:text-white"
                            @click="accountOpen = !accountOpen; searchOpen = false; cartOpen = false; mega = false"
                        >
                            Account
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/10 text-zinc-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 19.125a7.125 7.125 0 0 1 14.25 0"/></svg>
                            </span>
                        </button>
                        <div
                            x-show="accountOpen"
                            @click.outside="accountOpen = false"
                            x-transition
                            class="absolute right-0 top-full z-[120] mt-2 w-52 rounded-xl lux-glass-strong py-2 shadow-2xl"
                            x-cloak
                        >
                            <a href="{{ route('checkout') }}" class="block px-4 py-2.5 font-ui text-xs uppercase tracking-wider text-zinc-300 hover:bg-white/5 hover:text-white">Orders & bag</a>
                            <a href="{{ route('wishlist') }}" class="block px-4 py-2.5 font-ui text-xs uppercase tracking-wider text-zinc-300 hover:bg-white/5 hover:text-white">Wishlist</a>
                            <a href="{{ url('/admin') }}" class="block border-t border-white/10 px-4 py-2.5 font-ui text-[10px] uppercase tracking-wider text-zinc-500 hover:text-zinc-300">Concierge admin</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Spacer for fixed header --}}
        <div class="h-[60px] sm:h-[64px] lg:h-[72px]" aria-hidden="true"></div>

        <main class="min-h-[50vh]">
            @if (session('status'))
                <div class="mx-auto max-w-[1680px] px-4 pt-6 sm:px-6 lg:px-10">
                    <p class="rounded-xl border border-[#c9a227]/28 bg-[#c9a227]/10 px-4 py-3 text-sm text-[#e6cf5c]">{{ session('status') }}</p>
                </div>
            @endif
            @yield('content')
        </main>

        <footer class="mt-28 border-t border-white/[0.06] bg-gradient-to-b from-[#08090d] to-black pb-8 lg:pb-0">
            <div class="mx-auto grid max-w-[1680px] gap-12 px-4 py-16 sm:px-6 lg:grid-cols-4 lg:px-10">
                <div class="lg:col-span-2">
                    <p class="font-display text-2xl tracking-[0.14em] text-[#c9a227]">{{ config('app.name') }}</p>
                    <p class="mt-4 max-w-lg text-sm font-light leading-relaxed text-zinc-400">
                        A fragrance house dedicated solely to perfumes, attars, oud, and Arabic compositions — nothing else. Extrait concentration, precious oils, and uncompromising craft.
                    </p>
                </div>
                <div>
                    <p class="font-ui text-[10px] font-bold uppercase tracking-[0.38em] text-zinc-500">Collections</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-zinc-400">
                        <li><a href="{{ route('shop.index', ['category' => 'women-perfumes']) }}" class="transition hover:text-white">Women</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'men-perfumes']) }}" class="transition hover:text-white">Men</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'attars']) }}" class="transition hover:text-white">Attars</a></li>
                        <li><a href="{{ route('shop.index', ['category' => 'oud']) }}" class="transition hover:text-white">Oud</a></li>
                    </ul>
                </div>
                <div>
                    <p class="font-ui text-[10px] font-bold uppercase tracking-[0.38em] text-zinc-500">Service</p>
                    <p class="mt-4 text-sm text-zinc-400">White-glove shipping & authenticity guarantee.</p>
                    <p class="mt-3 text-xs text-zinc-600">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                </div>
            </div>
        </footer>

        {{-- Search overlay --}}
        <div
            x-show="searchOpen"
            x-transition.opacity.duration.300ms
            class="fixed inset-0 z-[200] flex items-start justify-center bg-black/75 px-4 pt-24 backdrop-blur-md sm:pt-32"
            x-cloak
            @click.self="searchOpen = false"
        >
            <div
                @click.stop
                class="w-full max-w-2xl rounded-2xl lux-glass-strong p-6 shadow-2xl sm:p-8"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 scale-[0.98]"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            >
                <p class="font-ui text-[10px] font-bold uppercase tracking-[0.4em] text-[#c9a227]">Search the house</p>
                <form action="{{ route('shop.index') }}" method="get" class="mt-4 flex gap-3">
                    <input name="search" type="search" autofocus placeholder="Oud, attar, rose absolue…" class="min-h-[52px] flex-1 rounded-xl border border-white/10 bg-black/50 px-5 text-sm text-white placeholder:text-zinc-600 focus:border-[#c9a227]/45 focus:outline-none">
                    <button type="submit" class="min-h-[52px] rounded-xl bg-[#c9a227] px-8 font-ui text-xs font-bold uppercase tracking-[0.15em] text-black transition hover:bg-[#e6cf5c]">Search</button>
                </form>
                <button type="button" class="mt-6 font-ui text-[11px] uppercase tracking-wider text-zinc-500 hover:text-white" @click="searchOpen = false">Close</button>
            </div>
        </div>

        {{-- Cart drawer --}}
        <div
            x-show="cartOpen"
            x-transition.opacity.duration.300ms
            class="fixed inset-0 z-[200] bg-black/60 backdrop-blur-sm"
            x-cloak
            @click.self="cartOpen = false"
        >
            <div
                @click.stop
                class="absolute right-0 top-0 flex h-full w-full max-w-md flex-col border-l border-white/[0.08] bg-[#08090d] shadow-[-24px_0_80px_rgba(0,0,0,0.85)]"
                x-transition:enter="transform transition ease-out duration-400"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
            >
                <div class="flex items-center justify-between border-b border-white/[0.06] px-6 py-5">
                    <p class="font-display text-xl text-white">Your bag</p>
                    <button type="button" class="rounded-full border border-white/10 p-2 text-zinc-400 hover:text-white" @click="cartOpen = false" aria-label="Close cart">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex flex-1 flex-col items-center justify-center gap-4 px-8 text-center">
                    <p class="text-sm text-zinc-500">Your selection is empty.</p>
                    <a href="{{ route('shop.index') }}" class="rounded-full bg-[#c9a227] px-8 py-3 font-ui text-xs font-bold uppercase tracking-[0.15em] text-black transition hover:bg-[#e6cf5c]">Continue shopping</a>
                </div>
                <div class="border-t border-white/[0.06] p-6">
                    <a href="{{ route('checkout') }}" class="flex min-h-[52px] w-full items-center justify-center rounded-xl border border-[#c9a227]/40 bg-[#c9a227]/10 font-ui text-xs font-bold uppercase tracking-[0.18em] text-[#e6cf5c] transition hover:bg-[#c9a227]/20">Secure checkout</a>
                </div>
            </div>
        </div>

        {{-- Mobile full menu --}}
        <div
            x-show="mobileMenu"
            class="fixed inset-0 z-[220] lg:hidden"
            x-cloak
            x-transition.opacity
        >
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="mobileMenu = false"></div>
            <div
                class="absolute inset-y-0 left-0 flex w-[min(92vw,380px)] flex-col border-r border-white/[0.08] bg-[#07080b] shadow-2xl"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-250"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
            >
                <div class="flex items-center justify-between border-b border-white/[0.06] px-5 py-4">
                    <span class="font-ui text-[10px] font-bold uppercase tracking-[0.35em] text-zinc-500">Menu</span>
                    <button type="button" class="p-2 text-zinc-400 hover:text-white" @click="mobileMenu = false">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <nav class="flex-1 overflow-y-auto px-5 py-6">
                    <a href="{{ route('home') }}" class="block border-b border-white/[0.05] py-4 font-ui text-sm font-medium uppercase tracking-wider text-white">Home</a>
                    <a href="{{ route('shop.index') }}" class="block border-b border-white/[0.05] py-4 font-ui text-sm font-medium uppercase tracking-wider text-zinc-300">Shop all</a>
                    <template x-for="(c, idx) in categories" :key="idx">
                        <a
                            :href="`{{ route('shop.index') }}?category=${c.slug}`"
                            class="block border-b border-white/[0.05] py-4 font-ui text-sm font-medium uppercase tracking-wider text-zinc-300"
                            x-text="c.name"
                            @click="mobileMenu = false"
                        ></a>
                    </template>
                </nav>
            </div>
        </div>

        {{-- Mobile bottom bar --}}
        <nav class="fixed bottom-0 left-0 right-0 z-[130] flex items-stretch justify-around border-t border-white/[0.08] bg-[#07080b]/95 px-1 py-2 backdrop-blur-xl lg:hidden safe-area-pb" aria-label="Mobile primary">
            <a href="{{ route('home') }}" class="flex flex-1 flex-col items-center gap-1 py-2 text-[10px] font-ui font-medium uppercase tracking-wider text-zinc-500">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/></svg>
                Home
            </a>
            <a href="{{ route('shop.index') }}" class="flex flex-1 flex-col items-center gap-1 py-2 text-[10px] font-ui font-medium uppercase tracking-wider text-zinc-500">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l-1 12H6L5 9z"/></svg>
                Shop
            </a>
            <button type="button" class="flex flex-1 flex-col items-center gap-1 py-2 text-[10px] font-ui font-medium uppercase tracking-wider text-zinc-500" @click="searchOpen = true; mobileMenu = false">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.34-4.34M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z"/></svg>
                Search
            </button>
            <button type="button" class="flex flex-1 flex-col items-center gap-1 py-2 text-[10px] font-ui font-medium uppercase tracking-wider text-[#c9a227]" @click="cartOpen = true; mobileMenu = false">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 0 0-8 0v4M5 9h14l-1 12H6L5 9z"/></svg>
                Bag
            </button>
            <a href="{{ route('wishlist') }}" class="flex flex-1 flex-col items-center gap-1 py-2 text-[10px] font-ui font-medium uppercase tracking-wider text-zinc-500">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                Saved
            </a>
        </nav>
    </div>

    @stack('scripts')
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .safe-area-pb { padding-bottom: max(0.5rem, env(safe-area-inset-bottom)); }
    </style>
</body>
</html>
