@extends('layouts.store')

@section('title', 'Boutique — '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-[1600px] px-4 py-14 sm:px-6 lg:px-10">
        <div class="max-w-3xl">
            <p class="text-[10px] font-semibold uppercase tracking-[0.45em] text-[#c9a227]">Boutique</p>
            <h1 class="mt-3 font-[family-name:var(--font-display)] text-4xl text-white sm:text-5xl">The collection</h1>
            <p class="mt-4 text-sm leading-relaxed text-zinc-400 sm:text-base">Perfumes, attars, and oud — filtered with the same rigor we use in the atelier.</p>
        </div>

        <form method="get" class="mt-10 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:flex-wrap">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search by name or SKU…" class="min-h-[48px] min-w-[220px] flex-1 rounded-2xl border border-white/10 bg-black/40 px-5 text-sm text-white placeholder:text-zinc-600 focus:border-[#c9a227]/45 focus:outline-none">
                <select name="category" class="min-h-[48px] rounded-2xl border border-white/10 bg-black/40 px-4 text-sm text-white focus:border-[#c9a227]/45 focus:outline-none">
                    <option value="">All categories</option>
                    @foreach($categoryPills as $cat)
                        <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="gender" class="min-h-[48px] rounded-2xl border border-white/10 bg-black/40 px-4 text-sm text-white focus:border-[#c9a227]/45 focus:outline-none">
                    <option value="">All genders</option>
                    <option value="women" @selected(request('gender') === 'women')>Women</option>
                    <option value="men" @selected(request('gender') === 'men')>Men</option>
                    <option value="unisex" @selected(request('gender') === 'unisex')>Unisex</option>
                </select>
                <select name="type" class="min-h-[48px] rounded-2xl border border-white/10 bg-black/40 px-4 text-sm text-white focus:border-[#c9a227]/45 focus:outline-none">
                    <option value="">All types</option>
                    <option value="perfume" @selected(request('type') === 'perfume')>Perfume</option>
                    <option value="attar" @selected(request('type') === 'attar')>Attar</option>
                    <option value="oud" @selected(request('type') === 'oud')>Oud</option>
                    <option value="arabic_collection" @selected(request('type') === 'arabic_collection')>Arabic collection</option>
                    <option value="luxury_collection" @selected(request('type') === 'luxury_collection')>Luxury collection</option>
                </select>
                <label class="flex min-h-[48px] cursor-pointer items-center gap-2 rounded-2xl border border-white/10 bg-black/40 px-4 text-sm text-zinc-300">
                    <input type="checkbox" name="limited" value="1" class="rounded border-white/20 bg-black text-[#c9a227] focus:ring-[#c9a227]" @checked(request('limited'))>
                    Limited only
                </label>
            </div>
            <button type="submit" class="min-h-[48px] shrink-0 rounded-2xl bg-[#c9a227] px-8 text-sm font-semibold uppercase tracking-[0.12em] text-black transition hover:bg-[#e6cf5c]">
                Apply
            </button>
        </form>

        <div class="mt-4 flex flex-wrap gap-2">
            @foreach($categoryPills as $cat)
                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="rounded-full border border-white/10 px-4 py-1.5 text-[11px] uppercase tracking-wider text-zinc-400 transition hover:border-[#c9a227]/35 hover:text-white {{ request('category') === $cat->slug ? 'border-[#c9a227]/50 bg-[#c9a227]/10 text-[#e8d48b]' : '' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <div class="mt-14 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($products as $product)
                <x-store.product-card data-lux-card :product="$product" />
            @empty
                <p class="col-span-full py-20 text-center text-zinc-500">No compositions match these filters.</p>
            @endforelse
        </div>

        <div class="mt-14 border-t border-white/5 pt-10">
            {{ $products->links() }}
        </div>
    </div>
@endsection
