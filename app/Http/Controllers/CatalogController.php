<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::getOrderedCategories();
        $query = Product::with(['primaryImage', 'category', 'seller'])
            ->where('status', 'approved');

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $products = $query->latest()->paginate(12)->withQueryString();
        $selectedCategory = $request->category;

        return view('pages.catalog', compact('products', 'categories', 'selectedCategory'));
    }
}
