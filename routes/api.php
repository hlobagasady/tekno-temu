<?php
use Illuminate\Support\Facades\Route; use Illuminate\Http\Request; use App\Http\Controllers\AuthController; use App\Http\Controllers\ProductController; use App\Http\Controllers\TaxonomyController;
Route::post('/auth/login',[AuthController::class,'login'])->middleware('throttle:5,1');
Route::get('/products',[ProductController::class,'index']);
Route::get('/products/{product}',[ProductController::class,'show']);
Route::middleware('jwt.admin')->group(function(){
    foreach (['categories','collections'] as $type) {
        Route::get('/'.$type, fn (Request $r) => app(TaxonomyController::class)->index($r, $type));
        Route::post('/'.$type, fn (Request $r) => app(TaxonomyController::class)->store($r, $type));
        Route::put('/'.$type.'/{id}', fn (Request $r, int $id) => app(TaxonomyController::class)->update($r, $type, $id));
        Route::delete('/'.$type.'/{id}', fn (int $id) => app(TaxonomyController::class)->destroy($type, $id));
    }
    Route::get('/auth/me',[AuthController::class,'me']);
    Route::post('/auth/logout',[AuthController::class,'logout']);
    Route::post('/products/import',[ProductController::class,'import']);
    Route::post('/products',[ProductController::class,'store']);
    Route::match(['put','patch'],'/products/{product}',[ProductController::class,'update']);
    Route::delete('/products/{product}',[ProductController::class,'destroy']);
});
