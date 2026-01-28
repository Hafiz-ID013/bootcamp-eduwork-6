<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Admin goes to dashboard
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('dashboard');
        }

        // User sees products
        $products = Product::latest()->paginate(8);

        return view('home', compact('products'));
    }
}
