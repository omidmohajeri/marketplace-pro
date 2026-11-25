<?php
// گزارش پایه فروش؛ نام ستون‌ها را با ساختار واقعی سفارش تطبیق دهید
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class AnalyticsController extends Controller {
 public function index(Request $request) {
  $range=$request->validate(['from'=>'nullable|date','to'=>'nullable|date|after_or_equal:from']);
  $query=Order::query()->whereIn('status',['paid','processing','shipped','delivered','completed']);
  if(!empty($range['from'])) $query->whereDate('created_at','>=',$range['from']);
  if(!empty($range['to'])) $query->whereDate('created_at','<=',$range['to']);
  $summary=(clone $query)->selectRaw('COUNT(*) as orders_count, COALESCE(SUM(grand_total),0) as gross_sales')->first();
  $daily=(clone $query)->selectRaw('DATE(created_at) as day, COUNT(*) as orders_count, SUM(grand_total) as gross_sales')->groupBy(DB::raw('DATE(created_at)'))->orderBy('day')->get();
  return view('admin.analytics.index',compact('summary','daily','range'));
 }
}