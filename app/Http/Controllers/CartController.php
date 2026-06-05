<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $cart = session()->get('cart', []);
        
        // If product already in cart, increment quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            // Add new product to cart
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image,
                "description" => $product->description
            ];
        }
        
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update cart quantity
     */
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {

            if ($request->quantity < 1) {
                unset($cart[$request->id]);
            } else {
                $cart[$request->id]['quantity'] = $request->quantity;
            }

            session()->put('cart', $cart);
        }

        return back();
    }
    

    /**
     * Remove product from cart
     */
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            
            return redirect()->back()->with('success', 'Product removed from cart!');
        }
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }
}
