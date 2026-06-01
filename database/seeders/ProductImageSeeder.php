<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
	// Number of images per product (1 = only primary, 2-3 = gallery)
	private const IMAGES_PER_PRODUCT = 2;

	public function run(): void
	{
		$products = Product::all();

		foreach ($products as $product) {
			// Skip if product already has images
			if ($product->images()->exists()) {
				continue;
			}

			for ($i = 0; $i < self::IMAGES_PER_PRODUCT; $i++) {
				// picsum.photos/seed/{seed}/{width}/{height} — same seed = same image always
				$seed = $product->id * 10 + $i;
				$url  = "https://picsum.photos/seed/{$seed}/640/480";

				$caBundle = env('CURL_CA_BUNDLE');
				$response = Http::timeout(10)
					->withOptions($caBundle ? ['verify' => $caBundle] : [])
					->get($url);

				if (!$response->successful()) {
					$this->command->warn("Failed to download image for product #{$product->id} (image {$i})");
					continue;
				}

				$filename = "products/seed_{$seed}.jpg";
				Storage::disk('public')->put($filename, $response->body());

				$product->images()->create([
					'image_path' => $filename,
					'is_default' => $i === 0,
					'sort_order' => $i,
				]);
			}

			$this->command->info("Images seeded for: {$product->name}");
		}
	}
}
