<?php

namespace App\Converter;

use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class IntegerToMoneyConverter {

    public function __construct(private readonly DecimalMoneyParser $parser) {

    }

    public function convert(int $money): Money {
        return $this->parser->parse($money / 100, new Currency('EUR'));
    }
}