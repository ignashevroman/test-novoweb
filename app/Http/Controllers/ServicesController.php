<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    /**
     * @return JsonResponse
     *
     * @noinspection PhpRedundantCatchClauseInspection
     */
    public function index(): JsonResponse
    {
        $path = config('services.external_api.services_path');

        if (!Storage::exists($path)) {
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        try {
            $services = json_decode(Storage::get($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return response()->json(['error' => 'Failed to get services'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['data' => $services ?? []]);
    }
}
