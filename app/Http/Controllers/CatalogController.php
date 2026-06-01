<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
	public function index(Request $request)
	{
		$categories = Category::all();

		$query = Product::with('categories');

		if ($request->filled('category')) {
			$query->whereHas('categories', fn($q) => $q->where('categories.id', $request->category));
		}

		$products = $query->get();
		$activeCategory = $request->integer('category') ?: null;

		return view('catalog.index', compact('products', 'categories', 'activeCategory'));
	}
}
