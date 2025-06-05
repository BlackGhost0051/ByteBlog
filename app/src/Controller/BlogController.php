<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\PostRepository;
use App\Entity\Post;
use App\Form\AddPostForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


final class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog_index')]
    public function index(PostRepository $postRepository): Response
    {

        $posts = $postRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/blog/add_post', name: 'add_post_index')]
    public function add_post(Request $request, EntityManagerInterface $em): Response
    {
        $post = new Post();

        $form = $this->createForm(AddPostForm::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setAuthor($this->getUser());

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Post added successfully!');

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/add_post.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/blog/post/{id}', name: 'blog_post')]
    public function show(Post $post): Response
    {
        return $this->render('blog/post.html.twig', [
            'post' => $post,
        ]);
    }
}
