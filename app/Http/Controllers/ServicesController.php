<?php

namespace App\Http\Controllers;

use App\Http\Util\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    /**
     * @return ApiResponse
     *
     * @noinspection PhpRedundantCatchClauseInspection
     */
    public function index(): ApiResponse
    {
        $path = config('services.external_api.services_path');

        if (!Storage::exists($path)) {
            return ApiResponse::error('Services not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $services = json_decode(Storage::get($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return ApiResponse::error('Failed to get services', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::success($services);
    }
}
