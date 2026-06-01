<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
	public function destroy(Product $product, ProductImage $image): RedirectResponse
	{
		Storage::disk('public')->delete($image->image_path);
		$wasPrimary = $image->is_default;
		$image->delete();

		if ($wasPrimary) {
			$product->images()->orderBy('sort_order')->first()?->update(['is_default' => true]);
		}

		return redirect()->route('admin.products.edit', $product)->with('success', 'Image deleted.');
	}

	public function setPrimary(Product $product, ProductImage $image): RedirectResponse
	{
		$product->images()->update(['is_default' => false]);
		$image->update(['is_default' => true]);

		return redirect()->route('admin.products.edit', $product)->with('success', 'Primary image updated.');
	}
}
