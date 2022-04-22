<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Service\FileUploader;

use App\Repository\TrickRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/trick')]
class TrickController extends AbstractController
{

    #[Route('/', name: 'trick_index', methods: ['GET'])]
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    #[Route('/all/{pageActuelle?1}/{pageNumber?10}', name: 'trick_index_all', methods: ['GET'])]
    public function indexAll(ManagerRegistry $doctrine, $pageActuelle, $pageNumber): Response
    {
      $repository = $doctrine->getRepository(Trick::class);

      $nbTricks = $repository->count([]);
      $tricks = $repository->findBy([],['createdAt'=>'DESC'], $pageNumber, ($pageActuelle - 1) * $pageNumber);


      $totalPages = ceil($nbTricks / $pageNumber);

      return $this->render('trick/index.html.twig', [
        'tricks' => $tricks,
        'totalPages' => $totalPages,
        'pageActuelle' => $pageActuelle,
      ]);
    }

    #[Route('/new', name: 'trick_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader, TokenStorageInterface $tokenStorage): Response
    {
        $trick = new Trick();
        //accès à new que pour les user connectés mettre message et bloquer accès
        $trick->setAuthor($tokenStorage->getToken()->getUser());
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imagesFile = $form->get('image')->getData();
              foreach ($imagesFile as $oneImageFile){
                if ($oneImageFile) {
                  $imageFileName = $fileUploader->upload($oneImageFile);
                  $img = new Image();
                  $img->setName($imageFileName);
                  $trick->addImage($img);
                }
          }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            $this->addFlash('success', 'Votre trick est bien ajouté');

            return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'trick_show', methods: ['GET', 'POST'])]
    public function show(Request $request, ManagerRegistry $doctrine, Trick $trick=null): Response
    {
            //if $trick is null redirect
            if($trick === null){
                $this->addFlash(
                'notice', 'Invalid parameter'
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
            $comment= $form->getData();
            $comment->setAuthor($this->getUser());
            $entityManager= $doctrine->getManager();


            $entityManager->persist($comment);
            $entityManager->flush();


            $this->addFlash('message', 'Votre commentaire a bien été ajouté');

              return $this->redirectToRoute('trick_show', ['id'=>$trick->getId()]);
          }


        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'formComment'=> $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'trick_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            $this->addFlash('success', 'Votre trick est bien modifié');

            return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'trick_delete', methods:['GET', 'POST']) ]
    public function delete(Request $request, Trick $trick, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
    }
}
