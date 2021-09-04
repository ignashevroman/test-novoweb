<?php


namespace App\Services\ExternalApi;


use App\Exceptions\ExternalApiException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    /**
     * @var GuzzleClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $key;

    /**
     * Client constructor.
     * @param string $url
     * @param string $key
     */
    public function __construct(string $url, string $key)
    {
        $this->key = $key;

        $this->client = new GuzzleClient(
            [
                'base_uri' => $url,
                'allow_redirects' => false,
                'timeout' => 60,
            ]
        );
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @return array|null
     * @throws ExternalApiException
     */
    protected function request(string $url = '', string $method = 'GET', array $options = []): ?array
    {
        $query = $options['query'] ?? [];
        if (!array_key_exists('key', $query)) {
            $query['key'] = $this->key;
            $options['query'] = $query;
        }

        $response = null;
        try {
            $response = $this->client->request($method, $url, $options);
        } catch (GuzzleException $e) {
            throw new ExternalApiException('Error while executing request', 0, $e, $url);
        }

        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @return array
     * @throws ExternalApiException
     */
    public function getServices(): array
    {
        return $this->request('', 'GET', [
            'query' => ['action' => 'services']
        ]) ?? [];
    }
}
