<?php
namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;


#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'app_contact')]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide.')]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide.')]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide.')]
    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide.')]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[Assert\NotBlank(message: 'Ce champ ne peut pas être vide')]
    #[Assert\Length(min: 25, minMessage: 'Votre message doit contenir au minimum {{ limit }} caractères.')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
     */
    public function setMessage(string $message): Contact
    {
        $this->message = $message;
        return $this;
    }




}