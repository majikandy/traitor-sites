<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Food;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $foods = json_decode(file_get_contents(database_path('seeders/data/foods.json')), true);

        foreach ($foods as $data) {
            Food::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
