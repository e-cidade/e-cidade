<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\DTO;

use DateTime;

class ActivityDTO extends BaseDTO
{
    /**
     * @var string Homologação | Produção
     */
    public string $cliente;

    public string $codigoSubSubClasse;

    public string $codigoSubClasse;

    public string $codigoClasse;

    public int $id;

    public DateTime $inclusao;

    public string $descricao;

    public function __construct(array $data)
    {
        if (empty($data)) {
            return;
        }
        foreach ($data as $attribute => $value) {
            if ($attribute === 'inclusao') {
                $value = $this->formatDateBr($value);
            }
            $this->$attribute = $value;
        }
    }

    public function getCnaeEstruturalEcidade(): string
    {
        return "{$this->codigoClasse}{$this->codigoSubClasse}";
    }

    public function getDescricaoEcidade(): string
    {
        return substr($this->descricao, 0, 200);
    }
}
