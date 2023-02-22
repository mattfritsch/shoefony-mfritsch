<?php

namespace App\EventSubscriber;

use App\Event\ContactCreated;
use App\Mailer\ContactMailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ContactSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ContactMailer $contactMailer,
    ) {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            ContactCreated::class => [
                ['sendEmail'],
            ],
        ];
    }

    public function sendEmail(ContactCreated $event): void
    {
       $this->contactMailer->send($event->contact);
    }

}