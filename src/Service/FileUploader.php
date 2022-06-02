<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{

  private $targetDirectory;
  private $avatarDirectory;
  private $slugger;

  public function __construct( string $targetDirectory, string $avatarDirectory, SluggerInterface $slugger)
  {
      $this->targetDirectory = $targetDirectory;
      $this->avatarDirectory = $avatarDirectory;
      $this->slugger = $slugger;
  }

  /**
   * @param string $type
   * @return string
   */
  private function getDirectoryByType(string $type){
    switch ($type) {
      case 'avatar':
        return $this->avatarDirectory;
        break;
      default:
        return $this->targetDirectory;
    }
  }

  /**
   * @param UploadedFile $file
   * @param array $option
   * @return string
   */
  public function upload(UploadedFile $file, array $option = [])
  {
      $type = 'image';
      if(!empty($option['avatar']) && $option['avatar'] === true)
      {
        $type = 'avatar';
      }
      $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
      $safeFilename = $this->slugger->slug($originalFilename);
      $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
      try{
          $directory = $this->getDirectoryByType($type);
          $file->move($directory,$fileName);
      } catch (FileException $e){
        echo $e->getMessage();
      }

      return $fileName;
  }


  public function getTargetDirectory()
  {
        return $this->targetDirectory;
  }

}
