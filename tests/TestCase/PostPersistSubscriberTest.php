<?php

namespace App\Tests\TestCase;

use App\DoctrineSubscriber\PostPersistSubscriber;
use App\Entity\User;
use App\Entity\UserLogin;
use App\Security\EmailVerifier;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use PHPUnit\Framework\TestCase;

class PostPersistSubscriberTest extends TestCase
{
    private PostPersistSubscriber $subscriber;
    private EmailVerifier $emailVerifier;

    public function setUp(): void
    {
        $this->emailVerifier = $this->createMock(EmailVerifier::class);
        $this->subscriber = new PostPersistSubscriber($this->emailVerifier);

    }

    public function testGetSubscribedEvents()
    {
        $expected = [
            Events::postPersist => 'postPersist',
        ];

        $actual = $this->subscriber->getSubscribedEvents();

        $this->assertEquals($expected, $actual);
    }

    public function testSendUserEmailVerificationWithUser()
    {
        $user = $this->createMock(User::class);

        $lifeCycleArgs = $this->createMock(LifecycleEventArgs::class);

        $lifeCycleArgs->expects($this->once())->method('getObject')->willReturn($this->returnValue($user));

        $this->emailVerifier->expects($this->once())->method('sendEmailConfirmation')->with($user);

        $this->subscriber->postPersist($lifeCycleArgs);
    }

    public function testSendUserEmailVerificationWithoutUser()
    {
        $user = $this->createMock(UserLogin::class);

        $lifeCycleArgs = $this->createMock(LifecycleEventArgs::class);

        $lifeCycleArgs->expects($this->once())->method('getObject')->willReturn($this->returnValue($user));

        $this->emailVerifier->expects($this->never())->method('sendEmailConfirmation')->with($user);

        $this->subscriber->postPersist($lifeCycleArgs);
    }
}