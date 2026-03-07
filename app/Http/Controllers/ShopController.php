<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display the shop page with real products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Lấy danh mục sản phẩm
        $categories = Category::all();
        
        // Xây dựng query sản phẩm
        $query = Product::with(['category', 'images'])
            ->where('StatusID', 1); // Chỉ lấy sản phẩm đang hoạt động
        
        // Lọc theo danh mục (category)
        if ($request->category) {
            $categoryIds = explode(',', $request->category);
            $query->whereIn('CategoryID', $categoryIds);
        }
        
        // Lọc theo khoảng giá
        if ($request->price && $request->price !== 'all') {
            $priceRange = explode('-', $request->price);
            if (count($priceRange) === 2) {
                $query->whereBetween('Price', [(int)$priceRange[0], (int)$priceRange[1]]);
            }
        }
        
        // Tìm kiếm theo tên
        if ($request->search) {
            $query->where('ProductName', 'like', '%' . $request->search . '%');
        }
        
        // Sắp xếp
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('Price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('Price', 'desc');
                break;
            case 'popular':
                $query->orderBy('PurchaseCount', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('CreatedAt', 'desc');
                break;
        }
        
        // Phân trang
        $products = $query->paginate(12)->appends($request->query());
        
        // Lấy sản phẩm nổi bật (cho phần hero hoặc featured)
        $featuredProducts = Product::with(['category', 'images'])
            ->where('StatusID', 1)
            ->orderBy('PurchaseCount', 'desc')
            ->limit(8)
            ->get();

        return view('shop', compact('products', 'featuredProducts', 'categories'));
    }
}
