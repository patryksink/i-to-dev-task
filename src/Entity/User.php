<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotNull(message: "Name cannot be null")]
    #[Assert\NotBlank(message: "Please enter a name")]
    #[Assert\Length(max: 255, maxMessage: 'Your email should be not be longer than {{ limit }} characters')]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email(message: "Please enter valid email")]
    #[Assert\NotNull(message: "Email cannot be null")]
    #[Assert\NotBlank(message: "Please enter a email")]
    #[Assert\Length(max: 180, maxMessage: 'Your email should be not be longer than {{ limit }} characters')]
    private ?string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\Length(min: 6, max: 4096, minMessage: 'Your password should be at least {{ limit }} characters', maxMessage: 'Your password should be not be longer than {{ limit }} characters')]
    private string $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserLogin::class, cascade: ['remove'])]
    private Collection $userLogins;

    public function __construct()
    {
        $this->userLogins = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
    }
}
