<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $user = User::find(Auth::user()->id);

        if ($user->isAdmin) {
            $query = Order::with('customer');
        } else {
            $query = Order::with('customer')->where('customer_id', $user->id);a
        }

        return response()->json($query->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Order $order
     * @return Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Order $order
     * @return Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function submitCart(Request $request)
    {
        $cart = session('cart');
        $user = User::find(Auth::user()->id);

        $orderSubTotal = 0;

        foreach ($cart as $cartItem) {
            $orderSubTotal += $cartItem['order']['price'];
        }

        /*
         * Generate order
         */
        $order = Order::create([
            'customer_id' => $user->id,
            'totalPrice' => $orderSubTotal,
            'includesBulk' => $request->additionalData['includeBulk'],
            'includesCodes' => $request->additionalData['includeCodes'],
            'bulkSpecifics' => $request->additionalData['bulkSpecifics'],
            'futurePackRequest' => $request->additionalData['futurePackRequests'],
        ]);

        /*
         * Generate orderlines
         */
        foreach ($cart as $cartItem) {
            OrderLine::create([
                'order_id' => $order->id,
                'product_listing_id' => $cartItem['order']['id'],
                'quantity' => $cartItem['quantity'],
                'isCompleted' => false,
            ]);
        }
        /*
         * Clear cart after processing order
         */
        session(['cart', []]);

        $newCart = session(['cart', []]);

        return response()->json();
    }
}
