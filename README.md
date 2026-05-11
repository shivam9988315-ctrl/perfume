# Noir Atelier — Luxury perfume e-commerce (Laravel)

Production-oriented foundation for a perfume storefront with **Filament 5** admin, **MySQL-ready** schema, **REST API** (`/api/v1`) using **JWT** (`php-open-source-saver/jwt-auth`) and **Laravel Sanctum** for future SPA/mobile tokens, **Stripe** + **PayPal** packages and **COD** flows, **shipping quote** service stub, coupons, orders, inventory on variants, CMS tables (banners, pages, blog, FAQs, inquiries), newsletter list, and a **dark / gold** Blade storefront.

## Quick start

See [INSTALLATION.md](INSTALLATION.md) for PHP extensions, MySQL, queues, and deployment. API surface is documented in [docs/API.md](docs/API.md).

```bash
composer install
copy .env.example .env   # or cp on Unix
php artisan key:generate
php artisan jwt:secret
php artisan migrate --seed
php artisan storage:link
npm install && npm run build
php artisan serve
```

- **Storefront**: `/` — hero, featured / new / bestseller / sale rows, brands, newsletter.
- **Shop**: `/shop`, `/shop/{slug}` — filters, gallery swap, variant list.
- **Admin**: `/admin` — dashboard stats widget, brands, categories, products, orders (edit-only), coupons, banners.

## What is included vs what you extend

| Area | Status |
|------|--------|
| Catalog, variants, stock, wishlist/cart schema | Migrations + models |
| Orders, tax, shipping flat/free threshold, coupons | API checkout + admin orders |
| Stripe PaymentIntent + transaction log | Service creates intent; webhook to mark paid is yours |
| PayPal capture | Package installed; wire redirect/capture + webhook |
| Real-time carrier rates, labels, tracking | Stub `ShippingQuoteService` — plug EasyPost/ShipEngine/etc. |
| Email verification, password reset | Verification on register; use Laravel Breeze/Fortify for web auth if needed |
| SMS, abandoned cart, AI recommendations, social login | Schema hooks / jobs not generated — add providers & jobs |
| Multi-language / multi-currency | User `locale`/`currency` + config notes in INSTALLATION |

## Security

- Spatie **roles & permissions** (`super_admin`, `admin`, `staff`, `customer`).
- Filament access restricted by `User::canAccessPanel()` to admin roles.
- API throttling via `throttle:api`; login/register throttled with `throttle:login`.
- Run `composer audit` regularly and keep `.env` out of version control.

## License

MIT (same as Laravel default).
