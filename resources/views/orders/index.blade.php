<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			My Orders
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">

					@if($orders->isEmpty())
						<p class="text-gray-500">No orders yet.</p>
					@else
						<table class="w-full text-left">
							<thead>
								<tr class="border-b dark:border-gray-700">
									<th class="py-2 pr-4">#</th>
									<th class="py-2 pr-4">Date</th>
									<th class="py-2 pr-4">Total</th>
									<th class="py-2 pr-4">Status</th>
									<th class="py-2"></th>
								</tr>
							</thead>
							<tbody>
								@foreach($orders as $order)
									<tr class="border-b dark:border-gray-700">
										<td class="py-2 pr-4">{{ $order->id }}</td>
										<td class="py-2 pr-4">{{ $order->created_at->format('d.m.Y H:i') }}</td>
										<td class="py-2 pr-4">{{ number_format($order->total_price, 2) }}</td>
										<td class="py-2 pr-4">
											<span class="px-2 py-1 text-xs rounded
												{{ $order->status === \App\Enums\OrderStatus::Pending  ? 'bg-yellow-100 text-yellow-800' : '' }}
												{{ $order->status === \App\Enums\OrderStatus::Paid     ? 'bg-blue-100 text-blue-800' : '' }}
												{{ $order->status === \App\Enums\OrderStatus::Shipped  ? 'bg-green-100 text-green-800' : '' }}
												{{ $order->status === \App\Enums\OrderStatus::Cancelled ? 'bg-red-100 text-red-800' : '' }}
											">
												{{ $order->status->value }}
											</span>
										</td>
										<td class="py-2">
											<a href="{{ route('orders.show', $order) }}"
												class="text-sm text-indigo-600 hover:underline">Detail</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@endif

				</div>
			</div>
		</div>
	</div>
</x-app-layout>
