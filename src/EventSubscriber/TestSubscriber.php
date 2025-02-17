<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;

class TestSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(ResponseEvent $event): void
    {
        // dd("Hello world!");
    }

    public function onSuccessLogin(AuthenticationSuccessEvent $event): void
    {
        // dd("Login successful!");
    }
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
            AuthenticationSuccessEvent::class => 'onSuccessLogin',
        ];
    }
}
