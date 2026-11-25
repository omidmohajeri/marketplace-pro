<?php
// ثبت درخواست مرجوعی؛ مالکیت سفارش در Query کنترل می‌شود
namespace App\Http\Controllers;
use App\Models\Order; use App\Models\ReturnRequest; use Illuminate\Http\Request;
class ReturnRequestController extends Controller {
 public function store(Request $r, Order $order){
  abort_unless((int)$order->user_id===(int)$r->user()->id,404);
  abort_unless(in_array($order->status,['paid','processing','shipped','delivered','completed']),422);
  $d=$r->validate(['reason'=>'required|string|min:10|max:3000']);
  ReturnRequest::firstOrCreate(['order_id'=>$order->id,'user_id'=>$r->user()->id],['reason'=>$d['reason']]);
  return back()->with('success','درخواست مرجوعی ثبت شد.');
 }
}