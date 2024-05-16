<?php

namespace App\Controller\Spectateur;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }

    #[Route('/conditions-generales-utilisation', name: 'app_cgu')]
    public function cgu(): Response
    {
        return $this->render('home/cgu.html.twig');
    }

    #[Route('/mail', name: 'app_mail')]
    public function mail(MailerInterface $mailer): Response // Ajoute le type de retour Response
    {
        $user = $this->getUser();
        if (!$user) {
            // Gère le cas où l'utilisateur n'est pas connecté
            // Par exemple, tu peux rediriger vers une page de connexion
            // ou renvoyer un message d'erreur approprié.
            // Ici, je vais simplement renvoyer une réponse vide.
            return new Response('Utilisateur non connecté.', Response::HTTP_UNAUTHORIZED);
        }

        $email = (new TemplatedEmail())
            ->from('BabyBoard <babyboard@alwaysdata.net>')
            ->to(new Address("arthur.felix28110@gmail.com"))
            ->subject('Bienvenue sur BabyBoard !')
            ->htmlTemplate('mails/index.html.twig')
            ->context(['user' => $user]); // Passer la variable 'user' au template Twig

        // dd($mailer->send($email)); // Commente ou supprime cette ligne pour permettre l'envoi réel

        // Envoie l'e-mail
        $mailer->send($email);

        // Retourne une réponse pour indiquer que l'e-mail a été envoyé avec succès
        return new Response('Email envoyé avec succès.', Response::HTTP_OK);
    }
}
