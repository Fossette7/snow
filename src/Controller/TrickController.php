<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Video;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Service\FileUploader;

use App\Repository\TrickRepository;
use App\Service\Manager\CommentManager;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/trick')]
class TrickController extends AbstractController
{

  #[Route('/new', name: 'trick_new', methods: ['GET', 'POST'])]
  public function new(
    Request $request,
    ManagerRegistry $doctrine,
    FileUploader $fileUploader,
    TokenStorageInterface $tokenStorage
  ): Response {
    $trick = new Trick();
    //accès à new uniquement pour les user connectés / rediriger vers page de connexion
    $trick->setAuthor($tokenStorage->getToken()->getUser());
    $form = $this->createForm(TrickType::class, $trick);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if (count($form->get('image')->getData()) > 3) {
        $form->get('image')->addError(new \Symfony\Component\Form\FormError('Merci d\'ajouter 3 photos maximum'));
      } else {
        if ($form->isValid()) {
          $imagesFile = $form->get('image')->getData();
          foreach ($imagesFile as $oneImageFile) {
            if ($oneImageFile) {
              $imageFileName = $fileUploader->upload($oneImageFile);
              $img = new Image();
              $img->setName($imageFileName);
              $trick->addImage($img);
            }
          }

          $formDate = $request->request->get('trick');
          if (!empty($formDate['video'])) {
            $videosUrl = $formDate['video'];
            foreach ($videosUrl as $oneVideo) {
              if (!empty($oneVideo)) {
                $vid = new Video();
                $vid->setUrl($oneVideo);
                $trick->addVideo($vid);
              }
            }
          }

          $entityManager = $doctrine->getManager();
          $entityManager->persist($trick);
          $entityManager->flush();

          $this->addFlash('success', 'Votre trick est bien ajouté');

          return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
        }
      }
    }

    return $this->render('trick/new.html.twig', [
      'trick' => $trick,
      'form' => $form->createView(),
    ]);
  }

  #[Route('/{id}/{slug}/detail', name: 'trick_show', methods: ['GET', 'POST'])]
  public function show(Request $request, ManagerRegistry $doctrine, Trick $trick = null, CommentManager $commentManager): Response
  {
    //if $trick is null redirect
    if ($trick === null) {
      $this->addFlash(
        'notice',
        'Invalid parameter'
      );

      return $this->redirectToRoute('trick_index');
    }

    //new comments
    $comment = new Comment();
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    //form comment
    if ($form->isSubmitted() && $form->isValid()) {
      $comment->setTrick($trick);
      $comment = $form->getData();
      $comment->setAuthor($this->getUser());
      $entityManager = $doctrine->getManager();


      $entityManager->persist($comment);
      $entityManager->flush();


      $this->addFlash('notice', 'Votre commentaire a bien été ajouté');

    }

    $currentPage = $request->query->get('page')??1;
    $comments = $commentManager->getCommentPagination($trick, $currentPage);
    $maxPages = $commentManager->getPaginationPage($trick);
    return $this->render('trick/show.html.twig', [
      'trick' => $trick,
      'formComment' => $form->createView(),
      'comments' => $comments,
      'maxPages' => $maxPages,
      'currentPage' => $currentPage
    ]);
  }

  #[Route('/{id}/{slug}/edit', name: 'trick_edit', methods: ['GET', 'POST'])]
  public function edit(
    Request $request,
    Trick $trick,
    EntityManagerInterface $entityManager,
    FileUploader $fileUploader
  ): Response {
    $form = $this->createForm(TrickType::class, $trick);
    $form->handleRequest($request);

    if ($form->isSubmitted()) {
      if ($form->get('image')->getData() !== null) {
        $maVal = count($form->get('image')->getData());
      } else {
        $maVal = 4;
      }
      //catch number of images + images already uploaded
      if ((count($form->get('image')->getData() ?? [])) + $trick->getImage()->count() > 3) {
        $form->get('image')->addError(new \Symfony\Component\Form\FormError('Merci d\'ajouter 3 photos maximum'));
      } else {
        if ($form->isValid()) {
          $imagesFiles = $form->get('image')->getData();
          if (count($imagesFiles) <= 3) {
            foreach ($imagesFiles as $oneImageFile) {
              $imageFileName = $fileUploader->upload($oneImageFile);
              $img = new Image();
              $img->setName($imageFileName);
              $trick->addImage($img);
            }

            $formDate = $request->request->get('trick');
            if (!empty($formDate['video'])) {
              $videosUrl = $formDate['video'];
              foreach ($videosUrl as $oneVideo) {
                if (!empty($oneVideo)) {
                  $vid = new Video();
                  $vid->setUrl($oneVideo);
                  $trick->addVideo($vid);
                }
              }
            }

            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Votre trick "'.$trick->getName().'" est bien modifié');
          }
        }

        return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
      }
    }

    return $this->renderForm('trick/edit.html.twig', [
      'trick' => $trick,
      'form' => $form,
    ]);
  }

  #[Route('/{id}/{slug}/delete', name: 'trick_delete', methods: ['GET', 'POST'])]
  public function delete(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
  {
    if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
      $entityManager->remove($trick);
      $entityManager->flush();
    }

    return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
  }

  #[Route('/{currentPage?1}/{nbrByPage?10}', name: 'trick_index', methods: ['GET'])]
  public function index(ManagerRegistry $doctrine, $currentPage): Response
  {
    $repository = $doctrine->getRepository(Trick::class);

    $nbTricks = $repository->count([]);

    $nbrByPage = 12;

    $tricks = $repository->findBy([], ['createdAt' => 'DESC'], $nbrByPage, ($currentPage - 1) * $nbrByPage);

    $totalPages = ceil($nbTricks / $nbrByPage);

    return $this->render('trick/index.html.twig', [
      'tricks' => $tricks,
      'totalPages' => $totalPages,
      'currentPage' => $currentPage,
      'nbrByPage' => $nbrByPage,
    ]);
  }
}
