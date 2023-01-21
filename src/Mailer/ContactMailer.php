<?php

namespace App\Mailer;

use App\Entity\Contact;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ContactMailer
{
    public function __construct(
        private string $contactEmailAddress,
        private MailerInterface $mailer,
        private Environment $twig)
    {
    }

    public function send(Contact $contact): void
    {
        $email = (new Email())
            ->from($contact->getEmail())
            ->to($this->contactEmailAddress)
            ->subject('Une nouvelle demande de contact a eu lieu')
            ->html($this->twig->render('email/contact.html.twig', [
                'contact' => $contact,
            ]));

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
        }
    }

}