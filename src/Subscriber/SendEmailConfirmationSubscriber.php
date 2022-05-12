<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Event\UserCreatedEvent;

use App\Security\EmailVerifier;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendEmailConfirmationSubscriber implements EventSubscriberInterface
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserCreatedEvent::class => 'sendEmail'
        ];
    }

    public function sendEmail(UserCreatedEvent $event): void
    {
        $user = $event->getUser();
        $plainPassword = $event->getPlainPassword();
        $this->emailVerifier->sendEmailConfirmation($user, $plainPassword);
    }
}