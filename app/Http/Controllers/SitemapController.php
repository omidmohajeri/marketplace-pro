<?php
// تولید sitemap ساده از محصولات منتشرشده
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Response;
class SitemapController extends Controller {
 public function __invoke(): Response {
  $products=Product::where('status','published')->select('id','slug','updated_at')->orderBy('id')->cursor();
  $xml='<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
  foreach($products as $p) $xml.='<url><loc>'.e(route('products.show',$p->slug)).'</loc><lastmod>'.$p->updated_at->toAtomString().'</lastmod></url>';
  $xml.='</urlset>'; return response($xml,200,['Content-Type'=>'application/xml; charset=UTF-8']);
 }
}