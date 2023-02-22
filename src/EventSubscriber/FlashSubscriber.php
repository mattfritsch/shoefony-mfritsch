<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\ContactCreated;
use App\Event\Store\CommentAdded;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class FlashSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            ContactCreated::class =>[
                ['onContactCreated']
            ],
            CommentAdded::class => [
                ['onCommentAdded']
            ]
        ];
    }

    public function onCommentAdded(CommentAdded $event): void
    {
        $this->addFlash('success', 'Merci, votre commentaire a bien été pris en compte.');
    }

    public function onContactCreated(ContactCreated $event): void
    {
        $this->addFlash('success', 'Merci, votre message a bien été pris en compte.');
    }

    private function addFlash(string $type, string $message): void
    {
        $session =$this->requestStack->getSession();

        $session->getFlashBag()->add($type, $message);
    }
}
