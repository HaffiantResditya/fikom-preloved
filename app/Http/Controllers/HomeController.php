<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $quickCategorySlugs = array_slice(Category::ORDERED_SLUGS, 0, 4);
        $categories = Category::getOrderedCategories();
        $quickCategories = $categories->whereIn('slug', $quickCategorySlugs);

        $featuredProducts = Product::with(['primaryImage', 'images', 'category', 'seller'])
            ->where('status', 'approved')
            ->latest()
            ->take(10)
            ->get();

        return view('pages.home', compact('categories', 'quickCategories', 'featuredProducts'));
    }

    public function categories()
    {
        $categories = Category::getOrderedCategories(withApprovedCount: true);

        return view('pages.categories', compact('categories'));
    }

    public function pendingNotice()
    {
        return view('pages.pending');
    }
}
