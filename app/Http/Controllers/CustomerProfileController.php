<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
            
        return view('profile.index', compact('user', 'orders'));
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

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['Avatar'] = $avatarPath;
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
