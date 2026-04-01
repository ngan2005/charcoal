<?php

// ============================================================
// NAMESPACE - Khai bao vung ten controller thuoc nhom Admin
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;       // Class co so, ke thua tu Controller
use App\Models\CartItem;                   // Model CartItem de xoa khi xoa san pham
use App\Models\Category;                   // Model Category - danh muc san pham
use App\Models\Product;                    // Model Product - san pham
use App\Models\ProductImage;               // Model ProductImage - anh san pham
use App\Models\ProductStatus;              // Model ProductStatus - trang thai san pham
use Illuminate\Http\Request;                // Class xu ly request HTTP
use Illuminate\Support\Facades\DB;          // Facade truy van database (dung DB::transaction)
use Illuminate\Support\Facades\Storage;     // Facade thao tac file storage (xoa anh)
use Illuminate\Validation\Rule;             // Class validation - kiem tra unique voi ignore

// ============================================================
// CLASS PRODUCTCONTROLLER - Controller quan ly san pham (CRUD)
// ============================================================
class ProductController extends Controller
{
    // ========================================================
    // METHOD: HIEN THI DANH SACH SAN PHAM
    // Route: GET /admin/products
    // Hien thi bang danh sach + tim kiem + phan trang
    // ========================================================
    public function index(Request $request)
    {
        // Lay gia tri search tu query string, loai bo khoang trang thua
        $search = trim((string) $request->input('search', ''));

        // Tao query builder tren model Product
        // ->with(['category', 'status']) = eager load, lay san category va status cung luc
        // Tranh loi N+1 query khi hien thi category_name va status_name
        $query = Product::query()->with(['category', 'status']);

        // Neu co tu khoa tim kiem -> loc theo ProductName hoac ProductCode
        if ($search !== '') {
            $query->where(function ($inner) use ($search) {
                // LIKE '%$search%' tim khong phan biet vi tri
                $inner->where('ProductName', 'like', '%' . $search . '%')
                    ->orWhere('ProductCode', 'like', '%' . $search . '%');
            });
        }

        // orderByDesc('ProductID') -> sap xep moi nhat truoc
        // ->paginate(10) -> phan trang 10 san pham 1 trang
        // ->withQueryString() -> giu nguyen query string khi chuyen trang
        $products = $query->orderByDesc('ProductID')->paginate(10)->withQueryString();

        // Lay danh sach categories de hien thi dropdown loc
        $categories = Category::orderBy('CategoryName')->get();

        // Lay danh sach statuses de hien thi dropdown loc
        $statuses = ProductStatus::orderBy('StatusID')->get();

        // Tra ve view voi cac bien: products, categories, statuses, filters
        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'statuses' => $statuses,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    // ========================================================
    // METHOD: TAO SAN PHAM MOI
    // Route: POST /admin/products
    // Validate, tao san pham, xu ly anh upload
    // ========================================================
    public function store(Request $request)
    {
        // -------------------- BUOC 1: VALIDATE DU LIEU --------------------
        $validated = $request->validate([
            'ProductName' => ['required', 'string', 'max:150'],           // Bat buoc, chuoi, toi da 150 ky tu
            'ProductCode' => ['nullable', 'string', 'max:30', 'unique:products,ProductCode'],  // Khong bat buoc, chi duy nhat
            'CategoryID' => ['required', 'integer', 'exists:categories,CategoryID'], // Phai ton tai trong bang categories
            'Price' => ['nullable', 'numeric', 'min:0'],                  // Khong bat buoc, so, >= 0
            'Weight' => ['nullable', 'numeric', 'min:0'],                // Trong luong, >= 0
            'Size' => ['nullable', 'string', 'max:50'],                  // Kich thuoc, toi da 50 ky tu
            'Stock' => ['nullable', 'integer', 'min:0'],                 // So luong ton kho, >= 0
            'StatusID' => ['required', 'integer', 'exists:product_status,StatusID'], // Phai ton tai trong bang product_status
            'Description' => ['nullable', 'string', 'max:5000'],          // Mo ta, toi da 5000 ky tu
            'images' => ['nullable', 'array'],                           // Mang chua cac file anh
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // Moi file anh: dinh dang image, kich thuoc <= 5MB
        ]);

        // Lay tat ca du lieu TUNGOAI TRU 'images' (vi xu ly rieng o duoi)
        $data = $request->except(['images']);

        // -------------------- BUOC 2: TAO SAN PHAM + ANH (TRANSACTION) --------------------
        // DB::transaction() dam bao tinh toan ven: neu 1 buoc loi -> rollback toan bo
        DB::transaction(function () use ($data, $request) {
            // Tao san pham moi trong bang products
            $product = Product::create($data);

            // Lay danh sach file anh tu request, mac dinh mang rong []
            $files = $request->file('images', []);
            
            // Kiem tra va xu ly tung file anh
            if (!empty($files) && is_array($files)) {
                foreach ($files as $index => $file) {
                    // $file->isValid() kiem tra file upload thanh cong
                    // $file->getSize() > 0 kiem tra file khong rong
                    if ($file && $file->isValid() && $file->getSize() > 0) {
                        // Tao ten file: timestamp_uniqueid_index.ext
                        // VD: 1710991234_a1b2c3_1.jpg
                        $filename = time() . '_' . uniqid() . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                        
                        // Duong dan luu: storage/app/public/products/
                        $destinationPath = storage_path('app/public/products');
                        
                        // Neu thu muc chua ton tai -> tao moi voi quyen 0755
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        // Di chuyen file den thu muc dich
                        $file->move($destinationPath, $filename);
                        
                        // Tao ban ghi ProductImage trong database
                        // $index === 0 -> anh dau tien la anh chinh (IsMain = 1)
                        ProductImage::create([
                            'ProductID' => $product->ProductID,
                            'ImageUrl' => asset('storage/products/' . $filename),  // asset() tao URL day du
                            'IsMain' => $index === 0 ? 1 : 0,                        // Anh dau tien la chinh
                        ]);
                    }
                }
            }
        });

        // -------------------- BUOC 3: CHUYEN HUONG --------------------
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Tao san pham thanh cong.');
    }

    // ========================================================
    // METHOD: CAP NHAT SAN PHAM
    // Route: PUT/PATCH /admin/products/{product}
    // Validate, cap nhat thong tin, xu ly xoa/doi anh chinh/them anh moi
    // ========================================================
    public function update(Request $request, Product $product)
    {
        // -------------------- BUOC 1: VALIDATE DU LIEU --------------------
        $validated = $request->validate([
            'ProductName' => ['required', 'string', 'max:150'],
            'ProductCode' => [
                'nullable',
                'string',
                'max:30',
                // Rule::unique() kiem tra unique nhung BO QUA chinh no
                // ->ignore($product->ProductID, 'ProductID') -> khong so sanh voi chinh no
                Rule::unique('products', 'ProductCode')->ignore($product->ProductID, 'ProductID'),
            ],
            'CategoryID' => ['required', 'integer', 'exists:categories,CategoryID'],
            'Price' => ['nullable', 'numeric', 'min:0'],
            'Weight' => ['nullable', 'numeric', 'min:0'],
            'Size' => ['nullable', 'string', 'max:50'],
            'Stock' => ['nullable', 'integer', 'min:0'],
            'StatusID' => ['required', 'integer', 'exists:product_status,StatusID'],
            'Description' => ['nullable', 'string', 'max:5000'],
            'DeleteImageIDs' => ['nullable', 'string'],   // Chuoi ID anh can xoa (VD: "1,2,3")
            'MainImageID' => ['nullable', 'integer'],    // ID anh duoc dat lam anh chinh
            'new_images' => ['nullable', 'array'],        // Mang anh moi them
            'new_images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // Gioi han 5MB
        ]);

        // Loai bo cac truong xu ly rieng khoi data cap nhat
        $data = $request->except(['DeleteImageIDs', 'MainImageID', 'new_images']);

        // -------------------- BUOC 2: CAP NHAT + XU LY ANH (TRANSACTION) --------------------
        DB::transaction(function () use ($product, $data, $request) {
            // Cap nhat thong tin san pham
            $product->update($data);

            // ============== XOA ANH ==============
            // Lay chuoi ID anh can xoa (vi du: "1,2,3")
            $deleteIds = $request->input('DeleteImageIDs');
            if (!empty($deleteIds)) {
                // Chuyen chuoi "1,2,3" thanh mang [1, 2, 3]
                $ids = array_map('intval', explode(',', $deleteIds));
                
                // Lay danh sach anh can xoa
                $imagesToDelete = ProductImage::whereIn('ImageID', $ids)->get();
                
                foreach ($imagesToDelete as $img) {
                    // Trich xuat ten file tu URL
                    // URL: http://localhost/storage/products/filename.jpg
                    // -> Path: products/filename.jpg
                    $url = $img->ImageUrl;
                    $path = str_replace(asset('storage/'), '', $url);
                    
                    // Kiem tra file co ton tai trong storage khong
                    if (Storage::disk('public')->exists($path)) {
                        // Xoa file khoi storage
                        Storage::disk('public')->delete($path);
                    }
                    
                    // Xoa ban ghi khoi database
                    $img->delete();
                }
            }

            // ============== DAT ANH CHINH ==============
            $mainImageId = $request->input('MainImageID');
            if (!empty($mainImageId)) {
                // Reset tat ca anh ve IsMain = 0 (khong phai anh chinh)
                ProductImage::where('ProductID', $product->ProductID)->update(['IsMain' => 0]);
                // Dat anh duoc chon lam anh chinh
                ProductImage::where('ImageID', $mainImageId)->update(['IsMain' => 1]);
            }

            // ============== THEM ANH MOI ==============
            $newFiles = $request->file('new_images', []);
            if (!empty($newFiles) && is_array($newFiles)) {
                foreach ($newFiles as $index => $file) {
                    if ($file && $file->isValid() && $file->getSize() > 0) {
                        // Ten file co them '_new' de phan biet voi anh cu
                        $filename = time() . '_new_' . uniqid() . '_' . ($index + 1) . '.' . $file->getClientOriginalExtension();
                        $destinationPath = storage_path('app/public/products');
                        
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        
                        $file->move($destinationPath, $filename);
                        
                        // Anh moi mac dinh la IsMain = 0
                        ProductImage::create([
                            'ProductID' => $product->ProductID,
                            'ImageUrl' => asset('storage/products/' . $filename),
                            'IsMain' => 0,
                        ]);
                    }
                }
            }
        });

        // -------------------- BUOC 3: CHUYEN HUONG --------------------
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Cap nhat san pham thanh cong.');
    }

    // ========================================================
    // METHOD: XOA SAN PHAM
    // Route: DELETE /admin/products/{product}
    // Xoa san pham + xoa tat ca anh + xoa lien quan (order details, cart items)
    // ========================================================
    public function destroy(Product $product)
    {
        // -------------------- TRANSACTION: XOA TOAN BO LIEN QUAN --------------------
        DB::transaction(function () use ($product) {
            // Xoa tat ca anh cua san pham nay trong bang product_images
            $product->images()->delete();
            
            // Xoa tat ca chi tiet don hang chua san pham nay
            $product->orderDetails()->delete();
            
            // Xoa khoi gio hang cua khach hang
            CartItem::where('ProductID', $product->ProductID)->delete();
            
            // Xoa san pham chinh
            $product->delete();
        });

        // -------------------- CHUYEN HUONG --------------------
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Xoa san pham thanh cong.');
    }

    // ========================================================
    // METHOD: LAY DANH SACH ANH (API JSON)
    // Route: GET /admin/products/{product}/images
    // Tra ve JSON chua danh sach anh cua san pham
    // ========================================================
    public function getImages(Product $product)
    {
        // Lay anh, chi chon cac cot can thiet
        $images = $product->images()->select(['ImageID', 'ImageUrl', 'IsMain'])->get();
        
        // Tra ve JSON: {"images": [...]}
        return response()->json(['images' => $images]);
    }
}
