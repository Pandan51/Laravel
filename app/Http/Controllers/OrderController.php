<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
	public function index()
	{
		$orders = auth()->user()->orders()->latest()->get();
		return view('orders.index', compact('orders'));
	}

	public function create()
	{
		$cart = auth()->user()->cart()->with('items.product')->first();

		if (!$cart || $cart->items->isEmpty()) {
			return redirect()->route('carts.index')->with('error', 'Your cart is empty.');
		}

		return view('orders.create', compact('cart'));
	}

	public function store(Request $request)
	{
		$cart = auth()->user()->cart()->with('items.product')->first();

		if (!$cart || $cart->items->isEmpty()) {
			return redirect()->route('carts.index')->with('error', 'Your cart is empty.');
		}

		$total = $cart->items->sum(fn($item) => $item->product->price * $item->quantity);

		$order = Order::create([
			'user_id'     => auth()->id(),
			'total_price' => $total,
			'status'      => OrderStatus::Pending,
		]);

		foreach ($cart->items as $item) {
			$order->items()->create([
				'product_id'        => $item->product_id,
				'quantity'          => $item->quantity,
				'price_at_purchase' => $item->product->price,
			]);
		}

		$cart->delete();

		return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully.');
	}

	public function show(Order $order)
	{
		$this->authorizeOrder($order);
		$order->load('items.product');
		return view('orders.show', compact('order'));
	}

	public function update(Request $request, Order $order)
	{
		$this->authorizeOrder($order);

		$request->validate([
			'status' => 'required|in:' . implode(',', array_column(OrderStatus::cases(), 'value')),
		]);

		$order->update(['status' => $request->status]);

		return redirect()->route('orders.show', $order)->with('success', 'Order status updated.');
	}

	public function destroy(Order $order)
	{
		$this->authorizeOrder($order);

		if ($order->status !== OrderStatus::Pending) {
			return redirect()->route('orders.show', $order)
				->with('error', 'Only pending orders can be cancelled.');
		}

		$order->update(['status' => OrderStatus::Cancelled]);

		return redirect()->route('orders.index')->with('success', 'Order cancelled.');
	}

	private function authorizeOrder(Order $order): void
	{
		if ($order->user_id !== auth()->id()) {
			abort(403);
		}
	}
}
