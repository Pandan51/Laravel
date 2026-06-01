<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
	public function index()
	{
		$products = Product::with('categories')->paginate(15);
		return view('products.index', compact('products'));
	}

	public function create()
	{
		$categories = Category::all();
		return view('products.create', compact('categories'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'name'        => 'required|string|max:255',
			'description' => 'nullable|string',
			'price'       => 'required|numeric|min:0',
			'images'      => 'nullable|array',
			'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
		]);

		$product = Product::create([
			'name'        => $request->name,
			'description' => $request->description,
			'price'       => $request->price,
		]);

		$product->categories()->sync($request->input('categories', []));

		if ($request->hasFile('images')) {
			foreach ($request->file('images') as $index => $file) {
				$path = $file->store('products', 'public');
				$product->images()->create([
					'image_path' => $path,
					'is_default' => $index === 0,
					'sort_order' => $index,
				]);
			}
		}

		return redirect()->route('admin.products.index')->with('success', 'Product created.');
	}

	public function show(Product $product)
	{
		return view('products.show', compact('product'));
	}

	public function edit(Product $product)
	{
		$categories = Category::all();
		return view('products.edit', compact('product', 'categories'));
	}

	public function update(Request $request, Product $product)
	{
		$request->validate([
			'name'        => 'required|string|max:255',
			'description' => 'nullable|string',
			'price'       => 'required|numeric|min:0',
			'images'      => 'nullable|array',
			'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
		]);

		$product->update([
			'name'        => $request->name,
			'description' => $request->description,
			'price'       => $request->price,
		]);

		$product->categories()->sync($request->input('categories', []));

		if ($request->hasFile('images')) {
			$nextOrder = ($product->images()->max('sort_order') ?? -1) + 1;
			$hasExisting = $product->images()->exists();

			foreach ($request->file('images') as $index => $file) {
				$path = $file->store('products', 'public');
				$product->images()->create([
					'image_path' => $path,
					'is_default' => !$hasExisting && $index === 0,
					'sort_order' => $nextOrder + $index,
				]);
			}
		}

		return redirect()->route('admin.products.index')->with('success', 'Product updated.');
	}

	public function destroy(Product $product)
	{
		foreach ($product->images as $image) {
			Storage::disk('public')->delete($image->image_path);
		}
		$product->delete();
		return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
	}
}
