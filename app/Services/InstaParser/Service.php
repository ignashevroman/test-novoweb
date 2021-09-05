<?php


namespace App\Services\InstaParser;


use App\Exceptions\InstagramParserException;
use App\Models\Profile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class Service
{
    /**
     * @var Parser
     */
    protected $parser;

    /**
     * Service constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param string $url
     * @return Profile
     * @throws InstagramParserException
     */
    public function getProfile(string $url): Profile
    {
        $fresh = false;
        $profile = Cache::get($url);
        if (!$profile) {
            $fresh = true;
            $profile = $this->parser->getProfile($url);
            Cache::add($url, $profile, 3600 * 24 * 7);
        }

        if ($fresh) {
            $model = Profile::updateOrCreate(
                Arr::only($profile, ['id']),
                Arr::except($profile, ['id'])
            );
        } else {
            $model = Profile::firstOrCreate(
                Arr::only($profile, ['id']),
                Arr::except($profile, ['id'])
            );
        }

        return $model;
    }
}
