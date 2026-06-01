<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			New Product
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6">
					<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
						@csrf

						<div class="mb-4">
							<x-input-label for="name" value="Name" />
							<x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
								value="{{ old('name') }}" required autofocus />
							<x-input-error :messages="$errors->get('name')" class="mt-2" />
						</div>

						<div class="mb-4">
							<x-input-label for="description" value="Description" />
							<textarea id="description" name="description"
								class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
								rows="4">{{ old('description') }}</textarea>
							<x-input-error :messages="$errors->get('description')" class="mt-2" />
						</div>

						<div class="mb-4">
							<x-input-label for="price" value="Price" />
							<x-text-input id="price" name="price" type="number" step="0.01" min="0"
								class="mt-1 block w-full" value="{{ old('price') }}" required />
							<x-input-error :messages="$errors->get('price')" class="mt-2" />
						</div>

						<div class="mb-4">
							<x-input-label for="images" value="Product Images" />
							<input id="images" name="images[]" type="file" accept="image/*" multiple
								class="mt-1 block w-full text-sm text-gray-700 dark:text-gray-300
									file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
									file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700
									hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300" />
							<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">First image will be set as primary. You can select multiple files.</p>
							<x-input-error :messages="$errors->get('images.*')" class="mt-2" />
						</div>

						@if($categories->isNotEmpty())
							<div class="mb-4">
								<x-input-label value="Categories" />
								<div class="mt-1 space-y-1">
									@foreach($categories as $category)
										<label class="flex items-center gap-2">
											<input type="checkbox" name="categories[]" value="{{ $category->id }}"
												{{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
												class="rounded border-gray-300 dark:border-gray-700" />
											{{ $category->name }}
										</label>
									@endforeach
								</div>
							</div>
						@endif

						<div class="flex gap-3">
							<x-primary-button>Save</x-primary-button>
							<a href="{{ route('admin.products.index') }}"
								class="px-4 py-2 text-sm text-gray-600 hover:underline">Cancel</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
