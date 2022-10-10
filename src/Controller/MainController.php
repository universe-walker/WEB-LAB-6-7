<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\PostFormType;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    #[Route('/list/', name: 'list')]
    public function list(ManagerRegistry $doctrine): Response
    {
        $session = new Session();
        $session->start();
        $name = $session->get('name') ?? null;
        $posts = $doctrine->getRepository(Post::class)->findAll();

        return $this->render('main/archive.html.twig', [
            'controller_name' => 'MainController',
            'posts' => $posts,
            'user_name' => $name
        ]);
    }

    #[Route('/', name: 'app_main')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $session = new Session();
        $session->start();

        $name = $session->get('name') ?? null;

        $posts = $doctrine->getRepository(Post::class)->findAll();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'posts' => $posts,
            'user_name' => $name
        ]);
    }

    #[Route('/post/{id}', name: 'posts')]
    public function post(int $id, ManagerRegistry $doctrine): Response
    {
        $session = new Session();
        $session->start();

        $name = $session->get('name') ?? null;
        $post = $doctrine->getRepository(Post::class)->findOneBy(["id" => $id]);
        $post->setViews($post->getViews() + 1);
        $comments = $post->getComments();

        $entityManager = $doctrine->getManager();
        $entityManager->persist($post);
        $entityManager->flush();


        return $this->render('main/post.html.twig', [
            'controller_name' => 'MainController',
            'post' => $post,
            'comments' => $comments,
            'user_name' => $name
        ]);
    }

    #[Route('/add-post', name: 'add_post')]
    public function addPost(ManagerRegistry $doctrine, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->isStarted()) {
            $session->start();
        }

        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();
            $post->setAuthor($doctrine->getRepository(User::class)
                ->find($this->container->get('security.token_storage')->getToken()->getUser()->getId()));
            $post->setDate(new \DateTime('now'));
            $post->setViews(0);
            $em = $doctrine->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('post', (['id' => $post->getId()]));
        }

        return $this->renderForm('main/add_post.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form
        ]);
    }
}
