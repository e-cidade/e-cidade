<?php

namespace App\Services\ParecerLicitacao;

use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;
use App\Repositories\Patrimonial\Licitacao\ParecerLicitacaoRepository;
use Illuminate\Database\Capsule\Manager as DB;

class RemoveParecerService{

  private ParecerLicitacaoRepository $parecerLicitacaoRepository;
  private LiclicitaRepository $liclicitaRepository;

  public function __construct(){
    $this->parecerLicitacaoRepository = new ParecerLicitacaoRepository();
    $this->liclicitaRepository = new LiclicitaRepository();
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

    DB::beginTransaction();
    try{
      $oParecer = $this->parecerLicitacaoRepository->find($data->l200_sequencial);
      if(empty($oParecer)){
        throw new \Exception("Parecer não encontrado", 1);
      }

      $oLicitacao = $this->liclicitaRepository->getLicitacao($oParecer->l200_licitacao);
      if(!in_array($oLicitacao->l20_statusenviosicom, [1]) && !$data->is_contass){
        throw new \Exception("O parecer não pode ser alterado.", 1);
      }

      $this->parecerLicitacaoRepository->delete($oParecer);

      DB::commit();
      return [
        'status' => 200,
        'message' => 'Parecer removido com sucesso',
        'data' => []
      ];
    } catch(\Throwable $e){
      DB::rollBack();
      return [
        'status' => 500,
        'message' => $e->getMessage()
      ];
    }
  }
}
