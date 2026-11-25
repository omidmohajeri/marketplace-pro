<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function home(): View
    {
        $products = Product::query()->where('status', 'active')
            ->with(['category', 'seller'])->latest()->take(12)->get();

        return view('store.home', compact('products'));
    }

    public function products(Request $request): View
    {
        $query = Product::query()->where('status', 'active')->with(['category', 'seller']);

        if ($request->filled('q')) {
            $term = trim($request->string('q')->toString());
            $query->where(function ($q) use ($term) {
                $q->where('name->fa', 'like', "%{$term}%")
                  ->orWhere('name->en', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category')));
        }

        $products = $query->latest()->paginate(24)->withQueryString();
        return view('store.products', compact('products'));
    }

    public function product(string $slug): View
    {
        $product = Product::query()->where('slug', $slug)
            ->where('status', 'active')->with(['category', 'seller'])->firstOrFail();

        return view('store.product', compact('product'));
    }
}
