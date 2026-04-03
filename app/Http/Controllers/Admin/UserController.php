<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Trang danh sách cần quay lại sau tạo / xóa / reset mật khẩu (từ hidden _redirect).
     */
    protected function intendedUserListRoute(Request $request): string
    {
        $target = (string) $request->input('_redirect', '');

        return match ($target) {
            'admin.users.admins', 'admin.users.staff', 'admin.users.customers' => $target,
            default => 'admin.users.index',
        };
    }

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
            $file = $request->file('AvatarFile');
            if ($file->isValid() && $file->getSize() > 0) {
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                if (!$filename || strpos($filename, '_.') !== false) {
                    $ext = $file->getClientOriginalExtension() ?: 'jpg';
                    $filename = time() . '_avatar.' . strtolower($ext);
                }
                Storage::disk('public')->makeDirectory('avatars');
                $relativePath = 'avatars/' . $filename;
                Storage::disk('public')->put($relativePath, $file->get());
                $data['Avatar'] = $relativePath;
            }
        }

        User::create($data);

        return redirect()
            ->route($this->intendedUserListRoute($request))
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
            $file = $request->file('AvatarFile');
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
                $data['Avatar'] = $relativePath;
            }
        }

        $user->update($data);

        return redirect()
            ->route($this->intendedUserListRoute($request))
            ->with('success', 'Cập nhật người dùng thành công.');
    }

    public function destroy(Request $request, User $user)
    {
        if ((int) $user->UserID === (int) Auth::id()) {
            return redirect()
                ->route($this->intendedUserListRoute($request))
                ->with('error', 'Không thể xóa tài khoản của chính bạn.');
        }

        try {
            $user->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'foreign key constraint')) {
                return redirect()
                    ->route($this->intendedUserListRoute($request))
                    ->with('error', 'Không thể xóa người dùng này vì còn dữ liệu liên quan (lịch hẹn, đơn hàng, thú cưng, v.v.). Vui lòng vô hiệu hóa tài khoản thay vì xóa.');
            }
            throw $e;
        }

        return redirect()
            ->route($this->intendedUserListRoute($request))
            ->with('success', 'Xóa người dùng thành công.');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:users,UserID'],
        ]);

        try {
            User::whereIn('UserID', $validated['ids'])->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'foreign key constraint')) {
                return redirect()
                    ->route('admin.users.index')
                    ->with('error', 'Không thể xóa một số người dùng vì còn dữ liệu liên quan (lịch hẹn, đơn hàng, thú cưng, v.v.). Vui lòng vô hiệu hóa tài khoản thay vì xóa.');
            }
            throw $e;
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Đã xóa các người dùng đã chọn.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $newPassword = '123456';

        $user->update([
            'Password' => Hash::make($newPassword),
        ]);

        return redirect()
            ->route($this->intendedUserListRoute($request))
            ->with('success', 'Đã đặt lại mật khẩu cho người dùng.')
            ->with('reset_password', [
                'name' => $user->FullName,
                'email' => $user->Email,
                'password' => $newPassword,
            ]);
    }
}
