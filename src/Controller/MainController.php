<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Store\Product;
use App\Form\ContactType;
use App\Mailer\ContactMailer;
use App\Manager\ContactManager;
use App\Repository\ContactRepository;
use App\Repository\Store\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MainController extends AbstractController
{
    public function  __construct(
        private ContactManager $contactManager,
        private ProductRepository $productRepository,
    ){
    }

    #[Route('/', name: 'main_homepage', methods: ['GET'])]
    public function homepage(): Response
    {
        $lastProducts = $this->productRepository->find4LastProducts();
        $popularProducts = $this->productRepository->find4PopularProducts();

        return $this->render('index.html.twig', [
            'lastProducts' => $lastProducts,
            'popularProducts' => $popularProducts,
        ]);
    }

    #[Route('/presentation', name: 'main_presentation')]
    public function presentation(): Response
    {
        return $this->render('presentation.html.twig');
    }

    #[Route('/contact', name: 'main_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactManager->save($contact);

            return $this->redirectToRoute('main_contact');
        }

        return $this->render('contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}