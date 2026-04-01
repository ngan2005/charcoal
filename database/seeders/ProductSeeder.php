<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Insert Products
        DB::table('products')->insert([
            [
                'ProductID' => 1,
                'ProductName' => 'Thức ăn hạt hữu cơ',
                'CategoryID' => 1,
                'Price' => 380000,
                'StatusID' => 1,
                'Description' => 'Thức ăn hạt cao cấp dành cho chó mèo.',
                'ProductCode' => 'P001'
            ],
            [
                'ProductID' => 2,
                'ProductName' => 'Đồ chơi xương gặm',
                'CategoryID' => 5,
                'Price' => 85000,
                'StatusID' => 1,
                'Description' => 'Đồ chơi an toàn cho thú cưng.',
                 'ProductCode' => 'P002'
            ],
            [
                'ProductID' => 3,
                'ProductName' => 'Đệm nằm cao cấp',
                'CategoryID' => 2,
                'Price' => 550000,
                'StatusID' => 1,
                'Description' => 'Đệm êm ái cho giấc ngủ ngon.',
                 'ProductCode' => 'P003'
            ],
            [
                'ProductID' => 4,
                'ProductName' => 'Áo thun thú cưng',
                'CategoryID' => 3,
                'Price' => 180000,
                'StatusID' => 1,
                'Description' => 'Áo thun thời trang, thoáng mát.',
                 'ProductCode' => 'P004'
            ],
             [
                'ProductID' => 5,
                'ProductName' => 'Pate mèo Royal',
                'CategoryID' => 1,
                'Price' => 45000,
                'StatusID' => 1,
                'Description' => 'Pate dinh dưỡng cho mèo.',
                 'ProductCode' => 'P005'
            ],
             [
                'ProductID' => 6,
                'ProductName' => 'Vòng cổ chuông',
                'CategoryID' => 2,
                'Price' => 60000,
                'StatusID' => 1,
                'Description' => 'Vòng cổ xinh xắn có chuông.',
                 'ProductCode' => 'P006'
            ],
             [
                'ProductID' => 7,
                'ProductName' => 'Balo vận chuyển',
                'CategoryID' => 2,
                'Price' => 420000,
                'StatusID' => 1,
                'Description' => 'Balo tiện lợi mang thú cưng đi chơi.',
                 'ProductCode' => 'P007'
            ],
             [
                'ProductID' => 8,
                'ProductName' => 'Sữa tắm SOS',
                'CategoryID' => 4,
                'Price' => 150000,
                'StatusID' => 1,
                'Description' => 'Sữa tắm dưỡng lông mượt mà.',
                 'ProductCode' => 'P008'
            ],
        ]);

        // Insert Images
        $products = DB::table('products')->get();
        $placeholderImages = [
            'https://placehold.co/400x300/orange/white?text=Product+1',
            'https://placehold.co/400x300/blue/white?text=Product+2',
            'https://placehold.co/400x300/green/white?text=Product+3',
            'https://placehold.co/400x300/purple/white?text=Product+4',
            'https://placehold.co/400x300/red/white?text=Product+5',
            'https://placehold.co/400x300/yellow/black?text=Product+6',
            'https://placehold.co/400x300/teal/white?text=Product+7',
            'https://placehold.co/400x300/pink/white?text=Product+8',
        ];
        $i = 0;
        foreach ($products as $p) {
             DB::table('product_images')->insert([
                'ProductID' => $p->ProductID,
                'ImageUrl' => $placeholderImages[$i % count($placeholderImages)],
                'IsMain' => 1
            ]);
            $i++;
        }
    }
}
