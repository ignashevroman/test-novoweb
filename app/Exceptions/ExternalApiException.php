<?php


namespace App\Exceptions;


use Exception;
use Throwable;

class ExternalApiException extends Exception
{
    /**
     * @var string
     */
    protected $url;

    /**
     * ExternalApiException constructor.
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
}
