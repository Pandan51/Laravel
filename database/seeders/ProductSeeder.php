<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
	public function run(): void
	{
		$categoryIds = Category::pluck('id');

		Product::factory(20)->create()->each(function (Product $product) use ($categoryIds) {
			$product->categories()->attach(
				$categoryIds->random(rand(1, 2))
			);
		});
	}
}
