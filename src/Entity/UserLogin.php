<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserLoginRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLoginRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UserLogin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['remove'], inversedBy: 'userLogins')]
    private ?User $user;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        if ($this->createdAt === null) {
            $this->createdAt = new \DateTime();
        }
    }
}
