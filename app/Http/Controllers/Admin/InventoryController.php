<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductStatus;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Hiển thị danh sách kho
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $stockStatus = $request->input('stock_status'); // all, low, out, in

        $products = Product::with(['category', 'status'])
            ->when($search, function($query) use ($search) {
                return $query->where(function($q) use ($search) {
                    $q->where('ProductCode', 'like', "%{$search}%")
                      ->orWhere('ProductName', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function($query) use ($categoryId) {
                return $query->where('CategoryID', $categoryId);
            })
            ->when($stockStatus, function($query) use ($stockStatus) {
                if ($stockStatus === 'low') {
                    return $query->where('Stock', '>', 0)->where('Stock', '<=', 10);
                } elseif ($stockStatus === 'out') {
                    return $query->where('Stock', 0);
                } elseif ($stockStatus === 'in') {
                    return $query->where('Stock', '>', 10);
                }
            })
            ->orderBy('Stock', 'asc')
            ->paginate(15);

        $categories = Category::orderBy('CategoryName')->get();
        $productStatuses = ProductStatus::all();

        // Thống kê
        $stats = [
            'total_products' => Product::count(),
            'total_stock' => Product::sum('Stock'),
            'low_stock' => Product::where('Stock', '>', 0)->where('Stock', '<=', 10)->count(),
            'out_of_stock' => Product::where('Stock', 0)->count(),
        ];

        return view('admin.inventory.index', compact('products', 'categories', 'productStatuses', 'stats'));
    }

    /**
     * Cập nhật số lượng tồn kho
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'Stock' => 'required|integer|min:0',
        ]);

        Product::where('ProductID', $id)->update([
            'Stock' => $request->Stock,
        ]);

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Cập nhật tồn kho thành công!');
    }

    /**
     * Trang chi tiết kho
     */
    public function show($id)
    {
        $product = Product::with(['category', 'status', 'images'])
            ->where('ProductID', $id)
            ->firstOrFail();

        // Lấy lịch sử nhập/xuất kho (từ order_details)
        $stockHistory = DB::table('order_details')
            ->select([
                'order_details.*',
                'orders.CreatedAt as order_date',
                'orders.Status as order_status',
                'users.FullName as customer_name'
            ])
            ->leftJoin('orders', 'order_details.OrderID', '=', 'orders.OrderID')
            ->leftJoin('users', 'orders.UserID', '=', 'users.UserID')
            ->where('order_details.ProductID', $id)
            ->whereNotNull('order_details.Quantity')
            ->orderBy('orders.CreatedAt', 'desc')
            ->limit(20)
            ->get();

        return view('admin.inventory.show', compact('product', 'stockHistory'));
    }

    /**
     * Thống kê kho
     */
    public function statistics()
    {
        $byCategory = Category::withCount('products')
            ->withSum('products', 'Stock')
            ->get();

        $lowStockProducts = Product::where('Stock', '>', 0)
            ->where('Stock', '<=', 10)
            ->orderBy('Stock', 'asc')
            ->limit(10)
            ->get();

        $outOfStockProducts = Product::where('Stock', 0)
            ->orderBy('ProductName')
            ->limit(10)
            ->get();

        return view('admin.inventory.statistics', compact('byCategory', 'lowStockProducts', 'outOfStockProducts'));
    }
}











