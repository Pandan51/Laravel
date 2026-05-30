<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
	public function definition(): array
	{
		return [
			'name'        => fake()->words(3, true),
			'description' => fake()->optional(0.7)->sentence(12),
			'price'       => fake()->randomFloat(2, 5, 500),
		];
	}
}
