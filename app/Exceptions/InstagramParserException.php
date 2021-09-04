<?php


namespace App\Exceptions;


use App\Http\Util\ApiResponse;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class InstagramParserException extends Exception
{
    /**
     * @var string
     */
    protected $url;

    /**
     * InstagramParserException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param string $url
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null, string $url = '')
    {
        $this->url = $url;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return ApiResponse
     */
    public function render(): ApiResponse
    {
        return ApiResponse::error('Failed to parse instagram ' . $this->url, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
