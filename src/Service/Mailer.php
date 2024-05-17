<?php
// src/Service/MessageGenerator.php
namespace App\Service;

use App\Entity\Player;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{
    private MailerInterface $mailer;
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function welcomeMail(Player $player): void
    {
        $email = (new TemplatedEmail())
            ->from('BabyBoard <babyboard@alwaysdata.net>')
            ->to(new Address($player->getEmail()))
            ->subject('Bienvenue sur BabyBoard !')
            ->htmlTemplate('mails/index.html.twig')
            ->context(['user' => $player]); // Passer la variable 'user' au template Twig

        // dd($mailer->send($email)); // Commente ou supprime cette ligne pour permettre l'envoi réel

        // Envoie l'e-mail
        $this->mailer->send($email);
    }

    public function forgotPassword(Player $player): void
    {
        $email = (new TemplatedEmail())
            ->from('BabyBoard <babyboard@alwaysdata.net>')
            ->to(new Address("arthur.felix28110@gmail.com"))
            ->subject('Bienvenue sur BabyBoard !')
            ->htmlTemplate('mails/index.html.twig')
            ->context(['user' => $player]); // Passer la variable 'user' au template Twig

        // dd($mailer->send($email)); // Commente ou supprime cette ligne pour permettre l'envoi réel

        // Envoie l'e-mail
        $this->mailer->send($email);
    }

    public function activateAccount(Player $player): void
    {
        $email = (new TemplatedEmail())
            ->from('BabyBoard <babyboard@alwaysdata.net>')
            ->to(new Address("arthur.felix28110@gmail.com"))
            ->subject('Bienvenue sur BabyBoard !')
            ->htmlTemplate('mails/index.html.twig')
            ->context(['user' => $player]); // Passer la variable 'user' au template Twig

        // dd($mailer->send($email)); // Commente ou supprime cette ligne pour permettre l'envoi réel

        // Envoie l'e-mail
        $this->mailer->send($email);
    }
}