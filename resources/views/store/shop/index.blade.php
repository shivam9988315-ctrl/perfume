@extends('layouts.store')

@section('title', 'Shop — '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="font-[family-name:var(--font-display)] text-4xl text-white">Shop</h1>
        <form method="get" class="mt-8 flex flex-wrap gap-3">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search" class="min-w-[200px] flex-1 rounded-full border border-white/10 bg-black/40 px-5 py-2.5 text-sm text-white placeholder:text-zinc-600 focus:border-[#c9a227]/50">
            <select name="gender" class="rounded-full border border-white/10 bg-black/40 px-4 py-2.5 text-sm text-white focus:border-[#c9a227]/50">
                <option value="">All genders</option>
                <option value="women" @selected(request('gender')==='women')>Women</option>
                <option value="men" @selected(request('gender')==='men')>Men</option>
                <option value="unisex" @selected(request('gender')==='unisex')>Unisex</option>
            </select>
            <button type="submit" class="rounded-full bg-[#c9a227] px-6 py-2.5 text-sm font-medium text-black">Filter</button>
        </form>
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($products as $product)
                <x-store.product-card :product="$product" />
            @endforeach
        </div>
        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </div>
@endsection
