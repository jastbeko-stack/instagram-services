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
        $featuredUsernames = \Illuminate\Support\Facades\Cache::remember('home_featured_usernames', 180, function () {
            return InstagramUsername::where('status', 'available')
                ->latest()
                ->take(6)
                ->get();
        });

        $categories = \Illuminate\Support\Facades\Cache::remember('home_categories', 300, function () {
            return SmmCategory::where('status', true)
                ->with(['services' => function ($query) {
                    $query->where('status', true)->orderBy('price_per_1k');
                }])
                ->orderBy('sort_order')
                ->get();
        });

        $stats = \Illuminate\Support\Facades\Cache::remember('home_stats', 300, function () {
            return [
                'total_usernames_sold' => UsernameOrder::count() + 148,
                'total_smm_orders' => SmmOrder::count() + 1520,
                'available_usernames' => InstagramUsername::where('status', 'available')->count(),
                'active_services' => SmmService::where('status', true)->count(),
            ];
        });

        return response()
            ->view('home', compact('featuredUsernames', 'categories', 'stats'))
            ->header('Cache-Control', 'public, s-maxage=120, stale-while-revalidate=600');
    }
}
