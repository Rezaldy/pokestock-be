<?php

namespace App\Http\Middleware;

use App\Models\Order;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAuthorizedForOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::find(Auth::user()->id);
        $order = $request->route('order');
        if (!$user->is_admin && $order->customer_id !== $user->id) {
            return response()->json([
                'You are not authorized to view this page.'
            ], 401);
        }

        return $next($request);
    }
}
