<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    /**
     * Hiển thị danh sách thú cưng của người dùng đang đăng nhập
     */
    public function index()
    {
        $pets = Pet::where('OwnerID', Auth::id())
            ->with('images')
            ->orderBy('PetName')
            ->get();

        return view('pets.index', compact('pets'));
    }

    /**
     * Hiển thị form thêm thú cưng mới
     */
    public function create()
    {
        return view('pets.create');
    }

    /**
     * Lưu thú cưng mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'PetName' => ['required', 'string', 'max:100'],
            'Species' => ['required', 'string', 'max:50'],
            'Breed' => ['nullable', 'string', 'max:50'],
            'Size' => ['nullable', 'string', 'max:50'],
            'Age' => ['nullable', 'integer', 'min:0'],
            'Notes' => ['nullable', 'string', 'max:255'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ]);

        $pet = Pet::create([
            'OwnerID' => Auth::id(),
            'PetName' => $validated['PetName'],
            'Species' => $validated['Species'],
            'Breed' => $validated['Breed'] ?? null,
            'Size' => $validated['Size'] ?? null,
            'Age' => $validated['Age'] ?? null,
            'Notes' => $validated['Notes'] ?? null,
        ]);

        // Handle image uploads - chỉ xử lý file thực sự được tải lên (path không rỗng)
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (!is_array($files)) {
                $files = [$files];
            }
            $files = array_filter($files, function ($file) {
                return $file && $file->isValid() && $file->getSize() > 0;
            });
            $files = array_values($files);
            Storage::disk('public')->makeDirectory('pet_images');
            foreach ($files as $index => $image) {
                $filename = time() . '_' . $index . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $image->getClientOriginalName());
                if (!$filename || strpos($filename, '_.') !== false) {
                    $ext = $image->getClientOriginalExtension() ?: 'jpg';
                    $filename = time() . '_pet_' . $index . '.' . strtolower($ext);
                }
                $relativePath = 'pet_images/' . $filename;
                Storage::disk('public')->put($relativePath, $image->get());
                PetImage::create([
                    'PetID' => $pet->PetID,
                    'ImageUrl' => $relativePath,
                    'IsMain' => $index === 0 ? 1 : 0,
                ]);
            }
        }

        return redirect()
            ->route('profile.index')
            ->with('success', 'Thêm thú cưng thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa thú cưng
     */
    public function edit(Pet $pet)
    {
        // Kiểm tra quyền sở hữu
        if ($pet->OwnerID !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa thú cưng này.');
        }

        $pet->load('images');

        return view('pets.edit', compact('pet'));
    }

    /**
     * Cập nhật thông tin thú cưng
     */
    public function update(Request $request, Pet $pet)
    {
        // Kiểm tra quyền sở hữu
        if ($pet->OwnerID !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa thú cưng này.');
        }

        $request->validate([
            'PetName' => ['required', 'string', 'max:100'],
            'Species' => ['required', 'string', 'max:50'],
            'Breed' => ['nullable', 'string', 'max:50'],
            'Size' => ['nullable', 'string', 'max:50'],
            'Age' => ['nullable', 'integer', 'min:0'],
            'Notes' => ['nullable', 'string', 'max:255'],
            'images.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer'],
            'set_main' => ['nullable', 'integer'],
        ]);

        // Chỉ cập nhật các trường thuộc bảng pets, tránh đưa file/array vào model
        $pet->update([
            'PetName' => $request->PetName,
            'Species' => $request->Species,
            'Breed' => $request->Breed ?? null,
            'Size' => $request->Size ?? null,
            'Age' => $request->Age ?? null,
            'Notes' => $request->Notes ?? null,
        ]);

        // Handle new image uploads - dùng put() thay vì store() để tránh lỗi "Path must not be empty" trên Windows
        if ($request->hasFile('images')) {
            $files = $request->file('images');
            if (!is_array($files)) {
                $files = [$files];
            }
            $files = array_filter($files, function ($file) {
                return $file && $file->isValid() && $file->getSize() > 0;
            });
            $files = array_values($files);
            Storage::disk('public')->makeDirectory('pet_images');
            $existingCount = $pet->images()->count();
            foreach ($files as $index => $image) {
                $filename = time() . '_' . $index . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $image->getClientOriginalName());
                if (!$filename || strpos($filename, '_.') !== false) {
                    $ext = $image->getClientOriginalExtension() ?: 'jpg';
                    $filename = time() . '_pet_' . $index . '.' . strtolower($ext);
                }
                $relativePath = 'pet_images/' . $filename;
                Storage::disk('public')->put($relativePath, $image->get());
                PetImage::create([
                    'PetID' => $pet->PetID,
                    'ImageUrl' => $relativePath,
                    'IsMain' => ($existingCount === 0 && $index === 0) ? 1 : 0,
                ]);
            }
        }

        // Handle image deletion
        if ($request->has('delete_images')) {
            $deleteIds = $request->delete_images;
            $imagesToDelete = PetImage::whereIn('ImageID', $deleteIds)->where('PetID', $pet->PetID)->get();
            
            foreach ($imagesToDelete as $image) {
                if ($image->ImageUrl && Storage::disk('public')->exists($image->ImageUrl)) {
                    Storage::disk('public')->delete($image->ImageUrl);
                }
                $image->delete();
            }
        }

        // Handle set main image
        if ($request->has('set_main') && $request->set_main) {
            PetImage::where('PetID', $pet->PetID)->update(['IsMain' => 0]);
            PetImage::where('ImageID', $request->set_main)->update(['IsMain' => 1]);
        }

        return redirect()
            ->route('profile.index')
            ->with('success', 'Cập nhật thú cưng thành công!');
    }

    /**
     * Xóa thú cưng
     */
    public function destroy(Pet $pet)
    {
        // Kiểm tra quyền sở hữu
        if ($pet->OwnerID !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa thú cưng này.');
        }

        // Delete images from storage
        foreach ($pet->images as $image) {
            if ($image->ImageUrl && Storage::disk('public')->exists($image->ImageUrl)) {
                Storage::disk('public')->delete($image->ImageUrl);
            }
        }

        $pet->delete();

        return redirect()
            ->route('profile.index')
            ->with('success', 'Xóa thú cưng thành công!');
    }
}

















