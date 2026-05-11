@extends('layouts.store')

@section('title', $product->meta_title ?: $product->name.' — '.config('app.name'))
@section('meta_description', $product->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($product->short_description ?? ''), 155))

@section('content')
    @php
        $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
        $mainSrc = $primary && \Illuminate\Support\Facades\Storage::disk('public')->exists($primary->path)
            ? \Illuminate\Support\Facades\Storage::url($primary->path)
            : 'https://picsum.photos/seed/p-'.$product->id.'/800/1000';
    @endphp
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-2">
            <div class="space-y-4">
                <div class="overflow-hidden rounded-3xl border border-white/10 bg-zinc-950">
                    <img src="{{ $mainSrc }}" alt="{{ $product->name }}" class="w-full object-cover" id="product-main-img">
                </div>
                @if($product->images->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-1">
                        @foreach($product->images as $img)
                            @php
                                $thumb = \Illuminate\Support\Facades\Storage::disk('public')->exists($img->path)
                                    ? \Illuminate\Support\Facades\Storage::url($img->path)
                                    : $mainSrc;
                            @endphp
                            <button type="button" class="h-20 w-16 shrink-0 overflow-hidden rounded-lg border border-white/10 focus:border-[#c9a227]" data-zoom-src="{{ $thumb }}" onclick="document.getElementById('product-main-img').src=this.dataset.zoomSrc">
                                <img src="{{ $thumb }}" alt="" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            <div>
                @if($product->brand)
                    <p class="text-xs uppercase tracking-[0.35em] text-[#c9a227]">{{ $product->brand->name }}</p>
                @endif
                <h1 class="mt-3 font-[family-name:var(--font-display)] text-4xl text-white lg:text-5xl">{{ $product->name }}</h1>
                @if($product->short_description)
                    <p class="mt-4 text-sm leading-relaxed text-zinc-400">{{ $product->short_description }}</p>
                @endif
                <dl class="mt-8 grid gap-3 text-sm text-zinc-400">
                    @if($product->fragrance_type)
                        <div class="flex justify-between border-b border-white/5 py-2"><dt>Fragrance</dt><dd class="text-zinc-200">{{ $product->fragrance_type }}</dd></div>
                    @endif
                    @if($product->gender)
                        <div class="flex justify-between border-b border-white/5 py-2"><dt>Gender</dt><dd class="text-zinc-200">{{ ucfirst($product->gender) }}</dd></div>
                    @endif
                </dl>
                <div class="mt-10 space-y-4">
                    @foreach($product->variants as $variant)
                        <div class="flex items-center justify-between rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                            <div>
                                <p class="text-sm text-white">{{ $variant->name }}</p>
                                <p class="text-xs text-zinc-500">SKU {{ $variant->sku }}</p>
                            </div>
                            <p class="text-[#c9a227]">${{ number_format((float) $variant->price, 2) }}</p>
                        </div>
                    @endforeach
                </div>
                <p class="mt-8 text-xs text-zinc-500">Checkout, wishlist, and payments are available via the REST API and admin workflows (Stripe, PayPal, COD).</p>
            </div>
        </div>

        @if($related->isNotEmpty())
            <section class="mt-20">
                <h2 class="font-[family-name:var(--font-display)] text-2xl text-white">You may also like</h2>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($related as $rp)
                        <x-store.product-card :product="$rp" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
