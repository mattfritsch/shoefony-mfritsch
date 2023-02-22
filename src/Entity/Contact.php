<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'app_contact')]
final class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide.')]
    #[ORM\Column]
    private ?string $firstName = null;

    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide.')]
    #[ORM\Column]
    private ?string $lastName = null;

    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide.')]
    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide.')]
    #[ORM\Column]
    private ?string $email = null;


    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide.')]
    #[Assert\Length(
        min: 25,
        minMessage: 'Votre message doit faire au moins {{ limit }} caractères.'
    )]
    #[ORM\Column]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $created_at;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeImmutable|null $created_at
     */
    public function setCreatedAt(?\DateTimeImmutable $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): Contact
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): Contact
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): Contact
    {
        $this->message = $message;
        return $this;
    }
}