<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceImageSeeder extends Seeder
{
    public function run(): void
    {
        $serviceImages = [
            [
                'ServiceID' => 1,
                'ImageUrl' => 'https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?auto=format&fit=crop&q=80&w=800',
                'IsMain' => 1
            ],
            [
                'ServiceID' => 2,
                'ImageUrl' => 'https://images.unsplash.com/photo-1591768793355-74d7c526cc4b?auto=format&fit=crop&q=80&w=800',
                'IsMain' => 1
            ],
            [
                'ServiceID' => 3,
                'ImageUrl' => 'https://images.unsplash.com/photo-1601758228041-f3b2795255f1?auto=format&fit=crop&q=80&w=800',
                'IsMain' => 1
            ],
            [
                'ServiceID' => 4,
                'ImageUrl' => 'https://images.unsplash.com/photo-1628009368231-7bb7cfcb0def?auto=format&fit=crop&q=80&w=800',
                'IsMain' => 1
            ],
        ];

        foreach ($serviceImages as $img) {
            DB::table('service_images')->updateOrInsert(
                ['ServiceID' => $img['ServiceID'], 'IsMain' => 1],
                ['ImageUrl' => $img['ImageUrl']]
            );
        }
    }
}
