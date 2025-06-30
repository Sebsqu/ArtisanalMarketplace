<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Products\Products;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Products::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Products::create([
            'user_id' => 1,
            'category_id' => 1,
            'name' => 'Ręcznie robiony kubek ceramiczny',
            'description' => 'Unikalny kubek ceramiczny wykonany ręcznie, idealny na prezent.',
            'price' => 49.99,
            'stock_quantity' => 5,
            'weight' => 0.5,
            'dimensions' => '10x10x10 cm',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/1.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 2,
            'category_id' => 2,
            'name' => 'Lniana torba na zakupy',
            'description' => 'Ekologiczna torba z lnu, wytrzymała i stylowa.',
            'price' => 35.00,
            'stock_quantity' => 10,
            'weight' => 0.2,
            'dimensions' => '40x35x10 cm',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/2.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 1,
            'category_id' => 3,
            'name' => 'Srebrna bransoletka z bursztynem',
            'description' => 'Ręcznie wykonana bransoletka ze srebra i bursztynu.',
            'price' => 120.00,
            'stock_quantity' => 3,
            'weight' => 0.05,
            'dimensions' => '20 cm',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/3.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 2,
            'category_id' => 4,
            'name' => 'Domowy dżem truskawkowy',
            'description' => 'Naturalny dżem z polskich truskawek, bez konserwantów.',
            'price' => 15.50,
            'stock_quantity' => 20,
            'weight' => 0.3,
            'dimensions' => 'Słoik 200ml',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/4.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 1,
            'category_id' => 5,
            'name' => 'Naturalne mydło lawendowe',
            'description' => 'Mydło ręcznie robione z naturalnych składników i olejku lawendowego.',
            'price' => 18.00,
            'stock_quantity' => 15,
            'weight' => 0.1,
            'dimensions' => '8x5x2 cm',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/5.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 2,
            'category_id' => 6,
            'name' => 'Obraz olejny "Pejzaż"',
            'description' => 'Ręcznie malowany obraz olejny przedstawiający pejzaż.',
            'price' => 350.00,
            'stock_quantity' => 1,
            'weight' => 1.2,
            'dimensions' => '60x40 cm',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/6.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 1,
            'category_id' => 7,
            'name' => 'Maskotka królik',
            'description' => 'Miękka maskotka dla dzieci, szyta ręcznie z bawełny.',
            'price' => 42.00,
            'stock_quantity' => 7,
            'weight' => 0.3,
            'dimensions' => '25x12x10 cm',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/7.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 2,
            'category_id' => 8,
            'name' => 'Zestaw kartek okolicznościowych',
            'description' => 'Ręcznie robione kartki na różne okazje, 5 sztuk w zestawie.',
            'price' => 25.00,
            'stock_quantity' => 12,
            'weight' => 0.15,
            'dimensions' => '15x10 cm',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/8.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 1,
            'category_id' => 9,
            'name' => 'Zestaw włóczek bawełnianych',
            'description' => 'Kolorowe włóczki do rękodzieła, 10 motków.',
            'price' => 60.00,
            'stock_quantity' => 8,
            'weight' => 0.8,
            'dimensions' => 'Paczka',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/9.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 2,
            'category_id' => 1,
            'name' => 'Poduszka dekoracyjna',
            'description' => 'Ręcznie szyta poduszka z naturalnych materiałów.',
            'price' => 55.00,
            'stock_quantity' => 6,
            'weight' => 0.6,
            'dimensions' => '40x40 cm',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/10.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 1,
            'category_id' => 2,
            'name' => 'Wełniany szalik',
            'description' => 'Ciepły, ręcznie robiony szalik z wełny merino.',
            'price' => 80.00,
            'stock_quantity' => 4,
            'weight' => 0.25,
            'dimensions' => '150x20 cm',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/11.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Products::create([
            'user_id' => 2,
            'category_id' => 3,
            'name' => 'Naszyjnik z kamieni naturalnych',
            'description' => 'Unikalny naszyjnik wykonany z naturalnych kamieni półszlachetnych.',
            'price' => 99.00,
            'stock_quantity' => 5,
            'weight' => 0.07,
            'dimensions' => '45 cm',
            'is_active' => true,
            'views_count' => 0,
            'urlImages' => 'storage/products/12.webp',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
