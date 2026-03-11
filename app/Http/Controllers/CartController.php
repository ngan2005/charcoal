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
            if (!empty($item->ProductID)) {
                // Get main image or first image
                $image = DB::table('product_images')
                    ->where('ProductID', $item->ProductID)
                    ->orderByDesc('IsMain')
                    ->first();
                $item->ImageURL = $image ? ($image->ImageUrl ?? null) : null;
            } else {
                $item->ImageURL = null; // Service or fallback: no image
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
        $productId = $request->input('ProductID') ?? $request->input('product_id');
        $serviceId = $request->input('ServiceID') ?? $request->input('service_id');
        $quantity = (int) ($request->input('Quantity') ?? $request->input('quantity', 1));
        $redirectToCheckout = $request->input('redirect') === 'checkout';

        if (empty($productId) && empty($serviceId)) {
            return redirect()->back()->with('error', 'Vui lòng chọn sản phẩm hoặc dịch vụ để thêm vào giỏ.');
        }

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

        // Thêm dịch vụ vào giỏ
        if (!empty($serviceId)) {
            $quantity = max(1, $quantity);
            $item = DB::table('cart_items')
                ->where('CartID', $cart->CartID)
                ->where('ServiceID', $serviceId)
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
                    'ProductID' => null,
                    'ServiceID' => $serviceId,
                    'Quantity' => $quantity,
                    'AddedAt' => now()
                ]);
            }
            if ($redirectToCheckout) {
                return redirect()->route('checkout.index')->with('success', 'Đã thêm dịch vụ vào giỏ! Chuyển tới thanh toán.');
            }
            return redirect()->back()->with('success', 'Đã thêm dịch vụ vào giỏ hàng!');
        }

        // Thêm sản phẩm vào giỏ
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
                'ServiceID' => null,
                'Quantity' => $quantity,
                'AddedAt' => now()
            ]);
        }

        if ($redirectToCheckout) {
            return redirect()->route('checkout.index')->with('success', 'Đã thêm vào giỏ hàng!');
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng!');
    }

    /**
     * Update quantity of a cart item
     */
    public function update(Request $request, $id)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Vui lòng đăng nhập.'], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = auth()->id();
        $cart = DB::table('carts')->where('UserID', $userId)->first();

        if (!$cart) {
            return response()->json(['error' => 'Giỏ hàng không tồn tại.'], 404);
        }

        $quantity = $request->input('quantity');

        // Update quantity
        DB::table('cart_items')
            ->where('CartItemID', $id)
            ->where('CartID', $cart->CartID)
            ->update(['Quantity' => $quantity]);

        // Get updated item to calculate new totals
        $item = DB::table('cart_items')
            ->leftJoin('products', 'cart_items.ProductID', '=', 'products.ProductID')
            ->leftJoin('services', 'cart_items.ServiceID', '=', 'services.ServiceID')
            ->where('cart_items.CartItemID', $id)
            ->select(
                'cart_items.*',
                'products.Price as ProductPrice',
                'services.BasePrice as ServicePrice'
            )
            ->first();

        if (!$item) {
            return response()->json(['error' => 'Mục không tồn tại.'], 404);
        }

        $price = ($item->ProductID ? $item->ProductPrice : $item->ServicePrice) ?? 0;
        $itemTotal = $price * $quantity;

        // Calculate cart subtotal (phải select ProductID/ServiceID để phân biệt giá)
        $allItems = DB::table('cart_items')
            ->leftJoin('products', 'cart_items.ProductID', '=', 'products.ProductID')
            ->leftJoin('services', 'cart_items.ServiceID', '=', 'services.ServiceID')
            ->where('cart_items.CartID', $cart->CartID)
            ->select(
                'cart_items.ProductID',
                'cart_items.ServiceID',
                'cart_items.Quantity',
                'products.Price as ProductPrice',
                'services.BasePrice as ServicePrice'
            )
            ->get();

        $subtotal = 0;
        foreach ($allItems as $i) {
            $p = ($i->ProductID ? ($i->ProductPrice ?? 0) : ($i->ServicePrice ?? 0)) ?: 0;
            $subtotal += ($p * $i->Quantity);
        }

        return response()->json([
            'success' => true,
            'itemTotal' => number_format($itemTotal, 0, ',', '.') . 'đ',
            'subtotal' => number_format($subtotal, 0, ',', '.') . 'đ',
            'itemCount' => $allItems->count()
        ]);
    }

    /**
     * Remove a single item from cart
     */
    public function destroy($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }

        $userId = auth()->id();
        $cart = DB::table('carts')->where('UserID', $userId)->first();

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng không tồn tại.');
        }

        DB::table('cart_items')
            ->where('CartItemID', $id)
            ->where('CartID', $cart->CartID)
            ->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    /**
     * Clear all items from cart
     */
    public function destroyAll()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }

        $userId = auth()->id();
        $cart = DB::table('carts')->where('UserID', $userId)->first();

        if ($cart) {
            DB::table('cart_items')->where('CartID', $cart->CartID)->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng.');
    }
}
