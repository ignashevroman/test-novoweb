<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParseInstagramProfileRequest;
use App\Http\Util\ApiResponse;
use App\Services\InstaParser\Parser;
use Illuminate\Support\Facades\Cache;

class InstagramParserController extends Controller
{
    /**
     * @param ParseInstagramProfileRequest $request
     * @param Parser $parser
     * @return ApiResponse
     */
    public function profile(ParseInstagramProfileRequest $request, Parser $parser): ApiResponse
    {
        $url = $request->get('url') ?? '';

        // Get profile from cache or parse from instagram and cache for a week
        $profile = Cache::remember($url, 3600 * 24 * 7, static function () use ($parser, $url) {
            return $parser->getProfile($url);
        });

        return ApiResponse::success($profile);
    }
}
