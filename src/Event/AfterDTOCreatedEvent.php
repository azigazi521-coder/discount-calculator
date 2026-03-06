<?php

declare(strict_types=1);

namespace App\Event;

use App\DTO\PromotionEnquiryInterface;
use Symfony\Contracts\EventDispatcher\Event;

class AfterDTOCreatedEvent extends Event
{
    public const NAME = 'dto.created';

    public function __construct(protected PromotionEnquiryInterface $dto) {}

    public function getDTO(): PromotionEnquiryInterface
    {
        return $this->dto;
    }
}
