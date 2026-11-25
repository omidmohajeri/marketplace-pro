<?php
// تست smoke برای در دسترس بودن صفحه جست‌وجو؛ تست داده واقعی در فاز تست تکمیلی اضافه شود
namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
class ProductSearchTest extends TestCase {
 use RefreshDatabase;
 public function test_search_page_returns_success(): void {
  $this->get('/search')->assertOk();
 }
}
