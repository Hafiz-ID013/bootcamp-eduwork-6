<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Store checkout order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $totalPrice = $product->price * $request->quantity;

        Order::create([
            'user_id'     => Auth::id(),
            'total_price' => $totalPrice,
            'status'      => 'paid',
        ]);

        // Clear cart session
        session()->forget('cart');

        return redirect()
            ->route('dashboard')
            ->with('success', 'Order completed successfully.');
    }
}
