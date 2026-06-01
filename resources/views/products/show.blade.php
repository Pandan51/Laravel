<x-app-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
				{{ $product->name }}
			</h2>
			@if(auth()->user()?->isAdmin())
				<div class="flex gap-3">
					<a href="{{ route('admin.products.edit', $product) }}"
						class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-sm">
						Edit
					</a>
					<form action="{{ route('admin.products.destroy', $product) }}" method="POST"
						onsubmit="return confirm('Delete this product?')">
						@csrf
						@method('DELETE')
						<button type="submit"
							class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
							Delete
						</button>
					</form>
				</div>
			@endif
		</div>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">

					<div>
						<span class="font-semibold">Price:</span>
						{{ number_format($product->price, 2) }}
					</div>

					@if($product->description)
						<div>
							<span class="font-semibold">Description:</span>
							<p class="mt-1 text-gray-700 dark:text-gray-300">{{ $product->description }}</p>
						</div>
					@endif

					<div>
						<span class="font-semibold">Categories:</span>
						{{ $product->categories->pluck('name')->join(', ') ?: '—' }}
					</div>

					@auth
						<form action="{{ route('carts.store') }}" method="POST" class="mt-4 flex items-center gap-3">
							@csrf
							<input type="hidden" name="product_id" value="{{ $product->id }}" />
							<input type="number" name="quantity" value="1" min="1"
								class="w-16 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm" />
							<x-primary-button>Add to Cart</x-primary-button>
						</form>
					@endauth

					<div class="mt-6">
						<a href="{{ route('catalog.index') }}" class="text-sm text-indigo-600 hover:underline">
							← Back to products
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
