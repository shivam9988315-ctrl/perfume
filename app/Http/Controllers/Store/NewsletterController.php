<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        NewsletterSubscriber::query()->firstOrCreate(
            ['email' => $data['email']],
            ['subscribed_at' => now()]
        );

        return back()->with('status', 'Thank you for subscribing.');
    }
}
