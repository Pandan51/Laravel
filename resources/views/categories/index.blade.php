<x-app-layout>
	<x-slot name="header">
		<div class="flex justify-between items-center">
			<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
				Categories
			</h2>
			<a href="{{ route('categories.create') }}"
				class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
				Add Category
			</a>
		</div>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 dark:text-gray-100">
					@if($categories->isEmpty())
						<p class="text-gray-500">No categories yet.</p>
					@else
						<table class="w-full text-left">
							<thead>
								<tr class="border-b dark:border-gray-700">
									<th class="py-2 pr-4">#</th>
									<th class="py-2 pr-4">Name</th>
									<th class="py-2">Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach($categories as $category)
									<tr class="border-b dark:border-gray-700">
										<td class="py-2 pr-4">{{ $category->id }}</td>
										<td class="py-2 pr-4">
											<a href="{{ route('categories.show', $category) }}"
												class="text-indigo-600 hover:underline">
												{{ $category->name }}
											</a>
										</td>
										<td class="py-2 flex gap-3">
											<a href="{{ route('categories.edit', $category) }}"
												class="text-sm text-yellow-600 hover:underline">Edit</a>
											<form action="{{ route('categories.destroy', $category) }}" method="POST"
												onsubmit="return confirm('Delete this category?')">
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
