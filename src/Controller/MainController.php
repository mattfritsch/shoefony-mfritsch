<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Mailer\ContactMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Routing\Annotation\Route;

final class MainController extends AbstractController
{
    public function  __construct(
        private ContactMailer $contactMailer
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
        //Création de notre entité et du formulaire basé dessus
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        //Demande au formulaire d'interpréter la Request
        $form->handleRequest($request);

        //Dans le cas de la soumission d'un formulaire valide
        if ($form->isSubmitted() && $form->isValid()){
            $this->contactMailer->send($contact);
            $this->addFlash('success', 'Merci, votre message a été pris en compte !');
            //Actions à effectuer après envoi du formulaire
            return $this->redirectToRoute('main_contact');
        }

        return $this->render('main/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
