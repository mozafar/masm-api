<?php

namespace App\Services\Callback;

class CallbackMessage
{
    protected $data;

    protected $headers = [];

    public static function create($data = []): self
    {
        return new static($data);
    }


    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function data($data): self
    {
        $this->data = $data;

        return $this;
    }

    public function header($name, $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'headers' => $this->headers
        ];
    }
}