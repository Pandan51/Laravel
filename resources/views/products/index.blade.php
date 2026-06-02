<x-app-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
				Products
			</h2>
			<div class="flex gap-2">
				<a href="{{ route('admin.categories.index') }}"
					class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 text-sm">
					Categories
				</a>
				<a href="{{ route('admin.products.create') }}"
					class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
					Add Product
				</a>
			</div>
		</div>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

			{{-- Filters --}}
			<div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
				<form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap gap-3 items-end">

					{{-- Name search --}}
					<div>
						<x-input-label for="search" value="Search" />
						<x-text-input id="search" name="search" type="text" class="mt-1 block"
							placeholder="Product name…" value="{{ request('search') }}" />
					</div>

					{{-- Category multi-select dropdown --}}
					@php $activeCategories = request()->input('categories', []); @endphp
					<div x-data="{ open: false }" class="relative">
						<x-input-label value="Categories" />
						<button type="button" @click="open = !open"
							class="mt-1 flex items-center gap-2 px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">
							@if(count($activeCategories) > 0)
								<span class="text-indigo-600 dark:text-indigo-400 font-medium">
									{{ count($activeCategories) }} selected
								</span>
							@else
								<span class="text-gray-500">All categories</span>
							@endif
							<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
							</svg>
						</button>

						<div x-show="open" @click.outside="open = false" x-cloak
							class="absolute z-10 mt-1 w-52 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg p-2 space-y-1">
							@foreach($categories as $category)
								<label class="flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-sm text-gray-700 dark:text-gray-300">
									<input type="checkbox" name="categories[]" value="{{ $category->id }}"
										{{ in_array($category->id, $activeCategories) ? 'checked' : '' }}
										class="rounded border-gray-300 dark:border-gray-600 text-indigo-600" />
									{{ $category->name }}
								</label>
							@endforeach
						</div>
					</div>

					<div class="flex gap-2">
						<x-primary-button>Filter</x-primary-button>
						@if(request('search') || count($activeCategories) > 0)
							<a href="{{ route('admin.products.index') }}"
								class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:underline">
								Clear
							</a>
						@endif
					</div>
				</form>
			</div>

			{{-- Table --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					@if($products->isEmpty())
						<p class="text-gray-500">No products found.</p>
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
										<td class="py-2 pr-4 text-gray-400 text-sm">{{ $product->id }}</td>
										<td class="py-2 pr-4">
											<a href="{{ route('products.show', $product) }}"
												class="text-indigo-600 hover:underline">
												{{ $product->name }}
											</a>
										</td>
										<td class="py-2 pr-4">{{ number_format($product->price, 2) }}</td>
										<td class="py-2 pr-4">
											@if($product->categories->isNotEmpty())
												<div class="flex flex-wrap gap-1">
													@foreach($product->categories as $category)
														<span class="inline-block px-2 py-0.5 text-xs rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300">
															{{ $category->name }}
														</span>
													@endforeach
												</div>
											@else
												<span class="text-gray-400">—</span>
											@endif
										</td>
										<td class="py-2 flex gap-3">
											<a href="{{ route('admin.products.edit', $product) }}"
												class="text-sm text-yellow-600 hover:underline">Edit</a>
											<form action="{{ route('admin.products.destroy', $product) }}" method="POST"
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
						@if($products->hasPages())
							<div class="mt-4">
								{{ $products->links() }}
							</div>
						@endif
					@endif
				</div>
			</div>

		</div>
	</div>
</x-app-layout>
