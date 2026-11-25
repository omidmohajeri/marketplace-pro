<?php
// نمایش عمومی صفحه منتشرشده با slug
namespace App\Http\Controllers;
use App\Models\SiteContent;
class SitePageController extends Controller {
 public function show(string $slug) {
  $page=SiteContent::where('type','page')->where('slug',$slug)->where('is_active',true)->firstOrFail();
  return view('pages.show',compact('page'));
 }
}