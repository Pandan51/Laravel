# laravel_catalog — Project Context

## Tech Stack
- PHP 8.2
- Laravel 11
- Blade (server-side templates, no separate frontend framework)
- PostgreSQL (DB_CONNECTION=pgsql, port 5432, database: catalog, user: postgres)

## Project Goal
Learning project — product catalog e-shop with cart and orders.

## Current State (2026-05-30)

### Migrations — done
All tables created and run:
- `users`, `cache` — Laravel default
- `products` — name, description (nullable), price (decimal 10,2)
- `categories` — name
- `product_category` — pivot (product_id + category_id)
- `carts` — user_id FK
- `cart_items` — cart_id FK, product_id FK, quantity
- `orders` — user_id FK, total_price (decimal 10,2), status (enum)
- `order_items` — order_id FK, product_id FK, quantity, price_at_purchase
- `product_images` — product_id FK (cascadeOnDelete), path, alt, is_primary, sort_order

### Models — done
All models in `app/Models/` with $fillable, casts, relationships.
Note: `BelongsToMany` pivot table explicitly set to `product_category` in both Product and Category models.

### Enums — done
- `app/Enums/OrderStatus.php` — Pending, Paid, Shipped, Cancelled

### Routes — done
- `Route::resource('products', ProductController::class)` — public
- `Route::resource('categories', CategoryController::class)` — public
- `Route::resource('orders', OrderController::class)` — auth
- `Route::resource('carts', CartController::class)` — auth

### Controllers — done
- `CategoryController` — full CRUD, route-model binding
- `ProductController` — full CRUD, categories sync via pivot
- `CartController` — index, store (add/increment), update (quantity), destroy (item or clear)
- `OrderController` — index, create, store (from cart), show, update (status), destroy (cancel)

### Blade Views — done
- `resources/views/categories/` — index, create, edit, show
- `resources/views/products/` — index, create, edit, show (+ Add to Cart button on show)
- `resources/views/carts/` — index (update quantity, remove item, clear, checkout link)
- `resources/views/orders/` — index, create (checkout summary), show (status update, cancel)
- `resources/views/layouts/navigation.blade.php` — updated with @auth/@guest, links to all sections

### Authentication — done
Laravel Breeze installed, views in `resources/views/auth/`.

### Seeders — done
- `ProductFactory` — random name, optional description, price 5–500
- `CategorySeeder` — 6 fixed categories
- `ProductSeeder` — 20 products via factory, each with 1–2 random categories
- `DatabaseSeeder` — creates test user (test@example.com / password), calls CategorySeeder + ProductSeeder

## Known Issues / Notes
- `product_images` table exists but upload functionality not implemented
- Status update on orders/show is accessible to any authenticated user (no admin role yet)
- Running `db:seed` multiple times without `migrate:fresh` creates duplicate records

## Next Steps (optional extensions)
1. Product image upload (ProductImage model + storage)
2. Admin middleware — restrict category/product create/edit/delete to admins
3. Pagination on products index (`Product::paginate(12)` instead of `all()`)
4. Flash messages — success feedback after create/update/delete
