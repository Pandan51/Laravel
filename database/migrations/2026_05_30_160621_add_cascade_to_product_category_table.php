<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('product_category', function (Blueprint $table) {
			$table->dropForeign(['product_id']);
			$table->dropForeign(['category_id']);

			$table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
			$table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
		});
	}

	public function down(): void
	{
		Schema::table('product_category', function (Blueprint $table) {
			$table->dropForeign(['product_id']);
			$table->dropForeign(['category_id']);

			$table->foreign('product_id')->references('id')->on('products');
			$table->foreign('category_id')->references('id')->on('categories');
		});
	}
};
