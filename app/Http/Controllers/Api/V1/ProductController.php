<?php
// API عمومی محصولات منتشرشده
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller; use App\Models\Product; use Illuminate\Http\Request;
class ProductController extends Controller {
 public function index(Request $r){
  $data=$r->validate(['q'=>'nullable|string|max:100','category_id'=>'nullable|integer','page'=>'nullable|integer|min:1']);
  $q=Product::whereIn('status',['active','published'])->with('category');
  if(!empty($data['q'])) $q->where('name','like','%'.$data['q'].'%');
  if(!empty($data['category_id'])) $q->where('category_id',$data['category_id']);
  return response()->json($q->paginate(20));
 }
 public function show(Product $product){abort_unless(in_array($product->status,['active','published'],true),404);return response()->json($product->load('category'));}
}