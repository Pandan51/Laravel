<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
	public function index()
	{
		$cart = auth()->user()->cart()->with('items.product')->first();
		return view('carts.index', compact('cart'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'product_id' => 'required|exists:products,id',
			'quantity'   => 'integer|min:1',
		]);

		$cart = auth()->user()->cart
			?? Cart::create(['user_id' => auth()->id()]);

		$existing = $cart->items()->where('product_id', $request->product_id)->first();

		if ($existing) {
			$existing->increment('quantity', $request->input('quantity', 1));
		} else {
			$cart->items()->create([
				'product_id' => $request->product_id,
				'quantity'   => $request->input('quantity', 1),
			]);
		}

		return redirect()->route('carts.index');
	}

	public function update(Request $request, Cart $cart)
	{
		$request->validate([
			'cart_item_id' => 'required|exists:cart_items,id',
			'quantity'     => 'required|integer|min:1',
		]);

		$cart->items()
			->where('id', $request->cart_item_id)
			->update(['quantity' => $request->quantity]);

		return redirect()->route('carts.index');
	}

	public function destroy(Request $request, Cart $cart)
	{
		if ($request->has('cart_item_id')) {
			$cart->items()->where('id', $request->cart_item_id)->delete();
		} else {
			$cart->delete();
		}

		return redirect()->route('carts.index');
	}
}
