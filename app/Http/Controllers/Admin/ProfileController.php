<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $roles = Role::orderBy('RoleName')->get();

        return view('admin.profile.index', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

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
            'AvatarFile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if (!empty($validated['Password'])) {
            $validated['Password'] = Hash::make($validated['Password']);
        } else {
            unset($validated['Password']);
        }

        if ($request->hasFile('AvatarFile')) {
            $path = $request->file('AvatarFile')->store('avatars', 'public');
            $validated['Avatar'] = Storage::disk('public')->url($path);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.profile.edit')
            ->with('success', 'Cập nhật hồ sơ thành công.');
    }
}
