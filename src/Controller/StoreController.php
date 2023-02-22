<?php

namespace App\Controller;

use App\Entity\Store\Comment;
use App\Form\CommentType;
use App\Manager\Store\ProductManager;
use App\Repository\Store\BrandRepository;
use App\Repository\Store\CommentRepository;
use App\Repository\Store\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class StoreController extends AbstractController
{

    public function  __construct(
        private ProductRepository $productRepository,
        private BrandRepository $brandRepository,
        private CommentRepository $commentRepository,
        private ProductManager $productManager,
    ){
    }

    #[Route('/store', name: 'app_store')]
    public function index(): Response
    {
        $products = $this->productRepository->findAll();

        $brands = $this->brandRepository->findAll();

        return $this->render('store/product-list.html.twig', [
            'products' => $products,
            'brands' => $brands,
            'brand' => null,
        ]);
    }

    #[Route('/store/{id}', name: 'app_store_brand', requirements: ['id' => '\d+'])]
    public function indexBrand(int $id): Response
    {
        $brand = $this->brandRepository->findOneBy(['id' => $id]);
        $products = $this->productRepository->findByBrand($brand);


        $brands = $this->brandRepository->findAll();

        return $this->render('store/product-list.html.twig', [
            'products' => $products,
            'brands' => $brands,
            'activeBrand' => $brand,
        ]);
    }

    #[Route('/store/product/{id}/details/{slug}', name: 'store_show_product', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function showProduct(int $id, string $slug, Request $request): Response
    {
        $product = $this->productRepository->findOneById($id);
        $brands = $this->brandRepository->findAll();

        if($product === null){
            throw new NotFoundHttpException();
        }

        if($product->getSlug() !== $slug){
            return $this->redirectToRoute('store_show_product', [
                'id' => $product->getId(),
                'slug' => $product->getSlug(),
            ], Response::HTTP_MOVED_PERMANENTLY);
        }

        //form
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->productManager->addComment($comment, $product);

            return $this->redirectToRoute('store_show_product', [
                'id' => $product->getId(),
                'slug' => $product->getSlug(),
            ]);
        }


        return $this->render('store/product-detail.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'id' => $id,
            'slug' => $slug,
            'brands' => $brands,
        ]);
    }
}
