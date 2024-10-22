<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\DTO;

use DBNumber;
use DBString;

class CompanyAdressDTO extends BaseDTO
{
    /**
     * U para Urbano, R para Rural, S para Sem regularização
     * @var string
     */
    public string $tipoCod = '';

    public string $inscricaoImobiliaria = '';

    public string $incra = '';

    public string $cidade = '';

    public string $tipoLogradouroCod = '';

    public string $numero = '';

    public string $complemento = '';

    public string $bairro = '';

    public string $cep = '';

    public string $uf = '';

    public string $pontoReferencia = '';

    public string $latitude = '';

    public string $longitude = '';

    public string $logradouro = '';

    public bool $exclusivamenteResidencial = false;

    public function __construct(array $data)
    {
        if (empty($data)) {
            return;
        }

        foreach ($data as $attribute => $value) {
            if ($attribute === 'numero') {
                $value = is_numeric($value) ? $value : 0;
            }
            $this->$attribute = $value;
        }
    }

    public function getLogradouroEcidadeFormat()
    {
        return substr($this->logradouro, 0, 40);
    }

    public function getBairroEcidadeFormat()
    {
        return substr($this->bairro, 0, 40);
    }

    public function getMunicipioEcidadeFormat(): string
    {
        return DBString::removerCaracteresEspeciais(DBString::removerAcentuacao($this->cidade));
    }

    public function getNumeroFormmated(): int
    {
        return DBNumber::brToInteger($this->numero);
    }

}
