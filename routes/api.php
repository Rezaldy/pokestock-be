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
    return response()->json([Auth::check(),[...$request->session()->all()]]);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::resource('shop', ShopController::class);

    /**
     * ADMIN ROUTES FOR PRODUCT MANAGEMENT
     */
    Route::group(['middleware' => 'auth.admin'], function () {
        Route::resources([
            'products' => ProductController::class,
            'products.productListings' => ProductProductListingController::class,
            'order' => OrderController::class,
        ]);
        Route::post('products/{product}/image/upload', [ProductController::class, 'uploadImage']);
    });
});
