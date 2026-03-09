<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\DTO\LowestPriceEnquiry;
use App\EventSubsriber\DTOSubscriber;
use App\Service\ServiceException;
use App\Tests\ServiceTestCase;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use App\Event\AfterDTOCreatedEvent;

class DtoSubscriberTest extends ServiceTestCase
{
    public function test_a_dto_is_created_afterit_has_been_created(): void
    {
        $dto = new LowestPriceEnquiry();
        $dto->setQuantity(-5);

        $event = new AfterDTOCreatedEvent($dto);

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $this->container->get('debug.event_dispatcher');

        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage('ConstraintViolationList');

        $eventDispatcher->dispatch($event, $event::NAME);
    }

    public function testEventSubscription(): void
    {
        $this->assertArrayHasKey(AfterDTOCreatedEvent::NAME, DTOSubscriber::getSubscribedEvents());
    }
}
