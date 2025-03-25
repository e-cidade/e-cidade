<?php

namespace App\Services\ParecerLicitacao;

use App\Repositories\Patrimonial\Licitacao\ParecerLicitacaoRepository;

class GetParecerLicitacaoByFilters{

  private ParecerLicitacaoRepository $parecerLicitacaoRepository;

  public function __construct(){
    $this->parecerLicitacaoRepository = new ParecerLicitacaoRepository();
  }

  public function execute(object $data){
    $aData = $this->parecerLicitacaoRepository->getAllByFilters(
      $data->l200_sequencial,
      $data->l20_codigo,
      $data->l20_numero,
      $data->l20_edital,
      $data->l200_data,
      $data->l200_tipoparecer,
      $data->l200_exercicio,
      $data->l20_objeto,
      $data->orderable,
      $data->search,
      $data->is_contass,
      $data->limit,
      $data->offset,
      $data->l20_instit
    );

    return [
      'status' => 200,
      'message' => 'Sucesso',
      'data' => [
        'total' => $aData['total'],
        'pareceres' => $aData['data']
      ]
    ];
  }
}
