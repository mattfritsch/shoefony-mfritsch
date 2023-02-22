<?php

namespace App\Mailer;

use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ContactMailer{

    public function __construct(
        private MailerInterface $mailer,
        private Environment $twig,
        private string $contactEmailAddress
    ){
    }

    public function send(Contact $contact){
            $email = (new Email())
            ->from($this->contactEmailAddress)
            ->to($contact->getEmail())
            ->subject('Message de contact sur Shoefony')
            ->html($this->twig->render('email/contact.html.twig', ['contact' => $contact]));

            $this->mailer->send($email);
    }

    /**
     * @return MailerInterface
     */
    public function getMailer(): MailerInterface
    {
        return $this->mailer;
    }

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }

    /**
     * @return string
     */
    public function getContactEmailAddress(): string
    {
        return $this->contactEmailAddress;
    }

}