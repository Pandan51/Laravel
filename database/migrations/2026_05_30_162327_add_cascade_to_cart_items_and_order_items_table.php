<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('cart_items', function (Blueprint $table) {
			$table->dropForeign(['cart_id']);
			$table->foreign('cart_id')->references('id')->on('carts')->cascadeOnDelete();
		});

		Schema::table('order_items', function (Blueprint $table) {
			$table->dropForeign(['order_id']);
			$table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
		});
	}

	public function down(): void
	{
		Schema::table('cart_items', function (Blueprint $table) {
			$table->dropForeign(['cart_id']);
			$table->foreign('cart_id')->references('id')->on('carts');
		});

		Schema::table('order_items', function (Blueprint $table) {
			$table->dropForeign(['order_id']);
			$table->foreign('order_id')->references('id')->on('orders');
		});
	}
};
