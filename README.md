# KEYPLO Merchant API

[Русская инструкция](README.ru.md) · [OpenAPI](openapi.json) · [Merchant integration hub](https://keyplo.com/panel/?view=integrations&lang=en)

KEYPLO provides a private business workspace for digital-goods resellers. Accounts are created by KEYPLO; public registration is closed.

## Current status

The account API is available. The supplier catalog, purchasing, funding/reconciliation and marketplace purchase verification are **not enabled**. The API currently returns an empty product catalog. Valid order POST requests return `503 PURCHASING_NOT_ENABLED`: no order is created and no money is debited. This repository contains documentation and sample clients, not the private server or a fulfillment connector.

## Quick start

1. Open **Integrations** in your KEYPLO account.
2. Create your API key after confirming your password and Authenticator (if enabled).
3. Save the key immediately in your server secret store. It is displayed once. Replacing it invalidates the old key; revoking it blocks access immediately.
4. Set the `KEYPLO_API_KEY` environment variable on your server.
5. Test `GET https://keyplo.com/api/v1/me` and `/balance`.

```sh
curl 'https://keyplo.com/api/v1/me' -H "Authorization: Bearer $KEYPLO_API_KEY"
```

Never commit a real key, put it in a browser/mobile client or include it in a delivery URL. No query-string or cookie authentication. HTTPS only. Suspended accounts cannot use their API key or delivery page.

## Endpoints

| Method | Path | Current behavior |
| --- | --- | --- |
| GET | /me | Own account identity and capabilities |
| GET | /balance | Own USD balance, integer `balance_minor` cents and decimal string `balance` |
| GET | /products | Empty list with `catalog_connected: false` |
| GET | /orders | Own orders only; `limit=1..100`, `offset=0..100000` |
| GET | /orders/{id} | Own order; a missing or another merchant's order returns 404 |
| POST | /orders | Contract validation, then 503; no purchase yet |

POST requires JSON up to 16 KB, `Idempotency-Key` (16–100 letters/digits/hyphens/underscores), and `product_id`, `external_id`, `quantity: 1`, `fields: {}`. The header is validated now; idempotent fulfillment and accounting are **not implemented**, and must be connected before purchasing is enabled.

```json
{"product_id":"PRODUCT_ID","external_id":"YOUR_ORDER_ID","quantity":1,"fields":{}}
```

## Delivery page and marketplaces

Each merchant gets a stable delivery page in **Integrations**. The merchant owner can also see links in **Merchants**. The page accepts a marketplace order identifier, but currently returns service-unavailable because marketplace verification is not connected. It never treats a typed ID or possession of the merchant link as proof of payment, and it does not reveal orders or product codes.

GGsel, Digiseller and FunPay still require platform-specific credential handling, verified order lookup, product mapping, replay prevention and fulfillment. The link alone is not a ready connector.

## Errors and limits

Errors contain `error.code`, `error.message` and `request_id`; the ID is also returned in `X-Request-Id`. Add `?lang=ru` for Russian errors. 120 requests/minute per account key; 240/minute per IP. On 429 wait for `Retry-After: 60`. Delivery checks use a separate 12/minute/IP limit. Use backoff for 429/503. Do not automatically retry a future purchase without the same idempotency key.

Status codes: 400 malformed request; 401 invalid/revoked key; 404 missing or inaccessible resource; 405 method; 413 body too large; 415 JSON required; 422 invalid order; 429 rate limit; 503 disabled/unavailable service.

## Samples

- [Python](examples/client.py): standard library only; account and balance calls.
- [Node.js](examples/client.mjs): built-in fetch; account and balance calls.
- [PHP](examples/client.php): cURL; account and balance calls.

Samples read a server environment variable and never embed a real key. Keep customer data and API secrets out of issue reports. Contact partnerships@keyplo.com privately for account support.
