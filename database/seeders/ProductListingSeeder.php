<?php

namespace Database\Seeders;

use App\Models\ProductListing;
use Illuminate\Database\Seeder;

class ProductListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductListing::factory()
            ->times(250)
            ->create();
    }
}
