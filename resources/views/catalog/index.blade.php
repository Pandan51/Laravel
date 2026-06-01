<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Product Catalog
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

			{{-- Category filter --}}
			<div class="flex flex-wrap gap-2 mb-8">
				<a href="{{ route('catalog.index') }}"
					class="px-4 py-2 rounded-full text-sm font-medium transition
						{{ !$activeCategory ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600' }}">
					All
				</a>
				@foreach($categories as $category)
					<a href="{{ route('catalog.index', ['category' => $category->id]) }}"
						class="px-4 py-2 rounded-full text-sm font-medium transition
							{{ $activeCategory === $category->id ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600' }}">
						{{ $category->name }}
					</a>
				@endforeach
			</div>

			{{-- Product grid --}}
			@if($products->isEmpty())
				<p class="text-gray-500 dark:text-gray-400">No products found.</p>
			@else
				<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
					@foreach($products as $product)
						<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden flex flex-col">
							<div class="h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
								@php $img = $product->images->firstWhere('is_default', true) ?? $product->images->first(); @endphp
								@if ($img)
									<img src="{{ asset('storage/' . $img->image_path) }}"
										alt="{{ $product->name }}"
										class="h-full w-full object-cover" />
								@else
									<span class="text-gray-400 dark:text-gray-500 text-xs">No image</span>
								@endif
							</div>
							<div class="p-4 flex flex-col flex-1">
								<h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1 leading-tight">
									{{ $product->name }}
								</h3>
								<p class="text-xs text-gray-400 dark:text-gray-500 mb-3">
									{{ $product->categories->pluck('name')->join(', ') ?: '—' }}
								</p>
								<p class="text-indigo-600 dark:text-indigo-400 font-bold text-lg mb-4">
									{{ number_format($product->price, 2) }} Kč
								</p>
								<div class="mt-auto">
									<a href="{{ route('products.show', $product) }}"
										class="block text-center w-full px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 transition">
										View
									</a>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			@if ($products->hasPages())
				<div class="mt-8">
					{{ $products->links() }}
				</div>
			@endif
		@endif

		</div>
	</div>
</x-app-layout>
