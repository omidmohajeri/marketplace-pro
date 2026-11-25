<?php
// ثبت نظر مشتری و گزارش تخلف
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\ContentReport;
use Illuminate\Http\Request;
class ProductReviewController extends Controller {
 public function store(Request $request, Product $product) {
  $data=$request->validate(['rating'=>'required|integer|min:1|max:5','title'=>'nullable|string|max:180','body'=>'required|string|min:10|max:5000']);
  // فقط سفارش پرداخت‌شده و متعلق به کاربر، خرید تأییدشده محسوب می‌شود
  $verified = $request->user()->orders()
   ->whereIn('status',['paid','processing','completed'])
   ->whereHas('items',fn($q)=>$q->where('product_id',$product->id))->exists();
  ProductReview::updateOrCreate(
   ['product_id'=>$product->id,'user_id'=>$request->user()->id],
   [...$data,'status'=>'pending','verified_purchase'=>$verified]
  );
  return back()->with('success','نظر شما برای بررسی ارسال شد.');
 }
 public function report(Request $request, ProductReview $review) {
  $data=$request->validate(['reason'=>'required|string|max:100','details'=>'nullable|string|max:2000']);
  ContentReport::create(['reporter_id'=>$request->user()->id,'reportable_type'=>ProductReview::class,'reportable_id'=>$review->id,...$data]);
  return back()->with('success','گزارش شما ثبت شد.');
 }
}