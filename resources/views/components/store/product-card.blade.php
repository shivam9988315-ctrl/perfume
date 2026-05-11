@props(['product'])
@php
    $img = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $src = $img && \Illuminate\Support\Facades\Storage::disk('public')->exists($img->path)
        ? \Illuminate\Support\Facades\Storage::url($img->path)
        : 'https://picsum.photos/seed/perfume-'.$product->id.'/700/900';
    $price = $product->variants->first()?->price ?? $product->base_price;
    $type = $product->product_type ?? 'perfume';
    $typeLabel = match ($type) {
        'attar' => 'Attar',
        'oud' => 'Oud',
        'arabic_collection' => 'Arabic',
        'luxury_collection' => 'Luxury',
        default => null,
    };
    $v0 = $product->variants->first();
    $quickAddHref = $v0 ? route('checkout', ['add' => $v0->id]) : route('shop.show', $product->slug);
@endphp
<article {{ $attributes->merge(['class' => 'lux-card group relative flex flex-col overflow-hidden rounded-2xl border border-white/[0.06] bg-[#0b0c10]/90']) }}>
    <div class="relative aspect-[3/4] overflow-hidden bg-zinc-950">
        <a href="{{ route('shop.show', $product->slug) }}" class="absolute inset-0 z-0 block">
            <img src="{{ $src }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-[1.1s] ease-out group-hover:scale-[1.06]" loading="lazy">
        </a>
        <div class="pointer-events-none absolute inset-0 z-[1] bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-80 transition duration-500 group-hover:opacity-100"></div>
        <div class="pointer-events-none absolute inset-0 z-[2] flex flex-col justify-between p-3">
            <div class="flex flex-wrap items-start justify-between gap-2">
                <div class="pointer-events-auto flex flex-wrap gap-1.5">
                    @if($product->is_on_sale ?? false)
                        <span class="rounded-full bg-[#c9a227] px-2.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-black">Sale</span>
                    @endif
                    @if($product->is_limited_edition ?? false)
                        <span class="rounded-full border border-[#e8d48b]/50 bg-black/60 px-2.5 py-0.5 text-[9px] font-semibold uppercase tracking-wider text-[#e8d48b]">Limited</span>
                    @endif
                    @if($typeLabel)
                        <span class="rounded-full border border-white/15 bg-black/50 px-2.5 py-0.5 text-[9px] font-medium uppercase tracking-wider text-zinc-200">{{ $typeLabel }}</span>
                    @endif
                </div>
                <a
                    href="{{ route('wishlist', ['for' => $product->slug]) }}"
                    class="pointer-events-auto flex h-10 w-10 items-center justify-center rounded-full border border-white/15 bg-black/55 text-zinc-300 backdrop-blur-md transition hover:border-[#c9a227]/45 hover:text-[#e6cf5c]"
                    title="Wishlist"
                    aria-label="Add to wishlist"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                </a>
            </div>
            <div class="pointer-events-auto translate-y-2 opacity-0 transition duration-500 group-hover:translate-y-0 group-hover:opacity-100">
                <a
                    href="{{ $quickAddHref }}"
                    class="flex min-h-[44px] w-full items-center justify-center rounded-full bg-[#c9a227] px-4 text-[10px] font-bold uppercase tracking-[0.14em] text-black shadow-lg shadow-black/40 transition hover:bg-[#e6cf5c]"
                >
                    Quick add
                </a>
            </div>
        </div>
    </div>
    <div class="relative flex flex-1 flex-col gap-2 p-5">
        @if($product->brand)
            <p class="text-[10px] font-semibold uppercase tracking-[0.28em] text-zinc-500">{{ $product->brand->name }}</p>
        @endif
        <a href="{{ route('shop.show', $product->slug) }}" class="font-[family-name:var(--font-display)] text-lg leading-snug text-white transition duration-300 group-hover:text-[#e8d48b] sm:text-xl">
            {{ $product->name }}
        </a>
        @if($v0 && $v0->volume_ml)
            <p class="text-[11px] text-zinc-500">{{ $v0->volume_ml }} ml · {{ $v0->name }}</p>
        @endif
        <p class="mt-auto pt-2 text-sm font-semibold tracking-wide text-[#c9a227]">${{ number_format((float) $price, 2) }}</p>
    </div>
</article>
