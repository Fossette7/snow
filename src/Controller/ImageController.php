<?php

namespace App\Controller;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    #[Route('/image', name: 'image')]
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

#[Route('/{id}/edit', name: 'image_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, Image $image, EntityManagerInterface $entityManager): Response
  {
    $form = $this->createForm(ImageType::class, $image);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entityManager->flush();

      $this->addFlash('success', 'Votre image est bien modifiée');

      return $this->redirectToRoute('trick_show', ['id'=>$image->getTrick()->getId()], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('image/edit.html.twig', [
      'image' => $image,
      'form' => $form,
    ]);
  }
}
