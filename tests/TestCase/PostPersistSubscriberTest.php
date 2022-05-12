<?php

namespace App\Tests\TestCase;

use App\Entity\User;
use App\Event\UserCreatedEvent;
use App\Security\EmailVerifier;
use App\Subscriber\SendEmailConfirmationSubscriber;
use PHPUnit\Framework\TestCase;

class PostPersistSubscriberTest extends TestCase
{
    private SendEmailConfirmationSubscriber $subscriber;
    private EmailVerifier $emailVerifier;

    public function setUp(): void
    {
        $this->emailVerifier = $this->createMock(EmailVerifier::class);
        $this->subscriber = new SendEmailConfirmationSubscriber($this->emailVerifier);
    }

    public function testGetSubscribedEvents()
    {
        $expected = [
            UserCreatedEvent::class => 'sendEmail'
        ];

        $actual = $this->subscriber->getSubscribedEvents();

        $this->assertEquals($expected, $actual);
    }

    public function testSendUserEmailVerificationWithoutUser()
    {
        $event = $this->createMock(UserCreatedEvent::class);

        $user = $this->createMock(User::class);

        $event->expects($this->once())->method('getUser')->willReturn($user);
        $event->expects($this->once())->method('getPlainPassword')->willReturn('');

        $this->emailVerifier->expects($this->once())->method('sendEmailConfirmation')->with($user, '');

        $this->subscriber->sendEmail($event);
    }
}