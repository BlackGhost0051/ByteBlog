<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



use App\Entity\User;
use App\Form\RegistrationForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;



final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register_index')]
    public function index(Request $request, EntityManagerInterface $em, UserRepository $userRepository, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $existingUser = $userRepository->findOneBy(['email' => $user->getEmail()]);

            if ($existingUser) {
                return $this->render('register/index.html.twig', [
                    'registrationForm' => $form->createView(),
                    'error' => "This email is already used.",
                ]);
            }

            if ($form->isValid()) {
                $plainPassword = $form->get('plainPassword')->getData();
                $hashedPassword = $hasher->hashPassword($user, $plainPassword);

                $user->setPassword($hashedPassword);
                $user->setIsAdmin(false);

                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('login_index');
            }
        }

//        return $this->render('register/index.html.twig', [
//            'controller_name' => 'RegisterController',
//        ]);
        return $this->render('register/index.html.twig', [
            'registrationForm' => $form->createView(),
            'error' => null,
        ]);
    }
}
