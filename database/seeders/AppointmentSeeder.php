<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have at least one user to be the customer/staff
        $user = User::first();
        if (!$user) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => bcrypt('password'),
            ]);
        } else {
            $userId = $user->UserID ?? $user->id; // Handle PK name difference if any (User schema usually 'id')
        }

        // Create a Pet if needed
        $petId = DB::table('pets')->insertGetId([
            'OwnerID' => $userId,
            'PetName' => 'LuLu',
            'Species' => 'Dog',
            'Breed' => 'Corgi',
            'Age' => 2
        ]);

        DB::table('appointments')->insert([
            [
                'CustomerID' => $userId,
                'StaffID' => $userId, // Self-assigned for demo
                'PetID' => $petId,
                'AppointmentTime' => now()->setTime(9, 0), // Today 9:00
                'Status' => 'Sắp diễn ra'
            ],
             [
                'CustomerID' => $userId,
                'StaffID' => $userId,
                'PetID' => $petId,
                'AppointmentTime' => now()->setTime(10, 30), // Today 10:30
                'Status' => 'Đang chuẩn bị'
            ]
        ]);
        
        // Link Services to Appointments
        // Assume AppointmentIDs are 1 and 2
        // DB::table('appointment_services')->insert([...]) 
        // Skip for now to keep simple
    }
}
