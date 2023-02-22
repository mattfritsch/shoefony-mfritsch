<?php

namespace App\Event;

use App\Entity\Contact;

final class ContactCreated
{
    public function __construct(
        public readonly Contact $contact,
    ) {}
}