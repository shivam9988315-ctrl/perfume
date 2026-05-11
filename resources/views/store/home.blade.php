@extends('layouts.store')

@section('title', config('app.name').' — Luxury Perfumes')

@section('content')
    <section class="relative overflow-hidden">
        <div class="swiper-effect mx-auto max-w-7xl px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-24 lg:pt-16">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-[#c9a227]/90">Private blend atelier</p>
                    <h1 class="mt-4 font-[family-name:var(--font-display)] text-4xl leading-[1.05] text-white sm:text-5xl lg:text-6xl">
                        Perfume as <span class="text-[#e8d48b]">jewellery</span> for the senses
                    </h1>
                    <p class="mt-6 max-w-xl text-sm leading-relaxed text-zinc-400 sm:text-base">
                        Discover extrait-strength silhouettes, slow-aged bases, and luminous top notes — composed for longevity on skin and in memory.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#c9a227] px-8 py-3 text-sm font-medium text-black transition hover:bg-[#e8d48b]">
                            Shop collection
                        </a>
                        <a href="{{ route('shop.index', ['gender' => 'unisex']) }}" class="inline-flex items-center justify-center rounded-full border border-white/15 px-8 py-3 text-sm text-zinc-200 transition hover:border-[#c9a227]/50 hover:text-white">
                            Unisex edits
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-tr from-[#c9a227]/20 via-transparent to-white/5 blur-2xl"></div>
                    <div class="relative overflow-hidden rounded-[1.75rem] border border-white/10 shadow-2xl">
                        @forelse($banners as $banner)
                            <div class="relative aspect-[4/5] bg-zinc-900">
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists($banner->image_path) ? \Illuminate\Support\Facades\Storage::url($banner->image_path) : 'https://picsum.photos/seed/banner-'.$banner->id.'/900/1100' }}" alt="{{ $banner->title }}" class="h-full w-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-8">
                                    <p class="font-[family-name:var(--font-display)] text-3xl text-white">{{ $banner->title }}</p>
                                    @if($banner->subtitle)
                                        <p class="mt-2 text-sm text-zinc-300">{{ $banner->subtitle }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="flex aspect-[4/5] items-center justify-center bg-zinc-900 p-10 text-center text-zinc-500">
                                Add hero banners in the admin panel.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $rows = [
            ['title' => 'Featured', 'items' => $featured],
            ['title' => 'New arrivals', 'items' => $newArrivals],
            ['title' => 'Best sellers', 'items' => $bestSellers],
            ['title' => 'On sale', 'items' => $onSale],
        ];
    @endphp

    @foreach($rows as $row)
        @if($row['items']->isNotEmpty())
            <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between gap-4">
                    <h2 class="font-[family-name:var(--font-display)] text-3xl text-white">{{ $row['title'] }}</h2>
                    <a href="{{ route('shop.index') }}" class="text-xs uppercase tracking-[0.2em] text-[#c9a227] hover:text-[#e8d48b]">View all</a>
                </div>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($row['items'] as $product)
                        <x-store.product-card :product="$product" />
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach

    @if($brands->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <h2 class="font-[family-name:var(--font-display)] text-3xl text-white">Brands</h2>
            <div class="mt-8 flex flex-wrap gap-3">
                @foreach($brands as $brand)
                    <span class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-xs uppercase tracking-widest text-zinc-300">{{ $brand->name }}</span>
                @endforeach
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-white/10 bg-gradient-to-br from-zinc-900/80 to-black/80 p-8 sm:p-12">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div>
                    <h2 class="font-[family-name:var(--font-display)] text-3xl text-white">Notes from the atelier</h2>
                    <p class="mt-3 text-sm text-zinc-400">Join the list for launches, limited editions, and private sale access.</p>
                </div>
                <form action="{{ route('newsletter') }}" method="post" class="flex flex-col gap-3 sm:flex-row">
                    @csrf
                    <input type="email" name="email" required placeholder="Email address" class="w-full flex-1 rounded-full border border-white/10 bg-black/40 px-5 py-3 text-sm text-white outline-none ring-0 placeholder:text-zinc-600 focus:border-[#c9a227]/50">
                    <button type="submit" class="rounded-full bg-[#c9a227] px-8 py-3 text-sm font-medium text-black transition hover:bg-[#e8d48b]">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
