<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Apply voucher / Check voucher validity
     */
    public function applyVoucher(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập.'], 401);
        }

        $code = strtoupper(trim($request->code));
        $subtotal = $request->subtotal ?? 0;

        if (empty($code)) {
            return response()->json(['success' => false, 'message' => 'Vui lòng nhập mã giảm giá.']);
        }

        // Get voucher
        $voucher = DB::table('vouchers')
            ->where('Code', $code)
            ->where('IsActive', true)
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.']);
        }

        // Check expiration
        if ($voucher->ExpiredAt && now()->greaterThan($voucher->ExpiredAt)) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn.']);
        }

        // Check quantity
        if ($voucher->Quantity !== null && $voucher->Quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
        }

        // Check min order amount
        if ($voucher->MinOrderAmount && $subtotal < $voucher->MinOrderAmount) {
            $minAmount = number_format($voucher->MinOrderAmount, 0, ',', '.');
            return response()->json([
                'success' => false, 
                'message' => "Đơn hàng tối thiểu {$minAmount}đ để sử dụng mã này."
            ]);
        }

        // Calculate discount
        $discount = 0;
        $discountType = '';

        if ($voucher->DiscountPercent) {
            // Percentage discount
            $discount = ($subtotal * $voucher->DiscountPercent) / 100;
            $discountType = 'percent';
            
            // Free shipping (100% discount is treated as free shipping)
            if ($voucher->DiscountPercent == 100) {
                $discountType = 'freeship';
                $discount = 0; // Free shipping handled separately
            }
        } elseif ($voucher->DiscountAmount) {
            // Fixed amount discount
            $discount = min($voucher->DiscountAmount, $subtotal); // Don't exceed subtotal
            $discountType = 'fixed';
        }

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'voucher' => [
                'id' => $voucher->VoucherID,
                'code' => $voucher->Code,
                'description' => $voucher->Description,
                'discount' => $discount,
                'discount_display' => number_format($discount, 0, ',', '.'),
                'discount_type' => $discountType,
                'discount_percent' => $voucher->DiscountPercent,
            ]
        ]);
    }

    /**
     * Display checkout page
     */
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $userId = auth()->id();
        
        // Get cart
        $cart = DB::table('carts')->where('UserID', $userId)->first();
        
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        // Get cart items
        $cartItems = DB::table('cart_items')
            ->leftJoin('products', 'cart_items.ProductID', '=', 'products.ProductID')
            ->leftJoin('services', 'cart_items.ServiceID', '=', 'services.ServiceID')
            ->where('cart_items.CartID', $cart->CartID)
            ->select(
                'cart_items.*', 
                'products.ProductName', 
                'products.Price as ProductPrice',
                'products.ProductCode',
                'services.ServiceName', 
                'services.BasePrice as ServicePrice'
            )
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        // Get user info
        $user = DB::table('users')->where('UserID', $userId)->first();

        // Calculate subtotal
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price = $item->ProductID ? $item->ProductPrice : $item->ServicePrice;
            $subtotal += ($price * $item->Quantity);
        }

        return view('checkout.index', compact('cartItems', 'subtotal', 'user'));
    }

    /**
     * Process the order
     */
    public function process(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:cash,momo',
        ], [
            'shipping_name.required' => 'Vui lòng nhập họ tên.',
            'shipping_phone.required' => 'Vui lòng nhập số điện thoại.',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        $userId = auth()->id();
        
        // Get cart
        $cart = DB::table('carts')->where('UserID', $userId)->first();
        
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        // Get cart items (kèm Stock để kiểm tra tồn kho)
        $cartItems = DB::table('cart_items')
            ->leftJoin('products', 'cart_items.ProductID', '=', 'products.ProductID')
            ->leftJoin('services', 'cart_items.ServiceID', '=', 'services.ServiceID')
            ->where('cart_items.CartID', $cart->CartID)
            ->select(
                'cart_items.*',
                'products.Price as ProductPrice',
                'products.Stock as ProductStock',
                'products.ProductName',
                'services.BasePrice as ServicePrice'
            )
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }

        // Kiểm tra đủ tồn kho cho từng sản phẩm
        foreach ($cartItems as $item) {
            if ($item->ProductID !== null) {
                $stock = (int) ($item->ProductStock ?? 0);
                $qty = (int) $item->Quantity;
                if ($stock < $qty) {
                    $name = $item->ProductName ?? 'Sản phẩm';
                    return redirect()->back()->withInput()->with('error', "Sản phẩm \"{$name}\" không đủ tồn kho (còn {$stock}, bạn đặt {$qty}). Vui lòng giảm số lượng hoặc bỏ khỏi giỏ.");
                }
            }
        }

        // Calculate total
        $total = 0;
        $discountAmount = 0;
        
        foreach ($cartItems as $item) {
            $price = $item->ProductID ? $item->ProductPrice : $item->ServicePrice;
            $total += ($price * $item->Quantity);
        }

        // Apply voucher if provided
        $voucherId = null;
        if ($request->voucher_code) {
            $voucher = DB::table('vouchers')
                ->where('Code', strtoupper($request->voucher_code))
                ->where('IsActive', true)
                ->first();

            if ($voucher) {
                $voucherId = $voucher->VoucherID;
                
                // Calculate discount
                if ($voucher->DiscountPercent && $voucher->DiscountPercent < 100) {
                    $discountAmount = ($total * $voucher->DiscountPercent) / 100;
                } elseif ($voucher->DiscountAmount) {
                    $discountAmount = min($voucher->DiscountAmount, $total);
                }

                // Decrease voucher quantity
                DB::table('vouchers')
                    ->where('VoucherID', $voucherId)
                    ->decrement('Quantity', 1);
            }
        }

        $finalTotal = max(0, $total - $discountAmount);

        // Generate order code
        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());

        // Create order
        $orderId = DB::table('orders')->insertGetId([
            'UserID' => $userId,
            'OrderCode' => $orderCode,
            'TotalAmount' => $finalTotal,
            'Status' => 'pending',
            'ShippingName' => $request->shipping_name,
            'ShippingPhone' => $request->shipping_phone,
            'ShippingAddress' => $request->shipping_address,
            'PaymentMethod' => $request->payment_method,
            'PaymentStatus' => 'unpaid',
            'VoucherID' => $voucherId,
            'DiscountAmount' => $discountAmount,
            'CreatedAt' => now(),
            'UpdatedAt' => now(),
        ]);

        // Create order details
        foreach ($cartItems as $item) {
            $price = $item->ProductID ? $item->ProductPrice : $item->ServicePrice;

            DB::table('order_details')->insert([
                'OrderID' => $orderId,
                'ProductID' => $item->ProductID,
                'ServiceID' => $item->ServiceID,
                'PetID' => $item->PetID,
                'Quantity' => $item->Quantity,
                'UnitPrice' => $price,
            ]);

            if ($item->ProductID) {
                // Trừ tồn kho khi đặt hàng
                DB::table('products')
                    ->where('ProductID', $item->ProductID)
                    ->decrement('Stock', $item->Quantity);

                // Cập nhật số lượng đã bán (PurchaseCount)
                DB::table('products')
                    ->where('ProductID', $item->ProductID)
                    ->increment('PurchaseCount', $item->Quantity);
            }
        }

        // Clear cart
        DB::table('cart_items')->where('CartID', $cart->CartID)->delete();

        // Redirect based on payment method
        if ($request->payment_method === 'momo') {
            return $this->processMoMo($orderId, $total, $orderCode);
        }

        // Cash on delivery - redirect to success page
        return redirect()->route('checkout.success', $orderId);
    }

    /**
     * Order success page
     */
    public function success($orderId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $order = DB::table('orders')
            ->where('OrderID', $orderId)
            ->where('UserID', auth()->id())
            ->first();

        if (!$order) {
            return redirect()->route('shop')->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Get order details
        $orderDetails = DB::table('order_details')
            ->leftJoin('products', 'order_details.ProductID', '=', 'products.ProductID')
            ->leftJoin('services', 'order_details.ServiceID', '=', 'services.ServiceID')
            ->where('order_details.OrderID', $orderId)
            ->select(
                'order_details.*',
                'products.ProductName',
                'products.ProductCode',
                'products.Price as ProductPrice',
                'services.ServiceName',
                'services.BasePrice as ServicePrice'
            )
            ->get();

        return view('checkout.success', compact('order', 'orderDetails'));
    }

    /**
     * Process MoMo payment (placeholder for future)
     */
    private function processMoMo($orderId, $amount, $orderCode)
    {
        return redirect()->route('checkout.success', $orderId)->with('info', 'Tính năng thanh toán MoMo đang được tích hợp. Đơn hàng của bạn đã được tạo.');
    }
}
