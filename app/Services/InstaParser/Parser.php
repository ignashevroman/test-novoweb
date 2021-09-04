<?php


namespace App\Services\InstaParser;


use App\Exceptions\InstagramParserException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

/**
 * Class Parser
 * @package App\Services\InstaParser
 */
class Parser
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Parser constructor.
     */
    public function __construct()
    {

        $this->client = new Client(
            [
                'base_uri' => 'https://www.instagram.com/',
                'timeout' => 60,
            ]
        );
    }

    /**
     * @param string $url
     * @return array
     * @throws InstagramParserException
     */
    public function getProfile(string $url): array
    {
        // Get the profile
        try {
            $response = $this->client->get($url);
        } catch (GuzzleException $e) {
            throw new InstagramParserException('Failed to get profile', 0, null, $url);
        }

        // Parse it
        $profile = $this->parseProfile($response->getBody()->getContents());
        if (!$profile) {
            throw new InstagramParserException('Failed to parse profile', 0, null, $url);
        }

        return $profile;
    }

    /**
     * @param string $html
     * @return array|null
     */
    protected function parseProfile(string $html): ?array
    {
        // Find initial JSON object with regex
        $matches = [];
        $sharedData = null;
        if (preg_match("/window\._sharedData\s?=\s?([^;]+);\s?<\/script>/", $html, $matches)) {
            $sharedData = json_decode($matches[1] ?? '', true, 512, JSON_THROW_ON_ERROR);
        }

        // Get user data from it
        return Arr::get($sharedData, 'entry_data.ProfilePage.0.graphql.user');
    }
}
