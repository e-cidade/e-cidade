<?php

namespace App\Repositories\Tributario\ISSQN\Redesim;

use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IFilters;
use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IRedesimApi;
use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IRedesimApiSettings;
use App\Repositories\Tributario\ISSQN\Redesim\DTO\RedesimApiResponse;
use BusinessException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

class ApiRedesim implements IRedesimApi {

    protected bool $debug = false;

    protected IRedesimApiSettings $settings;
    protected ClientInterface $client;

    protected string $baseUrl = '';

    public function __construct(IRedesimApiSettings $settings, ClientInterface $client)
    {
        $this->settings = $settings;
        $this->client = $client;
    }

    /**
     * @param IFilters|null $filters
     * @return RedesimApiResponse[] | string[] | null
     * @throws BusinessException
     * @throws GuzzleException
     */
    public function post(IFilters $filters = null): array
    {
        $httpBody = \GuzzleHttp\json_encode($this->settings->toArray());

        if ($filters instanceof IFilters) {
            $httpBody = \GuzzleHttp\json_encode((array_merge($this->settings->toArray(), $filters->toArray())));
        }

        $request = new Request('POST', $this->settings->getUrlApi().$this->baseUrl, [], $httpBody);

        $options = $this->createHttpClientOption();
        try {
            $apiResponse = \GuzzleHttp\json_decode($this->client->send($request, $options)->getBody());
            if(isset($apiResponse->errorRest)) {
                throw new \Exception($apiResponse->errorRest->message);
            }

            if (!isset($apiResponse->listaObj)) {
                return [];
            }
            /**
             * @var RedesimApiResponse[] | string[] $response
             */
            $response = $apiResponse->listaObj;

        } catch (ClientException|RequestException $e) {

            throw new BusinessException($e->getMessage());
        }

        return $response;
    }

    /**
     * Create http client option
     *
     * @return array of http client options
     * @throws BusinessException
     */
    protected function createHttpClientOption()
    {
        $options = ['verify' => false];
        if ($this->debug) {
            $filename = 'tmp/' . date('Y-m-d') . '_redesim.log';
            $options[RequestOptions::DEBUG] = fopen($filename, 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new BusinessException('Failed to open the debug file: ' . $filename);
            }
        }

        return $options;
    }
}
