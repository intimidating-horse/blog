<?php

namespace Database\Seeders;

use App\Models\Listing;
use Illuminate\Database\Seeder;

class ListingSeeder extends Seeder
{
    /**
     * Seed the listings table with fake listings.
     */
    public function run(): void
    {
        Listing::factory()->count(50)->create();
    }
}
