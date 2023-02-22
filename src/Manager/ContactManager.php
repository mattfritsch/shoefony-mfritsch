<?php

namespace App\Manager;

use App\Entity\Contact;
use App\Event\ContactCreated;
use App\Repository\ContactRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

final class ContactManager
{
    public function __construct(
        private ContactRepository $contactRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function save(Contact $contact): void
    {
        $this->contactRepository->save($contact, true);

        $this->eventDispatcher->dispatch(new ContactCreated($contact));
    }
}