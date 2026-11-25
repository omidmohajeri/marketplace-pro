<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductApiController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(
            Product::query()->where('status', 'active')->with(['category', 'seller'])
                ->latest()->paginate(20)
        );
    }

    public function show(string $slug): ProductResource
    {
        $product = Product::query()->where('slug', $slug)->where('status', 'active')
            ->with(['category', 'seller'])->firstOrFail();

        return new ProductResource($product);
    }
}
