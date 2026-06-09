<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Services\AdminData;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(): View
    {
        return view('admin.newsletter.index', [
            'active' => 'newsletter',
            'subscribers' => NewsletterSubscriber::orderByDesc('subscribed_at')->get(),
            'badges' => AdminData::getNavBadges(),
        ]);
    }
}
