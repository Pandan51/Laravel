document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.add-to-cart-form').forEach(form => {
		form.addEventListener('submit', async (e) => {
			e.preventDefault();

			const btn = form.querySelector('.add-to-cart-btn');
			btn.disabled = true;

			try {
				const response = await fetch(form.action, {
					method: 'POST',
					headers: {
						'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
						'Accept': 'application/json',
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: new URLSearchParams(new FormData(form)),
				});

				if (response.ok) {
					btn.textContent = '✓ Added!';
					btn.classList.replace('bg-indigo-600', 'bg-green-600');
					btn.classList.remove('hover:bg-indigo-700');

					setTimeout(() => {
						btn.textContent = 'Add to Cart';
						btn.classList.replace('bg-green-600', 'bg-indigo-600');
						btn.classList.add('hover:bg-indigo-700');
						btn.disabled = false;
					}, 2500);
				}
			} catch {
				btn.disabled = false;
			}
		});
	});
});
