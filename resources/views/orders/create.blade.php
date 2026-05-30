<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Checkout
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					<h3 class="font-semibold mb-4">Order Summary</h3>

					<table class="w-full text-left mb-6">
						<thead>
							<tr class="border-b dark:border-gray-700">
								<th class="py-2 pr-4">Product</th>
								<th class="py-2 pr-4">Price</th>
								<th class="py-2 pr-4">Qty</th>
								<th class="py-2">Subtotal</th>
							</tr>
						</thead>
						<tbody>
							@foreach($cart->items as $item)
								<tr class="border-b dark:border-gray-700">
									<td class="py-2 pr-4">{{ $item->product->name }}</td>
									<td class="py-2 pr-4">{{ number_format($item->product->price, 2) }}</td>
									<td class="py-2 pr-4">{{ $item->quantity }}</td>
									<td class="py-2">
										{{ number_format($item->product->price * $item->quantity, 2) }}
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
							</tr>
						</tfoot>
					</table>

					<form action="{{ route('orders.store') }}" method="POST">
						@csrf
						<div class="flex gap-3 items-center">
							<x-primary-button>Place Order</x-primary-button>
							<a href="{{ route('carts.index') }}"
								class="text-sm text-gray-600 hover:underline">← Back to cart</a>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</x-app-layout>
