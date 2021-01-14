<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductListing;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductListingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductListing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::all()->random()->id,
            'amount' => $this->faker->randomElement([1,10,25]),
            'price' => $this->faker->randomFloat(2, 10, 150),
            'isHidden' => $this->faker->boolean(60),
        ];
    }
}
