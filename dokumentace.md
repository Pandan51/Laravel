# Dokumentace projektu — Laravel E-shop Katalog

> Písemná část školního projektu. Deník zachycuje postup tvorby aplikace krok za krokem,
> s komentářem a odkazem na příslušnou část dokumentace Laravelu 11.

---

## 1. Instalace a konfigurace projektu

Projekt jsem začal instalací Laravelu přes Composer. 

```bash
composer create-project laravel/laravel laravel_catalog
```

Po instalaci bylo nutné nastavit soubor `.env` — přejmenoval jsem `.env.example`
a doplnil přihlašovací údaje k databázi. Jako databázi jsem zvolil **PostgreSQL**
místo výchozího MySQL, což vyžadovalo změnu `DB_CONNECTION=pgsql` a instalaci
PHP rozšíření `pdo_pgsql`.

**Klíčové nastavení v `.env`:**
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- `APP_URL`, `APP_KEY` (generuje `php artisan key:generate`)

> 📖 [Instalace](https://laravel.com/docs/11.x/installation) ·
> [Konfigurace prostředí](https://laravel.com/docs/11.x/configuration#environment-configuration)

---

## 2. Databáze a migrace

**Migrace jsou verzovací systém pro strukturu databáze.** Každá migrace je PHP třída
s metodami `up()` (vytvoření) a `down()` (rollback). Laravel je spouští příkazem:

```bash
php artisan migrate
```
Jsou další rozšíření jako
```bash
php artisan migrate:fresh
php artisan migrate:status
```

Pro projekt jsem vytvořil migrace pro tabulky:
`users`, `products`, `categories`, `product_category` (pivot), `carts`, `cart_items`,
`orders`, `order_items`, `product_images`.

Líbí se mi možnost lehce upravovat typy sloupců podle definovaných typů a rozšíření.
Dále je možné omezit sloupce.
Např.:
```php
$table->string('name', 100);
$table->string('email',254)->unique();
$table->timestamps();
```

**Příklad — pivot tabulka pro many-to-many vztah produktu a kategorie:**

```php
Schema::create('product_category', function (Blueprint $table) {
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->foreignId('category_id')->constrained()->cascadeOnDelete();
    $table->primary(['product_id', 'category_id']);
});
```

Důležitá volba bylo `cascadeOnDelete()` — při smazání produktu se automaticky smažou
i záznamy v pivot tabulce. Pro tabulku `product_images` jsem použil cascade pro fyzické
obrázky (soubory odstraňuje controller).

> 📖 [Migrace](https://laravel.com/docs/11.x/migrations) ·
> [Typy sloupců](https://laravel.com/docs/11.x/migrations#available-column-types) ·
> [Cizí klíče](https://laravel.com/docs/11.x/migrations#foreign-key-constraints)

---

## 3. Modely a Eloquent ORM

**Eloquent je ORM (Object-Relational Mapping) Laravelu** — každá tabulka má odpovídající
model jako PHP třídu. Model umožňuje pracovat s databází jako s objekty, bez psaní SQL.

Pro každou tabulku jsem vytvořil model příkazem:
```bash
php artisan make:model Product
```

Modely definují:
- **`$fillable`** — seznam sloupců, které lze hromadně přiřadit (ochrana před mass-assignment)
- **`$casts`** — automatická konverze typů (např. `price` → `decimal`, `status` → Enum)
- **Relace** — metody definující vztahy mezi tabulkami

**Příklad relací v modelu `Product`:**

```php
// Produkt patří do mnoha kategorií (many-to-many)
public function categories(): BelongsToMany
{
    return $this->belongsToMany(Category::class, 'product_category');
}

// Produkt má mnoho obrázků (one-to-many)
public function images(): HasMany
{
    return $this->hasMany(ProductImage::class);
}

// Hlavní obrázek (where is_default = true)
public function primaryImage(): HasOne
{
    return $this->hasOne(ProductImage::class)->where('is_default', true);
}
```

**Explicitní pojmenování pivot tabulky** (`'product_category'`) bylo nutné, protože
Laravel by jinak odvodil jiné jméno z abecedního pořadí názvů modelů.

> 📖 [Eloquent — začínáme](https://laravel.com/docs/11.x/eloquent#introduction) ·
> [Relace](https://laravel.com/docs/11.x/eloquent-relationships) ·
> [Many-to-Many](https://laravel.com/docs/11.x/eloquent-relationships#many-to-many)

---

## 4. Seedery a Factory

Aby bylo možné aplikaci předvést, potřebovala databáze testovací data. Laravel nabízí
**seedery** pro pevně daná data a **factories** pro generování náhodných záznamů.

```bash
php artisan db:seed          # spustí seedery
php artisan migrate:fresh --seed  # smaže vše a seeduje znovu (bezpečná varianta)
```

> **Důležité:** `db:seed` bez `migrate:fresh` vytváří duplicitní záznamy.
> Vždy používat `migrate:fresh --seed`.

**Vytvořené seedery:**
- `UserSeeder` — admin@example.com (`is_admin = true`) + běžný uživatel
- `CategorySeeder` — 6 fixních kategorií
- `ProductSeeder` — 20 produktů přes factory, každý s 1–2 náhodnými kategoriemi
- `ProductImageSeeder` — stahuje 2 obrázky na produkt z `picsum.photos` přes Guzzle

**Problém se SSL na Windows/WAMP:** Guzzle při stahování obrázků vyžadoval certifikát.
Nestačilo nastavit `curl.cainfo` v `php.ini` — bylo nutné přidat do `.env`:
```
CURL_CA_BUNDLE=C:/wamp64/bin/php/php8.3.x/extras/ssl/cacert.pem
```
**Použito pro stáhnutí obrázků na produkty pro testování**

> 📖 [Seedery](https://laravel.com/docs/11.x/seeding) ·
> [Factories](https://laravel.com/docs/11.x/eloquent-factories)

---

## 5. Autentizace — Laravel Breeze

Místo psaní autentizace od nuly jsem použil **Laravel Breeze** — oficiální starter kit,
který vygeneruje přihlašování, registraci, reset hesla a základní layout:

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run dev
```

Breeze vygenerovalo Blade views, routes (`/login`, `/register`, `/logout`...),
controllery a middleware `auth`. **Úspora desítek hodin práce.**

K tabulce `users` jsem přidal sloupec `is_admin` (boolean, default false) a metodu
`isAdmin()` v modelu User pro kontrolu oprávnění.

> 📖 [Laravel Breeze](https://laravel.com/docs/11.x/starter-kits#laravel-breeze) ·
> [Autentizace](https://laravel.com/docs/11.x/authentication)

---

## 6. Autorizace — Admin middleware

**Autentizace** ověřuje, kdo uživatel je. **Autorizace** ověřuje, co smí dělat.
Pro admin sekci jsem vytvořil vlastní middleware:

```bash
php artisan make:middleware AdminMiddleware
```

```php
public function handle(Request $request, Closure $next): Response
{
    if (!$request->user()?->isAdmin()) {
        abort(403);
    }
    return $next($request);
}
```

Middleware jsem zaregistroval a použil v routách jako skupinu:

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class)->except(['show']);
    Route::resource('categories', CategoryController::class);
});
```

Tím jsou **všechny admin routes chráněny** — neautentizovaný uživatel je přesměrován
na login, autentizovaný bez `is_admin` dostane HTTP 403.

> 📖 [Middleware](https://laravel.com/docs/11.x/middleware) ·
> [Skupiny middleware](https://laravel.com/docs/11.x/middleware#middleware-groups) ·
> [Autorizace](https://laravel.com/docs/11.x/authorization)

---

## 7. Routování

**Laravel router** mapuje URL adresy na controllery. Pro e-shop jsem použil tři úrovně:

```php
// Veřejné routes
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Přihlášení nutné
Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class);
    Route::resource('carts', CartController::class);
});



// Admin — auth + admin middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class)->except(['show']);
    // ...
});
```

**Resource routes** (`Route::resource`) jedním řádkem zaregistrují 7 standardních
akcí (index, create, store, show, edit, update, destroy) podle REST konvencí.
`->except(['show'])` vyloučí akce, které admin nepotřebuje.

> 📖 [Routování](https://laravel.com/docs/11.x/routing) ·
> [Resource controllery](https://laravel.com/docs/11.x/controllers#resource-controllers) ·
> [Route model binding](https://laravel.com/docs/11.x/routing#route-model-binding)

---

## 8. Controllery

**Controller** je třída, která zpracovává HTTP požadavek a vrací odpověď
(view, redirect, JSON...). Pro každou entitu jsem vytvořil resource controller:

```bash
php artisan make:controller ProductController --resource
```

**Zajímavý příklad — `CartController::store`** umí vracet jak redirect, tak JSON,
podle toho, zda jde o AJAX požadavek:

```php
public function store(Request $request)
{
    // ... logika přidání do košíku ...

    if ($request->expectsJson()) {
        return response()->json(['cart_count' => $cartCount]);
    }

    return back()->with('success', 'Added to cart.');
}
```

**Route model binding** — Laravel automaticky načte model z databáze podle parametru
v URL. Místo `Product::findOrFail($id)` stačí typovat `Product $product` v parametrech:

```php
public function edit(Product $product)   // Laravel najde produkt automaticky
```

> 📖 [Controllery](https://laravel.com/docs/11.x/controllers) ·
> [Route model binding](https://laravel.com/docs/11.x/routing#route-model-binding) ·
> [HTTP odpovědi](https://laravel.com/docs/11.x/responses)

---

## 9. Blade šablony

**Blade** je šablonovací engine Laravelu. Soubory `.blade.php` kombinují HTML
s PHP direktivami (`@if`, `@foreach`, `@auth`...) a kompilují se do čistého PHP.

**Klíčový koncept — layout a sloty:**

```blade
{{-- resources/views/layouts/app.blade.php --}}
<body>
    @include('layouts.navigation')
    <main>{{ $slot }}</main>
</body>

{{-- Jakýkoliv view --}}
<x-app-layout>
    <x-slot name="header">Nadpis stránky</x-slot>
    Obsah stránky...
</x-app-layout>
```

**Komponenty** (`x-`) jsou znovupoužitelné části UI. Breeze dodal komponenty jako
`x-primary-button`, `x-input-label`, `x-nav-link` — nemusel jsem je psát od nuly.

**Flash zprávy** — controller nastaví `session('success', '...')`, layout je zobrazí:
```blade
@if(session('success'))
    <div class="...">{{ session('success') }}</div>
@endif
```

> 📖 [Blade šablony](https://laravel.com/docs/11.x/blade) ·
> [Komponenty](https://laravel.com/docs/11.x/blade#components) ·
> [Layouts](https://laravel.com/docs/11.x/blade#layouts-using-template-inheritance)

---

## 10. Nahrávání souborů a Storage

Obrázky produktů se ukládají přes Laravel **Storage** fasádu. Soubory jdou do
`storage/app/public/products/`, odkud jsou přístupné přes symlink:

```bash
php artisan storage:link    # vytvoří public/storage → storage/app/public
```

**Nahrání obrázku v controlleru:**
```php
$path = $request->file('image')->store('products', 'public');
// výsledek: "products/abc123.jpg"
// URL: asset('storage/products/abc123.jpg')
```

**Mazání fyzického souboru** při smazání produktu nebo obrázku:
```php
Storage::disk('public')->delete($image->image_path);
```

Logika pro primární obrázek (`is_default`): při smazání hlavního obrázku se
automaticky přiřadí jako primární další obrázek seřazený podle `sort_order`.

> 📖 [Filesystem / Storage](https://laravel.com/docs/11.x/filesystem) ·
> [Nahrávání souborů](https://laravel.com/docs/11.x/filesystem#file-uploads)

---

## 11. AJAX košík a View Composer

**Přidání do košíku** funguje jako AJAX — stránka se neobnoví, tlačítko potvrdí akci:

```js
// resources/js/catalog.js
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const res = await fetch(form.action, { method: 'POST', body: new FormData(form), ... });
    const data = await res.json();
    // aktualizace badge v navigaci
});
```

**View Composer** sdílí `cartCount` (počet položek) se všemi views, kde se zobrazuje
navigace — bez nutnosti předávat proměnnou z každého controlleru zvlášť:

```php
// app/Providers/AppServiceProvider.php
View::composer('layouts.navigation', function ($view) {
    $view->with('cartCount', /* součet quantities v košíku */);
});
```

Badge v navigaci pak zobrazuje počet a skryje se, pokud je košík prázdný.

> 📖 [View Composers](https://laravel.com/docs/11.x/views#view-composers) ·
> [Service Providers](https://laravel.com/docs/11.x/providers) ·
> [HTTP požadavky (CSRF)](https://laravel.com/docs/11.x/csrf)

---

## 12. Enums a typová bezpečnost

PHP 8.1+ přineslo nativní **Enums**. Pro stav objednávky jsem vytvořil:

```php
enum OrderStatus: string
{
    case Pending   = 'Pending';
    case Paid      = 'Paid';
    case Shipped   = 'Shipped';
    case Cancelled = 'Cancelled';
}
```

Laravel Eloquent Enum cast automaticky převádí hodnotu z databáze na Enum instanci:
```php
protected $casts = ['status' => OrderStatus::class];
```

V Blade view pak lze iterovat přes hodnoty pro `<select>`:
```blade
@foreach(\App\Enums\OrderStatus::cases() as $status)
    <option value="{{ $status->value }}">{{ $status->value }}</option>
@endforeach
```

> 📖 [Eloquent Casting](https://laravel.com/docs/11.x/eloquent-mutators#enum-casting)

---

## 13. Stránkování (Pagination)

Laravel má **vestavěné stránkování** — stačí zavolat `paginate()` místo `get()`:

```php
$products = Product::paginate(12);           // 12 produktů na stránku
$products = Product::paginate(12)->withQueryString(); // zachová filtry v URL
```

Ve view se zobrazí navigace jedním řádkem:
```blade
{{ $products->links() }}
```

Laravel automaticky vygeneruje "Previous / Next" odkazy a číslování stránek,
kompatibilní s Tailwind CSS (Breeze používá Tailwind).

> 📖 [Stránkování](https://laravel.com/docs/11.x/pagination)

---

## Výhody a nevýhody Laravelu

### Výhody

**Rychlost vývoje**
Laravel eliminuje opakující se boilerplate. Příkazy `artisan make:model`,
`make:controller --resource`, `make:migration` vygenerují základ za sekundy.
Breeze přidá kompletní autentizaci za 5 minut.

**Eloquent ORM**
Práce s databází jako s objekty je intuitivní. Relace (`hasMany`, `belongsToMany`)
se definují jako metody — SQL join nemusí psát programátor vůbec. Eager loading
(`with('categories', 'images')`) řeší N+1 problém jednoduše.

**Konvence nad konfigurací**
Laravel předpokládá rozumné výchozí hodnoty (název tabulky = plural modelu,
pivot tabulka = abecední pořadí...). Programátor konfiguruje jen odchylky.

**Ekosystém**
Breeze (auth), Sanctum (API tokeny), Horizon (fronty), Telescope (debugging) —
oficiální balíčky pro většinu běžných potřeb. Velká komunita, výborná dokumentace.

**Bezpečnost v základu**
CSRF ochrana, SQL injection prevence přes prepared statements v Eloquentu,
mass-assignment ochrana přes `$fillable` — vše zapnuto od začátku.

**Artisan CLI**
`php artisan` nabízí desítky příkazů: generování kódu, spouštění migrací,
správa cache, interaktivní konzole (`tinker`). Výrazně urychluje práci.

---

### Nevýhody

**Magičnost ("Laravel magic")**
Framework dělá hodně věcí automaticky za programátora (route model binding,
service container, facades...). Pro začátečníka může být těžké pochopit,
co se děje "pod kapotou" — kód vypadá jako magie.

**Výkon vs. jednoduchost**
Eloquent je pohodlný, ale generuje složitější SQL dotazy než ručně psaný kód.
Pro aplikace s velmi vysokou zátěží může být nutné sáhnout po raw SQL
nebo query builderu.

**Závislost na frameworku**
Kód psaný "the Laravel way" je silně svázán s frameworkem. Přechod na jiný
framework nebo izolované testování části aplikace je obtížnější než u architektury
s čistou doménovou vrstvou.

**Verze a breaking changes**
Laravel vydává novou hlavní verzi každý rok. Upgrade může znamenat úpravy kódu
(např. Laravel 10 → 11 změnil strukturu `bootstrap/app.php` a middleware registraci).

**Přehnané pro malé projekty**
Pro jednoduchý web s pár stránkami je Laravel "přestřelený" — přináší overhead
(Composer, migrace, service providers...), který se vyplatí až od středně složitých projektů.

---

