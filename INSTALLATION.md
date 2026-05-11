# Installation

## Requirements

- PHP 8.2+ with extensions: `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `intl` (recommended for Filament)
- Composer 2.x
- Node.js 20+ and npm
- MySQL 8+ (recommended for production) or SQLite for local demos

## Local setup

1. Clone the repository and install PHP dependencies:

   ```bash
   composer install
   ```

   On Windows/XAMPP, if Composer blocks on `ext-intl`, you can temporarily use:

   ```bash
   composer install --ignore-platform-req=ext-intl
   ```

   Enable `intl` in `php.ini` before production.

2. Environment file:

   ```bash
   copy .env.example .env
   php artisan key:generate
   php artisan jwt:secret
   ```

3. Database:

   - **MySQL**: set `DB_CONNECTION=mysql` and credentials in `.env`, then:

     ```bash
     php artisan migrate --force
     php artisan db:seed --force
     ```

   - **SQLite** (default in `.env.example`): ensure `database/database.sqlite` exists, then run the same migrate/seed commands.

4. Link storage for uploaded images (admin uploads, catalog media):

   ```bash
   php artisan storage:link
   ```

5. Frontend assets:

   ```bash
   npm install
   npm run build
   ```

6. Run the application:

   ```bash
   php artisan serve
   ```

   - Storefront: `http://127.0.0.1:8000`
   - Admin panel: `http://127.0.0.1:8000/admin`

## Default accounts (after `db:seed`)

| Role        | Email                 | Password  |
|------------|------------------------|-----------|
| Super admin | `admin@perfume.test`  | `password` |
| Customer    | `customer@perfume.test` | `password` |

Change these immediately in any shared or production environment.

## Payments and shipping

- **Stripe**: set `STRIPE_KEY` and `STRIPE_SECRET`. Orders with `payment_method=stripe` receive a `PaymentIntent` `client_secret` in the API response for front-end confirmation. Add a webhook to mark orders paid when `payment_intent.succeeded` fires (implement in `routes/web.php` or a dedicated controller).
- **PayPal**: configure `PAYPAL_MODE` and the matching client ID/secret in `config/services.php` via `.env`. The project includes `srmklive/paypal`; wire capture/redirect flows where you process checkouts.
- **Cash on delivery**: creates a `payment_transactions` row with provider `cod`.

Shipping quotes use `App\Services\Shipping\ShippingQuoteService` (flat rate / free threshold). Replace with EasyPost, ShipEngine, Shippo, or carrier APIs for live rates, labels, and tracking.

## Production checklist

- `APP_ENV=production`, `APP_DEBUG=false`, strong `APP_KEY` and `JWT_SECRET`
- MySQL with TLS, restricted database user
- Queue worker: `php artisan queue:work` (for mail, webhooks, abandoned cart jobs you add)
- Scheduler: `* * * * * php artisan schedule:run`
- Redis for cache/session/queue (optional but recommended)
- HTTPS termination (nginx/Apache/Caddy) and `APP_URL` matching public URL
- `php artisan config:cache route:cache view:cache` after deploy

## Multi-language and multi-currency

- Users store `locale` and `currency` columns; extend middleware to set `App::setLocale()` from the authenticated user or route prefix.
- Exchange rates are not bundled; integrate an FX API and cache rates in `store_settings` or a dedicated table.
