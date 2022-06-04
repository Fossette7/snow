<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]

class AdminController extends AbstractController
{

    #[Route('/', name: 'admin_user_list', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
      return $this->render('admin/index.html.twig', [
        'users' => $userRepository->findAll(),
      ]);
    }
}
