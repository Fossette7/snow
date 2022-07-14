<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
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

  #[Route('/{id}/edit', name: 'comment_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      $this->addFlash('success', 'Votre commentaire est bien modifié');

      return $this->redirectToRoute('trick_show', ['id'=>$comment->getTrick()->getId(), 'slug'=> $comment->getTrick()->getSlug()], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('comment/edit.html.twig', [
      'comment' => $comment,
      'form' => $form,
    ]);
  }

  #[Route('/{id}/delete', name: 'comment_delete', methods: ['POST', 'GET'])]
  public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
      $entityManager->remove($comment);
      $entityManager->flush();

      $this->addFlash('success', 'Votre commentaire est bien supprimé');
    }

    return $this->redirectToRoute('trick_show', ['id'=>$comment->getTrick()->getId(), 'slug' => $comment->getTrick()->getSlug()], Response::HTTP_SEE_OTHER);
  }

}
