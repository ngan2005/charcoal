<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        DB::table('users')->insert([
            'FullName' => 'Administrator',
            'Email' => 'admin@pinkcharcoal.vn',
            'Password' => Hash::make('password'),
            'RoleID' => 1,
            'Avatar' => 'https://ui-avatars.com/api/?name=Administrator&background=0D8ABC&color=fff',
        ]);

        // Thêm Admin khác (Ví dụ)
        // DB::table('users')->insert([
        //     'FullName' => 'Admin 2',
        //     'Email' => 'admin2@pinkcharcoal.vn',
        //     'Password' => Hash::make('password'),
        //     'RoleID' => 1, // Role 1 là Admin
        //     'Avatar' => '...',
        // ]);

        // Staff
        DB::table('users')->insert([
            'FullName' => 'Minh Anh',
            'Email' => 'staff@pinkcharcoal.vn',
            'Password' => Hash::make('password'),
            'RoleID' => 2,
            'Avatar' => 'https://ui-avatars.com/api/?name=Minh+Anh&background=random',
        ]);

        // Client
        DB::table('users')->insert([
            'FullName' => 'Nguyễn Văn A',
            'Email' => 'client@pinkcharcoal.vn',
            'Password' => Hash::make('password'),
            'RoleID' => 3,
            'Avatar' => 'https://ui-avatars.com/api/?name=Nguyen+Van+A&background=random',
        ]);
    }
}
