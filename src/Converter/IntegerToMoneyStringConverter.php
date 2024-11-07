<?php

namespace App\Converter;

use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Parser\DecimalMoneyParser;

class IntegerToMoneyStringConverter {

    public function __construct(private readonly IntegerToMoneyConverter $moneyConverter, private readonly IntlMoneyFormatter $formatter) {

    }

    public function convert(int $amount): string {
        return $this->formatter->format($this->moneyConverter->convert($amount));
    }
}