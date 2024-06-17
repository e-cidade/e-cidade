<?php

namespace App\Repositories\Tributario\ISSQN\Redesim\DTO;

use stdClass;

class RedesimApiResponse
{
    /**
     * Identificador único
     * @var int
     */
    public int $id;

    /**
     * Município
     * @var string
     */
    public string $cliente;

    /**
     * @var stdClass
     */
    public stdClass $dados;

}
