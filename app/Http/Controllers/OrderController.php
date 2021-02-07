<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\ProductListing;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $user = User::find(Auth::user()->id);

        if ($user->isAdmin) {
            $query = Order::with(['customer', 'orderLines']);
        } else {
            $query = Order::with('customer')->where('customer_id', $user->id);
        }

        if (isset($request->status)) {
            $query->whereIn('status', $request->status);
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
     * @return JsonResponse
     */
    public function show(Request $request, Order $order)
    {
        $order = Order::with(['customer', 'orderLines'])->where('id', $order->id)->first();

        return response()->json($order);
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
            $orderSubTotal += ($cartItem['order']['price'] * $cartItem['quantity']);
        }

        /*
         * Generate order
         */
        $order = Order::create([
            'customer_id' => $user->id,
            'totalPrice' => $orderSubTotal,
            'includesBulk' => $request->additionalData['includeBulk'] ?? '',
            'includesCodes' => $request->additionalData['includeCodes'] ?? '',
            'bulkSpecifics' => $request->additionalData['bulkSpecifics'] ?? '',
            'futurePackRequest' => $request->additionalData['futurePackRequests'] ?? '',
        ]);

        /*
         * Generate orderlines
         */
        foreach ($cart as $cartItem) {
            $pl = ProductListing::with('product')->find($cartItem['order']['id']);
            $product = Product::find($pl->product_id);

            // Deduct quantity from stock
            $product->amount_in_stock -= ($cartItem['quantity'] * $pl->amount);
            $product->save();

            // Create orderline
            OrderLine::create([
                'order_id' => $order->id,
                'product_listing_id' => $pl->id,
                'quantity' => $cartItem['quantity'],
                'amount' => $cartItem['quantity'] * $pl->amount,
                'isCompleted' => false,
            ]);
        }
        /*
         * Clear cart after processing order
         */
        session(['cart', []]);

        $newCart = session(['cart', []]);

        return response()->json($newCart);
    }

    public function toggleOrderLineCompletion(Request $request, Order $order, OrderLine $orderLine) {
        $orderLine->isCompleted = !$orderLine->isCompleted;
        $orderLine->save();
    }

    public function submitPaymentReference(Request $request, Order $order) {
        $request->validate([
            'paymentReference' => ['required']
        ]);

        $order->paymentReference = $request->paymentReference;
        $order->status = 'paid';
        $order->save();
    }

    public function declinePayment(Request $request, Order $order) {
        $order->status = 'new';
        $order->paymentReference = null;
        $order->save();
    }

    public function confirmPayment(Request $request, Order $order) {
        $order->status = 'paymentConfirmed';
        $order->save();
    }

    public function cancel(Request $request, Order $order) {
        if ($order->status === 'completed') {
            return;
        }

        // Return stock
        $orderLines = $order->orderLines();

        foreach ($orderLines as $orderLine) {
            /** @var OrderLine $orderLine */
            /** @var ProductListing $productListing */
            $productListing = $orderLine->productListing;
            $product = $productListing->product;
            $quantity = $orderLine->quantity;
            $stockToReturn = $productListing->amount * $quantity;

            $product->amount_in_stock += $stockToReturn;
            $product->save();
        }

        // Set order as cancelled
        $order->status = 'cancelled';
        $order->save();
    }

    public function complete(Request $request, Order $order) {
        $order->status = 'completed';
        $order->save();
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function showAudits(Request $request, Order $order)
    {
        return response()->json($order->audits()->with('user')->get());
    }
}
