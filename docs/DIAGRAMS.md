# System Architecture Diagram

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
