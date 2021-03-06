<?php


namespace App\Services\ExternalApi;


use App\Exceptions\ExternalApiException;
use App\Services\ExternalApi\DTO\Order;
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
            throw new ExternalApiException('Error while executing request. ' . $e->getMessage(), 0, $e, $url);
        }

        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @return array
     * @throws ExternalApiException
     */
    public function getServices(): array
    {
        return $this->request('just', 'GET', [
            'query' => ['action' => 'services']
        ]) ?? [];
    }

    /**
     * @param Order $order
     * @return array|null
     * @throws ExternalApiException
     */
    public function sendOrder(Order $order): ?array
    {
        return $this->request('just/add', 'POST', [
            'query' => ['action' => 'add'],
            'json' => $order->toArray(),
        ]);
    }

    /**
     * @param int $id
     * @return array|null
     * @throws ExternalApiException
     */
    public function getOrderStatus(int $id): ?array
    {
        return $this->request('just', 'GET', [
            'query' => ['action' => 'status', 'order' => $id]
        ]);
    }
}
