<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\PcProc\GetDadosItensAdesaoService;

class GetItensAdesaoRegistroPrecos implements HandleRepositoryInterface {
  
  private GetDadosItensAdesaoService $getDadosItensAdesaoService;

  public function __construct()
  {
    $this->getDadosItensAdesaoService = new GetDadosItensAdesaoService();
  }

  public function handle(object $data)
  {
    return $this->getDadosItensAdesaoService->execute($data);
  }

}