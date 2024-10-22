<?php

namespace ECidade\WebService;

use stdClass;

class SimpleHttpClient
{
    protected $baseUri;
    protected $timeout;

    public function __construct($baseUri, $timeout = 120)
    {
        $this->baseUri = $baseUri;
        $this->timeout = $timeout;
    }

    public function request($method, $uri, $options = [], $retryMax = 3, $secondsDelay = 2)
    {
        $attempt = 0;

        do{
            $url = $this->baseUri . $uri;
            $headers = isset($options['headers']) ? $options['headers'] : [];
            $body = isset($options['body']) ? $options['body'] : null;
            $json = isset($options['json']) ? json_encode($options['json']) : null;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            curl_setopt($ch, CURLOPT_HEADER, true);

            if ($headers) {
                $formattedHeaders = [];
                foreach ($headers as $key => $value) {
                    $formattedHeaders[] = "$key: $value";
                }
                curl_setopt($ch, CURLOPT_HTTPHEADER, $formattedHeaders);
            }

            if ($body) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            }

            if ($json) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            }

            $response = curl_exec($ch);
            $error = curl_error($ch);

            if ($error) {
                throw new \Exception("cURL Error: $error");
            }

            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $responseHeaders = substr($response, 0, $headerSize);
            $responseBody = substr($response, $headerSize);
            curl_close($ch);

            $response = new SimpleHttpResponse($httpCode, $responseBody, $responseHeaders);
            if ($httpCode >= 200 && $httpCode < 300) {
                return $response;
            } else {
                $attempt++;
            }

            sleep($secondsDelay);
        } while($attempt < $retryMax);

        return $response;
    }

    public function get($uri, $options = [], $retryMax = 3, $secondsDelay = 2)
    {
        return $this->request('GET', $uri, $options, $retryMax, $secondsDelay);
    }

    public function post($uri, $options = [], $retryMax = 3, $secondsDelay = 2)
    {
        return $this->request('POST', $uri, $options, $retryMax, $secondsDelay);
    }

    public function put($uri, $options = [], $retryMax = 3, $secondsDelay = 2)
    {
        return $this->request('PUT', $uri, $options, $retryMax, $secondsDelay);
    }

    public function patch($uri, $options = [], $retryMax = 3, $secondsDelay = 2)
    {
        return $this->request('PATCH', $uri, $options, $retryMax, $secondsDelay);
    }

    public function delete($uri, $options = [], $retryMax = 3, $secondsDelay = 2)
    {
        return $this->request('DELETE', $uri, $options, $retryMax, $secondsDelay);
    }
}
