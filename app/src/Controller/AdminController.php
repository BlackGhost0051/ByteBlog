<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_index')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }


    #[Route('/admin/delete/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager
    ): RedirectResponse {
        $token = $request->request->get('_token');
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('delete_user_' . $id, $token))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('admin_index');
        }

        $user = $userRepository->find($id);

        if (!$user) {
            $this->addFlash('error', 'User not found.');
        } else {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'User deleted successfully.');
        }

        return $this->redirectToRoute('admin_index');
    }
}
