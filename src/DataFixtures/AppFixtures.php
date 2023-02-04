<?php

namespace App\DataFixtures;

use App\Entity\Store\Brand;
use App\Entity\Store\Color;
use App\Entity\Store\Image;
use App\Entity\Store\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadProducts();

        $this->manager->flush();
    }

    private function loadProducts(): void
    {
        $brand = (new Brand(
            'Adidas'
        ));

        $color = (new Color(
            'Rouge'
        ));

        $this->manager->persist($brand);
        $this->manager->persist($color);

        for($i = 1; $i < 15; $i++)
        {
            $image = (new Image(
               '/img/products/shoe-'.$i.'.jpg',
                'image product '.$i
            ));

            $product = (new Product(
                $image,
                $brand,
                'product '.$i,
                'Produit de description '.$i,
                (mt_rand(10,100))
            ));

            $product->setLongDescription('Voici la description longue de product '. $i);

            $brand->addProduct($product);
            $product->addColor($color);

            $this->manager->persist($product);
        }
    }
}
