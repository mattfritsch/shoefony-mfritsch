<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class MainController extends AbstractController
{
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

    #[Route('/contact', name: 'main_contact', methods: 'GET')]
    public function contact() : Response
    {
        return $this->render('main/contact.html.twig', [
            'controller_name' => 'MainController'
        ]);
    }
}
