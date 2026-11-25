<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // فهرست موجودی کالاهای فروشنده را نمایش می‌دهد.
    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        abort_unless($seller, 403);

        $products = Product::where('seller_id', $seller->id)
            ->select(['id', 'name', 'sku', 'stock', 'status'])
            ->orderBy('stock')
            ->paginate(20);

        return view('seller.inventory.index', compact('products'));
    }

    // موجودی را با اعتبارسنجی و محدودیت مالکیت به‌روزرسانی می‌کند.
    public function update(Request $request, Product $product)
    {
        $seller = $request->user()->seller;
        abort_unless($seller && (int) $product->seller_id === (int) $seller->id, 404);

        $data = $request->validate([
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
        ]);

        $product->update(['stock' => $data['stock']]);

        return back()->with('success', 'موجودی به‌روزرسانی شد.');
    }
}
