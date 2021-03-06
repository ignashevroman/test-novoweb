<?php

namespace App\Http\Controllers;

use App\Exceptions\InstagramParserException;
use App\Http\Requests\ParseInstagramProfileRequest;
use App\Http\Util\ApiResponse;
use App\Services\InstaParser\InstaParser;

class InstagramParserController extends Controller
{
    /**
     * @param ParseInstagramProfileRequest $request
     * @param InstaParser $parser
     * @return ApiResponse
     * @throws InstagramParserException
     */
    public function profile(ParseInstagramProfileRequest $request, InstaParser $parser): ApiResponse
    {
        $url = $request->get('url');
        $profile = $parser->getProfile($url);

        return ApiResponse::success($profile);
    }
}
