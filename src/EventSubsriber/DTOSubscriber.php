<?php

declare(strict_types=1);

namespace App\EventSubsriber;

use App\Event\AfterDTOCreatedEvent;
use App\Service\ServiceException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class DTOSubscriber implements EventSubscriberInterface
{

    public function __construct(private ValidatorInterface $validator) {}

    public static function getSubscribedEvents(): array
    {
        return [
            AfterDTOCreatedEvent::NAME => [
                ['validateDto', 100],
                ['testMethod', 10]
            ]
        ];
    }

    public function validateDto(AfterDTOCreatedEvent $event): void
    {
        $dto = $event->getDTO();
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            throw new ServiceException(422, 'VAlidation Failed');
        }
    }

    public function testMethod(AfterDTOCreatedEvent $event): void
    {
        // dd('testMethod called');
    }
}
