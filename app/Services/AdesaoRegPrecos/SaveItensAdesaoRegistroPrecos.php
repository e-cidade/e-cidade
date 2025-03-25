<?php

namespace App\Services\AdesaoRegPrecos;

use App\Models\Sicom\ItensRegPreco;
use App\Repositories\Sicom\ItensRegPrecoRepository;
use Illuminate\Database\Capsule\Manager as DB;

class SaveItensAdesaoRegistroPrecos {
  private ItensRegPrecoRepository $itensRegPrecoRepository;

  public function __construct()
  {
    $this->itensRegPrecoRepository = new ItensRegPrecoRepository();
  }

  public function execute(object $data){
    if(empty($data->itens)){
      return [
        'status' => 500,
        'message' => 'Por favor informe os itens do registro de preço',
        'data' => []
      ];
    }

    DB::beginTransaction();
    try{
      foreach($data->itens as $item){
        $isExisteLote = $this->itensRegPrecoRepository->validateLoteByName($item->si07_numerolote, $item->si07_descricaolote, $item->si07_sequencialadesao);
        
        if(!empty($isExisteLote)){
          throw new \Exception("Não poderá ser cadastrado uma mesma descrição para mais de um lote de um mesmo processo de adesão.");
        }

        $oItem = new ItensRegPreco([
          'si07_item'               => $item->pc01_codmater ?: $item->si07_item,
          'si07_codunidade'         => !empty($item->si07_codunidade) ? $item->si07_codunidade : $item->m61_codmatunid,
          'si07_sequencial'         => !empty($item->si07_sequencial) ? $item->si07_sequencial : null,
          'si07_numeroitem'         => !empty($item->si07_numeroitem) ? $item->si07_numeroitem : 0,
          'si07_numerolote'         => !empty($item->si07_numerolote) ? $item->si07_numerolote : null,
          'si07_fornecedor'         => !empty($item->si07_fornecedor) ? $item->si07_fornecedor : null,
          'si07_precounitario'      => !empty($item->si07_precounitario) ? str_replace(',', '.', $item->si07_precounitario) : 0,
          'si07_descricaolote'      => !empty($item->si07_descricaolote) ? $item->si07_descricaolote : null,
          'si07_sequencialadesao'   => !empty($item->si07_sequencialadesao)? $item->si07_sequencialadesao : $item->si06_sequencial,
          'si07_quantidadeaderida'  => !empty($item->si07_quantidadeaderida) ? $item->si07_quantidadeaderida : ($item->pc11_quant ?: 0),
          'si07_quantidadelicitada' => !empty($item->si07_quantidadelicitada)? $item->si07_quantidadelicitada : 0,
          'si07_percentual'         => !empty($item->percentual)? $item->percentual : ($item->si07_percentual ?: 0),
        ]);

        
        if(empty($item->si07_sequencial)){
          $resultNumero = $this->itensRegPrecoRepository->getCountIntensReg($oItem->si07_sequencialadesao);
          if(empty($resultNumero)){
            $oItem->si07_numeroitem = 1;
          } else {
            $oItem->si07_numeroitem = $this->itensRegPrecoRepository->getNextNumeroItem($oItem->si07_sequencialadesao);
          }

          $oItem->si07_sequencial = $this->itensRegPrecoRepository->getNextVal();    
          $this->itensRegPrecoRepository->save($oItem);
        } else {
          $this->itensRegPrecoRepository->update(
            $item->si07_sequencial,
            $oItem->toArray()
          );
        }
      }
      
      DB::commit();
      return [
        'status' => 200,
        'message' => 'Informações salvas com sucesso!',
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