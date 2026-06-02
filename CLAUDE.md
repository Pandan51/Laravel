# laravel_catalog — Project Context

## Tech Stack
- PHP 8.3
- Laravel 11
- Blade (server-side templates, no separate frontend framework)
- Alpine.js (bundled via Breeze) — used for UI interactions (dropdowns, toggles)
- Vite — JS/CSS bundler (`npm run dev` / `npm run build`)
- PostgreSQL (DB_CONNECTION=pgsql, port 5432, database: catalog, user: postgres)

## Project Goal
Learning project — product catalog e-shop with cart and orders.

## Current State (2026-06-02)

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
- `product_images` — product_id FK (cascadeOnDelete), image_path, is_default, sort_order

### Models — done
All models in `app/Models/` with $fillable, casts, relationships.
- `BelongsToMany` pivot table explicitly set to `product_category` in both Product and Category models.
- `Product` has `images()` HasMany and `primaryImage()` HasOne (where is_default = true)

### Enums — done
- `app/Enums/OrderStatus.php` — Pending, Paid, Shipped, Cancelled

### Routes — done
- `/` and `/dashboard` — redirect na `catalog.index`
- Public (prefix `catalog/`):
  - `GET /catalog` → `CatalogController@index` (name: `catalog.index`); filter: `?category={id}`
  - `GET /catalog/products/{product}` → `ProductController@show` (name: `products.show`)
- Auth: `orders`, `carts` resource
- Admin middleware group (`auth` + `admin`, prefix `admin/`, name `admin.`):
  - `products` resource (except show)
  - `categories` resource
  - `DELETE admin/products/{product}/images/{image}` → `ProductImageController@destroy`
  - `PATCH admin/products/{product}/images/{image}/primary` → `ProductImageController@setPrimary`

### Controllers — done
- `CatalogController` — public catalog with category filter (`?category={id}`), paginate(12), withQueryString()
- `CategoryController` — full CRUD, route-model binding (admin only)
- `ProductController` — full CRUD, filter by name (ilike) + multi-category (whereIn), paginate(15), withQueryString(); image upload on store/update; storage cleanup on destroy
- `ProductImageController` — destroy (delete file + reassign primary), setPrimary (reset all → set one)
- `CartController` — index, store (add/increment; returns JSON for AJAX requests), update (quantity), destroy (item or clear)
- `OrderController` — index, create, store (from cart), show, update (status), destroy (cancel); ownership check via `authorizeOrder()` (abort 403 on mismatch)

### Blade Views — done
- `resources/views/catalog/` — index (category filter, product grid with primary image + Add to Cart button, pagination)
- `resources/views/products/` — index (Admin heading, name search + multi-category dropdown filter, category chips, pagination), create (image upload), edit (image management in separate card + upload), show (image gallery)
- `resources/views/categories/` — index, create, edit, show
- `resources/views/carts/` — index (product name links to product.show, update quantity, remove, clear, checkout)
- `resources/views/orders/` — index, create (checkout summary), show (status update, cancel)
- `resources/views/components/flash-message.blade.php` — global success/error flash (included in app layout)
- `resources/views/layouts/navigation.blade.php` — logo → catalog, Catalog + Admin links, cart icon with badge, Orders + Profile + Logout in user dropdown

### Authentication — done
Laravel Breeze installed, views in `resources/views/auth/`.
- `users` table has `is_admin` boolean column
- `admin` middleware checks `is_admin` flag

### Seeders — done
- `UserSeeder` — admin@example.com (is_admin=true) + user@example.com (is_admin=false), password: "password"
- `CategorySeeder` — 6 fixed categories
- `ProductSeeder` — 20 products via factory, each with 1–2 random categories
- `ProductImageSeeder` — downloads 2 images per product from picsum.photos (seed-based, deterministic); reads `CURL_CA_BUNDLE` from `.env` for Guzzle SSL on Windows
- `OrderSeeder` — 3 orders per user (Pending, Paid, Shipped), each with 2–3 random products
- `DatabaseSeeder` — calls all seeders in order: User → Category → Product → ProductImage → Order

### Image upload — done
- Files stored in `storage/app/public/products/` via `Storage::disk('public')`
- Accessible via `asset('storage/...')` after `php artisan storage:link`
- `is_default` marks the primary image; `sort_order` determines gallery order
- On product delete: physical files removed before model deletion
- On image delete: file removed, primary auto-reassigned to next by sort_order if needed
- Edit view: existing images in separate card (outside main form) to avoid nested form HTML bug

### JS — done
- `resources/js/catalog.js` — Add to Cart AJAX (fetch POST, JSON response, button confirmation)
- Imported in `resources/js/app.js`; after changes run `npm run dev`

### Cart badge — done
- `AppServiceProvider` — View Composer shares `cartCount` (sum of quantities) with `layouts.navigation`
- Badge shown on cart icon when count > 0; displays 99+ for large counts

## Known Issues / Notes
- Running `db:seed` without `migrate:fresh` creates duplicate records — always use `migrate:fresh --seed`
- WAMP/Windows: Guzzle SSL requires `CURL_CA_BUNDLE` in `.env` pointing to `cacert.pem`; PHP `php.ini` curl.cainfo alone is not enough for Guzzle
- Nested forms (form inside form) are invalid HTML — browsers ignore inner form tags, causing unexpected submissions. Keep image action forms outside the product edit form.

## Next Steps (optional extensions)
1. Pagination on categories index
2. Order status history log
