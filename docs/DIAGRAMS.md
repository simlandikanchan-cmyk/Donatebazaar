# System Architecture Diagram

The big picture first: three frontends (public, user, admin) talk to one Laravel app, which splits requests through controllers into services, models, events, and queue jobs, and finally lands on MySQL, Redis, and Razorpay.

```
                                    ┌─────────────────┐
                                    │     Users       │
                                    │  (Donors,       │
                                    │   Creators,     │
                                    │   Admins)       │
                                    └────────┬────────┘
                                             │
                         ┌───────────────────┼───────────────────┐
                         │                   │                   │
                         ▼                   ▼                   ▼
              ┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐
              │  Public Portal   │ │  User Dashboard  │ │  Admin Dashboard │
              │                  │ │                  │ │                  │
              │  • Homepage      │ │  • Campaigns     │ │  • Analytics     │
              │  • Campaigns     │ │  • Donations     │ │  • Users         │
              │  • Donation Flow │ │  • Wallet        │ │  • Campaigns     │
              │  • Auth          │ │  • Profile       │ │  • Settlements   │
              │  • Blog/Events   │ │  • KYC           │ │  • KYC           │
              └────────┬─────────┘ └────────┬─────────┘ └────────┬─────────┘
                       │                    │                    │
                       └────────────────────┼────────────────────┘
                                            │
                                            ▼
                              ┌──────────────────────────┐
                              │      Laravel 12 App       │
                              │                          │
                              │  ┌────────────────────┐  │
                              │  │    Controllers     │  │
                              │  │       (78)         │  │
                              │  └─────────┬──────────┘  │
                              │            │             │
                              │  ┌─────────▼──────────┐  │
                              │  │     Services       │  │
                              │  │       (12)         │  │
                              │  └─────────┬──────────┘  │
                              │            │             │
                              │  ┌─────────▼──────────┐  │
                              │  │      Models        │  │
                              │  │       (56)         │  │
                              │  └─────────┬──────────┘  │
                              │            │             │
                              │  ┌─────────▼──────────┐  │
                              │  │  Events/Listeners  │  │
                              │  │     (10/11)        │  │
                              │  └────────────────────┘  │
                              │                          │
                              │  ┌────────────────────┐  │
                              │  │   Queue Jobs (5)   │  │
                              │  └────────────────────┘  │
                              │                          │
                              │  ┌────────────────────┐  │
                              │  │ Notifications (16) │  │
                              │  └────────────────────┘  │
                              └────────────┬─────────────┘
                                           │
                 ┌─────────────────────────┼─────────────────────────┐
                 │                         │                         │
                 ▼                         ▼                         ▼
      ┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
      │     MySQL 8      │    │     Redis 7      │    │    Razorpay      │
      │                  │    │                  │    │                  │
      │  • 95 tables     │    │  • Sessions      │    │  • Payments      │
      │  • 244 migrations│    │  • Cache         │    │  • Webhooks      │
      │  • Financial data│    │  • Queues        │    │  • Refunds       │
      │  • KYC documents │    │  • Pub/Sub       │    │  • Payouts       │
      └──────────────────┘    └──────────────────┘    └──────────────────┘
```

---

## Module Interaction Diagram

How the internal modules relate during the money lifecycle: campaigns feed donations, donations go through the payment gateway, the wallet records the credit, and settlements finally drive payout attempts — with KYC and notifications running alongside.

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           DonateBazaar                                   │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│   ┌──────────────┐     ┌──────────────┐     ┌──────────────┐            │
│   │   Campaign   │────▶│   Donation   │────▶│   Payment    │            │
│   │   Module     │     │   Module     │     │   Gateway    │            │
│   └──────┬───────┘     └──────┬───────┘     └──────┬───────┘            │
│          │                    │                    │                    │
│          │                    ▼                    │                    │
│          │            ┌──────────────┐             │                    │
│          │            │    Wallet    │◀────────────┘                    │
│          │            │    Module    │                                  │
│          │            └──────┬───────┘                                  │
│          │                   │                                          │
│          ▼                   ▼                                          │
│   ┌──────────────┐     ┌──────────────┐                                 │
│   │  Settlement  │◀────│  Settlement  │                                 │
│   │    Engine    │     │    State     │                                 │
│   └──────┬───────┘     │    Machine   │                                 │
│          │             └──────────────┘                                 │
│          ▼                                                              │
│   ┌──────────────┐     ┌──────────────┐     ┌──────────────┐            │
│   │   Payout     │     │     KYC      │     │ Notification │            │
│   │   Attempt    │     │   Module     │     │   Module     │            │
│   └──────────────┘     └──────────────┘     └──────────────┘            │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## Database Schema Overview

