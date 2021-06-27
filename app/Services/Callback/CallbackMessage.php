<?php

namespace App\Services\Callback;

class CallbackMessage
{

    /**
     * The POST data of the Webhook request.
     *
     * @var mixed
     */
    protected $data;

    /**
     * The headers to send with the request.
     *
     * @var array
     */
    protected $headers = [];

    public static function create($data = '')
    {
        return new static($data);
    }

    /**
     * @param mixed $data
     */
    public function __construct($data = '')
    {
        $this->data = $data;
    }

    /**
     * Set the Webhook data to be JSON encoded.
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function data($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Add a Webhook request custom header.
     *
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function header($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'data' => $this->data,
            'headers' => $this->headers
        ];
    }
}