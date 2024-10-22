<?php

namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Configuracoes\DbConfigRepository;
use App\Repositories\Contracts\HandleRepositoryInterface;

class ListagemDepartamento implements HandleRepositoryInterface{
    private DbConfigRepository $dbConfigRepository;

    public function __construct()
    {
        $this->dbConfigRepository = new DbConfigRepository();
    }

    public function handle($data)
    {
        return ['dbconfig' => $this->dbConfigRepository->getDados($data->id_usuario) ];
    }
}
