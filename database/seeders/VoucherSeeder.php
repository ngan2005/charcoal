<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $vouchers = [
            [
                'Code' => 'WELCOME10',
                'DiscountPercent' => 10,
                'DiscountAmount' => null,
                'MinOrderAmount' => 0,
                'Quantity' => 100,
                'Description' => 'Giảm 10% cho đơn hàng đầu tiên',
                'IsActive' => true,
                'ExpiredAt' => Carbon::now()->addMonths(3),
                'CreatedAt' => $now,
            ],
            [
                'Code' => 'SALE20',
                'DiscountPercent' => 20,
                'DiscountAmount' => null,
                'MinOrderAmount' => 500000,
                'Quantity' => 50,
                'Description' => 'Giảm 20% cho đơn hàng từ 500k',
                'IsActive' => true,
                'ExpiredAt' => Carbon::now()->addMonths(2),
                'CreatedAt' => $now,
            ],
            [
                'Code' => 'GIAM100K',
                'DiscountPercent' => null,
                'DiscountAmount' => 100000,
                'MinOrderAmount' => 300000,
                'Quantity' => 30,
                'Description' => 'Giảm 100.000đ cho đơn hàng từ 300k',
                'IsActive' => true,
                'ExpiredAt' => Carbon::now()->addMonth(),
                'CreatedAt' => $now,
            ],
            [
                'Code' => 'VIP15',
                'DiscountPercent' => 15,
                'DiscountAmount' => null,
                'MinOrderAmount' => 0,
                'Quantity' => 20,
                'Description' => 'Giảm 15% dành riêng cho khách VIP',
                'IsActive' => true,
                'ExpiredAt' => Carbon::now()->addMonths(6),
                'CreatedAt' => $now,
            ],
            [
                'Code' => 'FREESHIP',
                'DiscountPercent' => 100,
                'DiscountAmount' => null,
                'MinOrderAmount' => 200000,
                'Quantity' => 100,
                'Description' => 'Miễn phí vận chuyển cho đơn từ 200k',
                'IsActive' => true,
                'ExpiredAt' => Carbon::now()->addMonths(1),
                'CreatedAt' => $now,
            ],
        ];

        foreach ($vouchers as $v) {
            DB::table('vouchers')->updateOrInsert(
                ['Code' => $v['Code']],
                $v
            );
        }
    }
}
