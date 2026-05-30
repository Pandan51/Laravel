<x-app-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
				Order #{{ $order->id }}
			</h2>
			@if($order->status === \App\Enums\OrderStatus::Pending)
				<form action="{{ route('orders.destroy', $order) }}" method="POST"
					onsubmit="return confirm('Cancel this order?')">
					@csrf
					@method('DELETE')
					<button type="submit"
						class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
						Cancel Order
					</button>
				</form>
			@endif
		</div>
	</x-slot>

	<div class="py-12">
		<div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

			{{-- Status + meta --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
				@if(session('error'))
					<p class="mb-4 text-red-600 text-sm">{{ session('error') }}</p>
				@endif

				<div class="flex gap-8">
					<div>
						<span class="text-sm text-gray-500">Date</span>
						<p>{{ $order->created_at->format('d.m.Y H:i') }}</p>
					</div>
					<div>
						<span class="text-sm text-gray-500">Status</span>
						<p class="font-semibold">{{ $order->status->value }}</p>
					</div>
					<div>
						<span class="text-sm text-gray-500">Total</span>
						<p class="font-semibold">{{ number_format($order->total_price, 2) }}</p>
					</div>
				</div>

				{{-- Status update form --}}
				<form action="{{ route('orders.update', $order) }}" method="POST" class="mt-4 flex gap-3 items-center">
					@csrf
					@method('PUT')
					<select name="status"
						class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
						@foreach(\App\Enums\OrderStatus::cases() as $status)
							<option value="{{ $status->value }}"
								{{ $order->status === $status ? 'selected' : '' }}>
								{{ $status->value }}
							</option>
						@endforeach
					</select>
					<x-primary-button>Update Status</x-primary-button>
				</form>
			</div>

			{{-- Items --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
				<h3 class="font-semibold mb-4">Items</h3>
				<table class="w-full text-left">
					<thead>
						<tr class="border-b dark:border-gray-700">
							<th class="py-2 pr-4">Product</th>
							<th class="py-2 pr-4">Price at purchase</th>
							<th class="py-2 pr-4">Qty</th>
							<th class="py-2">Subtotal</th>
						</tr>
					</thead>
					<tbody>
						@foreach($order->items as $item)
							<tr class="border-b dark:border-gray-700">
								<td class="py-2 pr-4">{{ $item->product->name }}</td>
								<td class="py-2 pr-4">{{ number_format($item->price_at_purchase, 2) }}</td>
								<td class="py-2 pr-4">{{ $item->quantity }}</td>
								<td class="py-2">
									{{ number_format($item->price_at_purchase * $item->quantity, 2) }}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				<div class="mt-4">
					<a href="{{ route('orders.index') }}" class="text-sm text-indigo-600 hover:underline">
						← Back to orders
					</a>
				</div>
			</div>

		</div>
	</div>
</x-app-layout>
