<?php

namespace ECidade\WebService;

use stdClass;

class SimpleHttpResponse
{
    protected $statusCode;
    protected $body;
    protected $headers;

    public function __construct($statusCode, $body, $headers)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->headers = $this->parseHeaders($headers);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getContents()
    {
        return $this->body;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function json()
    {
        return json_decode($this->body, true);
    }

    protected function parseHeaders($rawHeaders)
    {
        $headers = [];
        $lines = explode("\r\n", $rawHeaders);
        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }
}
