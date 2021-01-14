<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text(50),
            'type' => $this->faker->numberBetween(0,4),
            'amount_in_stock' => $this->faker->numberBetween(0,100),
            'hidden' => $this->faker->boolean(20),
            'image' => 'https://images-na.ssl-images-amazon.com/images/I/A1cnNs5NUwL._AC_SX425_.jpg',
        ];
    }
}
