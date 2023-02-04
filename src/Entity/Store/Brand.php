<?php

namespace App\Entity\Store;

use App\Repository\Store\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
#[ORM\Table(name: 'sto_brand')]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name;

    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Product::class)]
    private Collection $products;

    public function __construct(
        string $name
    )
    {
        $this->name = $name;
        $this->products = new ArrayCollection();
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if(!$this->products->contains($product)){
            $this->products[] = $product;
            $product->setBrand($this);
        }
        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if(!$this->products->contains($product)){
            $this->products->removeElement($product);
            if($product->getBrand() === $this){
                $product->setBrand(null);
            }
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

}
