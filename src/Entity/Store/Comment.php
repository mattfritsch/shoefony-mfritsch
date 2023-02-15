<?php

namespace App\Entity\Store;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table('sto_comment')]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(name: 'sto_product_id', nullable: false)]
    private ?Product $product;

    public function __construct(
    )
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @param string|null $pseudo
     * @return Comment
     */
    public function setPseudo(?string $pseudo): Comment
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    /**
     * @param string|null $message
     * @return Comment
     */
    public function setMessage(?string $message): Comment
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param \DateTimeImmutable|null $createdAt
     * @return Comment
     */
    public function setCreatedAt(?\DateTimeImmutable $createdAt): Comment
    {
        $this->createdAt = $createdAt;
        return $this;
    }


}
