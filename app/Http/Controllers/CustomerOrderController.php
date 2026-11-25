<?php
// نمایش سفارش فقط برای مالک آن؛ مقصد بازگشت از پرداخت
namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\Http\Request;
class CustomerOrderController extends Controller {
    public function show(Request $request, Order $order) {
        abort_unless((int)$order->user_id === (int)$request->user()->id, 404);
        return view('orders.show', ['order'=>$order->load('items')]);
    }
}
