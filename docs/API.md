# REST API (v1)

Base URL: `/api/v1`

Unless noted, JSON requests and responses use `application/json`. Authenticated routes expect:

```http
Authorization: Bearer <jwt>
```

JWTs are issued by `php-open-source-saver/jwt-auth` (`auth:api` guard).

## Auth

| Method | Path | Description |
|--------|------|-------------|
| POST | `/auth/register` | Body: `name`, `email`, `password`, `password_confirmation`. Returns `token`. Sends email verification. |
| POST | `/auth/login` | Body: `email`, `password`. Returns `token`. |
| POST | `/auth/logout` | Invalidates current token. |
| POST | `/auth/refresh` | Rotates JWT. |
| GET | `/auth/me` | Current user. |

## Catalog (public)

| Method | Path | Description |
|--------|------|-------------|
| GET | `/categories` | Root categories with children. |
| GET | `/categories/{slug}` | Category with active products. |
| GET | `/products` | Paginated list. Query: `category`, `brand`, `gender`, `fragrance_type`, `min_price`, `max_price`, `search`, `featured`, `new`, `bestseller`, `sale`, `type` (product line: `perfume`, `attar`, `oud`, etc.), `limited` (boolean), `sort` (`newest`, `price_asc`, `price_desc`), `per_page`. |
| GET | `/products/{slug}` | Product detail with related items. |

## Cart (auth)

| Method | Path | Description |
|--------|------|-------------|
| GET | `/cart` | Current user cart with line totals. |
| POST | `/cart/items` | Body: `product_variant_id`, optional `quantity`. |
| PATCH | `/cart/items/{cartItem}` | Body: `quantity`. |
| DELETE | `/cart/items/{cartItem}` | Remove line. |

## Coupons (auth)

| Method | Path | Description |
|--------|------|-------------|
| POST | `/coupons/validate` | Body: `code`, `subtotal`. Returns discount preview. |

## Orders (auth)

| Method | Path | Description |
|--------|------|-------------|
| GET | `/orders` | Paginated order history. |
| GET | `/orders/{order}` | Order detail (owner only). |
| POST | `/orders` | Creates order from cart. Body: `payment_method` (`stripe`, `paypal`, `cod`), `shipping_address` (array), optional `billing_address`, `coupon_code`, `customer_note`. Clears cart and decrements stock. Response includes `stripe.client_secret` when applicable. |

## Configuration knobs

- Tax: `SHOP_TAX_RATE` (e.g. `0.08` for 8%).
- Shipping: `SHOP_SHIPPING_FLAT`, `SHOP_SHIPPING_FREE_ABOVE` (see `config/shop.php`).

## Errors

Validation errors return `422` with Laravel’s standard `{ "message": "...", "errors": { ... } }` payload. Authenticated routes return `401` without a valid JWT.
