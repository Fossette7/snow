<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;

use App\Repository\videoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



#[Route('/video')]
class VideoController extends AbstractController
{
    #[Route('/list', name: 'video_index')]
    public function index(videoRepository $videoRepository): Response
    {
        return $this->render('video/index.html.twig', [
            'videos' => $videoRepository->findAll(),
        ]);
    }


  #[Route('/{id}/delete', name: 'video_delete', methods:['GET', 'POST']) ]
  public function delete(Request $request, video $video, EntityManagerInterface $entityManager)
  {
      $entityManager->remove($video);
      $entityManager->flush();

      $this->addFlash('warning', 'Votre video est bien supprimée');

    $route = $request->headers->get('referer');
    return new RedirectResponse($route);
  }

}
