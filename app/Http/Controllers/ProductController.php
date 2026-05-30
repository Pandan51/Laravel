<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	public function index()
	{
		$products = Product::all();
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
			'name' => 'required|string|max:255',
			'description' => 'nullable|string',
			'price' => 'required|numeric|min:0',
		]);

		$product = Product::create([
			'name' => $request->name,
			'description' => $request->description,
			'price' => $request->price,
		]);

		$product->categories()->sync($request->input('categories', []));

		return redirect()->route('products.index');
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
			'name' => 'required|string|max:255',
			'description' => 'nullable|string',
			'price' => 'required|numeric|min:0',
		]);

		$product->update([
			'name' => $request->name,
			'description' => $request->description,
			'price' => $request->price,
		]);

		$product->categories()->sync($request->input('categories', []));

		return redirect()->route('products.index');
	}

	public function destroy(Product $product)
	{
		$product->delete();
		return redirect()->route('products.index');
	}
}
