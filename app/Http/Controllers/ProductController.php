<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductListing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Product::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $product = new Product;
        $product->amount_in_stock = $request->amount_in_stock;
        $product->hidden_stock = $request->hidden_stock;
        $product->description = $request->description;
        $product->hidden = $request->hidden;
        $product->name = $request->name;
        $product->type = $request->type;
        $product->image = '';
        $product->save();
    }

    /**
     * Display the specified resource.
     *
     * @param int $product
     * @return JsonResponse
     */
    public function show(int $product)
    {
        return response()->json(Product::find($product));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Request $request, Product $product)
    {
        $product->name = $request->name;
        $product->amount_in_stock = (int)$request->amount_in_stock;
        $product->hidden_stock = (int)$request->hidden_stock;
        $product->description = $request->description;
        $product->hidden = $request->hidden;
        $product->type = (int)$request->type;
        $product->save();

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $product
     */
    public function destroy(int $product)
    {
        /** @var Product $product */
        $product = Product::find($product);
        foreach ($product->productListings()->get() as $listing) {
            ProductListing::destroy($listing->id);
        }
        Product::destroy($product->id);
    }

    public function uploadImage(Request $request, int $product)
    {
        $product = Product::find($product);
        $parsedPath = parse_url($product->image);
        if (Storage::exists($parsedPath['path'])) {
            Storage::delete($parsedPath['path']);
        }

        $image = $request->file('image');
        $fileName = $product->id . '.' . $image->guessClientExtension();
        $image->storeAs('public/images/products', $fileName);

        $product->image = env('APP_URL') . "/storage/images/products/{$fileName}";
        $product->save();
        return response()->json($product);
    }

    public function unhideMultiple(Request $request)
    {
        $ids = $request->all();
        $productsToUnhide = array_map(function ($id) {
            return Product::find($id);
        }, $ids);

        foreach ($productsToUnhide as $productToUnhide) {
            $productToUnhide->hidden = false;
            $productToUnhide->save();
        }
    }

    public function removeMultiple(Request $request)
    {
        $ids = $request->all();
        $productsToDelete = array_map(function ($id) {
            return Product::with('productListings')->find($id);
        }, $ids);

        foreach ($productsToDelete as $productToDelete) {
            foreach ($productToDelete->productListings as $listing) {
                ProductListing::destroy($listing->id);
            }
            Product::destroy($productToDelete->id);
        }
    }

    public function hideMultiple(Request $request)
    {
        $ids = $request->all();
        $productsToHide = array_map(function ($id) {
            return Product::find($id);
        }, $ids);

        foreach ($productsToHide as $productToHide) {
            $productToHide->hidden = true;
            $productToHide->save();
        }
    }

}
