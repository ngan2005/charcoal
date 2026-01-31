<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->string('search')->trim();
        $roleId = $request->string('role')->trim();
        $status = $request->string('status')->trim();

        $query = User::query()->with('role');

        if ($search->isNotEmpty()) {
            $query->where(function ($q) use ($search) {
                $q->where('FullName', 'like', '%' . $search . '%')
                    ->orWhere('Email', 'like', '%' . $search . '%');
            });
        }

        if ($roleId->isNotEmpty()) {
            $query->where('RoleID', $roleId);
        }

        if ($status->isNotEmpty()) {
            $query->where('IsActive', $status === 'active' ? 1 : 0);
        }

        $users = $query->orderByDesc('UserID')->paginate(10)->withQueryString();
        $roles = Role::orderBy('RoleName')->get();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => [
                'search' => $search->toString(),
                'role' => $roleId->toString(),
                'status' => $status->toString(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'FullName' => ['required', 'string', 'max:100'],
            'Email' => ['required', 'email', 'max:100', 'unique:users,Email'],
            'Password' => ['required', 'string', 'min:6', 'confirmed'],
            'Phone' => ['nullable', 'string', 'max:15'],
            'Address' => ['nullable', 'string', 'max:255'],
            'RoleID' => ['required', 'integer', 'exists:roles,RoleID'],
            'IsActive' => ['required', 'boolean'],
            'AvatarFile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = $request->except('AvatarFile');
        $data['Password'] = Hash::make($validated['Password']);

        if ($request->hasFile('AvatarFile')) {
            $path = $request->file('AvatarFile')->store('avatars', 'public');
            $data['Avatar'] = Storage::disk('public')->url($path);
        }

        User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Tạo người dùng thành công.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'FullName' => ['required', 'string', 'max:100'],
            'Email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'Email')->ignore($user->UserID, 'UserID'),
            ],
            'Password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'Phone' => ['nullable', 'string', 'max:15'],
            'Address' => ['nullable', 'string', 'max:255'],
            'RoleID' => ['required', 'integer', 'exists:roles,RoleID'],
            'IsActive' => ['required', 'boolean'],
            'AvatarFile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = $request->except('AvatarFile');
        if (!empty($validated['Password'])) {
            $data['Password'] = Hash::make($validated['Password']);
        } else {
            unset($data['Password']);
        }

        if ($request->hasFile('AvatarFile')) {
            $path = $request->file('AvatarFile')->store('avatars', 'public');
            $data['Avatar'] = Storage::disk('public')->url($path);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Cập nhật người dùng thành công.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Xóa người dùng thành công.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:users,UserID'],
        ]);

        User::whereIn('UserID', $validated['ids'])->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Đã xóa các người dùng đã chọn.');
    }

    public function resetPassword(User $user)
    {
        $newPassword = Str::random(10);

        $user->update([
            'Password' => Hash::make($newPassword),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Đã đặt lại mật khẩu cho người dùng.')
            ->with('reset_password', [
                'name' => $user->FullName,
                'email' => $user->Email,
                'password' => $newPassword,
            ]);
    }
}
