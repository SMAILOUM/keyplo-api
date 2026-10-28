# KEYPLO Merchant API

Server-side API for account, balance, live catalog, quotes and purchasing. EN/RU reference and OpenAPI.

[Interactive API reference](https://keyplo.com/docs/?lang=en) · [OpenAPI](./openapi.json)

## Start

1. The owner creates your account and allocates KEYPLO credit.
2. Create your API key personally in Integrations; store it only on your server.
3. Call GET /me and GET /balance.
4. Browse GET /products?kind=gift_card; choose a category_id to retrieve products.
5. POST /quotes with product_id, quantity and fields.
6. POST /orders with the same data plus external_id and max_total_minor. Supply a stable Idempotency-Key.
7. Poll GET /orders/{id}; deliver only when status is completed.

## Safety

Prices are USD cents. Never change the idempotency key to retry an uncertain purchase. Read status first. Unknown and needs_review orders require owner reconciliation. No public deposit API. GGsel, Digiseller and FunPay verification/mapping remain in development; a delivery link alone does not activate fulfillment. Steam nominal top-up is not yet exposed because the published rate alone does not include account-specific purchase fees.

120 requests/minute/account, 240/IP; JSON max 16 KB; HTTPS and Authorization: Bearer required. Keys are never accepted in query strings.

[Python](./examples/client.py) · [Node.js](./examples/client.mjs) · [PHP](./examples/client.php)

Steam gift browsing uses a first-500-game sample with local pages. Pass a numeric Steam App ID as category_id to retrieve another game directly. Category lists may be cached for five minutes; purchase quotes always refresh prices.
