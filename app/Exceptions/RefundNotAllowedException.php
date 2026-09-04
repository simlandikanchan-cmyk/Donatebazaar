<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when a refund cannot be safely processed through the normal flow.
 *
 * This signals a *blocked* financial operation (e.g. the donation has already
 * been paid out to the campaign owner, or a payout is currently in flight).
 * When thrown, the system does NOT auto-reverse a completed payout nor call an
 * unverified gateway reversal API — an operator must run a manual recovery or
 * reversal workflow instead.
 */
class RefundNotAllowedException extends RuntimeException
{
}
