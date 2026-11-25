<?php
// صف بررسی نظرات، پرسش‌ها و گزارش‌های کاربران
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\ProductQuestion;
use App\Models\ContentReport;
use Illuminate\Http\Request;
class ReviewModerationController extends Controller {
 public function index() {
  return view('admin.moderation.index',[
   'reviews'=>ProductReview::with(['product','user'])->where('status','pending')->latest()->paginate(20,['*'],'reviews'),
   'questions'=>ProductQuestion::with(['product','user'])->where('status','pending')->latest()->paginate(20,['*'],'questions'),
   'reports'=>ContentReport::with('reportable')->where('status','open')->latest()->paginate(20,['*'],'reports')
  ]);
 }
 public function review(Request $request, ProductReview $review) {
  $data=$request->validate(['status'=>'required|in:approved,rejected']);
  $review->update($data); return back()->with('success','وضعیت نظر به‌روزرسانی شد.');
 }
 public function question(Request $request, ProductQuestion $question) {
  $data=$request->validate(['status'=>'required|in:approved,rejected']);
  $question->update($data); return back()->with('success','وضعیت پرسش به‌روزرسانی شد.');
 }
 public function report(Request $request, ContentReport $report) {
  $data=$request->validate(['status'=>'required|in:reviewed,dismissed']);
  $report->update($data); return back()->with('success','گزارش رسیدگی شد.');
 }
}