<?php


namespace App\Services\InstaParser;


use App\Exceptions\InstagramParserException;
use App\Models\Profile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class InstaParser
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
            // If there's no profile in cache
            // then get it from parser and save in cache for a week
            $fresh = true;
            $profile = $this->parser->getProfile($url);
            Cache::add($url, $profile, 3600 * 24 * 7);
        }

        // Add url to save into database
        if (!array_key_exists('url', $profile)) {
            $profile['url'] = $url;
        }

        if ($fresh) {
            // If data is fresh we need to update or create new one
            /** @var Profile $model */
            $model = Profile::query()
                ->updateOrCreate(
                    Arr::only($profile, ['id']),
                    Arr::except($profile, ['id'])
                );
        } else {
            // If data already was in cache
            // then just get it from database and create if not exists
            /** @var Profile $model */
            $model = Profile::query()
                ->firstOrCreate(
                    Arr::only($profile, ['id']),
                    Arr::except($profile, ['id'])
                );
        }

        return $model;
    }
}
