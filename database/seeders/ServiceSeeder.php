<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'ServiceID' => 1,
                'ServiceName' => 'Tắm thú cưng',
                'BasePrice' => 150000,
                'Duration' => 60,
                'Description' => 'Tắm, sấy và vệ sinh cơ bản.',
            ],
             [
                'ServiceID' => 2,
                'ServiceName' => 'Cắt tỉa lông',
                'BasePrice' => 200000,
                'Duration' => 90,
                'Description' => 'Cắt tỉa và tạo kiểu lông theo yêu cầu.',
            ],
             [
                'ServiceID' => 3,
                'ServiceName' => 'Lưu trú (Hotel)',
                'BasePrice' => 250000,
                'Duration' => 1440,
                'Description' => 'Chăm sóc lưu trú theo ngày.',
            ],
             [
                'ServiceID' => 4,
                'ServiceName' => 'Tiêm phòng',
                'BasePrice' => 180000,
                'Duration' => 30,
                'Description' => 'Tiêm phòng định kỳ cho thú cưng.',
            ],
        ]);
    }
}
