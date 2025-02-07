<?php

namespace SIMDE\Ginger\Domain\Proxy;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UTCotizProxy
{
    private Client $client;

    public function __construct(string $apiUrl, string $apiKey)
    {
        $this->client = new Client(
            [
                'base_uri' => $apiUrl,
                'headers' => [
                    'X-API-KEY' => $apiKey
                ],
            ]
        );
    }

    /**
     * @throws GuzzleException
     */
    public function getUserByLogin(string $login): array
    {
        $response = $this->client->get('/api/ginger/' . $login);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function getUserByCard(string $card): array
    {
        $response = $this->client->get( '/api/ginger/badge/' . $card);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     */
    public function getMemberships(string $param)
    {
        $response = $this->client->get('/api/users/' . $param.'/memberships');
        return json_decode($response->getBody()->getContents());
    }
}