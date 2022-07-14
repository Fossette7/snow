<?php

namespace App\Service\Manager;

use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\Persistence\ManagerRegistry;

class CommentManager
{
  private $doctrine;
  private $elementToDisplayPagination = 5;

  public function __construct(ManagerRegistry $doctrine)
  {
    $this->doctrine = $doctrine;
  }

  public function getCommentPagination(Trick $trick, int $currentPage=1)
  {
    $totalComment = $trick->getComments()->count();

    $bddStartAt = ($this->elementToDisplayPagination * $currentPage) - $this->elementToDisplayPagination;
    $commentToDisplay = $this->doctrine->getRepository(Comment::class)->findBy(
      ['trick' => $trick],
      ['createdAt' => 'DESC'],
      $this->elementToDisplayPagination,
      $bddStartAt);

    return $commentToDisplay;
  }

  public function getPaginationPage(Trick $trick)
  {
    $trickComment = $this->doctrine->getRepository(Comment::class)->findBy(['trick' => $trick], ['createdAt' => 'DESC']);
    $totalTrickComment = count($trickComment);
    $maxPages = (int)ceil($totalTrickComment/$this->elementToDisplayPagination);

    return $maxPages;
  }

}
