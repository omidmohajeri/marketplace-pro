<?php
// endpoint نمایش اطلاعات پایه کاربر واردشده
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller; use Illuminate\Http\Request;
class MeController extends Controller {
 public function __invoke(Request $r){return response()->json(['id'=>$r->user()->id,'name'=>$r->user()->name,'email'=>$r->user()->email]);}
}