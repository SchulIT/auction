<?php

namespace App\Twig;

use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MoneyExtension extends AbstractExtension {

    public function __construct(private readonly DecimalMoneyParser $parser) {

    }

    public function getFilters(): array {
        return [
            new TwigFilter('to_money', [$this, 'toMoney'])
        ];
    }

    public function toMoney(int $money): Money {
        return $this->parser->parse($money / 100, new Currency('EUR'));
    }
}