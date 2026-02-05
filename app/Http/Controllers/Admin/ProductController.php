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
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data = $request->except(['images']);

        DB::transaction(function () use ($data, $request) {
            $product = Product::create($data);

            // Xử lý ảnh - ảnh đầu tiên là ảnh chính
            $files = $request->file('images', []);
            
            if (!empty($files) && is_array($files)) {
                foreach ($files as $index => $file) {
                    if ($file && $file->isValid() && $file->getSize() > 0) {
                        $filename = time() . '_' . uniqid() . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                        $destinationPath = storage_path('app/public/products');
                        
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        $file->move($destinationPath, $filename);
                        
                        ProductImage::create([
                            'ProductID' => $product->ProductID,
                            'ImageUrl' => asset('storage/products/' . $filename),
                            'IsMain' => $index === 0 ? 1 : 0,
                        ]);
                    }
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
            'DeleteImageIDs' => ['nullable', 'string'],
            'MainImageID' => ['nullable', 'integer'],
            'new_images' => ['nullable', 'array'],
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data = $request->except(['DeleteImageIDs', 'MainImageID', 'new_images']);

        DB::transaction(function () use ($product, $data, $request) {
            $product->update($data);

            // Xóa ảnh được chọn
            $deleteIds = $request->input('DeleteImageIDs');
            if (!empty($deleteIds)) {
                $ids = array_map('intval', explode(',', $deleteIds));
                $imagesToDelete = ProductImage::whereIn('ImageID', $ids)->get();
                
                foreach ($imagesToDelete as $img) {
                    // Trích xuất tên file từ URL (ví dụ: http://.../storage/products/filename.jpg -> products/filename.jpg)
                    $url = $img->ImageUrl;
                    $path = str_replace(asset('storage/'), '', $url);
                    
                    if (Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                    
                    $img->delete();
                }
            }

            // Đặt ảnh chính
            $mainImageId = $request->input('MainImageID');
            if (!empty($mainImageId)) {
                // Reset tất cả ảnh về không phải chính
                ProductImage::where('ProductID', $product->ProductID)->update(['IsMain' => 0]);
                // Đặt ảnh được chọn làm chính
                ProductImage::where('ImageID', $mainImageId)->update(['IsMain' => 1]);
            }

            // Thêm ảnh mới
            $newFiles = $request->file('new_images', []);
            if (!empty($newFiles) && is_array($newFiles)) {
                foreach ($newFiles as $index => $file) {
                    if ($file && $file->isValid() && $file->getSize() > 0) {
                        $filename = time() . '_new_' . uniqid() . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                        $destinationPath = storage_path('app/public/products');
                        
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        $file->move($destinationPath, $filename);
                        
                        ProductImage::create([
                            'ProductID' => $product->ProductID,
                            'ImageUrl' => asset('storage/products/' . $filename),
                            'IsMain' => 0,
                        ]);
                    }
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

    // API: Lấy danh sách ảnh của sản phẩm
    public function getImages(Product $product)
    {
        $images = $product->images()->select(['ImageID', 'ImageUrl', 'IsMain'])->get();
        return response()->json(['images' => $images]);
    }
}


