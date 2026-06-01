<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			New Category
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-xl mx-auto sm:px-6 lg:px-8">
			<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
				<div class="p-6">
					<form action="{{ route('admin.categories.store') }}" method="POST">
						@csrf

						<div class="mb-4">
							<x-input-label for="name" value="Name" />
							<x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
								value="{{ old('name') }}" required autofocus />
							<x-input-error :messages="$errors->get('name')" class="mt-2" />
						</div>

						<div class="flex gap-3">
							<x-primary-button>Save</x-primary-button>
							<a href="{{ route('admin.categories.index') }}"
								class="px-4 py-2 text-sm text-gray-600 hover:underline">Cancel</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
