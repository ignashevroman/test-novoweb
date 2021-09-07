<?php


namespace App\Http\Util;


use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    public static function success($data = null, int $code = self::HTTP_OK): self
    {
        $response = ['success' => true];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return new self($response, $code);
    }

    public static function error(string $error, int $code = self::HTTP_BAD_REQUEST): self
    {
        return new self(['success' => false, 'error' => $error], $code);
    }
}

