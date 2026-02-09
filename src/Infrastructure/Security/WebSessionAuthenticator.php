<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use App\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class WebSessionAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return $request->getSession()->has('api_token');
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->getSession()->get('api_token');

        if (!$token) {
            throw new AuthenticationException('No session token.');
        }

        return new SelfValidatingPassport(
            new UserBadge($token, function (string $token) {
                $user = $this->userRepository->findByApiToken($token);

                if (!$user) {
                    throw new UserNotFoundException();
                }

                return new SecurityUser($user);
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->remove('api_token');
        return new RedirectResponse('/web/login');
    }
}
