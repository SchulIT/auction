<?php

namespace App\Validation;

use App\Auction\BidManager;
use App\Converter\IntegerToMoneyStringConverter;
use App\Entity\Bid;
use Money\Currency;
use Money\Money;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class GreaterThanHighestBidValidator extends ConstraintValidator {

    public function __construct(private readonly BidManager $bidManager, private readonly IntegerToMoneyStringConverter $converter) {

    }

    public function validate(mixed $value, Constraint $constraint): void {
        if(!$constraint instanceof GreaterThanHighestBid) {
            throw new UnexpectedTypeException($constraint, GreaterThanHighestBid::class);
        }

        $bid = $this->context->getObject();

        if(!$bid instanceof Bid) {
            return;
        }

        $minimumBid = $this->bidManager->getMinimumAmountForNewBid($bid->getAuction(), $bid->getUser());

        if($minimumBid > $bid->getAmount()) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ amount }}', $this->converter->convert($minimumBid))
                ->addViolation();
        }
    }
}