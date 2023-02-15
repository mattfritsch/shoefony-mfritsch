<?php

namespace App\Controller;


use App\Entity\Store\Comment;
use App\Entity\Store\Product;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Repository\CommentRepository;
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
        private ColorRepository $colorRepository,
        private CommentRepository $commentRepository
    )
    {
    }

    #[Route('/store', name: 'store_products')]
    public function index(): Response
    {
        $products = $this->productRepository->findAllWithImages();
        $brands = $this->brandRepository->findAll();
        return $this->render('store/product-list.html.twig', [
            'products' => $products,
            'brands' => $brands
        ]);
    }

    #[Route('/store/{brandId}', name: 'store_products_brand', requirements: ['brandId' => '\d+'])]
    public function indexBrand(int $brandId): Response
    {
        $brand = $this->brandRepository->findBy(['id' => $brandId]);
        $products = $this->productRepository->findBrandWithImages($brand);

        $brands = $this->brandRepository->findAll();

        return $this->render('store/product-list.html.twig', [
            'products' => $products,
            'brands' => $brands,
            'brandActive' => $brandId
        ]);
    }

    #[Route('/store/product/{id}/details/{slug}', name: 'store_show_product', requirements: ['id' => '\d+'])]
    public function showProduct(int $id, string $slug, Request $request) : Response
    {
        $product = $this->productRepository->findOneByIdWithImage($id);

        $brand = $this->brandRepository->findAll();
        $comments = $this->commentRepository->findBy(['product' => $id], ['createdAt' => 'DESC']);

        if(!$product){
            throw new NotFoundHttpException('Le produit d\'id : ' .$id.  ' n\'existe pas');
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setProduct($product);
            $this->commentRepository->save($comment, true);

            $this->addFlash('success', 'Merci, votre avis a été posté !');

            return $this->redirectToRoute('store_show_product', [
                'id' =>$product->getId(),
                'slug' => $product->getSlug()
            ], Response::HTTP_MOVED_PERMANENTLY);
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
            'form' => $form->createView(),
            'product' => $product,
            'brands' => $brand,
            'url' => $this->generateUrl('store_show_product', [
                'id' => $id,
                'slug' => $slug
            ]),
            'comments' => $comments
        ]);
    }
}
