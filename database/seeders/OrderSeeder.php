<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
	public function run(): void
	{
		$products = Product::all();

		$statuses = [
			OrderStatus::Pending,
			OrderStatus::Paid,
			OrderStatus::Shipped,
		];

		User::all()->each(function (User $user) use ($products, $statuses) {
			foreach ($statuses as $status) {
				$items = $products->random(rand(2, 3));

				$total = $items->sum(fn($p) => $p->price * 1);

				$order = Order::create([
					'user_id'     => $user->id,
					'total_price' => $total,
					'status'      => $status,
				]);

				foreach ($items as $product) {
					$order->items()->create([
						'product_id'        => $product->id,
						'quantity'          => 1,
						'price_at_purchase' => $product->price,
					]);
				}
			}
		});
	}
}
