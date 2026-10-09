<?php

namespace App\Http\Controllers;

use App\Models\InstagramUsername;
use App\Models\SmmCategory;
use App\Models\SmmOrder;
use App\Models\SmmService;
use App\Models\UsernameOrder;

class HomeController extends Controller
{
    public function index()
    {
        $featuredUsernames = InstagramUsername::where('status', 'available')
            ->latest()
            ->take(6)
            ->get();

        $categories = SmmCategory::where('status', true)
            ->with(['services' => function ($query) {
                $query->where('status', true)->orderBy('price_per_1k');
            }])
            ->orderBy('sort_order')
            ->get();

        $stats = [
            'total_usernames_sold' => UsernameOrder::count() + 148,
            'total_smm_orders' => SmmOrder::count() + 1520,
            'available_usernames' => InstagramUsername::where('status', 'available')->count(),
            'active_services' => SmmService::where('status', true)->count(),
        ];

        return view('home', compact('featuredUsernames', 'categories', 'stats'));
    }
}
