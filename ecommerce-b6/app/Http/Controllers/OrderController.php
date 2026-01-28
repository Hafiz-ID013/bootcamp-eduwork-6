<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stock) {
            return back()->with('error', 'Stock not sufficient.');
        }

        DB::transaction(function () use ($product, $request) {

            $total = $product->price * $request->quantity;

            $order = Order::create([
                'user_id'     => Auth::id(),
                'total_price' => $total,
                'status'      => 'paid',
            ]);

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
            ]);

            // reduce stock
            $product->decrement('stock', $request->quantity);
        });

        return redirect()->route('home')
            ->with('success', 'Order completed.');
    }
}
