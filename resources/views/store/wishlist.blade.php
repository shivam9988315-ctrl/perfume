@extends('layouts.store')

@section('title', 'Wishlist — '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-[1100px] px-4 py-20 sm:px-6 lg:px-10">
        <p class="text-[10px] font-semibold uppercase tracking-[0.45em] text-[#c9a227]">Wishlist</p>
        <h1 class="mt-3 font-[family-name:var(--font-display)] text-4xl text-white sm:text-5xl">Saved for later</h1>
        <p class="mt-4 max-w-xl text-sm text-zinc-400">
            Sync your wishlist with an account via the REST API, or continue browsing the boutique — your selections deserve a quiet room of their own.
        </p>
        <div class="mt-14 rounded-[1.75rem] border border-dashed border-white/15 bg-[#08090c]/80 p-16 text-center">
            <p class="text-sm text-zinc-500">No saved items in this session.</p>
            <a href="{{ route('shop.index') }}" class="mt-8 inline-flex rounded-full border border-[#c9a227]/40 px-8 py-3 text-xs font-semibold uppercase tracking-[0.2em] text-[#e8d48b] transition hover:bg-[#c9a227]/10">
                Explore boutique
            </a>
        </div>
    </div>
@endsection
