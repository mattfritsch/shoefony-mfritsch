<?php

namespace App\Controller;


use App\Repository\Store\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository
    )
    {
    }

    #[Route('/store', name: 'store_products')]
    public function index(): Response
    {
        return $this->render('store/product-list.html.twig', [
            'products' => $this->productRepository->findAll(),
        ]);
    }

    #[Route('/store/product/{id}/details/{slug}', name: 'store_show_product', requirements: ['id' => '\d+'])]
    public function showProduct(int $id, string $slug, Request $request) : Response
    {
        return $this->render('store/product-detail.html.twig', [
            'id' => $id,
            'slug' => $slug,
            'ip' => $request->getClientIp(),
            'url' => $this->generateUrl('store_show_product', [
                'id' => $id,
                'slug' => $slug
            ])
        ]);
    }
}
