<?php

namespace App\DataFixtures;

use App\Entity\Store\Brand;
use App\Entity\Store\Color;
use App\Entity\Store\Comment;
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

        $this->loadBrands();

        $this->loadColors();

        $this->loadProducts();

        $manager->flush();
    }

    private function loadBrands(): void
    {
        $brands = [
            ['Nike'],
            ['Adidas'],
            ['Puma'],
            ['Reebook'],
        ];

        foreach ($brands as $key => [$name]) {
            $brand = new Brand();
            $brand->setName($name);
            $this->manager->persist($brand);
            $this->addReference(Brand::class . $key, $brand);
        }
    }

    private function loadColors(): void
    {
        $colors = [
            ['Rouge'],
            ['Bleu'],
            ['Vert'],
            ['Blanc'],
        ];

        foreach ($colors as $key => [$name]) {
            $color = new Color();
            $color->setName($name);
            $this->manager->persist($color);
            $this->addReference(Color::class . $key, $color);
        }
    }

    /**
     * @throws \Exception
     */
    private function loadProducts(): void
    {

        for($i = 1; $i < 15; $i++) {
            $product = new Product(
                new Image('img/products/shoe-'.$i.'.jpg', 'Image du produit '.$i),
                'product '.$i,
                'Produit de description '.$i,
                mt_rand(10, 100),
            );

            $product->setSlug('product-'.$i);
            $product->setLongDescription('Voici la description longue du produit '.$i);

            /** @var Brand $brand */
            $brand = $this->getReference(Brand::class . random_int(0, 3));
            $product->setBrand($brand);

            [$min, $max] = $this->getBoundaries(4);

            for($j = $min; $j < $max; $j++) {
                /** @var Color $color */
                $color = $this->getReference(Color::class . $j);
                $product->addColor($color);
            }

            for($j = 0; $j < random_int(0,20); $j++) {
                $comment = $this->createRandomComment($product);
                $product->addComment($comment);
            }

            $this->manager->persist($product);

            sleep(1);
        }
    }

    private function createRandomComment(Product $product) : Comment
    {
        $pseudos = [
            'moulino',
            'mezza',
            'atong',
            'neirda'
        ];

        $messages = [
            'Super produit je recommande',
            'Attention, ça taille grand !',
            'M\'ouais, bof',
            'Pas ouf :/',
            'Parfait pour mes séances de running intensives'
        ];

        return new Comment();
    }

    /**
     *
     * @return array{0:int, 1:int}
     * @throws \Exception
     */
    private function getBoundaries(int $max): array
    {
        $min = random_int(0, $max);
        $max = random_int($min, $max);

        return [$min, $max];
    }
}