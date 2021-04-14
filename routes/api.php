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
    Route::post('shop/cart/submit', [OrderController::class, 'submitCart']);
    Route::resource('orders', OrderController::class);

    /*
     * Order changes
     */
    Route::group(['middleware' => 'order.authorized'], function () {
        Route::post('/orders/{order}/submitPaymentReference', [OrderController::class, 'submitPaymentReference']);
    });

    // Order cancel
    Route::post('/orders/{order}/cancel',           [OrderController::class, 'cancel']);

    /**
     * ADMIN ROUTES FOR PRODUCT MANAGEMENT
     */
    Route::group(['middleware' => 'auth.admin'], function () {
        Route::resources([
            'products' => ProductController::class,
            'products.productListings' => ProductProductListingController::class,
        ]);
        Route::post('/products/{product}/image/upload', [ProductController::class, 'uploadImage']);
        Route::post('/products/unhide.multiple', [ProductController::class, 'unhideMultiple']);
        Route::post('/products/remove.multiple', [ProductController::class, 'removeMultiple']);
        Route::post('/products/hide.multiple', [ProductController::class, 'hideMultiple']);

        /*
         * Order changes
         */
        Route::post('/orders/{order}/orderLine/{orderLine}/toggleCompletion', [OrderController::class, 'toggleOrderLineCompletion']);
        Route::post('/orders/{order}/declinePayment',   [OrderController::class, 'declinePayment']);
        Route::post('/orders/{order}/confirmPayment',   [OrderController::class, 'confirmPayment']);
        Route::post('/orders/{order}/complete',         [OrderController::class, 'complete']);

        /*
         * Audits
         */
        Route::get('/products/{product}/audits', [ProductController::class, 'showAudits']);
        Route::get('/orders/{order}/audits', [OrderController::class, 'showAudits']);
    });
});
