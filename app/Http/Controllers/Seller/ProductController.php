<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // فهرست کالاهای متعلق به فروشنده جاری را نمایش می‌دهد.
    public function index(Request $request)
    {
        $seller = $request->user()->seller;
        abort_unless($seller, 403);

        // هرگز محصولات فروشندگان دیگر در این کوئری وارد نمی‌شوند.
        $products = Product::where('seller_id', $seller->id)->latest()->paginate(15);

        return view('seller.products.index', compact('products'));
    }

    // فرم ثبت کالای جدید را نمایش می‌دهد.
    public function create(Request $request)
    {
        abort_unless($request->user()->seller, 403);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('seller.products.form', ['product' => new Product(), 'categories' => $categories]);
    }

    // اطلاعات کالای جدید را اعتبارسنجی و ذخیره می‌کند.
    public function store(Request $request)
    {
        $seller = $request->user()->seller;
        abort_unless($seller && $seller->status === 'active', 403, 'فروشگاه فعال نیست.');

        // فقط فیلدهای مجاز فروشنده پذیرفته می‌شوند و وضعیت انتشار کنترل‌شده است.
        $data = $this->validatedData($request);
        $data['seller_id'] = $seller->id;
        $data['slug'] = Str::slug(($data['sku'] ?? 'product').'-'.Str::random(6));
        $data['status'] = 'pending';

        // کالای جدید برای بررسی مدیر ثبت می‌شود.
        Product::create($data);

        return redirect()->route('seller.products.index')->with('success', 'کالا ثبت شد و پس از بررسی منتشر می‌شود.');
    }

    // فرم ویرایش کالا را فقط برای مالک همان کالا نمایش می‌دهد.
    public function edit(Request $request, Product $product)
    {
        $this->authorizeOwnership($request, $product);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        return view('seller.products.form', compact('product', 'categories'));
    }

    // تغییرات کالای متعلق به فروشنده جاری را ذخیره می‌کند.
    public function update(Request $request, Product $product)
    {
        $this->authorizeOwnership($request, $product);
        $data = $this->validatedData($request);
        unset($data['status'], $data['seller_id'], $data['slug']);

        // ویرایش کالا وضعیت تأیید قبلی را به بررسی مجدد می‌برد.
        $data['status'] = 'pending';
        $product->update($data);

        return redirect()->route('seller.products.index')->with('success', 'تغییرات ذخیره شد و برای بررسی ارسال شد.');
    }

    // کالای متعلق به فروشنده را به‌صورت نرم‌حذف حذف می‌کند.
    public function destroy(Request $request, Product $product)
    {
        $this->authorizeOwnership($request, $product);
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'کالا حذف شد.');
    }

    // مالکیت کالا را پیش از هر عملیات بررسی می‌کند.
    private function authorizeOwnership(Request $request, Product $product): void
    {
        abort_unless($request->user()->seller && (int) $product->seller_id === (int) $request->user()->seller->id, 404);
    }

    // داده‌های ورودی را با قواعد پایه فروش کالا اعتبارسنجی می‌کند.
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name.fa' => ['required', 'string', 'max:180'],
            'name.en' => ['required', 'string', 'max:180'],
            'description.fa' => ['nullable', 'string', 'max:10000'],
            'description.en' => ['nullable', 'string', 'max:10000'],
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:80'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'gte:price'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);
    }
}
