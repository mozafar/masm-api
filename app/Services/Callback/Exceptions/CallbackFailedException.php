<?php

namespace App\Services\Callback\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class CallbackFailedException extends Exception
{
    private $response;

    /**
     * @param Response $response
     * @param string $message
     * @param int|null $code
     */
    public function __construct(Response $response, string $message, int $code = null)
    {
        $this->response = $response;
        $this->message = $message;
        $this->code = $code ?? $response->status();

        parent::__construct($message, $code);
    }

    /**
     * @param Response $response
     * @return self
     */
    public static function callbackRespondedWithAnError(Response $response)
    {
        return new self(
            $response,
            sprintf('Callback responded with an error: `%s`', $response->body())
        );
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
