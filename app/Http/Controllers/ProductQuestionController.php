<?php
// ثبت پرسش مشتری و پاسخ توسط فروشنده یا مدیر
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductQuestion;
use Illuminate\Http\Request;
class ProductQuestionController extends Controller {
 public function store(Request $request, Product $product) {
  $data=$request->validate(['question'=>'required|string|min:5|max:3000']);
  ProductQuestion::create(['product_id'=>$product->id,'user_id'=>$request->user()->id,'question'=>$data['question'],'status'=>'pending']);
  return back()->with('success','پرسش شما برای بررسی ثبت شد.');
 }
 public function answer(Request $request, ProductQuestion $question) {
  // مجوز پاسخ‌دهنده باید با policy فروشنده مالک محصول یا مدیر محدود شود
  $question->load('product');
  $isAdmin = $request->user()->role === 'admin';
  $sellerId = $request->user()->sellerProfile?->id;
  abort_unless($isAdmin || ($sellerId && (int)$sellerId === (int)$question->product->seller_id), 403);
  $data=$request->validate(['answer'=>'required|string|min:2|max:3000']);
  $question->update(['answer'=>$data['answer'],'answered_by'=>$request->user()->id,'status'=>'answered']);
  return back()->with('success','پاسخ ثبت شد.');
 }
}