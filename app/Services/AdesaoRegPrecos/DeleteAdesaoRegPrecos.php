<?php

namespace App\Services\AdesaoRegPrecos;

use App\Repositories\Contabilidade\CondataconfRepository;
use App\Repositories\Patrimonial\AcordoRepository;
use App\Repositories\Sicom\AdesaoRegPrecosDocumentosRepository;
use App\Repositories\Sicom\AdesaoRegPrecosRepository;
use App\Repositories\Sicom\ItensRegPrecoRepository;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteAdesaoRegPrecos {
  private AdesaoRegPrecosRepository $adesaoRegPrecosRepository;
  private CondataconfRepository $condataconfRepository;
  private ItensRegPrecoRepository $itensRegPrecoRepository;
  private AdesaoRegPrecosDocumentosRepository $adesaoRegPrecosDocumentosRepository;
  private AcordoRepository $acordoRepository;

  public function __construct()
  {
    $this->adesaoRegPrecosRepository = new AdesaoRegPrecosRepository();
    $this->condataconfRepository = new CondataconfRepository();
    $this->itensRegPrecoRepository = new ItensRegPrecoRepository();
    $this->adesaoRegPrecosDocumentosRepository = new AdesaoRegPrecosDocumentosRepository();
    $this->acordoRepository = new AcordoRepository();
  }

  public function execute(object $data){
    $oAdesao = $this->adesaoRegPrecosRepository->getAdesaoRegPrecoByCodigo($data->si06_sequencial);
    
    if($oAdesao->si06_statusenviosicom == 5){
      throw new \Exception("Não é possível excluir a adesão, pois ela já foi enviada ao SICOM.");
    }

    $oAcordo = $this->acordoRepository->getAcordosByAdesao($oAdesao->si06_sequencial);
    if(!$oAcordo->isEmpty()){
      throw new \Exception("A exclusão não pode ser realizada, pois esta adesão já está vinculada a um acordo.");
    }
    
    // if(!empty($oAdesao->si06_dataadesao)){
    //   $aDataEnceramento = $this->condataconfRepository->getEncerramentoPatrimonial($data->anousu, $data->instit);
    //   $dataEncerramentoPartrimonial = \DateTime::createFromFormat('Y-m-d', $aDataEnceramento->c99_datapat);
    //   if($dataEncerramentoPartrimonial >= $oAdesao->si06_dataadesao){
    //       throw new \Exception("O período já foi encerrado para envio do SICOM. Verifique os dados do lançamento e entre em contato com o suporte");
    //   }
    // }


    DB::beginTransaction();
    try{
      
      $this->itensRegPrecoRepository->deleteByAdesao($oAdesao->si06_sequencial);
      $this->adesaoRegPrecosDocumentosRepository->deleteByAdesao($oAdesao->si06_sequencial);
      $this->adesaoRegPrecosRepository->delete($oAdesao->si06_sequencial);

      DB::commit();
      return [
        'status' => 200,
        'message' => 'Adesão de Registro de Preço removida com sucesso!',
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