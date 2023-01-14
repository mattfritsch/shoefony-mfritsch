<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{
    #[Route('/store', name: 'store_products')]
    public function index(): Response
    {
        return $this->render('store/product-list.html.twig', [
            'controller_name' => 'StoreController',
        ]);
    }

    #[Route('/store/product/{id}/details/{slug}', name: 'store_show_product', requirements: ['id' => '\d+'])]
    public function showProduct(int $id, string $slug, Request $request) : Response
    {
        return $this->render('store/product-detail.html.twig', [
            'controller_name' => 'StoreController',
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
