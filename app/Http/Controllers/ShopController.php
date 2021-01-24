<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::where('hidden', false)
            ->with('productListings');

        if ($request->type) {
            $query->where('type', (int) $request->type);
        }

        return response()->json($query->get(['id', 'name', 'description', 'type', 'amount_in_stock', 'image']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        //
    }

    public function addToCart(Request $request)
    {
        $cart = session('cart', []);

        $productToAdd = Product::find($request->order['product_id']);

        if (array_key_exists($request->order['id'], $cart) && ($cart[$request->order['id']]['quantity'] + $request->quantity) > $productToAdd->amount_in_stock) {
            return response()->json(['Exceeds product stock'], 400);
        }

        $cart[$request->order['id']] = [
            'order' => $request->order,
            'quantity' => array_key_exists($request->order['id'], $cart) ?
                ($cart[$request->order['id']]['quantity'] + $request->quantity) :
                $request->quantity,
            'product' => $productToAdd->toArray()
        ];

        session(['cart' => $cart]);

        return response()->json(session('cart'));
    }

    public function getCart()
    {
        return response()->json(session('cart'));
    }
}
