<?php

namespace App\Entity\Store;

use App\Repository\Store\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\Table(name: 'sto_image')]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url;

    #[ORM\Column(length: 255)]
    private ?string $alt;

    public function __construct(
        string $url,
        string $alt,
    )
    {
        $this->url = $url;
        $this->alt = $alt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }


    public function getAlt(): ?string
    {
        return $this->alt;
    }

}