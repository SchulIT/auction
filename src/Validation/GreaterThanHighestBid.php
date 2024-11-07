<?php

namespace App\Validation;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class GreaterThanHighestBid extends Constraint {
    public string $message = 'This value must be greater than {{ amount }}.';
}