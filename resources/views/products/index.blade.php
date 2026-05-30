<x-app-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
				Products
			</h2>
			<a href="{{ route('products.create') }}"
				class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
				Add Product
			</a>
		</div>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					@if($products->isEmpty())
						<p class="text-gray-500">No products yet.</p>
					@else
						<table class="w-full text-left">
							<thead>
								<tr class="border-b dark:border-gray-700">
									<th class="py-2 pr-4">#</th>
									<th class="py-2 pr-4">Name</th>
									<th class="py-2 pr-4">Price</th>
									<th class="py-2 pr-4">Categories</th>
									<th class="py-2">Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach($products as $product)
									<tr class="border-b dark:border-gray-700">
										<td class="py-2 pr-4">{{ $product->id }}</td>
										<td class="py-2 pr-4">
											<a href="{{ route('products.show', $product) }}"
												class="text-indigo-600 hover:underline">
												{{ $product->name }}
											</a>
										</td>
										<td class="py-2 pr-4">{{ number_format($product->price, 2) }}</td>
										<td class="py-2 pr-4">
											{{ $product->categories->pluck('name')->join(', ') ?: '—' }}
										</td>
										<td class="py-2 flex gap-3">
											<a href="{{ route('products.edit', $product) }}"
												class="text-sm text-yellow-600 hover:underline">Edit</a>
											<form action="{{ route('products.destroy', $product) }}" method="POST"
												onsubmit="return confirm('Delete this product?')">
												@csrf
												@method('DELETE')
												<button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
											</form>
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
