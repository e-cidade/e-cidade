<?php
namespace App\Services\LicLicitaLotes;

use App\Models\Patrimonial\Licitacao\Liclicitemlote;
use App\Repositories\Patrimonial\Licitacao\LicLicitaLotesRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitemLoteRepository;
use Illuminate\Database\Capsule\Manager as DB;

class InsertLicLicitaLotesService{
    private LicLicitaLotesRepository $liclicitalotesRepository;
    private LiclicitemLoteRepository $licilicitemloteRepository;


    public function __construct()
    {
        $this->liclicitalotesRepository = new LicLicitaLotesRepository();
        $this->licilicitemloteRepository = new LiclicitemLoteRepository();
    }


    public function execute(object $data){
        DB::beginTransaction();
        try{
            $lotes = [];
            foreach ($data->itens as &$value) {
                if(empty($value->lote_id)){
                    continue;
                }

                if(empty($lotes[$value->lote_id])){
                    $oLote = $this->liclicitalotesRepository->getLoteByCodigo($value->lote_id)->toArray();
                    if(empty($oLote)){
                        continue;
                    }

                    $lotes[$value->lote_id] = $oLote;
                    $oNumeroLote = $this->licilicitemloteRepository->getNumeroLote($oLote->l24_codigo)->l04_numerolote ?? null;
                    if(empty($oNumeroLote)){
                        $oNumeroLote = $this->licilicitemloteRepository->getCodigoLote();
                    }
                    $lotes[$value->lote_id]['l04_numerolote'] = $oNumeroLote;
                } else {
                    $oNumeroLote = $lotes[$value->lote_id]['l04_numerolote'];
                }

                $dataLote = [
                    'l04_liclicitem'      => $value->l21_codigo,
                    'l04_descricao'       => $lotes[$value->lote_id]['l24_pcdesc'],
                    'l04_seq'             => 0,
                    'l04_numerolote'      => $oNumeroLote,
                    'l04_codlilicitalote' => $lotes[$value->lote_id]['l24_codigo'],
                ];

                if(!empty($value->l04_codigo)){
                    $oItemLote = $this->licilicitemloteRepository->getItemByCodigo($value->l04_codigo);
                    $this->licilicitemloteRepository->update($oItemLote, $dataLote);
                    continue;
                }
                $oLiclicitemLote = $this->salvarItensLicitacaoLote((object)$dataLote, false);
                if(empty($oLiclicitemLote->l04_codigo)){
                    throw new \Exception('No foi possivel inserir o item na tabela Liclicitemlote');
                }
            }

            if(!empty($lotes)){
                foreach ($lotes as $key => $value) {
                    $this->updateSeqItensLoteByCodigoLote($key);
                }
            }

            DB::commit();
            
            $aLotesAssociados = $this->licilicitemloteRepository->validaLotesAssociados($data->l20_codigo);
            return [
                'status' => 200,
                'message' => 'Itens inseridos com sucesso!',
                'data' => [
                    'redirect' => !$aLotesAssociados->isEmpty()
                ]
            ];
        } catch(\Exception $e){
            DB::rollBack();
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    private function salvarItensLicitacaoLote ($dados, $isGeraLoteAlto = true):Liclicitemlote
    {
        $aliclicitemlote = [];
        if(empty($dados->l04_descricao)){
            if($isGeraLoteAlto){
                $l04_descricao = 'LOTE_AUTOITEM_'.$dados->pc81_codprocitem;
                $aliclicitemlote['l04_descricao'] = $l04_descricao;
            } else {
                $aliclicitemlote['l04_descricao'] = null;
            }
        } else {
            $aliclicitemlote['l04_descricao'] = $dados->l04_descricao;
        }

        if(!empty($dados->l04_codlilicitalote)){
            $aliclicitemlote['l04_codlilicitalote'] = $dados->l04_codlilicitalote;
        }

        $aliclicitemlote['l04_seq'] = $dados->l04_seq ?? null;

        if(!empty($dados->l04_numerolote)){
            $aliclicitemlote['l04_numerolote'] = $dados->l04_numerolote;
        }

        $aliclicitemlote['l04_liclicitem'] = $dados->l04_liclicitem;
        return $this->licilicitemloteRepository->insert($aliclicitemlote);
    }

    private function updateSeqItensLoteByCodigoLote(int $l24_codigo){
        DB::beginTransaction();
        try {
            $itens = $this->licilicitemloteRepository->getItemByLote($l24_codigo);
            if(!empty($itens)){
                $seq = 1;
                foreach($itens as $value){
                    $this->licilicitemloteRepository->updateSeq($value['l04_codigo'], $seq);
                    $seq++;
                }
            }

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Sequência atualizada com sucesso!',
                'data' => []
            ];
        } catch(\Exception $e){
            DB::rollBack();
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    private function updateSeqItensLote(int $l20_codigo){
        DB::beginTransaction();
        try{
            $oLotes = $this->liclicitalotesRepository->getLotesByLicLicita($l20_codigo);
            foreach($oLotes as $oLote){
                $itens = $this->licilicitemloteRepository->getItemByLote($oLote['l24_codigo']);
                if(empty($itens)){
                    continue;
                }

                $seq = 1;
                foreach($itens as $value){
                    $this->licilicitemloteRepository->updateSeq($value['l04_codigo'], $seq);
                    $seq++;
                }
            }
            DB::commit();
            return [
                'status' => 200,
                'message' => 'Itens removidos com sucesso!',
                'data' => []
            ];
        } catch(\Exception $e){
            DB::rollBack();
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }
}
