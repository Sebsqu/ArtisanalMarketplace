<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'name' => 'Sebastian Skubała',
            'email' => 'seba153@onet.pl',
            'email_verified_at' => now(),
            'role' => 'user',
            'password' => bcrypt('TESTuje12@'),
            'created_at' => now(),
            'updated_at' => now(),
            'phone_number' => '123456789',
            'city' => 'Warszawa',
            'postal_code' => '00-001',
            'address' => 'ul. Testowa 1, 00-001 Warszawa',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Tymoteusz Palak',
            'email' => 'tpalak@gmail.com',
            'email_verified_at' => now(),
            'role' => 'user',
            'password' => bcrypt('TESTuje12@'),
            'created_at' => now(),
            'updated_at' => now(),
            'phone_number' => '123456789',
            'city' => 'Dąbrowa Górnicza',
            'postal_code' => '00-001',
            'address' => 'ul. 11 listopaca 1c, 00-002 Dąbrowa Górnicza',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Jakub Kurzacz',
            'email' => 'jkurzacz@gmail.com',
            'email_verified_at' => now(),
            'role' => 'admin',
            'password' => bcrypt('TESTuje12@'),
            'created_at' => now(),
            'updated_at' => now(),
            'phone_number' => '123456789',
            'city' => 'Kłobuck',
            'postal_code' => '00-001',
            'address' => 'ul. Kłobucka 12b, 00-003 Kłobuck',
            'is_active' => true,
        ]);
    }
}
