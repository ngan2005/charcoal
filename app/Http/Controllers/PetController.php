<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    /**
     * Hiển thị danh sách thú cưng của người dùng đang đăng nhập
     */
    public function index()
    {
        $pets = Pet::where('OwnerID', Auth::id())
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
        ]);

        Pet::create([
            'OwnerID' => Auth::id(),
            'PetName' => $validated['PetName'],
            'Species' => $validated['Species'],
            'Breed' => $validated['Breed'] ?? null,
            'Size' => $validated['Size'] ?? null,
            'Age' => $validated['Age'] ?? null,
            'Notes' => $validated['Notes'] ?? null,
        ]);

        return redirect()
            ->route('pets.index')
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

        $validated = $request->validate([
            'PetName' => ['required', 'string', 'max:100'],
            'Species' => ['required', 'string', 'max:50'],
            'Breed' => ['nullable', 'string', 'max:50'],
            'Size' => ['nullable', 'string', 'max:50'],
            'Age' => ['nullable', 'integer', 'min:0'],
            'Notes' => ['nullable', 'string', 'max:255'],
        ]);

        $pet->update($validated);

        return redirect()
            ->route('pets.index')
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

        $pet->delete();

        return redirect()
            ->route('pets.index')
            ->with('success', 'Xóa thú cưng thành công!');
    }
}





