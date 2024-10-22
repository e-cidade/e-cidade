<?php
namespace App\Repositories\Contracts\Configuracoes;

interface DbConfigRepositoryInterface
{
    public function getByCodigo(int $iCodigo);
    public function getDados(int $id_usuario);
}
