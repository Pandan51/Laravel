<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Shopping Cart
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					@if(!$cart || $cart->items->isEmpty())
						<p class="text-gray-500">Your cart is empty.</p>
					@else
						<table class="w-full text-left">
							<thead>
								<tr class="border-b dark:border-gray-700">
									<th class="py-2 pr-4">Product</th>
									<th class="py-2 pr-4">Price</th>
									<th class="py-2 pr-4">Quantity</th>
									<th class="py-2 pr-4">Subtotal</th>
									<th class="py-2"></th>
								</tr>
							</thead>
							<tbody>
								@foreach($cart->items as $item)
									<tr class="border-b dark:border-gray-700">
										<td class="py-3 pr-4">
												<a href="{{ route('products.show', $item->product) }}"
													class="hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">
													{{ $item->product->name }}
												</a>
											</td>
										<td class="py-3 pr-4">{{ number_format($item->product->price, 2) }}</td>

										{{-- Update quantity form --}}
										<td class="py-3 pr-4">
											<form action="{{ route('carts.update', $cart) }}" method="POST"
												class="flex items-center gap-2">
												@csrf
												@method('PUT')
												<input type="hidden" name="cart_item_id" value="{{ $item->id }}" />
												<input type="number" name="quantity" value="{{ $item->quantity }}"
													min="1" class="w-16 border-gray-300 dark:border-gray-700 dark:bg-gray-900
													dark:text-gray-300 rounded-md shadow-sm text-sm" />
												<button type="submit" class="text-sm text-indigo-600 hover:underline">
													Update
												</button>
											</form>
										</td>

										<td class="py-3 pr-4">
											{{ number_format($item->product->price * $item->quantity, 2) }}
										</td>

										{{-- Remove item form --}}
										<td class="py-3">
											<form action="{{ route('carts.destroy', $cart) }}" method="POST">
												@csrf
												@method('DELETE')
												<input type="hidden" name="cart_item_id" value="{{ $item->id }}" />
												<button type="submit" class="text-sm text-red-600 hover:underline">
													Remove
												</button>
											</form>
										</td>
									</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3" class="py-4 text-right font-semibold pr-4">Total:</td>
									<td class="py-4 font-semibold">
										{{ number_format($cart->items->sum(fn($i) => $i->product->price * $i->quantity), 2) }}
									</td>
									<td></td>
								</tr>
							</tfoot>
						</table>

						<div class="mt-6 flex justify-between items-center">
							{{-- Clear cart --}}
							<form action="{{ route('carts.destroy', $cart) }}" method="POST"
								onsubmit="return confirm('Clear entire cart?')">
								@csrf
								@method('DELETE')
								<button type="submit" class="text-sm text-red-600 hover:underline">
									Clear cart
								</button>
							</form>

							<a href="{{ route('orders.create') }}"
								class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
								Proceed to checkout →
							</a>
						</div>
					@endif

				</div>
			</div>
		</div>
	</div>
</x-app-layout>
