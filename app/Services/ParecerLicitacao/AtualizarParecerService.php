<?php

namespace App\Services\ParecerLicitacao;

use App\Models\Patrimonial\Licitacao\ParecerLicitacao;
use App\Repositories\Patrimonial\Licitacao\HabilitacaoFornRepository;
use App\Repositories\Patrimonial\Licitacao\HistoricoCgmRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicitaSituacaoRepository;
use App\Repositories\Patrimonial\Licitacao\ParecerLicitacaoRepository;
use App\Repositories\Patrimonial\Protocolo\CgmRepository;
use Illuminate\Database\Capsule\Manager as DB;

class AtualizarParecerService{

  private ParecerLicitacaoRepository $parecerLicitacaoRepository;
  private LiclicitaRepository $liclicitaRepository;
  private LicLicitaSituacaoRepository $licLicitaSituacaoRepository;
  private HistoricoCgmRepository $historicoCgmRepository;
  private CgmRepository $cgmRepository;
  private HabilitacaoFornRepository $habilitacaoFornRepository;

  public function __construct(){
    $this->parecerLicitacaoRepository = new ParecerLicitacaoRepository();
    $this->liclicitaRepository = new LiclicitaRepository();
    $this->licLicitaSituacaoRepository = new LicLicitaSituacaoRepository();
    $this->historicoCgmRepository = new HistoricoCgmRepository();
    $this->cgmRepository = new CgmRepository();
    $this->habilitacaoFornRepository = new HabilitacaoFornRepository();
  }

  public function execute(object $data){
    $oLicitacao = $this->liclicitaRepository->getLicitacao($data->l200_licitacao);
    $oLicSituacao = $this->licLicitaSituacaoRepository->findSituacaoParecer($oLicitacao->l20_codigo, 1);

    if(date('Y-m-d', strtotime($data->l200_data)) > date('Y-m-d')){
      throw new \Exception("A data do parecer não pode ser superior a data atual.", 1);
    }

    if(!in_array($oLicitacao->l20_statusenviosicom, [1, 2]) && !$data->is_contass){
      throw new \Exception("O parecer não pode ser alterado.", 1);
    }

    if($data->l200_tipoparecer == 3){
      if($oLicitacao->l20_licsituacao == 0){
        throw new \Exception("Usuário, é necessário efetuar o julgamento da licitação.", 1);
      }
      if($oLicitacao->l20_licsituacao == 1 && $data->l200_data < $oLicSituacao->l11_data){
        $dtDataJulgShow = date('d/m/Y', strtotime($oLicSituacao->l11_data));
        throw new \Exception("Licitação julgada em $dtDataJulgShow. A data do parecer deverá ser igual ou superior a data de julgamento.", 1);
      }
      if($oLicitacao->l20_licsituacao == 10){
        throw new \Exception("Licitação já homologada.", 1);
      }
    }

    if($data->l200_tipoparecer == 2 && $data->l200_data < $oLicitacao->l20_dataaber){
      $dtDataEmissaoShow = date('d/m/Y', strtotime($oLicitacao->l20_dataaber));
      throw new \Exception("Edital emitido em '.$dtDataEmissaoShow.'. A data do parecer do tipo (2- Jurdico-Edital) não pode ser anterior a data de emissão do edital.", 1);
    }

    $oCgmTipo = $this->historicoCgmRepository->getNumeroByNumCgm($data->l200_numcgm, 1);
    if($data->datausu < $oCgmTipo->z09_datacadastro){
      throw new \Exception("Usuário: A data de cadastro do CGM informado é superior a data do procedimento que está sendo realizado. Corrija a data de cadastro do CGM e tente novamente!", 1);
    }

    $oCgm = $this->cgmRepository->getCgm($data->l200_numcgm);
    if(mb_strlen($oCgm->z01_cgccpf) > 11){
      throw new \Exception("O CGM selecionado deverá ser de Pessoa Física.", 1);
    }

    if(in_array($oLicitacao->l20_codtipocom, [4,6]) && !empty($this->habilitacaoFornRepository->getFornecedoresByLicitacao($oLicitacao->l20_codigo))){
      throw new \Exception("Verifique os fornecedores habilitados!", 1);
    }

    $oData = new ParecerLicitacao([
      'l200_licitacao'    => $oLicitacao->l20_codigo,
      'l200_exercicio'    => !empty($data->anousu) ? $data->anousu : null,
      'l200_data'         => !empty($data->l200_data) ? date('Y-m-d', strtotime($data->l200_data)) : null,
      'l200_tipoparecer'  => !empty($data->l200_tipoparecer) ? $data->l200_tipoparecer : null,
      'l200_numcgm'       => !empty($data->l200_numcgm) ? $data->l200_numcgm : null,
      'l200_descrparecer' => !empty($data->l200_descrparecer) ? $data->l200_descrparecer : null
    ]);
    
    DB::beginTransaction();
    try{
      $oData = $this->parecerLicitacaoRepository->update($data->l200_sequencial, $oData->toArray());

      DB::commit();
      return [
        'status' => 200,
        'message' => 'Parecer atualizada com sucesso!',
        'data' => [
          'parecer' => $oData->toArray()
        ]
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
