<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalApi\JsonPlaceholder;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    private const BASE_URL = 'https://jsonplaceholder.typicode.com';

    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }

    public function fetchUsers(): array
    {
        $response = $this->httpClient->request('GET', self::BASE_URL . '/users');

        return $response->toArray();
    }
}
