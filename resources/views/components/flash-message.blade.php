@if (session('success'))
	<div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-700 rounded-md text-sm text-green-700 dark:text-green-400">
		{{ session('success') }}
	</div>
@endif

@if (session('error'))
	<div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-300 dark:border-red-700 rounded-md text-sm text-red-700 dark:text-red-400">
		{{ session('error') }}
	</div>
@endif
