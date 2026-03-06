<?php

declare(strict_types=1);

namespace App\Filter\Modifier;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\DTO\PromotionEnquiryInterface;

class EvenItemsMultiplier implements PriceModifierInterface
{
    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {

        /** @var LowestPriceEnquiry $enquiry */
        $requestQuantity = $enquiry->getQuantity() ?? null;
        $minPromotionQuantity = $promotion->getCriteria()['minimum_quantity'] ?? null;

        if (!$requestQuantity || !$minPromotionQuantity || $requestQuantity < $minPromotionQuantity) {
            return (int)($price * $quantity);
        }

        $productsCountPromontionNotApplied = $requestQuantity % $minPromotionQuantity;
        $productsCountPromotionApplied = $requestQuantity - $productsCountPromontionNotApplied;

        return (int)(($price * $productsCountPromotionApplied * $promotion->getAdjustment()) + ($productsCountPromontionNotApplied * $price));
    }
}
