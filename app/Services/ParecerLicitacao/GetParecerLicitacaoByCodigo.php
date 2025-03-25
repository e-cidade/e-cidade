<?php

namespace App\Services\ParecerLicitacao;

use App\Repositories\Patrimonial\Licitacao\ParecerLicitacaoRepository;
use Illuminate\Database\Capsule\Manager as DB;

class GetParecerLicitacaoByCodigo{

  private ParecerLicitacaoRepository $parecerLicitacaoRepository;

  public function __construct(){
    $this->parecerLicitacaoRepository = new ParecerLicitacaoRepository();
  }

  public function execute(object $data){
    if(empty($data->l200_sequencial)){
      return [
        'status' => 400,
        'message' => 'Por favor informe o identificador do parecer',
        'data' => [
          'parecer' => []
        ]
      ];
    }

    $oParecer = $this->parecerLicitacaoRepository->getParecerLicitacaoByCodigo($data->l200_sequencial)->toArray();
    return [
      'status' => 200,
      'message' => 'Parecer carregado com sucesso!',
      'data' => [
        'parecer' => $oParecer
      ]
    ];
  }
}
