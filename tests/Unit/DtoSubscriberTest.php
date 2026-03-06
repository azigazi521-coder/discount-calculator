<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\LowestPriceEnquiry;
use App\Event\AfterDTOCreatedEvent;
use App\Tests\ServiceTestCase;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DtoSubscriberTest extends ServiceTestCase
{
    public function test_a_dto_is_created_afterit_has_been_created(): void
    {
        $dto = new LowestPriceEnquiry();
        $dto->setQuantity(-5);

        $event = new AfterDTOCreatedEvent($dto);

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->container->get('debug.event_dispatcher');

        $this->expectException(ValidationFailedException::class);
        $this->expectExceptionMessage('This value should be positive.');

        $eventDispatcher->dispatch($event, $event::NAME);
    }
}
