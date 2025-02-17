<?php

namespace App\Controller;

use App\DTO\ContactDto;
use App\Event\ContactRequestEvent;
use App\Event\LoginRequestEvent;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact.index')]
    public function index(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $contact = new ContactDto();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $dispatcher->dispatch(new ContactRequestEvent($contact));
                $dispatcher->dispatch(new LoginRequestEvent());
                $this->addFlash('success', 'Votre message a été envoyé.');
            } catch (\Throwable $th) {
                $this->addFlash('danger', 'Impossible d\' envoyer votre mail ');
            }

            return $this->redirectToRoute('contact.index');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
