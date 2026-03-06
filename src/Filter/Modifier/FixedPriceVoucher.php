<?php

declare(strict_types=1);

namespace App\Filter\Modifier;

use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\DTO\PromotionEnquiryInterface;

class FixedPriceVoucher implements PriceModifierInterface
{
    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {

        /** @var LowestPriceEnquiry $enquiry */
        $requestCode = $enquiry->getVoucherCode() ?? null;
        $promotionCode = $promotion->getCriteria()['code'] ?? null;

        if (!$requestCode || $requestCode !== $promotionCode) {
            return (int)($price * $quantity);
        }

        return (int)($quantity * $promotion->getAdjustment());
    }
}
