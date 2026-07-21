<?php

namespace App\Exceptions;

use Exception;

class GatewayException extends Exception
{
    public static function timeout(string $message = 'Gateway request timed out.'): TimeoutException
    {
        return new TimeoutException($message);
    }

    public static function invalidSignature(string $message = 'Invalid webhook signature.'): InvalidSignatureException
    {
        return new InvalidSignatureException($message);
    }

    public static function temporary(string $message = 'Temporary gateway failure.'): TemporaryFailureException
    {
        return new TemporaryFailureException($message);
    }

    public static function permanent(string $message = 'Permanent gateway failure.'): PermanentFailureException
    {
        return new PermanentFailureException($message);
    }

    public static function duplicate(string $message = 'Duplicate payout request.'): DuplicateRequestException
    {
        return new DuplicateRequestException($message);
    }
}
