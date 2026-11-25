<?php
// ثبت و مشاهده تیکت فقط برای مالک تیکت
namespace App\Http\Controllers;
use App\Models\SupportTicket; use Illuminate\Http\Request;
class SupportTicketController extends Controller {
 public function index(Request $r){return view('support.index',['tickets'=>$r->user()->supportTickets()->latest()->paginate(15)]);}
 public function store(Request $r){$d=$r->validate(['subject'=>'required|string|max:180','category'=>'required|string|max:60','body'=>'required|string|min:5|max:5000']);$t=SupportTicket::create(['user_id'=>$r->user()->id,'subject'=>$d['subject'],'category'=>$d['category']]);$t->messages()->create(['user_id'=>$r->user()->id,'body'=>$d['body']]);return redirect()->route('support.index');}
}