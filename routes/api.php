<?php

use App\Http\Controllers\{
    AuthController,
    OrderController,
    ProductController,
    ProductProductListingController,
    ShopController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/checkAuth', function (Request $request) {
    return response()->json([Auth::check(),$request->session()->all()]);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::resource('shop', ShopController::class);
    Route::post('shop/addToCart', [ShopController::class, 'addToCart']);
    Route::post('shop/cart', [ShopController::class, 'getCart']);
    Route::post('shop/cart/clear', [ShopController::class, 'clearCart']);

    /**
     * ADMIN ROUTES FOR PRODUCT MANAGEMENT
     */
    Route::group(['middleware' => 'auth.admin'], function () {
        Route::resources([
            'products' => ProductController::class,
            'products.productListings' => ProductProductListingController::class,
            'order' => OrderController::class,
        ]);
        Route::post('/products/{product}/image/upload', [ProductController::class, 'uploadImage']);
        Route::post('/products/unhide.multiple', [ProductController::class, 'unhideMultiple']);
        Route::post('/products/remove.multiple', [ProductController::class, 'removeMultiple']);
        Route::post('/products/hide.multiple', [ProductController::class, 'hideMultiple']);
    });
});
