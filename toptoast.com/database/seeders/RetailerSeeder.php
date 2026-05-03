<?php

namespace Database\Seeders;

use App\Models\Retailer;
use Illuminate\Database\Seeder;

class RetailerSeeder extends Seeder
{
    public function run(): void
    {
        $retailers = [
            [
                'name' => 'Tesco',
                'slug' => 'tesco',
                'color' => '#e22129',
                'type' => 'supermarket',
                'fetch_strategy' => 'scrape',
                'is_active' => true,
            ],
            [
                'name' => "Sainsbury's",
                'slug' => 'sainsburys',
                'color' => '#ff7300',
                'type' => 'supermarket',
                'fetch_strategy' => 'scrape',
                'is_active' => true,
            ],
            [
                'name' => 'Waitrose',
                'slug' => 'waitrose',
                'color' => '#6db33f',
                'type' => 'supermarket',
                'fetch_strategy' => 'scrape',
                'is_active' => true,
            ],
            [
                'name' => 'ASDA',
                'slug' => 'asda',
                'color' => '#78be20',
                'type' => 'supermarket',
                'fetch_strategy' => 'scrape',
                'is_active' => true,
            ],
            [
                'name' => 'M&S',
                'slug' => 'marks-and-spencer',
                'color' => '#222222',
                'type' => 'supermarket',
                'fetch_strategy' => 'scrape',
                'is_active' => true,
            ],
            [
                'name' => 'Dishoom',
                'slug' => 'dishoom',
                'color' => '#c8102e',
                'type' => 'restaurant',
                'fetch_strategy' => 'manual',
                'is_active' => true,
            ],
            [
                'name' => 'Wahaca',
                'slug' => 'wahaca',
                'color' => '#e8b84b',
                'type' => 'restaurant',
                'fetch_strategy' => 'manual',
                'is_active' => true,
            ],
        ];

        foreach ($retailers as $retailer) {
            Retailer::updateOrCreate(['slug' => $retailer['slug']], $retailer);
        }
    }
}
