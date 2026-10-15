<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private function getSettings(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    public function home()
    {
        $settings = $this->getSettings();

        $categories = Category::where('is_active', true)
            ->withCount([
                'products' => fn ($q) => $q->where('is_available', true)
            ])
            ->latest()
            ->take(8)
            ->get();

        $featuredProducts = Product::with(['category', 'images'])
            ->where('is_available', true)
            ->where('is_featured', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->latest()
            ->take(8)
            ->get();

        $products = Product::with(['category', 'images'])
            ->where('is_available', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->latest()
            ->take(8)
            ->get();

        return view('user.home', compact(
            'settings',
            'categories',
            'featuredProducts',
            'products'
        ));
    }

    public function allCategories(Request $request)
    {
        $settings = $this->getSettings();

        $query = Category::where('is_active', true);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query
            ->withCount([
                'products' => fn ($q) => $q->where('is_available', true)
            ])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('user.categories.allcategories', compact('settings', 'categories'));
    }

    public function allProducts(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer', 'min:1'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
            'sort' => ['nullable', 'in:latest,oldest,price_low,price_high,name'],
        ]);

        $settings = $this->getSettings();

        $query = Product::with(['category', 'images'])
            ->where('is_available', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true));

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        switch ($request->input('sort', 'latest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('user.products.allproducts', compact(
            'settings',
            'products',
            'categories'
        ));
    }

public function about()
{
    return view('user.about');
}


public function contact()
{
    $settings = Setting::pluck('value', 'key')->toArray();

    return view('user.contact', compact('settings'));
}

}