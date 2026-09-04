<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'razorpay' => [
        'key' => env('RAZORPAY_KEY'),
        'secret' => env('RAZORPAY_SECRET'),
        'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
    ],

    'donation' => [
        'platform_fee_percent' => env('DONATION_PLATFORM_FEE_PERCENT', 5.0),
        'receipt_url_ttl_hours' => env('DONATION_RECEIPT_URL_TTL_HOURS', 24),
        'min_amount' => env('DONATION_MIN_AMOUNT', 1),
        'max_amount' => env('DONATION_MAX_AMOUNT', 500000),
        'currency' => env('DONATION_CURRENCY', 'INR'),
        /*
         * Who bears the Razorpay processing (gateway) fee?
         *
         * ONLY the following value is currently supported:
         *
         * 'platform'       -> the platform absorbs the gateway fee. Campaign owner
         *                     always receives net_amount = total_amount - platform_fee.
         *                     Actual retained revenue = platform_fee - gateway_fee - gateway_tax.
         *
         * The value 'campaign_owner' is NOT implemented: the wallet credit and
         * payout code would not subtract the gateway fee, so selecting it would
         * silently change accounting expectations without changing the actual
         * money calculation. The FinancialLedgerService::bearer() method rejects
         * any unsupported value loudly rather than pretending to work.
         */
        'gateway_fee_bearer' => env('GATEWAY_FEE_BEARER', 'platform'),
        /*
         * If the actual gateway fee cannot be fetched from the payment record,
         * the system records the fee capture as unavailable and does NOT invent
         * an estimated value. Set this to true only if you have a verified
         * provider contract with a fixed processing rate.
         */
        'allow_estimated_gateway_fee' => env('ALLOW_ESTIMATED_GATEWAY_FEE', false),
        'estimated_gateway_fee_percent' => env('ESTIMATED_GATEWAY_FEE_PERCENT', 0.0),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
    ],

];
