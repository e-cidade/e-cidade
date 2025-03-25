<?php

namespace App\Application\Patrimonial\AdesaoRegistroPrecos;

use App\Repositories\Contracts\HandleRepositoryInterface;
use App\Services\AdesaoRegPrecos\GetAdesaoRegPrecos;

class ListagemAdesaoRegistroPrecos implements HandleRepositoryInterface {
  
  private GetAdesaoRegPrecos $getAdesaoRegPrecos;

  public function __construct()
  {
    $this->getAdesaoRegPrecos = new GetAdesaoRegPrecos();
  }

  public function handle(object $data)
  {
    return $this->getAdesaoRegPrecos->execute((object)[
      'si06_sequencial'   => $data-> si06_sequencial ?: null,
      'si06_numeroprc'    => $data-> si06_numeroprc ?: null,
      'si06_numlicitacao' => $data-> si06_numlicitacao ?: null,
      'si06_numeroadm'    => $data-> si06_numeroadm ?: null,
      'orderable'         => $data->orderable ?? [],
      'search'            => $data->search ?? '',
      'limit'             => (!empty($data->limit) ? $data->limit : 20),
      'offset'            => (!empty($data->offset) ? ($data->offset - 1) : 0),
      'si06_instit'       => $data->instit ?? null
    ]);
  }

}