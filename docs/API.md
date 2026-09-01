# API Endpoints

## Base URL

```
https://your-domain.com/api/v1
```

## Authentication

Most API endpoints require authentication. Include the session cookie or API token in requests.

---

## Health Check

### GET /health

Returns system health status.

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

Verify a Razorpay payment.

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

Get states for a country.

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

Get cities for a state.

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

Get all notification types.

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

Get user's notification preferences.

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

Update notification preferences.

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

Update a specific preference.

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

Delete a preference (resets to default).

### POST /notification-preferences/reset-all

Reset all preferences to defaults.

---

## Webhooks

### POST /payment/webhook

Razorpay webhook endpoint (excluded from CSRF).

**Headers:**
| Name | Description |
|---|---|
| X-Razorpay-Signature | Webhook signature for verification |

---

## Campaigns

### GET /campaigns

Get all active campaigns.

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

Get a single campaign by slug.

---

## Donations

### GET /donations

Get user's donation history.

### POST /donations

Create a new donation.

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

Get user's wallet details.

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

Get wallet transaction history.

---

## Settlements

### GET /settlements

Get user's settlements.

### POST /settlements

Request a new settlement.

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

| Endpoint | Limit |
|---|---|
| All API | 60 requests/minute |
| Payment verify | 10 requests/minute |
| Webhooks | 120 requests/minute |

---

## Error Responses

All errors follow this format:

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
