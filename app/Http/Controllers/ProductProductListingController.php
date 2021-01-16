<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductListing;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductProductListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(int $product)
    {
        return response()->json(Product::with('productListings')->find($product)->productListings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param Product $product
     * @param ProductListing $productListing
     * @return JsonResponse
     */
    public function create(Request $request, Product $product, ProductListing $productListing)
    {
        $newProductListing = new ProductListing;
        $newProductListing->amount     = $request->amount;
        $newProductListing->product_id = $request->product_id;
        $newProductListing->price      = $request->price;
        $newProductListing->isDiscount = $request->isDiscount === 'true' ? 1 : 0;
        $newProductListing->save();

        return response()->json($productListing);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $product
     * @param int $productListing
     * @return JsonResponse
     */
    public function show(int $product, int $productListing)
    {
        return response()->json(ProductListing::find($productListing));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProductListing $productListing
     * @return JsonResponse
     */
    public function edit(ProductListing $productListing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ProductListing $productListing
     * @return JsonResponse
     */
    public function update(Request $request, Product $product, ProductListing $productListing)
    {
        $productListing->amount     = $request->amount;
        $productListing->price      = $request->price;
        $productListing->isDiscount = $request->isDiscount;
        $productListing->save();

        return response()->json($productListing);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $productListing
     */
    public function destroy(int $product, int $productListing)
    {
        ProductListing::destroy($productListing);
    }
}
