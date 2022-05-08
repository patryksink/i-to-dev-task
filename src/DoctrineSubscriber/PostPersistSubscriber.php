<?php

declare(strict_types=1);

namespace App\DoctrineSubscriber;

use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class PostPersistSubscriber implements EventSubscriber
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist => 'postPersist',
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof User) {
            $this->emailVerifier->sendEmailConfirmation($entity);
        }
    }
}
