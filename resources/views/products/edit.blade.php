<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Edit Product
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">

			{{-- Existing images — separate block, outside the product form --}}
			@php $existingImages = $product->images()->orderBy('sort_order')->get(); @endphp
			@if ($existingImages->isNotEmpty())
				<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
					<div class="p-6">
						<x-input-label value="Current Images" />
						<div class="mt-3 flex flex-wrap gap-3">
							@foreach ($existingImages as $img)
								<div class="w-32">
									<img src="{{ asset('storage/' . $img->image_path) }}"
										alt="{{ $product->name }}"
										class="h-32 w-32 object-cover rounded-md border-2 {{ $img->is_default ? 'border-indigo-500' : 'border-gray-200 dark:border-gray-700' }}" />
									@if ($img->is_default)
										<span class="mt-1 inline-block text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded">Primary</span>
									@endif
									<div class="mt-1 flex gap-2">
										@unless ($img->is_default)
											<form action="{{ route('admin.product-images.set-primary', [$product, $img]) }}" method="POST">
												@csrf
												@method('PATCH')
												<button type="submit" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Set primary</button>
											</form>
										@endunless
										<form action="{{ route('admin.product-images.destroy', [$product, $img]) }}" method="POST"
											onsubmit="return confirm('Delete this image?')">
											@csrf
											@method('DELETE')
											<button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">Delete</button>
										</form>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			@endif

			{{-- Product edit form --}}
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6">
					<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
						@csrf
						@method('PUT')

						<div class="mb-4">
							<x-input-label for="name" value="Name" />
							<x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
								value="{{ old('name', $product->name) }}" required autofocus />
							<x-input-error :messages="$errors->get('name')" class="mt-2" />
						</div>

						<div class="mb-4">
							<x-input-label for="description" value="Description" />
							<textarea id="description" name="description"
								class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
								rows="4">{{ old('description', $product->description) }}</textarea>
							<x-input-error :messages="$errors->get('description')" class="mt-2" />
						</div>

						<div class="mb-4">
							<x-input-label for="price" value="Price" />
							<x-text-input id="price" name="price" type="number" step="0.01" min="0"
								class="mt-1 block w-full" value="{{ old('price', $product->price) }}" required />
							<x-input-error :messages="$errors->get('price')" class="mt-2" />
						</div>

						<div class="mb-4">
							<x-input-label for="images" value="{{ $existingImages->isNotEmpty() ? 'Add More Images' : 'Product Images' }}" />
							<input id="images" name="images[]" type="file" accept="image/*" multiple
								class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300
									file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
									file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700
									hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300" />
							@if ($existingImages->isEmpty())
								<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">First image will be set as primary.</p>
							@endif
							<x-input-error :messages="$errors->get('images.*')" class="mt-2" />
						</div>

						@if($categories->isNotEmpty())
							<div class="mb-4">
								<x-input-label value="Categories" />
								<div class="mt-1 space-y-1">
									@foreach($categories as $category)
										<label class="flex items-center gap-2">
											<input type="checkbox" name="categories[]" value="{{ $category->id }}"
												{{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}
												class="rounded border-gray-300 dark:border-gray-700" />
											{{ $category->name }}
										</label>
									@endforeach
								</div>
							</div>
						@endif

						<div class="flex gap-3">
							<x-primary-button>Update</x-primary-button>
							<a href="{{ route('admin.products.index') }}"
								class="px-4 py-2 text-sm text-gray-600 hover:underline">Cancel</a>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</x-app-layout>
