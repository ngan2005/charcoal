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

        // Insert Images (Using the same dummy image/url for now)
        $products = DB::table('products')->get();
        foreach ($products as $p) {
             DB::table('product_images')->insert([
                'ProductID' => $p->ProductID,
                'ImageUrl' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBTJcXM2rNNiE5eHdgsmEdzMq_PYYBbzdT3KcWFTsiY-sziPtkeoG9qtB1heoe5VuN_XVG4zmlleoK4UNhNyZjWd6U_wHzF-cWN_H6YhwrptzhxB23IwQdwuOkrOUXPwFVGcj7V0T-jGU1KL4KNqSaM-Q2kjxxr8wg4Ub4vndfIxZ91UXVrTdcz4vvIc4UuBUlDemAx6EsFlK9mj1Wck-g6KHl8PxpSxAXM9XpsXuBxHD7-bF68JSzBJh595vAHbrsAmy3BW1wSRO4',
                'IsMain' => 1
            ]);
        }
    }
}
