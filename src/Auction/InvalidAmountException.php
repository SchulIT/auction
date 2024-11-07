<?php

namespace App\Auction;

use Exception;
use Throwable;

class InvalidAmountException extends Exception {
    public function __construct(private readonly int $minimumAmount, private readonly int $givenAmount, string $message = "", int $code = 0, ?Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function getMinimumAmount(): int {
        return $this->minimumAmount;
    }

    public function getGivenAmount(): int {
        return $this->givenAmount;
    }
}