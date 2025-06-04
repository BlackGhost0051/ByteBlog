<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\LoginForm;

final class LoginController extends AbstractController
{
    #[Route('/login', name: 'login_index')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername() ?? '';

        $form = $this->createForm(LoginForm::class, [
            'email' => $lastUsername,
        ]);

        return $this->render('login/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }
}
