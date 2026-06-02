<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
			Admin
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
			<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

				{{-- Products --}}
				<a href="{{ route('admin.products.index') }}"
					class="block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition group">
					<div class="flex items-center gap-4">
						<div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
								class="w-6 h-6 text-indigo-600 dark:text-indigo-400">
								<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
							</svg>
						</div>
						<div>
							<p class="text-sm text-gray-500 dark:text-gray-400">Products</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $productCount }}</p>
						</div>
					</div>
					<p class="mt-4 text-sm text-indigo-600 dark:text-indigo-400 group-hover:underline">
						Manage products →
					</p>
				</a>

				{{-- Categories --}}
				<a href="{{ route('admin.categories.index') }}"
					class="block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition group">
					<div class="flex items-center gap-4">
						<div class="p-3 bg-emerald-100 dark:bg-emerald-900 rounded-lg">
							<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
								class="w-6 h-6 text-emerald-600 dark:text-emerald-400">
								<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
								<path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
							</svg>
						</div>
						<div>
							<p class="text-sm text-gray-500 dark:text-gray-400">Categories</p>
							<p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $categoryCount }}</p>
						</div>
					</div>
					<p class="mt-4 text-sm text-emerald-600 dark:text-emerald-400 group-hover:underline">
						Manage categories →
					</p>
				</a>

			</div>
		</div>
	</div>
</x-app-layout>
