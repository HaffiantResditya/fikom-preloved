<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $quickCategorySlugs = array_slice(Category::ORDERED_SLUGS, 0, 4);

        $categories = Cache::remember('home_categories_v1', 300, function () {
            return Category::getOrderedCategories();
        });

        $quickCategories = $categories->whereIn('slug', $quickCategorySlugs);

        $featuredProducts = Cache::remember('home_featured_products_v1', 60, function () {
            return Product::with(['primaryImage', 'images', 'category', 'seller'])
                ->where('status', 'approved')
                ->latest()
                ->take(10)
                ->get();
        });

        return view('pages.home', compact('categories', 'quickCategories', 'featuredProducts'));
    }

    public function categories()
    {
        $categories = Cache::remember('all_categories_approved_count_v1', 180, function () {
            return Category::getOrderedCategories(withApprovedCount: true);
        });

        return view('pages.categories', compact('categories'));
    }

    public function pendingNotice()
    {
        if (auth()->check() && auth()->user()->isSuspended()) {
            return redirect()->route('suspended.notice');
        }
        if (auth()->check() && auth()->user()->isApproved()) {
            return redirect()->route('home');
        }
        return view('pages.pending');
    }

    public function suspendedNotice()
    {
        if (auth()->check() && auth()->user()->isApproved()) {
            return redirect()->route('home');
        }
        return view('pages.suspended');
    }
}
