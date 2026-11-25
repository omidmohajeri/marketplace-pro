<?php
// جست‌وجوی محصولات با فیلترهای اعتبارسنجی‌شده
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductSearchController extends Controller {
 public function __invoke(Request $request) {
  $filters=$request->validate([
   'q'=>'nullable|string|max:120','category_id'=>'nullable|integer',
   'min_price'=>'nullable|numeric|min:0','max_price'=>'nullable|numeric|gte:min_price',
   'seller_id'=>'nullable|integer','in_stock'=>'nullable|boolean',
   'sort'=>'nullable|in:newest,price_asc,price_desc,popular'
  ]);
  $query=Product::query()->whereIn('status',['active','published']); // فقط کالاهای منتشرشده
  if(!empty($filters['q'])) $query->where(function($q) use($filters) {
   $term=trim($filters['q']); $q->where('name','like',"%{$term}%")->orWhere('description','like',"%{$term}%");
  });
  foreach(['category_id','seller_id'] as $field) if(!empty($filters[$field])) $query->where($field,$filters[$field]);
  if(isset($filters['min_price'])) $query->where('price','>=',$filters['min_price']);
  if(isset($filters['max_price'])) $query->where('price','<=',$filters['max_price']);
  if(!empty($filters['in_stock'])) $query->where('stock','>',0);
  match($filters['sort']??'newest') {
   'price_asc'=>$query->orderBy('price'),
   'price_desc'=>$query->orderByDesc('price'),
   'popular'=>$query->withCount('orderItems')->orderByDesc('order_items_count'),
   default=>$query->latest()
  };
  return view('products.search', ['products'=>$query->paginate(24)->withQueryString(),'filters'=>$filters]);
 }
}