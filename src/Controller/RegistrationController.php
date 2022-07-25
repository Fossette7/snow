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
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;



class RegistrationController extends AbstractController
{
    private $mailer;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $helper;
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
            $entityManager->flush();

          //verify email / https://github.com/SymfonyCasts/verify-email-bundle
          $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'verification_enregistrement',
            $user->getId(),
            $user->getEmail()
          );

          $email = new TemplatedEmail();
          $email->from('tests_oc@douastart.com');
          $email->to('tests_oc@douastart.com'); //$user->getEmail()
          $email->subject('Votre inscription sur Snowtricks');
          $email->htmlTemplate('registration/email.html.twig');
          $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);
          $email->text('Hello World');
          $this->mailer->send($email);

          $this->addFlash('success', 'Un e-mail de validation vous a été envoyé');

          return $userAuthenticator->authenticateUser(
           $user,
           $authenticator,
           $request,
          );

          return $this->redirectToRoute('home');

        }
        return $this->render('registration/register.html.twig',[
            'registrationForm' => $form->createView()
        ]);
    }

  #[Route('/verify', name: 'verification_enregistrement')]

    public function verifyUserEmail (Request $request) :Response
  {
      //die('verification enregistrement de compte');
      $this->denyAccessUnlessGranted ('IS_AUTHENTICATED_FULLY');
      $user = $this->getUser();
      // do not get  the User's Id or Email from Request Object
        try{
          $this->verifyEmailHelper->validateEmailConfirmation(
            $request->getUri(),
            $user->getId(),
            $user->getEmail());
        }catch (VerifyEmailExceptionInterface $e) {
          $this->addFlash('error', $e->getReason());

          return $this->redirectToRoute('register');
        }

    $this->addFlash('success', 'Votre adresse e-mail est vérifiée.');

    return $this->redirectToRoute('home');
  }
}
