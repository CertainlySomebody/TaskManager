<?php

namespace App\Domain\User\Entity;

use App\Domain\User\ValueObject\UserId;

class User
{
    public function __construct(
        private UserId $id,
        private string $name,
        private string $email,
        private string $phone,
        private ?string $username,
        private array $roles,
        private ?int $externalId
    ) {
    }

    public function getId(): UserId
    {
        return $this->id;
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
