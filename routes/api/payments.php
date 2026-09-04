<?php

// Payment verification is handled exclusively via web routes
// (routes/web/donations.php) which enforce session auth + CSRF protection.
// The API route was removed to eliminate an unauthenticated IDOR vector —
// see routes/web/donations.php:18-20 for the secured endpoint.
