<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\ContactRequestEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class MailingSubscriber implements EventSubscriberInterface
{

    public function __construct(private readonly MailerInterface $mailer) {}
    public function onConctactRequestEvent($event): void
    {
        $contact = $event->data;
        $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to($contact->email)
            ->subject('Mon suggestion par rapport a votre profile')
            ->text($contact->message);

        $this->mailer->send($email);
    }

    public function onLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if (!$user instanceof User) {
            return;
        }

        $email = (new TemplatedEmail())
            ->from('eloi.randriamihaingo@dragonbleu.fr')
            ->to("nandry556@gmail.com")
            ->subject('Connexion')
            ->htmlTemplate('registration/confirmation_email.html.twig');

        $this->mailer->send($email);
    }
    public static function getSubscribedEvents(): array
    {
        return [
            ContactRequestEvent::class => 'onConctactRequestEvent',
            InteractiveLoginEvent::class => 'onLogin'
        ];
    }
}
