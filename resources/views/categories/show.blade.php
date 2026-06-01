<x-app-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
				{{ $category->name }}
			</h2>
			@if(auth()->user()?->isAdmin())
				<div class="flex gap-3">
					<a href="{{ route('admin.categories.edit', $category) }}"
						class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-sm">
						Edit
					</a>
					<form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
						onsubmit="return confirm('Delete this category?')">
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
				<div class="p-6 text-gray-900 dark:text-gray-100">
					<p class="mb-4">
						<span class="font-semibold">ID:</span> {{ $category->id }}
					</p>

					<h3 class="font-semibold mb-2">Products in this category</h3>
					@if($category->products->isEmpty())
						<p class="text-gray-500">No products assigned yet.</p>
					@else
						<ul class="list-disc list-inside">
							@foreach($category->products as $product)
								<li>{{ $product->name }}</li>
							@endforeach
						</ul>
					@endif

					<div class="mt-6">
						<a href="{{ route('admin.categories.index') }}" class="text-sm text-indigo-600 hover:underline">
							← Back to categories
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
