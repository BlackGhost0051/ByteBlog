<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController {

    #[Route('/', name: 'home_index')]
    function index(): Response
    {
//        $contents = $this->renderView('home/index.html.twig');
//
//        return new Response($contents);
        return $this->render('home/index.html.twig');
    }
}
