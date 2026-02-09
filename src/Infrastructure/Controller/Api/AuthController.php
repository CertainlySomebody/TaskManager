<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\Api;

use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if (!$email) {
            return new JsonResponse(['error' => 'Email is required.'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return new JsonResponse(['error' => 'User not found.'], Response::HTTP_NOT_FOUND);
        }

        $token = bin2hex(random_bytes(32));
        $user->setApiToken($token);
        $this->userRepository->save($user);

        return new JsonResponse([
            'token' => $token,
            'user' => [
                'id' => $user->getId()->getValue(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ],
        ]);
    }
}

