<?php

namespace App\Exceptions;

use Exception;

class InvalidSettlementTransitionException extends Exception
{
    public function __construct(
        string $from,
        string $to,
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        $message = $message !== ''
            ? $message
            : "Invalid settlement transition: {$from} -> {$to}.";

        parent::__construct($message, $code, $previous);
    }
}
