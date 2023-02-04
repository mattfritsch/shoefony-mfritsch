<?php

namespace App\Controller;


use App\Repository\Store\BrandRepository;
use App\Repository\Store\ColorRepository;
use App\Repository\Store\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private BrandRepository $brandRepository,
        private ColorRepository $colorRepository
    )
    {
    }

    #[Route('/store', name: 'store_products')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();
        $brands = $this->brandRepository->findAll();
        return $this->render('store/product-list.html.twig', [
            'products' => $products,
            'brands' => $brands
        ]);
    }

    #[Route('/store/product/{id}/details/{slug}', name: 'store_show_product', requirements: ['id' => '\d+'])]
    public function showProduct(int $id, string $slug, Request $request) : Response
    {
        $product = $this->productRepository->find($id);
        $brand = $this->brandRepository->findAll();

        if(!$product){
            throw new NotFoundHttpException('Le produit d\'id : ' .$id.  ' n\'existe pas');
        }

        if($product->getSlug() !== $slug){
            return $this->redirectToRoute('store_show_product', [
                'id' =>$product->getId(),
                'slug' => $product->getSlug()
            ], Response::HTTP_MOVED_PERMANENTLY);
        }

        $colors = $this->colorRepository->findAll();

        foreach ($colors as $color) {
            $product->addColor($color);
        }


        return $this->render('store/product-detail.html.twig', [
            'product' => $product,
            'brands' => $brand,
            'url' => $this->generateUrl('store_show_product', [
                'id' => $id,
                'slug' => $slug
            ])
        ]);
    }
}
