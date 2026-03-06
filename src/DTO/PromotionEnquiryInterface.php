<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Product;
use JsonSerializable;

interface PromotionEnquiryInterface extends JsonSerializable
{
    public function getProduct(): ?Product;

    public function setPromotionId(int $promotionId);

    public function setPromotionName(string $name);
}
