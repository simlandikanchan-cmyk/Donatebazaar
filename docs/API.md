# API Endpoints

## Base URL

```
https://your-domain.com/api/v1
```

## Authentication

Most API endpoints require an authenticated session. Pass the session cookie or API token in your request; unauthenticated calls are rejected with `401`.

---

## Health Check

### GET /health

Returns the current system health status, including cache, database, queue, and Redis checks.

**Response:**
```json
{
  "status": "ok",
  "checks": {
    "cache": "ok",
    "database": "ok",
    "queue": "ok",
    "redis": "ok"
  },
  "timestamp": "2026-09-01T12:00:00Z"
}
```

---

## Payments

### POST /payment/verify

Verifies a Razorpay payment using the payment ID, order ID, and signature returned by the checkout.

**Request:**
```json
{
  "razorpay_payment_id": "pay_xxxxxxxx",
  "razorpay_order_id": "order_xxxxxxxx",
  "razorpay_signature": "xxxxxxxx"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Payment verified",
  "donation_id": 123
}
```

---

## Locations

### GET /states/{country}

Returns the states for a country. The current integration uses `"india"`.

**Parameters:**
| Name | Type | Description |
|---|---|---|
| country | string | Country name (use "india") |

**Response:**
```json
[
  {"code": "MH", "name": "Maharashtra"},
  {"code": "KA", "name": "Karnataka"}
]
```

### GET /cities/{state}

Returns the cities for a given state code.

**Parameters:**
| Name | Type | Description |
|---|---|---|
| state | string | State code (e.g., "MH") |

**Response:**
```json
[
  {"id": 1, "name": "Mumbai"},
  {"id": 2, "name": "Pune"}
]
```

---

## Notifications (Authenticated)

### GET /notification-types

Lists every notification type the user can configure.

**Response:**
```json
{
  "data": [
    {"key": "donation_received", "label": "Donation Received"},
    {"key": "campaign_approved", "label": "Campaign Approved"}
  ]
}
```

### GET /notification-preferences

Returns the user's current notification preferences.

**Response:**
```json
{
  "data": [
    {
      "type": "donation_received",
      "channel": "mail",
      "enabled": true
    }
  ]
}
```

### POST /notification-preferences

Replaces the user's preferences in one call.

**Request:**
```json
{
  "preferences": [
    {
      "type": "donation_received",
      "channel": "mail",
      "enabled": true
    }
  ]
}
```

### PUT /notification-preferences/{type}/{channel}

Toggles a single preference.

**Parameters:**
| Name | Type | Description |
|---|---|---|
| type | string | Notification type key |
| channel | string | Channel (mail, database, slack) |

**Request:**
```json
{
  "enabled": true
}
```

### DELETE /notification-preferences/{type}/{channel}

Deletes a preference and resets it to the default.

### POST /notification-preferences/reset-all

Resets all preferences to their defaults.

---

## Webhooks

### POST /payment/webhook

Razorpay's webhook endpoint. This route is excluded from CSRF protection because Razorpay cannot send a session token.

**Headers:**
| Name | Description |
|---|---|
| X-Razorpay-Signature | Webhook signature used for verification |

---

## Campaigns

### GET /campaigns

Lists all active campaigns.

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Help Build a School",
      "slug": "help-build-a-school",
      "description": "Campaign description...",
      "cover_image": "https://...",
      "goal_amount": 500000,
      "raised_amount": 125000,
      "location": "Mumbai",
      "start_date": "2026-09-01",
      "end_date": "2026-12-31",
      "campaign_state": "active",
      "is_featured": true,
      "is_urgent": false,
      "followers_count": 45,
      "donations_count": 23,
      "category": {
        "id": 1,
        "name": "Education"
      },
      "user": {
        "id": 1,
        "name": "John Doe",
        "avatar": "https://..."
      }
    }
  ]
}
```

### GET /campaigns/{slug}

Returns a single campaign by its slug.

---

## Donations

### GET /donations

Returns the authenticated user's donation history.

### POST /donations

Creates a new donation for the given campaign.

**Request:**
```json
{
  "campaign_id": 1,
  "amount": 1000,
  "is_anonymous": false,
  "message": "Keep up the good work!"
}
```

---

## Wallet

### GET /wallet

Returns the user's wallet balances.

**Response:**
```json
{
  "data": {
    "id": 1,
    "balance": 50000,
    "reserved_balance": 10000,
    "available_balance": 40000,
    "currency": "INR",
    "total_credits": 75000,
    "total_debits": 25000
  }
}
```

### GET /wallet/transactions

Returns the wallet's transaction history.

---

## Settlements

### GET /settlements

Returns the user's settlement requests.

### POST /settlements

Requests a new payout from the available balance.

**Request:**
```json
{
  "amount": 25000
}
```

**Response:**
```json
{
  "data": {
    "id": 1,
    "amount": 25000,
    "status": "pending",
    "created_at": "2026-09-01T12:00:00Z"
  }
}
```

---

## Rate Limiting

| Endpoint | Limit |
|---|---|
| All API | 60 requests/minute |
| Payment verify | 10 requests/minute |
| Webhooks | 120 requests/minute |

---

## Error Responses

Every error follows the same shape, so a client can handle validation failures and server errors uniformly:

```json
{
  "message": "Error description",
  "errors": {
    "field": ["Validation error message"]
  }
}
```

| Status | Description |
|---|---|
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 429 | Too Many Requests |
| 500 | Server Error |