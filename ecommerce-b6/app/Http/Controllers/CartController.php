<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        return view('cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => $product->price,
                'image'    => $product->image,
                'quantity' => $request->quantity
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart')
            ->with('success', 'Product added to cart');
    }

    public function remove($id)
    {
        $cart = session()->get('cart');

        unset($cart[$id]);

        session()->put('cart', $cart);

        return back();
    }
}
