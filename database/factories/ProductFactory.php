<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
   /**
    * Define the model's default state.
    *
    * @return array<string, mixed>
    */
   public function definition()
   {
      $name = $this->faker->sentence(3);
      return [
         'id_product' => 'PRD' . $this->faker->unique()->unixTime(),
         'slug' => str()->slug($name),
         'name' => $name,
         'detail' => $this->faker->paragraphs(mt_rand(5, 10), true),
         'price' => mt_rand(50000, 700000),
         'discount' => mt_rand(0, 75),
         'weight' => mt_rand(1000, 5000),
         'stock' => mt_rand(100, 500),
      ];
   }
}
