<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }

        $userId = auth()->id();
        
        // Find or create cart
        $cart = DB::table('carts')->where('UserID', $userId)->first();
        if (!$cart) {
            $maxCartId = DB::table('carts')->max('CartID');
            $newCartId = $maxCartId ? $maxCartId + 1 : 1;
            
            DB::table('carts')->insert([
                'CartID' => $newCartId,
                'UserID' => $userId,
                'CreatedAt' => now()
            ]);
            $cart = DB::table('carts')->where('CartID', $newCartId)->first();
        }

        // Fetch cart items with product details
        $cartItems = DB::table('cart_items')
            ->leftJoin('products', 'cart_items.ProductID', '=', 'products.ProductID')
            ->leftJoin('services', 'cart_items.ServiceID', '=', 'services.ServiceID')
            ->where('cart_items.CartID', $cart->CartID)
            ->select(
                'cart_items.*', 
                'products.ProductName', 
                'products.Price as ProductPrice', 
                'services.ServiceName', 
                'services.BasePrice as ServicePrice'
            )
            ->get();
            
        // For images we need to fetch separately because of the 1-N relationship
        foreach ($cartItems as $item) {
            if ($item->ProductID) {
                // Get main image or first image
                $image = DB::table('product_images')
                    ->where('ProductID', $item->ProductID)
                    ->orderByDesc('IsMain')
                    ->first();
                $item->ImageURL = $image ? $image->ImageURL : null;
            } else if ($item->ServiceID) {
                $item->ImageURL = null; // Services might not have specific cart images if schema doesn't support, but we can fall back to placehold.
            }
        }

        // Calculate subtotal
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->ProductID ? $item->ProductPrice : $item->ServicePrice;
            $subtotal += ($price * $item->Quantity);
        }

        return view('cart.index', compact('cartItems', 'subtotal'));
    }
}
