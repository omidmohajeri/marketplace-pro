<?php
// نمایش اعلان‌های کاربر و علامت‌گذاری به‌عنوان خوانده‌شده
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class NotificationController extends Controller {
 public function index(Request $request) { return view('notifications.index',['notifications'=>$request->user()->notifications()->latest()->paginate(20)]); }
 public function read(Request $request,string $id) {
  $notification=$request->user()->notifications()->whereKey($id)->firstOrFail(); // جلوگیری از دسترسی به اعلان دیگران
  $notification->markAsRead(); return back();
 }
 public function readAll(Request $request) { $request->user()->unreadNotifications->markAsRead(); return back(); }
}