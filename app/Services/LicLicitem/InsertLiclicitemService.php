<?php
namespace App\Services\LicLicitem;

use App\Models\Patrimonial\Licitacao\LicLicitaLotes;
use App\Repositories\Patrimonial\Compras\PcParamRepository;
use App\Repositories\Patrimonial\Compras\PcprocRepository;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Repositories\Patrimonial\Licitacao\LicLicitaLotesRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitemLoteRepository;
use App\Repositories\Patrimonial\Licitacao\ProcessoCompraLoteRepository;
use App\Services\Patrimonial\Licitacao\LiclicitemLoteService;
use App\Services\Patrimonial\Licitacao\LiclicitemService;
use Illuminate\Database\Capsule\Manager as DB;

class InsertLiclicitemService{
    private LiclicitaRepository $liclicitaRepository;
    private PcprocRepository $pcprocRepository;
    private LiclicitemService $liclicitemService;
    private LiclicitemLoteService $liclicitemLoteService;
    private ProcessoCompraLoteRepository $processocompraloteRepository;
    private LicLicitaLotesRepository $liclicitalotesRepository;
    private LicilicitemRepository $licilicitemRepository;
    private LiclicitemLoteRepository $licilicitemloteRepository;

    public function __construct()
    {
        $this->liclicitaRepository = new LiclicitaRepository();
        $this->pcprocRepository = new pcprocRepository();
        $this->liclicitemService = new LiclicitemService();
        $this->liclicitemLoteService = new LiclicitemLoteService();
        $this->processocompraloteRepository = new ProcessoCompraLoteRepository();
        $this->liclicitalotesRepository = new LicLicitaLotesRepository();
        $this->licilicitemRepository = new LicilicitemRepository();
        $this->licilicitemloteRepository = new LiclicitemLoteRepository();
    }

    public function execute(object $data){
        DB::beginTransaction();
        try{
            $oDispensa = $this->liclicitaRepository->getDispensasInexigibilidadeByCodigo($data->l20_codigo);
            if(empty($data->itens)){
                return [
                    'status' => 500,
                    'message' => 'Nenhum item foi selecionado!',
                    'data' => []
                ];
            }
            
            $aLotes = [];
            foreach($data->itens as &$value){
                $value->l20_codigo = $oDispensa->l20_codigo;

                $oLiclicitem = $this->liclicitemService->salvarItensLicitacao($value, true);

                if(empty($oLiclicitem)){
                    throw new \Exception('Não foi possivel interir o item na tabela Liclicitem');
                }

                if($oDispensa->l20_tipojulg != 3){
                    $value->l04_liclicitem = $oLiclicitem->l21_codigo;

                    $iSeqLote = null;
                    if(!empty($value->pc68_nome)){
                        if(!empty($aLotes[$value->pc68_nome])){
                            $iSeqLote = $aLotes[$value->pc68_nome];
                        } else {
                            $oLote = $this->liclicitalotesRepository->getLoteByDescricaoAndLicitacao($value->pc68_nome, $oDispensa->l20_codigo);
                            if(empty($oLote)){
                                $oData = new LicLicitaLotes([
                                    'l24_codigo'       => $this->liclicitalotesRepository->getCodigo(),
                                    'l24_codliclicita' => $oDispensa->l20_codigo,
                                    'l24_pcdesc'       => $value->pc68_nome
                                ]);
                                $oLote = $this->liclicitalotesRepository->save($oData);
                            }
                            $aLotes[$value->pc68_nome] = $oLote->l24_codigo;
                            $iSeqLote = $oLote->l24_codigo;
                        }
                    }
    
                    $value->l04_codlilicitalote = $iSeqLote;
                    if(!empty($value->pc68_nome)){
                        $value->l04_descricao = $value->pc68_nome;
                    }
                    
                    $oLiclicitemLote = $this->liclicitemLoteService->salvarItensLicitacaoLoteAutomatico($value);
                    if(empty($oLiclicitemLote->l04_codigo)){
                        throw new \Exception('No foi possivel inserir o item na tabela Liclicitemlote');
                    }

                    // if($oDispensa->l20_tipojulg == 3){
                    //     // $oLiclicitemLote = $this->liclicitemLoteService->salvarItensLicitacaoLote($value, false);
                    // } else {
                    //     $oLiclicitemLote = $this->liclicitemLoteService->salvarItensLicitacaoLoteAutomatico($value);
                    //     if(empty($oLiclicitemLote->l04_codigo)){
                    //         throw new \Exception('No foi possivel inserir o item na tabela Liclicitemlote');
                    //     }
                    // }
                }
            }

            $this->updateSeqItens($oDispensa->l20_codigo);
            $this->updateSeqItensLote($oDispensa->l20_codigo);

            // $oLotesProcessoCompra = $this->processocompraloteRepository->getLoteByProcessoCompra($data->processocompra);
            // if(!empty($oLotesProcessoCompra)){
            //     foreach($oLotesProcessoCompra as $loteprocessocompra){
            //         if(empty($loteprocessocompra->pc68_nome)){
            //             continue;
            //         }

            //         $oLote = $this->liclicitalotesRepository->getLoteByDescricaoAndLicitacao($loteprocessocompra->pc68_nome, $oDispensa->l20_codigo);
            //         if(!empty($oLote)){
            //             continue;
            //         }

            //         $oData = new LicLicitaLotes([
            //             'l24_codigo' => $this->liclicitalotesRepository->getCodigo(),
            //             'l24_codliclicita' => $oDispensa->l20_codigo,
            //             'l24_pcdesc' => $loteprocessocompra->pc68_nome
            //         ]);
            //         $this->liclicitalotesRepository->save($oData);
            //     }
            // }

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Itens salvos com sucesso!',
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

    public function updateSeqItens($l20_codigo){
        DB::beginTransaction();
        try{
            $itens = $this->licilicitemRepository->getAll($l20_codigo);
            if(!empty($itens)){
                $seq = 1;
                foreach($itens as $item){
                    $item['l21_ordem'] = $seq;
                    $this->licilicitemRepository->updateSeq($item['l21_codigo'], $seq);
                    $seq++;
                }
            }

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Itens salvos com sucesso',
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

    public function updateSeqItensLote($l20_codigo){
        DB::beginTransaction();
        try{
            $lotes = $this->licilicitemloteRepository->getItensByLiclicitam($l20_codigo);
            if(!empty($lotes)){
                $seq = 1;
                foreach($lotes as $lote){
                    if(!empty($lote['pc11_seq'])){
                        $seq = $lote['pc11_seq'];
                    }
                    $oNumeroLote = $this->licilicitemloteRepository->getCodigoLote();
                    $this->licilicitemloteRepository->updateSeqAndNumeroLote($lote['l04_codigo'], $seq, $oNumeroLote);
                    $seq++;
                }
            }

            DB::commit();
            return [
                'status' => 200,
                'message' => 'Lotes salvos com sucesso',
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