The schema groups cleanly around users, campaigns, donations, the settlement/payout stack, KYC, content, and supporting modules like gift cards and coupons.

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         Database Schema (95 Tables)                      │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐          │
│  │  users          │  │  campaigns      │  │  donations      │          │
│  │  user_fundraiser│  │  campaign_      │  │  donation_items │          │
│  │  _levels        │  │  products       │  │  donation_      │          │
│  │  phone_         │  │  campaign_      │  │  payments       │          │
│  │  verifications  │  │  updates        │  │  refunds        │          │
│  └─────────────────┘  │  campaign_      │  └─────────────────┘          │
│                       │  settlements    │                                │
│  ┌─────────────────┐  │  campaign_logs  │  ┌─────────────────┐          │
│  │  wallets        │  │  campaign_      │  │  recurring_     │          │
│  │  wallet_        │  │  media          │  │  donations      │          │
│  │  transactions   │  └─────────────────┘  └─────────────────┘          │
│  └─────────────────┘                                                     │
│                       ┌─────────────────┐  ┌─────────────────┐          │
│  ┌─────────────────┐  │  settlements    │  │  payout_        │          │
│  │  kyc_           │  │  settlement_    │  │  accounts       │          │
│  │  verifications  │  │  items          │  │  payout_        │          │
│  │  organization_  │  │  settlement_    │  │  attempts       │          │
│  │  applications   │  │  state_logs     │  └─────────────────┘          │
│  │  organizations  │  │  settlement_    │                                │
│  └─────────────────┘  │  metadata       │  ┌─────────────────┐          │
│                       └─────────────────┘  │  risk_config    │          │
│  ┌─────────────────┐                       │  risk_rules     │          │
│  │  blogs          │  ┌─────────────────┐  │  risk_scores    │          │
│  │  blog_comments  │  │  events         │  │  risk_rule_logs │          │
│  │  blog_likes     │  │  event_         │  └─────────────────┘          │
│  │  blog_reports   │  │  registrations  │                                │
│  │  blog_status_   │  └─────────────────┘  ┌─────────────────┐          │
│  │  logs           │                       │  gift_cards     │          │
│  └─────────────────┘  ┌─────────────────┐  │  coupons        │          │
│                       │  categories     │  │  coupon_        │          │
│  ┌─────────────────┐  │  category_      │  │  redemptions    │          │
│  │  jobs           │  │  products       │  └─────────────────┘          │
│  │  job_posts      │  └─────────────────┘                                │
│  │  job_post_      │  ┌─────────────────┐  ┌─────────────────┐          │
│  │  applications   │  │  volunteers     │  │  notifications  │          │
│  └─────────────────┘  │  volunteer_     │  │  notification_  │          │
│                       │  applications   │  │  preferences    │          │
│  ┌─────────────────┐  │  volunteer_     │  └─────────────────┘          │
│  │  partnerships   │  │  assignments    │                                │
│  │  faqs           │  └─────────────────┘  ┌─────────────────┐          │
│  │  legal_pages    │                       │  subscribers    │          │
│  │  messages       │  ┌─────────────────┐  │  tags           │          │
│  │  contact_       │  │  product_       │  │  product_       │          │
│  │  messages       │  │  reservations   │  │  reservations   │          │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘          │
│                                                                          │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## Settlement State Machine

A settlement starts `pending`, gets approved (manually or automatically), moves to `processing` while the payout runs, and ends at `paid`, `rejected`, or `failed` — with failures returning to the queue for a retry.

```
                                    ┌──────────────┐
                                    │              │
                                    │   PENDING    │
                                    │              │
                                    └──────┬───────┘
                                           │
                          ┌────────────────┼────────────────┐
                          │                │                │
                          ▼                ▼                ▼
                   ┌────────────┐   ┌────────────┐   ┌────────────┐
                   │   AUTO_    │   │  APPROVED  │   │  REJECTED  │
                   │  APPROVED  │   │            │   │            │
                   └─────┬──────┘   └─────┬──────┘   └────────────┘
                         │                │
                         └────────┬───────┘
                                  │
                                  ▼
                           ┌────────────┐
                           │ PROCESSING │
                           └─────┬──────┘
                                 │
                    ┌────────────┼────────────┐
                    │            │            │
                    ▼            │            ▼
             ┌──────────┐       │     ┌──────────┐
             │          │       │     │          │
             │   PAID   │       │     │  FAILED  │
             │          │       │     │          │
             └──────────┘       │     └────┬─────┘
                                │          │
                                │          │ (retry)
                                │          │
                                ▼          ▼
                         ┌──────────┐ ┌──────────┐
                         │CANCELLED │ │ PENDING  │
                         │          │ │ (retry)  │
                         └──────────┘ └──────────┘
```

---

## Payment Flow

A donation starts on the campaign page, creates a Razorpay order, and after the user pays, either the browser verification route or the webhook completes the donation and credits the wallet.

```
┌────────┐     ┌────────────┐     ┌────────────┐     ┌──────────┐
│  User  │────▶│  Campaign  │────▶│  Donation  │────▶│ Razorpay │
│        │     │   Page     │     │   Form     │     │  Order   │
└────────┘     └────────────┘     └────────────┘     └────┬─────┘
                                                          │
                                                          ▼
                                                   ┌──────────┐
                                                   │  Payment │
                                                   │  Page    │
                                                   └────┬─────┘
                                                        │
                                                        ▼
┌────────┐     ┌────────────┐     ┌────────────┐     ┌──────────┐
│ Wallet │◀────│  Donation  │◀────│  Payment   │◀────│  User    │
│Credit  │     │  Complete  │     │  Verify    │     │  Pays    │
└────────┘     └────────────┘     └────────────┘     └──────────┘
                                       ▲
                                       │
                                 ┌─────┴─────┐
                                 │  Webhook  │
                                 │  Handler  │
                                 └───────────┘
```