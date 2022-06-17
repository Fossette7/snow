<?php

namespace App\Service;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SendMailService extends AbstractController
{
  private $mailer;

  public function __construct(MailerInterface $mailer)
  {
    $this->mailer = $mailer;
  }

  public function sendForgottenPasswordMail(MailerInterface $mailer)
  {
    if($email){
      $email = (new TemplatedEmail())
        ->from(new Address('reybeka.dev@gmail.com'))
        ->to(new Address($email))
        ->subject('Reset your password!')

        // path of the Twig template to render
        ->htmlTemplate('reset_password/email.html.twig')

        // pass variables (name => value) to the template
        ->context([
          'expiration_date' => new \DateTime('+1 hour'),
        ])
      ;
      $this->mailer->send($email);
    }
  }

  public function sendWelcomeMail(MailerInterface $mailer)
  {
    if($email){
      $email = (new TemplatedEmail())
        ->from(new Address('reybeka.dev@gmail.com'))
        ->to(new Address($email))
        ->subject('Thanks for signing up!')

        // path of the Twig template to render
        ->htmlTemplate('registration/email.html.twig')

        // pass variables (name => value) to the template
        ->context([
          'expiration_date' => new \DateTime('+1 hour'),
        ])
      ;
      $this->mailer->send($email);
    }
  }

}
