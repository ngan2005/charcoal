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

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để mua hàng.');
        }

        $userId = auth()->id();
        $productId = $request->input('ProductID');
        $quantity = $request->input('Quantity', 1);

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
            $cart = DB::table('carts')->where('UserID', $userId)->first();
        }

        if ($quantity <= 0) {
            DB::table('cart_items')
                ->where('CartID', $cart->CartID)
                ->where('ProductID', $productId)
                ->delete();
            return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
        }

        $item = DB::table('cart_items')
            ->where('CartID', $cart->CartID)
            ->where('ProductID', $productId)
            ->first();

        if ($item) {
            DB::table('cart_items')
                ->where('CartItemID', $item->CartItemID)
                ->update(['Quantity' => $item->Quantity + $quantity]);
        } else {
            $maxId = DB::table('cart_items')->max('CartItemID');
            DB::table('cart_items')->insert([
                'CartItemID' => $maxId ? $maxId + 1 : 1,
                'CartID' => $cart->CartID,
                'ProductID' => $productId,
                'Quantity' => $quantity,
                'AddedAt' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }
}
