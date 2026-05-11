@props(['product'])
@php
    $img = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
    $src = $img && \Illuminate\Support\Facades\Storage::disk('public')->exists($img->path)
        ? \Illuminate\Support\Facades\Storage::url($img->path)
        : 'https://picsum.photos/seed/perfume-'.$product->id.'/600/800';
    $price = $product->variants->first()?->price ?? $product->base_price;
@endphp
<article {{ $attributes->merge(['class' => 'group relative flex flex-col overflow-hidden rounded-2xl border border-white/5 bg-zinc-900/40 shadow-[0_20px_60px_-30px_rgba(0,0,0,0.85)] transition hover:border-[#c9a227]/35 hover:shadow-[0_28px_80px_-36px_rgba(201,162,39,0.25)]']) }}>
    <a href="{{ route('shop.show', $product->slug) }}" class="block aspect-[3/4] overflow-hidden bg-zinc-950">
        <img src="{{ $src }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
        @if($product->is_on_sale)
            <span class="absolute left-3 top-3 rounded-full bg-[#c9a227] px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-black">Sale</span>
        @endif
    </a>
    <div class="flex flex-1 flex-col gap-2 p-4">
        @if($product->brand)
            <p class="text-[10px] uppercase tracking-[0.25em] text-zinc-500">{{ $product->brand->name }}</p>
        @endif
        <a href="{{ route('shop.show', $product->slug) }}" class="font-[family-name:var(--font-display)] text-lg leading-snug text-white transition group-hover:text-[#e8d48b]">
            {{ $product->name }}
        </a>
        <p class="mt-auto text-sm text-[#c9a227]">${{ number_format((float) $price, 2) }}</p>
    </div>
</article>
