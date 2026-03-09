<?php

declare(strict_types=1);

namespace App\Filter\Modifier;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\DTO\PromotionEnquiryInterface;

class DateRangeMultiplier implements PriceModifierInterface
{
    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {

        /** @var LowestPriceEnquiry $enquiry */
        $requestDate = date_create($enquiry->getRequestDate());
        $from = date_create($promotion->getCriteria()['from']);
        $to = date_create($promotion->getCriteria()['to']);

        if (!($requestDate >= $from && $requestDate < $to)) {
            return (int)($price * $quantity);
        }

        return (int)(($price * $quantity) * $promotion->getAdjustment());
    }
}
