<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Mailer\ContactMailer;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MainController extends AbstractController
{

    public function  __construct(
        private ContactMailer $contactMailer,
        //private EntityManagerInterface $entityManager
        private ContactRepository $contactRepository
    ){
    }

    #[Route('/', name: 'main_homepage', methods: 'GET')]
    public function homepage(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'year' => date('Y')
        ]);
    }

    #[Route('/presentation', name: 'main_presentation', methods: 'GET')]
    public function presentation() : Response
    {
        return $this->render('main/presentation.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/contact', name: 'main_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request) : Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->contactRepository->save($contact, true);

            /*$this->entityManager->persist($contact);
            $this->entityManager->flush();*/

            $this->contactMailer->send($contact);
            $this->addFlash('success', 'Merci, votre message a été pris en compte !');
            return $this->redirectToRoute('main_contact');
        }

        return $this->render('main/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
