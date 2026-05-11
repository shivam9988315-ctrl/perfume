@extends('layouts.store')

@section('title', config('app.name').' — Luxury Perfumes & Attars')

@section('content')
    @php
        $imgUrl = function (?string $path, string $fallbackSeed): string {
            if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                return \Illuminate\Support\Facades\Storage::url($path);
            }
            return 'https://picsum.photos/seed/'.$fallbackSeed.'/1920/1080';
        };
    @endphp

    {{-- Full-width hero slider --}}
    <section data-hero-slider class="relative h-[min(92svh,940px)] w-full overflow-hidden bg-[#030304]">
        @forelse($banners as $banner)
            <div data-hero-slide class="hero-slide {{ $loop->first ? 'is-active' : '' }}">
                <img
                    src="{{ $imgUrl($banner->image_path, 'hero-'.$banner->id) }}"
                    alt="{{ $banner->title }}"
                    class="h-full w-full object-cover object-center scale-[1.03] transition-transform duration-[12s] ease-linear hover:scale-105"
                    fetchpriority="{{ $loop->first ? 'high' : 'low' }}"
                    loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/75 to-black/20 sm:via-black/55"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-black/30"></div>
                <div class="absolute inset-0 flex items-end">
                    <div class="mx-auto w-full max-w-[1600px] px-4 pb-20 pt-32 sm:px-6 lg:px-10 lg:pb-28">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.55em] text-[#c9a227] sm:text-[11px]">Est. atelier — limited batches</p>
                        <h1 class="mt-5 max-w-3xl font-[family-name:var(--font-display)] text-4xl leading-[1.05] text-white sm:text-5xl lg:text-6xl xl:text-7xl">
                            {{ $banner->title }}
                        </h1>
                        @if($banner->subtitle)
                            <p class="mt-5 max-w-xl text-sm leading-relaxed text-zinc-300 sm:text-base">{{ $banner->subtitle }}</p>
                        @endif
                        <div class="mt-10 flex flex-wrap gap-4">
                            <a href="{{ $banner->link_url ?: route('shop.index') }}" class="inline-flex min-h-[48px] items-center justify-center rounded-full bg-[#c9a227] px-10 text-sm font-semibold uppercase tracking-[0.12em] text-black transition hover:bg-[#e6cf5c]">
                                Discover
                            </a>
                            <a href="{{ route('shop.index', ['category' => 'attars']) }}" class="inline-flex min-h-[48px] items-center justify-center rounded-full border border-white/20 px-10 text-sm font-medium text-white transition hover:border-[#c9a227]/50 hover:text-[#e8d48b]">
                                Attar atelier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div data-hero-slide class="hero-slide is-active flex items-center justify-center bg-[#0a0a0c]">
                <p class="text-zinc-500">Upload hero banners in admin.</p>
            </div>
        @endforelse

        @if($banners->count() > 1)
            <button type="button" data-hero-prev class="absolute left-4 top-1/2 z-[2] hidden -translate-y-1/2 rounded-full border border-white/15 bg-black/40 p-3 text-white backdrop-blur-md transition hover:border-[#c9a227]/40 hover:text-[#e8d48b] md:inline-flex" aria-label="Previous slide">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            </button>
            <button type="button" data-hero-next class="absolute right-4 top-1/2 z-[2] hidden -translate-y-1/2 rounded-full border border-white/15 bg-black/40 p-3 text-white backdrop-blur-md transition hover:border-[#c9a227]/40 hover:text-[#e8d48b] md:inline-flex" aria-label="Next slide">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
            </button>
            <div class="absolute bottom-8 left-1/2 z-[2] flex -translate-x-1/2 gap-2">
                @foreach($banners as $b)
                    <button type="button" data-hero-dot class="h-1.5 w-8 rounded-full transition {{ $loop->first ? 'bg-amber-500' : 'bg-white/25' }}" aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            </div>
        @endif
    </section>

    @php
        $productGrid = fn (string $title, string $subtitle, $items, string $href) => compact('title', 'subtitle', 'items', 'href');
        $grids = array_filter([
            $productGrid('Best sellers', 'Most loved worldwide', $bestSellers, route('shop.index', ['bestseller' => 1])),
            $productGrid('New arrivals', 'Fresh from maceration', $newArrivals, route('shop.index', ['new' => 1])),
            $productGrid('Exclusive attars', 'Oil parfums — one drop, hours of sillage', $exclusiveAttars, route('shop.index', ['category' => 'attars'])),
            $productGrid('Oud collection', 'Resinous depth & majlis silence', $oudCollection, route('shop.index', ['category' => 'oud'])),
            $productGrid('Arabic fragrances', 'Heritage blends & modern couture', $arabicFragrances, route('shop.index', ['category' => 'arabic-collection'])),
            $productGrid('Limited edition', 'Numbered releases', $limitedEdition, route('shop.index', ['limited' => 1])),
        ], fn ($g) => $g['items']->isNotEmpty());
    @endphp

    @foreach($grids as $block)
        <section class="lux-reveal mx-auto max-w-[1600px] px-4 py-20 sm:px-6 lg:px-10">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.4em] text-[#c9a227]/90">{{ $block['subtitle'] }}</p>
                    <h2 class="mt-2 font-[family-name:var(--font-display)] text-3xl text-white sm:text-4xl">{{ $block['title'] }}</h2>
                </div>
                <a href="{{ $block['href'] }}" class="lux-underline text-xs font-semibold uppercase tracking-[0.25em] text-zinc-400 hover:text-white">View collection</a>
            </div>
            <div class="mt-12 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($block['items'] as $product)
                    <x-store.product-card data-lux-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endforeach

    @if($onSale->isNotEmpty())
        <section class="lux-reveal border-y border-white/[0.05] bg-gradient-to-b from-[#0a0b0f] to-transparent py-20">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-10">
                <div class="flex items-end justify-between gap-4">
                    <h2 class="font-[family-name:var(--font-display)] text-3xl text-white sm:text-4xl">Seasonal offers</h2>
                    <a href="{{ route('shop.index') }}" class="text-xs font-semibold uppercase tracking-[0.2em] text-[#c9a227] hover:text-[#e8d48b]">Shop sale</a>
                </div>
                <div class="mt-12 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach($onSale as $product)
                        <x-store.product-card data-lux-card :product="$product" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Brand story + Arabic luxury --}}
    <section class="lux-reveal relative py-24">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(201,162,39,0.06),transparent_45%)]"></div>
        <div class="mx-auto grid max-w-[1600px] gap-14 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:gap-20 lg:px-10">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.45em] text-zinc-500">Maison story</p>
                <h2 class="mt-4 font-[family-name:var(--font-display)] text-3xl leading-tight text-white sm:text-4xl lg:text-5xl">
                    Where <span class="text-[#e8d48b]">Arabic majesty</span> meets European couture
                </h2>
                <p class="mt-6 text-sm leading-relaxed text-zinc-400 sm:text-base">
                    Our compositions honor the majlis tradition — depth, courtesy, and silence — then tailor them for global evenings: precise extrait dosages, immaculate glass, and small-batch maceration.
                </p>
            </div>
            <div dir="rtl" class="rounded-3xl border border-[#c9a227]/20 bg-gradient-to-br from-[#12141a] to-black/80 p-8 sm:p-10">
                <p class="font-[family-name:var(--font-arabic)] text-2xl leading-relaxed text-[#e8d48b] sm:text-3xl">
                    العطرُ لُغةٌ بلا كلمات — نصنعُها بذهبٍ وصبرٍ وذاكرةِ المساء
                </p>
                <p class="mt-4 text-left text-sm text-zinc-500 ltr:text-right" dir="ltr">“Perfume is a language without words — we craft it with gold, patience, and the memory of evening.”</p>
            </div>
        </div>
    </section>

    @if($testimonials->isNotEmpty())
        <section class="lux-reveal py-24">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-10">
                <h2 class="font-[family-name:var(--font-display)] text-3xl text-white sm:text-4xl">Client voices</h2>
                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    @foreach($testimonials as $t)
                        <blockquote class="lux-card flex flex-col rounded-2xl border border-white/[0.06] bg-[#0c0d11]/80 p-8">
                            <div class="flex gap-1 text-[#c9a227]">
                                @for($i = 0; $i < min(5, (int) $t->rating); $i++)
                                    <span aria-hidden="true">★</span>
                                @endfor
                            </div>
                            <p class="mt-5 text-sm leading-relaxed text-zinc-300">“{{ $t->quote }}”</p>
                            <footer class="mt-6 border-t border-white/5 pt-5 text-xs uppercase tracking-[0.2em] text-zinc-500">
                                <span class="text-white">{{ $t->name }}</span>
                                @if($t->location)
                                    <span class="text-zinc-600"> — {{ $t->location }}</span>
                                @endif
                            </footer>
                        </blockquote>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Instagram-style gallery --}}
    <section class="lux-reveal py-20">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-10">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.4em] text-[#c9a227]">@maison moments</p>
                    <h2 class="mt-2 font-[family-name:var(--font-display)] text-3xl text-white">The atelier in frames</h2>
                </div>
                <span class="text-xs text-zinc-500">Curated visuals</span>
            </div>
            <div class="mt-10 grid grid-cols-2 gap-2 sm:grid-cols-3 md:gap-3 lg:grid-cols-6">
                @foreach(range(1, 6) as $i)
                    <a href="{{ route('shop.index') }}" class="group relative aspect-square overflow-hidden rounded-xl border border-white/[0.06] bg-zinc-900">
                        <img src="https://picsum.photos/seed/lux-flacon-ig-{{ $i }}/600/600" alt="" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                        <div class="pointer-events-none absolute inset-0 bg-black/0 transition group-hover:bg-black/30"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 transition group-hover:opacity-100">
                            <span class="rounded-full border border-white/30 bg-black/50 px-3 py-1 text-[10px] uppercase tracking-widest text-white">View</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if($brands->isNotEmpty())
        <section class="lux-reveal border-t border-white/[0.05] py-16">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-10">
                <h2 class="font-[family-name:var(--font-display)] text-2xl text-white">Partner maisons</h2>
                <div class="mt-8 flex flex-wrap gap-3">
                    @foreach($brands as $brand)
                        <span class="rounded-full border border-white/10 bg-white/[0.03] px-5 py-2.5 text-[11px] font-medium uppercase tracking-[0.2em] text-zinc-300">{{ $brand->name }}</span>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="lux-reveal mx-auto max-w-[1600px] px-4 py-24 sm:px-6 lg:px-10">
        <div class="relative overflow-hidden rounded-[2rem] border border-[#c9a227]/20 bg-gradient-to-br from-[#14161d] via-[#0a0b0f] to-black p-10 sm:p-14 lg:p-16">
            <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-[#c9a227]/10 blur-3xl"></div>
            <div class="relative grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <h2 class="font-[family-name:var(--font-display)] text-3xl text-white sm:text-4xl">Private list</h2>
                    <p class="mt-4 max-w-md text-sm leading-relaxed text-zinc-400">Limited drops, attar previews, and invitation-only evenings — delivered quietly to your inbox.</p>
                </div>
                <form action="{{ route('newsletter') }}" method="post" class="flex flex-col gap-4 sm:flex-row sm:items-stretch">
                    @csrf
                    <input type="email" name="email" required placeholder="Email address" class="min-h-[52px] w-full flex-1 rounded-2xl border border-white/10 bg-black/50 px-6 text-sm text-white outline-none placeholder:text-zinc-600 focus:border-[#c9a227]/45">
                    <button type="submit" class="min-h-[52px] shrink-0 rounded-2xl bg-[#c9a227] px-10 text-sm font-semibold uppercase tracking-[0.15em] text-black transition hover:bg-[#e6cf5c]">
                        Join
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
