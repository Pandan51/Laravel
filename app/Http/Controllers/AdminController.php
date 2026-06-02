<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class AdminController extends Controller
{
	public function index()
	{
		return view('admin.index', [
			'productCount'  => Product::count(),
			'categoryCount' => Category::count(),
		]);
	}
}
