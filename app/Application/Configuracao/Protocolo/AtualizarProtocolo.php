<?php
namespace App\Application\Configuracao\Protocolo;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\Configuracoes\ProtocoloService;
use App\Services\Patrimonial\DispensasInexigibilidades\LicLicitaService;

class AtualizarProtocolo implements HandleRepositoryInterface{

    private ProtocoloService $protocoloService;

    public function __construct()
    {
        $this->protocoloService = new ProtocoloService();
    }

    public function handle(object $data)
    {
        return $this->protocoloService->atualizarProtocolo(
            $data
        );
    }
}
