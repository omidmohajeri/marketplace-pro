<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// این کنترلر سبد خرید کاربران واردشده و مهمان را مدیریت می‌کند.
class CartController extends Controller
{
    // سبد فعال را با اقلام و اطلاعات محصول نمایش می‌دهد.
    public function index(Request $request)
    {
        $cart = $this->resolveCart($request);
        $cart->load('items.product');

        return view('cart.index', compact('cart'));
    }

    // محصول را با کنترل موجودی به سبد خرید اضافه می‌کند.
    public function add(Request $request, Product $product)
    {
        $data = $request->validate(['quantity' => ['nullable', 'integer', 'min:1', 'max:20']]);
        $quantity = $data['quantity'] ?? 1;

        // فقط محصولات فعال و دارای موجودی قابل خرید هستند.
        abort_unless($product->status === 'active' && $product->stock > 0, 404);

        DB::transaction(function () use ($request, $product, $quantity) {
            // ردیف محصول برای جلوگیری از فروش بیش از موجودی قفل می‌شود.
            $lockedProduct = Product::query()->lockForUpdate()->findOrFail($product->id);

            // سبد متعلق به کاربر یا نشست مهمان پیدا می‌شود.
            $cart = $this->resolveCart($request);

            // تعداد قبلی و جدید با هم جمع می‌شوند.
            $item = CartItem::firstOrNew(['cart_id' => $cart->id, 'product_id' => $lockedProduct->id]);
            $newQuantity = (int) $item->quantity + $quantity;

            // اگر موجودی کافی نباشد، تراکنش با خطا متوقف می‌شود.
            if ($newQuantity > $lockedProduct->stock) {
                abort(422, 'تعداد درخواستی بیشتر از موجودی انبار است.');
            }

            // تعداد نهایی در سبد ذخیره می‌شود.
            $item->quantity = $newQuantity;
            $item->save();
        });

        // پس از افزودن موفق، کاربر به سبد خرید منتقل می‌شود.
        return redirect()->route('cart.index')->with('success', 'محصول به سبد خرید اضافه شد.');
    }

    // تعداد یک قلم سبد خرید را تغییر می‌دهد.
    public function update(Request $request, CartItem $item)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:20']]);
        $cart = $this->resolveCart($request);

        // مالکیت قلم و موجودی محصول بررسی می‌شود.
        abort_unless($item->cart_id === $cart->id, 404);
        abort_if($data['quantity'] > $item->product->stock, 422, 'موجودی کافی نیست.');

        // تعداد جدید ذخیره می‌شود.
        $item->update(['quantity' => $data['quantity']]);

        return back()->with('success', 'سبد خرید به‌روزرسانی شد.');
    }

    // یک قلم را از سبد خرید حذف می‌کند.
    public function remove(Request $request, CartItem $item)
    {
        $cart = $this->resolveCart($request);

        // کاربر فقط اجازه حذف قلم متعلق به سبد خودش را دارد.
        abort_unless($item->cart_id === $cart->id, 404);

        $item->delete();

        return back()->with('success', 'محصول از سبد حذف شد.');
    }

    // سبد خرید جاری را برای کاربر واردشده یا مهمان برمی‌گرداند.
    private function resolveCart(Request $request): Cart
    {
        // برای کاربر واردشده، سبد بر اساس شناسه کاربر انتخاب می‌شود.
        if ($request->user()) {
            return Cart::firstOrCreate(['user_id' => $request->user()->id], ['currency' => 'IRR']);
        }

        // برای مهمان، شناسه نشست به عنوان کلید سبد استفاده می‌شود.
        $sessionId = $request->session()->getId();

        return Cart::firstOrCreate(['session_id' => $sessionId, 'user_id' => null], ['currency' => 'IRR']);
    }
}
