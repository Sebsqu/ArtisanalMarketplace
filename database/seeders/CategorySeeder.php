<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Products\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Dom i Wnętrze',
            'Moda i Akcesoria',
            'Biżuteria',
            'Rzemieślnicza Spiżarnia',
            'Kosmetyki Naturalne',
            'Sztuka',
            'Dla dzieci',
            'Papier i Papeteria',
            'Materiały',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
