@extends('layouts.store')

@section('title', $product->meta_title ?: $product->name.' — '.config('app.name'))
@section('meta_description', $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($product->short_description ?? ''), 155))

@section('content')
    @php
        $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
        $mainSrc = $primary && \Illuminate\Support\Facades\Storage::disk('public')->exists($primary->path)
            ? \Illuminate\Support\Facades\Storage::url($primary->path)
            : 'https://picsum.photos/seed/p-'.$product->id.'/900/1120';
        $firstVariant = $product->variants->first();
    @endphp

    <div class="mx-auto max-w-[1600px] px-4 py-10 pb-40 sm:px-6 lg:px-10 lg:py-16 lg:pb-16">
        <nav class="text-[11px] uppercase tracking-[0.2em] text-zinc-500">
            <a href="{{ route('shop.index') }}" class="hover:text-white">Boutique</a>
            <span class="mx-2 text-zinc-700">/</span>
            @if($product->category)
                <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="hover:text-white">{{ $product->category->name }}</a>
                <span class="mx-2 text-zinc-700">/</span>
            @endif
            <span class="text-zinc-300">{{ $product->name }}</span>
        </nav>

        <div class="mt-10 grid gap-12 lg:grid-cols-12 lg:gap-10">
            {{-- Gallery + zoom --}}
            <div class="lg:col-span-6">
                <div data-pdp-zoom class="relative cursor-crosshair overflow-hidden rounded-[1.75rem] border border-white/[0.08] bg-zinc-950 shadow-[0_40px_120px_-50px_rgba(0,0,0,0.95)]">
                    <img data-pdp-zoom-img src="{{ $mainSrc }}" alt="{{ $product->name }}" class="aspect-[3/4] w-full object-cover transition-transform duration-300 ease-out will-change-transform">
                    <div class="pointer-events-none absolute right-4 top-4 rounded-full border border-white/10 bg-black/50 px-3 py-1 text-[9px] font-semibold uppercase tracking-widest text-zinc-400">Hover to magnify</div>
                </div>
                @if($product->images->count() > 1)
                    <div class="mt-4 flex gap-3 overflow-x-auto pb-1">
                        @foreach($product->images as $img)
                            @php
                                $thumb = \Illuminate\Support\Facades\Storage::disk('public')->exists($img->path)
                                    ? \Illuminate\Support\Facades\Storage::url($img->path)
                                    : $mainSrc;
                            @endphp
                            <button type="button" class="group relative h-24 w-20 shrink-0 overflow-hidden rounded-xl border border-white/10 ring-0 transition hover:border-[#c9a227]/50 focus:border-[#c9a227] focus:outline-none" data-pdp-thumb="{{ $thumb }}">
                                <img src="{{ $thumb }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Buy box (sticky on large screens) --}}
            <div class="lg:col-span-6 lg:sticky lg:top-28 lg:self-start">
                @if($product->brand)
                    <p class="text-[10px] font-semibold uppercase tracking-[0.4em] text-[#c9a227]">{{ $product->brand->name }}</p>
                @endif
                <h1 class="mt-3 font-[family-name:var(--font-display)] text-4xl leading-[1.05] text-white sm:text-5xl lg:text-6xl">
                    {{ $product->name }}
                </h1>
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    @if($product->is_limited_edition)
                        <span class="rounded-full border border-[#e8d48b]/40 bg-[#c9a227]/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-widest text-[#e8d48b]">Limited edition</span>
                    @endif
                    @if($product->fragrance_type)
                        <span class="text-xs text-zinc-500">{{ $product->fragrance_type }}</span>
                    @endif
                </div>
                @if($product->short_description)
                    <p class="mt-6 text-sm leading-relaxed text-zinc-400 sm:text-base">{{ $product->short_description }}</p>
                @endif

                <div id="pdp-variants" class="mt-10 space-y-3">
                    @foreach($product->variants as $variant)
                        <div class="flex flex-col gap-3 rounded-2xl border border-white/[0.08] bg-white/[0.03] p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-medium text-white">{{ $variant->name }}</p>
                                <p class="mt-1 text-[11px] text-zinc-500">
                                    SKU {{ $variant->sku }}
                                    @if($variant->volume_ml)
                                        · {{ $variant->volume_ml }} ml
                                    @endif
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <p class="text-lg font-semibold text-[#e8d48b]">${{ number_format((float) $variant->price, 2) }}</p>
                                <a href="{{ route('checkout', ['variant' => $variant->id]) }}" class="inline-flex min-h-[44px] items-center justify-center rounded-full bg-[#c9a227] px-6 text-xs font-bold uppercase tracking-[0.12em] text-black transition hover:bg-[#e6cf5c]">
                                    Buy now
                                </a>
                                <a href="{{ route('checkout', ['add' => $variant->id]) }}" class="inline-flex min-h-[44px] items-center justify-center rounded-full border border-white/15 px-6 text-xs font-semibold uppercase tracking-[0.12em] text-white transition hover:border-[#c9a227]/45 hover:text-[#e8d48b]">
                                    Add to bag
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 grid gap-4 sm:grid-cols-2">
                    <a href="{{ route('wishlist') }}" class="flex items-center justify-center gap-2 rounded-2xl border border-white/10 py-3 text-xs font-semibold uppercase tracking-[0.15em] text-zinc-300 transition hover:border-[#c9a227]/35 hover:text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                        Wishlist
                    </a>
                    <a href="{{ route('checkout') }}" class="flex items-center justify-center rounded-2xl border border-white/10 py-3 text-xs font-semibold uppercase tracking-[0.15em] text-zinc-400 transition hover:text-white">
                        Secure checkout
                    </a>
                </div>

                @if($product->top_notes || $product->middle_notes || $product->base_notes)
                    <div class="mt-14 border-t border-white/[0.06] pt-12">
                        <h2 class="font-[family-name:var(--font-display)] text-2xl text-white">Olfactory pyramid</h2>
                        <div class="mt-8 grid gap-6 sm:grid-cols-3">
                            @if($product->top_notes)
                                <div class="rounded-2xl border border-white/[0.06] bg-gradient-to-b from-white/[0.04] to-transparent p-6">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#c9a227]">Top</p>
                                    <p class="mt-3 text-sm leading-relaxed text-zinc-300">{{ $product->top_notes }}</p>
                                </div>
                            @endif
                            @if($product->middle_notes)
                                <div class="rounded-2xl border border-white/[0.06] bg-gradient-to-b from-white/[0.04] to-transparent p-6">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#c9a227]">Heart</p>
                                    <p class="mt-3 text-sm leading-relaxed text-zinc-300">{{ $product->middle_notes }}</p>
                                </div>
                            @endif
                            @if($product->base_notes)
                                <div class="rounded-2xl border border-white/[0.06] bg-gradient-to-b from-white/[0.04] to-transparent p-6">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.35em] text-[#c9a227]">Base</p>
                                    <p class="mt-3 text-sm leading-relaxed text-zinc-300">{{ $product->base_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if($product->description)
                    <div class="mt-14 border-t border-white/[0.06] pt-12">
                        <h2 class="font-[family-name:var(--font-display)] text-2xl text-white">The composition</h2>
                        <div class="prose prose-invert prose-sm mt-6 max-w-none text-zinc-400 prose-p:leading-relaxed">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                @endif

                @if($product->reviews->isNotEmpty())
                    <div class="mt-14 border-t border-white/[0.06] pt-12">
                        <h2 class="font-[family-name:var(--font-display)] text-2xl text-white">Reviews</h2>
                        <ul class="mt-6 space-y-4">
                            @foreach($product->reviews as $rev)
                                <li class="rounded-2xl border border-white/[0.06] bg-black/30 p-5">
                                    <div class="flex items-center gap-2 text-[#c9a227]">
                                        @for($i = 0; $i < $rev->rating; $i++)<span>★</span>@endfor
                                    </div>
                                    <p class="mt-2 text-sm text-zinc-300">{{ $rev->comment }}</p>
                                    <p class="mt-2 text-xs text-zinc-600">{{ $rev->user?->name }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        @if($related->isNotEmpty())
            <section class="mt-28 border-t border-white/[0.06] pt-20">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <h2 class="font-[family-name:var(--font-display)] text-3xl text-white">Related compositions</h2>
                    <p class="text-xs uppercase tracking-[0.2em] text-zinc-500">Swipe to explore</p>
                </div>
                <div class="related-scroller mt-10 -mx-4 px-4 sm:-mx-6 sm:px-6 lg:-mx-10 lg:px-10">
                    @foreach($related as $rp)
                        <div class="flex h-full w-[min(86vw,300px)] flex-col sm:w-[min(42vw,320px)] lg:w-[280px]">
                            <x-store.product-card data-lux-card class="h-full flex-1" :product="$rp" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    @if($firstVariant)
        <div
            class="fixed left-0 right-0 z-[125] border-t border-white/[0.08] bg-[#07080b]/95 px-4 py-3 backdrop-blur-2xl lg:hidden [bottom:calc(3.65rem+env(safe-area-inset-bottom,0px))]"
        >
            <div class="mx-auto flex max-w-[1600px] items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="truncate font-[family-name:var(--font-display)] text-sm text-white">{{ $product->name }}</p>
                    <p class="mt-0.5 font-ui text-lg font-semibold text-[#e8d48b]">${{ number_format((float) $firstVariant->price, 2) }}</p>
                </div>
                <a
                    href="{{ route('checkout', ['add' => $firstVariant->id]) }}"
                    class="inline-flex min-h-[48px] shrink-0 items-center justify-center rounded-full bg-[#c9a227] px-7 text-[11px] font-bold uppercase tracking-[0.12em] text-black transition hover:bg-[#e6cf5c]"
                >
                    Add to bag
                </a>
            </div>
        </div>
    @endif
@endsection
