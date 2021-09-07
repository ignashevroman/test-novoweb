<?php

namespace App\Http\Controllers;

use App\Http\Util\ApiResponse;
use App\Models\Service;

class ServicesController extends Controller
{
    /**
     * @return ApiResponse
     */
    public function index(): ApiResponse
    {
        $services = Service::all();

        return ApiResponse::success($services);
    }
}
