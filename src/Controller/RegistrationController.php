<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AuthenAuthenticator;
use App\Service\FileUploader;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;



class RegistrationController extends AbstractController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

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
            //$entityManager->flush();


          $email = new TemplatedEmail();
          $email->from('reybeka.dev@gmail.com');
          $email->to($user->getEmail());
          $email->subject('Votre inscription sur Snowtricks');
          $email->htmlTemplate('registration/email.html.twig');
          $email->context(['toto' => 'Validé - Welcome e-mail']);
          $email->text('Hello World');
          $this->mailer->send($email);

          $this->addFlash('success', 'Un e-mail de validation a été envoyé, vérifier vos e-mails');

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

  #[Route('/verify', name: 'verification_enregistrement')]

    public function verifyUserEmail (Request $request) :Response
  {
    die('verification enregistrement de compte');
      $this->denyAccessUnlessGranted ('IS_AUTHENTICATED_FULLY');
      $user = $this->getUser();
      // do not get  the User's Id or Email from Request Obbject
        try{
          $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());
        }catch (VerifyEmailExceptionInterface $e) {
          $this->addFlash('verify_email_error', $e->getReason());

          return $this->redirectToRoute('register');
        }

    // Mark your user as verified. e.g. switch a User::verified property to true

    $this->addFlash('success', 'Your e-mail address has been verified.');

    return $this->redirectToRoute('home');
  }
}
