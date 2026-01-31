<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'CategoryID' => 1,
                'CategoryName' => 'Đồ ăn',
            ],
            [
                'CategoryID' => 2,
                'CategoryName' => 'Phụ kiện',
            ],
            [
                'CategoryID' => 3,
                'CategoryName' => 'Quần áo',
            ],
            [
                'CategoryID' => 4,
                'CategoryName' => 'Vệ sinh',
            ],
            [
                'CategoryID' => 5,
                'CategoryName' => 'Đồ chơi',
            ],
        ]);
    }
}
