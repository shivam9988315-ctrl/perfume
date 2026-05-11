@extends('layouts.store')

@section('title', 'Checkout — '.config('app.name'))

@section('content')
    <div class="mx-auto max-w-[1100px] px-4 py-16 sm:px-6 lg:px-10">
        <p class="text-[10px] font-semibold uppercase tracking-[0.45em] text-[#c9a227]">Checkout</p>
        <h1 class="mt-3 font-[family-name:var(--font-display)] text-4xl text-white sm:text-5xl">Refined to the last detail</h1>
        <p class="mt-4 max-w-2xl text-sm leading-relaxed text-zinc-400">
            This screen previews the luxury checkout experience. Wire it to your cart session or the REST API for Stripe, PayPal, and COD — tax and shipping from your store rules apply at payment.
        </p>

        @if(request()->filled('variant') || request()->filled('add'))
            <div class="mt-8 rounded-2xl border border-[#c9a227]/25 bg-[#c9a227]/5 px-5 py-4 text-sm text-[#e8d48b]">
                @if(request()->filled('variant'))
                    Selected variant ID: <strong class="text-white">{{ request('variant') }}</strong> — <em>Buy now</em> flow.
                @else
                    Add to bag — variant ID: <strong class="text-white">{{ request('add') }}</strong>
                @endif
            </div>
        @endif

        <div class="mt-14 grid gap-10 lg:grid-cols-5">
            <div class="lg:col-span-3 space-y-6">
                <div class="rounded-[1.5rem] border border-white/[0.08] bg-[#0a0b0f]/90 p-8">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.25em] text-zinc-500">Delivery</h2>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <input type="text" readonly placeholder="Full name" class="rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-zinc-500" value="">
                        <input type="email" readonly placeholder="Email" class="rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-zinc-500">
                        <input type="text" readonly placeholder="City" class="rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-sm text-zinc-500 sm:col-span-2">
                    </div>
                    <p class="mt-4 text-xs text-zinc-600">Connect these fields to your checkout controller or SPA consuming <code class="text-zinc-500">POST /api/v1/orders</code>.</p>
                </div>
                <div class="rounded-[1.5rem] border border-white/[0.08] bg-[#0a0b0f]/90 p-8">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.25em] text-zinc-500">Payment</h2>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <span class="rounded-full border border-[#c9a227]/40 bg-[#c9a227]/10 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-[#e8d48b]">Stripe</span>
                        <span class="rounded-full border border-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-zinc-400">PayPal</span>
                        <span class="rounded-full border border-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-zinc-400">COD</span>
                    </div>
                </div>
            </div>
            <aside class="lg:col-span-2">
                <div class="sticky top-28 rounded-[1.5rem] border border-white/[0.08] bg-gradient-to-b from-[#12141c] to-black p-8">
                    <h2 class="text-xs font-semibold uppercase tracking-[0.25em] text-zinc-500">Summary</h2>
                    <dl class="mt-6 space-y-3 text-sm text-zinc-400">
                        <div class="flex justify-between"><dt>Subtotal</dt><dd class="text-white">—</dd></div>
                        <div class="flex justify-between"><dt>Shipping</dt><dd class="text-white">Calculated</dd></div>
                        <div class="flex justify-between"><dt>Tax</dt><dd class="text-white">Per region</dd></div>
                    </dl>
                    <div class="mt-8 border-t border-white/10 pt-6">
                        <p class="text-xs uppercase tracking-widest text-zinc-500">Total</p>
                        <p class="mt-1 font-[family-name:var(--font-display)] text-3xl text-[#e8d48b]">—</p>
                    </div>
                    <p class="mt-6 text-[11px] leading-relaxed text-zinc-600">
                        Guest checkout, coupons, and confirmation emails are supported via the API layer — align this UI with your cart state.
                    </p>
                </div>
            </aside>
        </div>
    </div>
@endsection
