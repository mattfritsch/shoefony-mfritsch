<?php

namespace App\DataFixtures;

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
        for($i = 1; $i < 15; $i++)
        {
            $product = (new Product(
                'product '.$i,
                'Produit de description '.$i,
                (mt_rand(10,100))
            ));

            $this->manager->persist($product);
        }
    }
}
