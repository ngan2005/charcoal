<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $query = Product::query()->with(['category', 'status']);

        if ($search !== '') {
            $query->where(function ($inner) use ($search) {
                $inner->where('ProductName', 'like', '%' . $search . '%')
                    ->orWhere('ProductCode', 'like', '%' . $search . '%');
            });
        }

        $products = $query->orderByDesc('ProductID')->paginate(10)->withQueryString();
        $categories = Category::orderBy('CategoryName')->get();
        $statuses = ProductStatus::orderBy('StatusID')->get();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'statuses' => $statuses,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ProductName' => ['required', 'string', 'max:150'],
            'ProductCode' => ['nullable', 'string', 'max:30', 'unique:products,ProductCode'],
            'CategoryID' => ['required', 'integer', 'exists:categories,CategoryID'],
            'Price' => ['nullable', 'numeric', 'min:0'],
            'Weight' => ['nullable', 'numeric', 'min:0'],
            'Size' => ['nullable', 'string', 'max:50'],
            'Stock' => ['nullable', 'integer', 'min:0'],
            'StatusID' => ['required', 'integer', 'exists:product_status,StatusID'],
            'Description' => ['nullable', 'string', 'max:255'],
            'MainImage' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'GalleryImages' => ['nullable', 'array'],
            'GalleryImages.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data = $request->except(['MainImage', 'GalleryImages']);

        DB::transaction(function () use ($data, $request) {
            $product = Product::create($data);

            if ($request->hasFile('MainImage')) {
                $path = $request->file('MainImage')->store('products', 'public');
                ProductImage::create([
                    'ProductID' => $product->ProductID,
                    'ImageUrl' => Storage::disk('public')->url($path),
                    'IsMain' => 1,
                ]);
            }

            if ($request->hasFile('GalleryImages')) {
                foreach ($request->file('GalleryImages') as $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'ProductID' => $product->ProductID,
                        'ImageUrl' => Storage::disk('public')->url($path),
                        'IsMain' => 0,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Tạo sản phẩm thành công.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'ProductName' => ['required', 'string', 'max:150'],
            'ProductCode' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('products', 'ProductCode')->ignore($product->ProductID, 'ProductID'),
            ],
            'CategoryID' => ['required', 'integer', 'exists:categories,CategoryID'],
            'Price' => ['nullable', 'numeric', 'min:0'],
            'Weight' => ['nullable', 'numeric', 'min:0'],
            'Size' => ['nullable', 'string', 'max:50'],
            'Stock' => ['nullable', 'integer', 'min:0'],
            'StatusID' => ['required', 'integer', 'exists:product_status,StatusID'],
            'Description' => ['nullable', 'string', 'max:255'],
            'MainImage' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'GalleryImages' => ['nullable', 'array'],
            'GalleryImages.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $data = $request->except(['MainImage', 'GalleryImages']);

        DB::transaction(function () use ($product, $data, $request) {
            $product->update($data);

            if ($request->hasFile('MainImage')) {
                ProductImage::where('ProductID', $product->ProductID)
                    ->where('IsMain', 1)
                    ->update(['IsMain' => 0]);

                $path = $request->file('MainImage')->store('products', 'public');
                ProductImage::create([
                    'ProductID' => $product->ProductID,
                    'ImageUrl' => Storage::disk('public')->url($path),
                    'IsMain' => 1,
                ]);
            }

            if ($request->hasFile('GalleryImages')) {
                foreach ($request->file('GalleryImages') as $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'ProductID' => $product->ProductID,
                        'ImageUrl' => Storage::disk('public')->url($path),
                        'IsMain' => 0,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công.');
    }
}
