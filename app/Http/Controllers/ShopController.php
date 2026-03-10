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
        // Lấy danh mục sản phẩm và danh sách dịch vụ (cho bộ lọc)
        $categories = Category::all();
        $allServices = \App\Models\Service::all();
        
        // --- Xử lý Sản phẩm ---
        $productQuery = Product::with(['category', 'images'])
            ->where('StatusID', 1);
        
        if ($request->category) {
            $categoryIds = explode(',', $request->category);
            $productQuery->whereIn('CategoryID', $categoryIds);
        }
        
        if ($request->price && $request->price !== 'all') {
            $priceRange = explode('-', $request->price);
            if (count($priceRange) === 2) {
                $productQuery->whereBetween('Price', [(int)$priceRange[0], (int)$priceRange[1]]);
            }
        }
        
        if ($request->search) {
            $productQuery->where('ProductName', 'like', '%' . $request->search . '%');
        }
        
        // Sắp xếp sản phẩm
        $this->applySorting($productQuery, $request->sort);
        $products = $productQuery->paginate(20)->appends($request->query());

        // --- Xử lý Dịch vụ ---
        $serviceQuery = \App\Models\Service::with('images');
        
        if ($request->service) {
            $serviceIds = explode(',', $request->service);
            $serviceQuery->whereIn('ServiceID', $serviceIds);
        }

        if ($request->search) {
            $serviceQuery->where('ServiceName', 'like', '%' . $request->search . '%');
        }

        $services = $serviceQuery->get();
        
        // Lấy sản phẩm nổi bật
        $featuredProducts = Product::with(['category', 'images'])
            ->where('StatusID', 1)
            ->orderBy('PurchaseCount', 'desc')
            ->limit(8)
            ->get();

        return view('shop', compact('products', 'featuredProducts', 'categories', 'allServices', 'services'));
    }

    /**
     * Helper to apply sorting to queries
     */
    private function applySorting($query, $sort)
    {
        switch ($sort) {
            case 'price_asc': $query->orderBy('Price', 'asc'); break;
            case 'price_desc': $query->orderBy('Price', 'desc'); break;
            case 'popular': $query->orderBy('PurchaseCount', 'desc'); break;
            case 'newest':
            default: $query->orderBy('CreatedAt', 'desc'); break;
        }
    }

    /**
     * Display the product detail page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with(['category', 'images'])
            ->where('ProductID', $id)
            ->where('StatusID', 1)
            ->firstOrFail();

        // Lấy sản phẩm liên quan (cùng danh mục, không bao gồm chính nó)
        $relatedProducts = Product::with(['category', 'images'])
            ->where('CategoryID', $product->CategoryID)
            ->where('ProductID', '!=', $id)
            ->where('StatusID', 1)
            ->limit(4)
            ->get();

        return view('product-detail', compact('product', 'relatedProducts'));
    }
}
