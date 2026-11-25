<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

// این کنترلر CRUD محصولات فروشگاه را در پنل مدیر کل انجام می‌دهد.
class ProductController extends Controller
{
    // فهرست محصولات با جست‌وجو و صفحه‌بندی را نمایش می‌دهد.
    public function index(Request $request)
    {
        $products = Product::with(['category', 'seller'])
            ->when($request->filled('q'), function ($query) use ($request) {
                // جست‌وجو در کد کالا، شناسه و نام فارسی/انگلیسی.
                $term = '%' . trim($request->string('q')->toString()) . '%';
                $query->where(function ($sub) use ($term) {
                    $sub->where('sku', 'like', $term)
                        ->orWhere('slug', 'like', $term)
                        ->orWhere('name->fa', 'like', $term)
                        ->orWhere('name->en', 'like', $term);
                });
            })
            ->latest()->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    // فرم ساخت محصول جدید را نمایش می‌دهد.
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.form', ['product' => new Product(), 'categories' => $categories]);
    }

    // محصول جدید را با اعتبارسنجی ورودی‌ها ذخیره می‌کند.
    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        // شناسه فروشنده از فرم مدیر دریافت نمی‌شود؛ محصول جدید متعلق به فروشگاه اصلی است.
        $data['seller_id'] = null;
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']['en'] ?: $data['name']['fa']) . '-' . Str::lower(Str::random(5));
        $data['status'] = $data['status'] ?? 'draft';

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'محصول با موفقیت ایجاد شد.');
    }

    // فرم ویرایش محصول را نمایش می‌دهد.
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    // اطلاعات محصول را به‌روزرسانی می‌کند.
    public function update(Request $request, Product $product)
    {
        $data = $this->validatedData($request, $product);
        $data['slug'] = $data['slug'] ?: $product->slug;
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'محصول به‌روزرسانی شد.');
    }

    // محصول را حذف می‌کند؛ اقلام سفارش به دلیل کلید خارجی nullable باقی می‌مانند.
    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'محصول حذف شد.');
    }

    // قوانین اعتبارسنجی مشترک برای ایجاد و ویرایش محصول.
    private function validatedData(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($product?->id)],
            'slug' => ['nullable', 'string', 'max:190', Rule::unique('products', 'slug')->ignore($product?->id)],
            'name.fa' => ['required', 'string', 'max:190'],
            'name.en' => ['required', 'string', 'max:190'],
            'description.fa' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'compare_at_price' => ['nullable', 'integer', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['draft', 'active', 'archived'])],
        ]);
    }
}
