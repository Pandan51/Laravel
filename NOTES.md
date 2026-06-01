# Laravel Catalog — Poznámky k dokumentaci

Tento soubor slouží jako pracovní zápisník pro budoucí dokumentaci.
Cíl: popsat postup tvorby jednotlivých částí Laravel projektu od nuly.

---

## Osnova dokumentace (plánováno)

1. Vytvoření projektu a instalace Breeze
2. Migrace — návrh schématu, tvorba tabulek
3. Modely — fillable, casts, relationships (HasMany, BelongsToMany, pivot)
4. Enums — OrderStatus, použití v migraci a modelu
5. Seeders — factories, DatabaseSeeder, pořadí volání
6. Routes — resource routes, middleware skupiny, prefix + name
7. Controllers — CRUD, route-model binding, validace, redirect s flash
8. Blade views — layout, komponenty, @auth/@guest, formuláře
9. Autentizace a admin role — Breeze, is_admin middleware
10. File upload — Storage facade, storage:link, public disk
11. Správa obrázků — multiple images, primary image, delete + reassign
12. Pagination — paginate(), withQueryString(), links()
13. Flash messages — session(), komponenta, globální layout

---

## Poznámky k jednotlivým tématům

### Seeders — pořadí záleží
DatabaseSeeder volá seedery v pořadí, které respektuje FK závislosti:
UserSeeder → CategorySeeder → ProductSeeder → ProductImageSeeder.
Bez správného pořadí selže FK constraint (produkt neexistuje, když se přidávají obrázky).

### Nested forms — HTML past
`<form>` uvnitř `<form>` je nevalidní HTML. Prohlížeč vnitřní form tagy ignoruje,
ale jejich inputy (včetně `@method('DELETE')`) přiřadí k vnějšímu formuláři.
Výsledek: kliknutí na "Delete image" odešle vnější formulář produktu s `_method=DELETE`
→ smaže celý produkt místo obrázku.
Řešení: formuláře pro správu obrázků musí být mimo hlavní form produktu — samostatná karta/sekce.

### Guzzle SSL na Windows (WAMP)
PHP `php.ini` nastavení `curl.cainfo` nestačí — Guzzle používá vlastní CA bundle
a systémové env proměnné, ne PHP ini.
Správný fix: `CURL_CA_BUNDLE=cesta/k/cacert.pem` v `.env` + číst přes
`Http::withOptions(['verify' => env('CURL_CA_BUNDLE')])` přímo v kódu.
Stáhnout cacert.pem: https://curl.se/ca/cacert.pem

### Primary image pattern
Single active selection na DB úrovni:
1. `UPDATE SET is_default = false` (všechny obrázky produktu)
2. `UPDATE SET is_default = true` (zvolený obrázek)
Alternativa (najít aktuální a změnit jen ten) vyžaduje extra SELECT a může vést
ke stavu se dvěma primárními při race condition.

### cascadeOnDelete
`$table->foreignId('product_id')->constrained()->cascadeOnDelete()`
Smaže záznamy v `product_images` automaticky při smazání produktu na DB úrovni.
Platí pouze pro DB — fyzické soubory je stále nutné mazat ručně v controlleru.
Změna migrace se projeví až po `migrate:fresh`.

---

## TODO pro dokumentaci
- [ ] Projít každý bod osnovy a doplnit konkrétní ukázky kódu
- [ ] Popsat tvorbu migrace krok za krokem (artisan make:migration, Blueprint metody)
- [ ] Popsat resource routes a co generují (php artisan route:list)
- [ ] Přidat sekci o Blade komponentách (x-app-layout, x-input-label, atd.)
