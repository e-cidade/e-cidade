<?php

namespace App\Repositories\Tributario\ISSQN\Redesim;

use App\Repositories\Tributario\ISSQN\Redesim\Contracts\IRedesimApiSettings;
use Illuminate\Database\Eloquent\Model;

class ApiRedesimSettings implements IRedesimApiSettings
{

    public int $idCliente;

    public int $idFornecedor;

    public string $chaveDeAcesso;

    protected string $urlApi;

    public function __construct(Model $model)
    {
        $this->idCliente = (int) $model->q180_client_id;
        $this->idFornecedor = (int) $model->q180_vendor_id;
        $this->chaveDeAcesso = $model->q180_access_key;
        $this->urlApi = $model->q180_url_api;
    }

    public function toArray(): array
    {
        return [
            'idCliente' => $this->idCliente,
            'idFornecedor' => $this->idFornecedor,
            'chaveDeAcesso' => $this->chaveDeAcesso,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public function getUrlApi(): string
    {
        return $this->urlApi;
    }

}
