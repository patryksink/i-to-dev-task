<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class UserCreatedEvent extends Event
{
    private User $user;
    private string $plainPassword;

    public function __construct(User $user, string $plainPassword)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}