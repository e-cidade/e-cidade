<?php
namespace App\Application\Patrimonial\PlanoContratacao;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Repositories\Configuracoes\DbConfigRepository;

class UnidadeCompradora implements HandleRepositoryInterface
{
    private DbConfigRepository $dbConfigRepository;

    public function __construct()
    {
        $this->dbConfigRepository = new DbConfigRepository();
    }

    public function handle($data)
    {
        return ['unidadeCompradora' => $this->dbConfigRepository->getDados($data->id_usuario)];
    }
}
