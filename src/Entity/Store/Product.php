<?php

namespace App\Entity\Store;

use App\Repository\Store\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: 'sto_product')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name;

    #[ORM\Column(length: 255)]
    private ?string $slug;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $longDescription;


    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'sto_image_id', nullable: false)]
    private ?Image $image;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(name: 'sto_brand_id', nullable: false)]
    private ?Brand $brand;

    #[ORM\ManyToMany(targetEntity: Color::class)]
    #[ORM\JoinColumn(name: 'sto_product_color')]
    private Collection $colors;


    public function __construct(
        Image $image,
        Brand $brand,
        string $name,
        string $description,
        float $price
    )
    {
        $this->image = $image;
        $this->brand = $brand;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;

        $slugger = new AsciiSlugger();
        $this->slug = $slugger->slug($name)->toString();

        $this->createdAt = new \DateTimeImmutable();
        $this->colors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(?string $longDescription): self
    {
        $this->longDescription = $longDescription;
        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Color>
     */
    public function getColors(): Collection
    {
        return $this->colors;
    }

    public function addColor(Color $color): self
    {
        if (!$this->colors->contains($color)) {
            $this->colors->add($color);
        }

        return $this;
    }

    public function removeColor(Color $color): self
    {
       if($this->colors->contains($color)){
           $this->colors->removeElement($color);
       }

        return $this;
    }
}
