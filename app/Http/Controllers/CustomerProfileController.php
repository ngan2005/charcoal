<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CustomerProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Load recent orders
        $orders = \Illuminate\Support\Facades\DB::table('orders')
            ->where('UserID', $user->UserID)
            ->orderByDesc('CreatedAt')
            ->take(5)
            ->get();

        // Load pets of current user (for "Thú cưng của tôi" in profile)
        $pets = Pet::where('OwnerID', $user->UserID)->with('images')->orderBy('PetName')->get();
            
        return view('profile.index', compact('user', 'orders', 'pets'));
    }

    public function update(Request $request)
    {
        $user = User::find(auth()->id());
        
        $request->validate([
            'FullName' => 'required|string|max:100',
            'Phone' => 'nullable|string|max:15',
            'Address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = [
            'FullName' => $request->FullName,
            'Phone' => $request->Phone,
            'Address' => $request->Address,
        ];

        // Handle Avatar Upload - dùng get() thay vì storeAs để tránh lỗi "Path must not be empty" trên Windows
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->isValid() && $file->getSize() > 0) {
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                if (!$filename || strpos($filename, '_.') !== false) {
                    $ext = $file->getClientOriginalExtension() ?: 'jpg';
                    $filename = time() . '_avatar.' . strtolower($ext);
                }
                Storage::disk('public')->makeDirectory('avatars');
                $relativePath = 'avatars/' . $filename;
                Storage::disk('public')->put($relativePath, $file->get());
                if ($user->Avatar && Storage::disk('public')->exists($user->Avatar)) {
                    Storage::disk('public')->delete($user->Avatar);
                }
                $user->Avatar = $relativePath;
                $user->save();
            }
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::find(auth()->id());

        if (!Hash::check($request->current_password, $user->Password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        $user->update([
            'Password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Đổi mật khẩu thành công!');
    }
}
