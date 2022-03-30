<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment', name: 'comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

  #[Route('/{id}', name: 'comment_delete', methods: ['POST'])]
  public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
      $entityManager->remove($comment);
      $entityManager->flush();
    }

    return $this->redirectToRoute('trick_show', [], Response::HTTP_SEE_OTHER);
  }

}
