<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;

use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



#[Route('/image')]
class ImageController extends AbstractController
{
    #[Route('/list', name: 'image_index')]
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }


  #[Route('/{id}/delete', name: 'image_delete', methods:['GET', 'POST']) ]
  public function delete(Request $request, Image $image, EntityManagerInterface $entityManager)
  {
      $entityManager->remove($image);
      $entityManager->flush(

      $this->addFlash('warning', 'Votre image est bien supprimée');

    $route = $request->headers->get('referer');
    return new RedirectResponse($route);
  }

}
