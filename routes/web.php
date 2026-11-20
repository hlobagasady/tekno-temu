<?php
use App\Models\Product; use App\Models\Category; use App\Models\Collection;
use Illuminate\Support\Facades\Route;
Route::get('/', function () { try { $categories = Category::orderBy('name')->get(); $collections = Collection::orderBy('name')->get(); } catch (\Throwable) { $categories = collect(); $collections = collect(); } return view('home', compact('categories', 'collections')); });
Route::get('/products/{product:slug}', function (Product $product) { $categories = Category::orderBy('name')->get(); $recommendations = Product::where('status','published')->where('category',$product->category)->whereKeyNot($product->id)->latest()->limit(4)->get(); return view('product', compact('product','recommendations','categories')); })->name('products.show');
Route::view('/admin', 'admin');
