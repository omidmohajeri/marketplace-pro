<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

// این کنترلر دسته‌بندی‌های چندزبانه فروشگاه را مدیریت می‌کند.
class CategoryController extends Controller
{
    // همه دسته‌بندی‌ها را همراه با تعداد محصولات نمایش می‌دهد.
    public function index()
    {
        $categories = Category::withCount('products')->with('children')->orderBy('sort_order')->paginate(30);
        return view('admin.categories.index', compact('categories'));
    }

    // فرم ایجاد دسته‌بندی جدید را نمایش می‌دهد.
    public function create()
    {
        $category = new Category();
        $parents = Category::orderBy('sort_order')->get();
        return view('admin.categories.form', compact('category', 'parents'));
    }

    // دسته‌بندی جدید را ذخیره می‌کند.
    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']['en']) . '-' . Str::lower(Str::random(4));
        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'دسته‌بندی ایجاد شد.');
    }

    // فرم ویرایش دسته‌بندی را نمایش می‌دهد.
    public function edit(Category $category)
    {
        $parents = Category::whereKeyNot($category->id)->orderBy('sort_order')->get();
        return view('admin.categories.form', compact('category', 'parents'));
    }

    // اطلاعات دسته‌بندی را ذخیره می‌کند.
    public function update(Request $request, Category $category)
    {
        $data = $this->validatedData($request, $category);
        $data['slug'] = $data['slug'] ?: $category->slug;
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'دسته‌بندی به‌روزرسانی شد.');
    }

    // حذف دسته‌بندی دارای محصول به دلیل محدودیت کلید خارجی انجام نمی‌شود.
    public function destroy(Category $category)
    {
        abort_if($category->products()->exists() || $category->children()->exists(), 422, 'ابتدا محصولات و زیرشاخه‌ها را منتقل یا حذف کنید.');
        $category->delete();

        return back()->with('success', 'دسته‌بندی حذف شد.');
    }

    // داده‌های دو زبانه دسته‌بندی را اعتبارسنجی می‌کند.
    private function validatedData(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'parent_id' => ['nullable', 'integer', Rule::exists('categories', 'id')->where(fn ($q) => $q->where('id', '!=', $category?->id ?? 0))],
            'name.fa' => ['required', 'string', 'max:190'],
            'name.en' => ['required', 'string', 'max:190'],
            'slug' => ['nullable', 'string', 'max:190', Rule::unique('categories', 'slug')->ignore($category?->id)],
            'description.fa' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
