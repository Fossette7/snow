<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AuthenAuthenticator;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'register')]
    public function index( Request $request, UserPasswordHasherInterface $userPasswordHasher,UserAuthenticatorInterface $userAuthenticator,
      AuthenAuthenticator $authenticator,  EntityManagerInterface $entityManager, FileUploader $fileUploader) : Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //encode a new password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
              $user,
              $form->get('plainPassword')->getData()
              )
            );
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile){
              $avatarFileName = $fileUploader->upload($avatarFile, ['avatar' => true]);
              $user->setAvatar($avatarFileName);
            }
            $user->setRoles(['ROLE_USER']);
            $entityManager->persist($user);
            $entityManager->flush();

          return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
          );

        }

        return $this->render('registration/register.html.twig',[
            'registrationForm' => $form->createView()
        ]);
    }

}
