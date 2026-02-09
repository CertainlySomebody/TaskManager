<?php

namespace App\Domain\User\Entity;

use App\Domain\User\ValueObject\UserId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    public function __construct(
        UserId $userId,

        #[ORM\Column(type: 'string', length: 255)]
        private string $name,

        #[ORM\Column(type: 'string', length: 255, unique: true)]
        private string $email,

        #[ORM\Column(type: 'string', length: 50)]
        private string $phone,

        #[ORM\Column(type: 'string', length: 255, nullable: true)]
        private ?string $username,

        #[ORM\Column(type: 'json')]
        private array $roles,

        #[ORM\Column(name: 'external_id', type: 'integer', unique: true, nullable: true)]
        private ?int $externalId
    ) {
        $this->id = $userId->getValue();
    }

    public function getId(): UserId
    {
        return new UserId($this->id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }
}
