<?php

namespace App\Services\Patrimonial\compras;

use App\Models\Patrimonial\Compras\Itemprecoreferencia;
use App\Models\Patrimonial\Compras\Pcorcamitem;
use App\Models\Patrimonial\Compras\Pcprocitem;
use App\Models\Patrimonial\Compras\Solicitavinculo;
use App\Models\Patrimonial\Compras\Solicitem;
use App\Models\Patrimonial\Compras\Solicitempcmater;
use App\Models\Patrimonial\Compras\Solicitemregistropreco;
use App\Models\Patrimonial\Compras\Solicitemunid;
use App\Models\Patrimonial\Compras\Solicitemvinculo;
use App\Repositories\Patrimonial\Compras\ItemprecoreferenciaRepository;
use App\Repositories\Patrimonial\Compras\PcdotacRepository;
use App\Repositories\Patrimonial\Compras\PcorcamitemprocRepository;
use App\Repositories\Patrimonial\Compras\PcorcamitemRepository;
use App\Repositories\Patrimonial\Compras\PcorcamvalRepository;
use App\Repositories\Patrimonial\Compras\PcprocitemRepository;
use App\Repositories\Patrimonial\Compras\PcprocRepository;
use App\Repositories\Patrimonial\Compras\PrecoreferenciaRepository;
use App\Repositories\Patrimonial\Compras\SolicitavinculoRepository;
use App\Repositories\Patrimonial\Compras\SolicitempcmaterRepository;
use App\Repositories\Patrimonial\Compras\SolicitemregistroprecoRepository;
use App\Repositories\Patrimonial\Compras\SolicitemRepository;
use App\Repositories\Patrimonial\Compras\SolicitemunidRepository;
use App\Repositories\Patrimonial\Compras\SolicitemvinculoRepository;
use App\Repositories\Patrimonial\Compras\ProcessocompraloteitemRepository;
use Illuminate\Database\Capsule\Manager as DB;

class PcitenscotaService
{
    private $pcprocRepository;
    private $pcprocitemRepository;
    private $solicitemRepository;
    private $pcdotacRepository;
    private $solicitempcmaterRepository;
    private $solicitemunidRepository;
    private $solicitavinculoRepository;
    private $solicitemvinculoRepository;
    private $solicitemregistroprecoRepository;
    private $pcorcamitemprocRepository;
    private $pcorcamitemRepository;
    private $pcorcamvalRepository;
    private $itemprecoreferenciaRepository;
    private $precoreferenciaRepository;
    private $processocompraloteitemRepository;
    public function __construct()
    {
        $this->pcprocRepository = new pcprocRepository();
        $this->pcprocitemRepository = new pcprocitemRepository();
        $this->solicitemRepository = new SolicitemRepository();
        $this->solicitempcmaterRepository = new SolicitempcmaterRepository();
        $this->solicitemunidRepository = new SolicitemunidRepository();
        $this->solicitavinculoRepository = new SolicitavinculoRepository();
        $this->solicitemvinculoRepository = new SolicitemvinculoRepository();
        $this->solicitemregistroprecoRepository = new SolicitemregistroprecoRepository();
        $this->pcorcamitemprocRepository = new PcorcamitemprocRepository();
        $this->pcorcamitemRepository = new PcorcamitemRepository();
        $this->pcorcamvalRepository = new PcorcamvalRepository();
        $this->itemprecoreferenciaRepository = new ItemprecoreferenciaRepository();
        $this->precoreferenciaRepository = new PrecoreferenciaRepository();
        $this->processocompraloteitemRepository = new ProcessocompraloteitemRepository();
        $this->pcdotacRepository = new PcdotacRepository();
    }

    /**
     * @return int
     * 1 | Normal
     * 2 | Pacto
     * 7 | Contrato
     * 6 | Compilaca Registro Preco
     * 4 | Estimativa de Registro de Precos
     * 3 | Abertura de Registro de Precos
     * 5 | Registro de Precos
     * 8 | Solicitacao Unificada
     */
    public function getOriginPcproc(int $pc80_codproc): int
    {
        $rsOrigem = $this->pcprocRepository->getOrigimProcessodeCompras($pc80_codproc);
        return $rsOrigem[0]->pc10_solicitacaotipo;
    }

    public function getItensSolicitem(string $campos, int $pc81_codprocitem): object
    {
        $aSolicitem = $this->pcprocitemRepository->getSolicitemfromPcprocItem($campos, $pc81_codprocitem);
        return $aSolicitem[0];
    }
    public function getItemPrecoreferencia(int $pc80_codproc, int $pc01_codmater, bool $reservado): object
    {
        $aItem = $this->itemprecoreferenciaRepository->getPrecoMedio($pc80_codproc,$pc01_codmater,$reservado);
        return $aItem[0];
    }
    private function getOrcamento(int $pc81_codprocitem): array
    {
        return $this->pcprocitemRepository->getOrcamento($pc81_codprocitem);
    }

    public function itemCota(int $pc01_codmater, int $pc80_codproc): array
    {
        $rsitenscota = $this->solicitemRepository->getItemCotaSolicitem($pc01_codmater, $pc80_codproc);

        $itensCotaExcluir = [];
        $itensCotaUpdate = [];

        foreach ($rsitenscota as $item) {
            if ($item->pc11_reservado == true) {
                $itensCotaExcluir[] = $item;
            } else {
                $itensCotaUpdate[] = $item;
            }
        }

        if (!empty($itensCotaUpdate) && !empty($itensCotaExcluir)) {
            $itensCotaUpdate[0]->pc11_quant += $itensCotaExcluir[0]->pc11_quant;
        }

        return [
            'excluir' => array_values($itensCotaExcluir),
            'update' => array_values($itensCotaUpdate)
        ];
    }

    private function salvarExclusivo(object $oItem): bool
    {
        $oItemSolicitem = $this->getItensSolicitem("pc11_codigo,pc11_reservado,pc11_exclusivo",$oItem->pc81_codprocitem);
        $oItemSolicitem->pc11_reservado = 't';
        $oItemSolicitem->pc11_exclusivo = 't';
        return $this->solicitemRepository->update($oItemSolicitem->pc11_codigo,get_object_vars($oItemSolicitem));
    }

    private function updateSolicitem (object $solicitem): bool
    {
        $solicitemupdate = [];
        $solicitemupdate['pc11_codigo'] = $solicitem->pc11_codigo;
        $solicitemupdate['pc11_quant'] = $solicitem->pc11_quant;

        return $this->solicitemRepository->update($solicitem->pc11_codigo,$solicitemupdate);
    }

    private function updateitemprecoreferencia(object $item): bool
    {

        $oItemPrecoReferencia = $this->getItemPrecoreferencia($item->pc81_codproc,$item->pc01_codmater,false);
        $si02_vltotalprecoreferencia = $oItemPrecoReferencia->si02_vlprecoreferencia * $item->pc11_quant;

        $oItemPrecoReferencia->si02_qtditem    = $item->pc11_quant;
        $oItemPrecoReferencia->si02_vltotalprecoreferencia = $si02_vltotalprecoreferencia;

        return $this->itemprecoreferenciaRepository->update($item->si02_sequencial,get_object_vars($oItemPrecoReferencia));
    }

    private function updatePcorcamval(object $pcorcamval)
    {
        $pcorcamvalupdate = [];
        $pcorcamvalupdate['pc23_quant'] = $pcorcamval->pc23_quant;

        return $this->pcorcamvalRepository->update($pcorcamval->pc23_orcamitem,$pcorcamvalupdate);
    }

    private function excluirItemCotaExclusivo(object $oItem):  bool
    {
        $oItemSolicitem = $this->getItensSolicitem("pc11_codigo,pc11_reservado,pc11_exclusivo",$oItem->pc81_codprocitem);
        $oItemSolicitem->pc11_reservado = 'f';
        $oItemSolicitem->pc11_exclusivo = 'f';
        return $this->solicitemRepository->update($oItemSolicitem->pc11_codigo,get_object_vars($oItemSolicitem));
    }

    public function retiraQtdItemSolicitemRegistrodepreco(object $item,string $quantidade)
    {
        $aSolicitemregistropreco = [];
        $aSolicitemregistropreco['pc57_quantmax'] = $item->pc11_quant - $quantidade;

        try {
            $this->solicitemregistroprecoRepository->updateOnSolicitem($item->pc11_codigo,$aSolicitemregistropreco);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function retirarQtdItemReservado(object $oItem,object $oItemPrecoReferencia): bool
    {
        $oItemSolicitem = $this->getItensSolicitem("pc11_codigo,pc11_quant",$oItem->pc81_codprocitem);
        $oItemSolicitem->pc11_quant = $oItemSolicitem->pc11_quant - $oItem->qtdCota;

        $oPcorcamval = $this->getOrcamento($oItem->pc81_codprocitem);

        try {
            $solicitem = $this->solicitemRepository->update($oItemSolicitem->pc11_codigo,get_object_vars($oItemSolicitem));
        } catch (\Exception $e) {
            return false;
        }

        $oItemPrecoReferencia->si02_qtditem = $oItemPrecoReferencia->si02_qtditem - $oItem->qtdCota;
        $si02_vltotalprecoreferencia = $oItemPrecoReferencia->si02_vlprecoreferencia * $oItem->qtdCota;
        $oItemPrecoReferencia->si02_vltotalprecoreferencia -= $si02_vltotalprecoreferencia;

        try {
            $itemprecoreferencia = $this->itemprecoreferenciaRepository->update($oItemPrecoReferencia->si02_sequencial,get_object_vars($oItemPrecoReferencia));
        } catch (\Exception $e) {
            return false;
        }

        foreach ($oPcorcamval as $pcorcam){
            unset($pcorcam->pc22_codorc);
            $pcorcam->pc23_quant = $pcorcam->pc23_quant - $oItem->qtdCota;

            try {
                $pccorcamval = $this->pcorcamvalRepository->update($pcorcam->pc23_orcamitem,get_object_vars($pcorcam));
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;
    }

    public function salvarItemCota(object $oItem, bool $isNew, bool $isRegistroPreco, bool $isSaveItemZerado, bool $isSaveItemCompilacao): Solicitem
    {
        $campos = "solicitem.*,solicitemregistropreco.*,pc16_codmater,pc17_unid,pc17_quant,pc81_codproc";
        $oItemSolicitem = $this->getItensSolicitem($campos,$oItem->pc81_codprocitem);
        $oItemPrecoReferencia = $this->getItemPrecoreferencia($oItemSolicitem->pc81_codproc,$oItem->pc01_codmater,false);

        $aNovoItemCota = [];
        $aNovoItemCota['pc11_numero'] = $isNew ? $oItemSolicitem->pc11_numero : $oItem->pc11_numero;
        $aNovoItemCota['pc11_seq'] = $oItemSolicitem->pc11_seq + 1;
        $aNovoItemCota['pc11_quant'] = $isSaveItemZerado ? 0 : $oItem->qtdCota;
        $aNovoItemCota['pc11_vlrun'] = 0;
        $aNovoItemCota['pc11_liberado'] = $oItemSolicitem->pc11_liberado;
        $aNovoItemCota['pc11_servicoquantidade'] = $oItemSolicitem->pc11_servicoquantidade;
        $aNovoItemCota['pc11_reservado'] = 't';
        $aNovoItemCota['pc11_exclusivo'] = 'f';
        $aNovoItemCota['pc11_usuario'] = db_getsession('DB_id_usuario');
        $solicitem = $this->salvarItemSolicitem($aNovoItemCota);

        //reordenar aqui a sequencia dos itens da solicitem
        if(!$isSaveItemZerado){
         $this->atualizarSeqItensInclusao($solicitem->pc11_seq,$oItemSolicitem->pc11_numero);
        }

        $aSolicitempcmater = [];
        $aSolicitempcmater['pc16_codmater'] = $oItemSolicitem->pc16_codmater;
        $aSolicitempcmater['pc16_solicitem'] = $solicitem->pc11_codigo;

        $aDotacoes = $this->pcdotacRepository->getDotacoesItem($oItemSolicitem->pc11_codigo);

        if(count($aDotacoes) > 1){
            foreach ($aDotacoes as $Dot){
                $aPcdotac = [];
                $aPcdotac['pc13_anousu'] = $Dot->pc13_anousu;
                $aPcdotac['pc13_coddot'] = $Dot->pc13_coddot;
                $aPcdotac['pc13_codigo'] = $solicitem->pc11_codigo;
                $aPcdotac['pc13_depto'] = $Dot->pc13_depto;
                $aPcdotac['pc13_quant'] = 0;
                $aPcdotac['pc13_valor'] = 0;
                $aPcdotac['pc13_codele'] = $Dot->pc13_depto;

                $this->pcdotacRepository->insert($aPcdotac);
            }
        }else{
            foreach ($aDotacoes as $Dot) {
                $aPcdotac = [];
                $aPcdotac['pc13_anousu'] = $Dot->pc13_anousu;
                $aPcdotac['pc13_coddot'] = $Dot->pc13_coddot;
                $aPcdotac['pc13_codigo'] = $solicitem->pc11_codigo;
                $aPcdotac['pc13_depto'] = $Dot->pc13_depto;
                $aPcdotac['pc13_quant'] = $oItem->qtdCota;
                $aPcdotac['pc13_valor'] = 0;
                $aPcdotac['pc13_codele'] = $Dot->pc13_depto;
                $this->pcdotacRepository->insert($aPcdotac);
            }
        }

        try {
            $this->salvarItemSolicitempcmater($aSolicitempcmater);
        } catch (\Exception $e) {
            return false;
        }

        $aSolicitemunid = [];
        $aSolicitemunid['pc17_unid'] = $oItemSolicitem->pc17_unid;
        $aSolicitemunid['pc17_quant'] = $oItemSolicitem->pc17_quant;
        $aSolicitemunid['pc17_codigo'] = $solicitem->pc11_codigo;

        try {
            $this->salvarItemSolicitemunid($aSolicitemunid);
        } catch (\Exception $e) {
            return false;
        }

        if($isSaveItemCompilacao){
            $aSolicitemregistropreco = [];
            $aSolicitemregistropreco['pc57_solicitem']            = $solicitem->pc11_codigo;
            $aSolicitemregistropreco['pc57_quantmin']             = 1;
            $aSolicitemregistropreco['pc57_quantmax']             = $oItem->qtdCota;
            $aSolicitemregistropreco['pc57_itemorigem']           = $oItemSolicitem->pc57_itemorigem;
            $aSolicitemregistropreco['pc57_ativo']                = 't';
            $aSolicitemregistropreco['pc57_quantidadeexecedente'] = 0;
            $this->salvarSolicitemregistropreco($aSolicitemregistropreco);
            $this->retiraQtdItemSolicitemRegistrodepreco($oItemSolicitem,$oItem->qtdCota);
        }
        if($isRegistroPreco){
            $aSolicitemvinculo = [];
            $aSolicitemvinculo['pc55_solicitempai'] = $solicitem->pc11_codigo;
            $aSolicitemvinculo['pc55_solicitemfilho'] = $oItem->pc55_solicitemfilho;
            $this->salvarSolicitemvinculo($aSolicitemvinculo);
        }

        if(!$isRegistroPreco){
            $this->retirarQtdItemReservado($oItem,$oItemPrecoReferencia);

            $aPcprocitem = [];
            $aPcprocitem['pc81_codproc'] = $oItemSolicitem->pc81_codproc;
            $aPcprocitem['pc81_solicitem'] = $solicitem->pc11_codigo;
            $oPcprocitem = $this->salvarPcprocitem($aPcprocitem);

            $oOrcamentos = $this->getOrcamento($oItem->pc81_codprocitem);

            $pcorcamitem = [];
            $pcorcamitem['pc22_codorc'] = $oOrcamentos[0]->pc22_codorc;
            $oPcorcamitem = $this->salvarPcorcamitem($pcorcamitem);

            $si02_vltotalprecoreferencia = $oItemPrecoReferencia->si02_vlprecoreferencia * $oItem->qtdCota;

            $aItemPrecoreferencia = [];
            $aItemPrecoreferencia['si02_precoreferencia']       = $oItemPrecoReferencia->si02_precoreferencia;
            $aItemPrecoreferencia['si02_itemproccompra']        = $oPcorcamitem->pc22_orcamitem;
            $aItemPrecoreferencia['si02_vlprecoreferencia']     = $oItemPrecoReferencia->si02_vlprecoreferencia;
            $aItemPrecoreferencia['si02_vlpercreferencia']      = $oItemPrecoReferencia->si02_vlpercreferencia;
            $aItemPrecoreferencia['si02_coditem']               = $oItemPrecoReferencia->si02_coditem;
            $aItemPrecoreferencia['si02_qtditem']               = $oItem->qtdCota;
            $aItemPrecoreferencia['si02_codunidadeitem']        = $oItemPrecoReferencia->si02_codunidadeitem;
            $aItemPrecoreferencia['si02_reservado']             = $oItemPrecoReferencia->si02_reservado;
            $aItemPrecoreferencia['si02_tabela']                = $oItemPrecoReferencia->si02_tabela;
            $aItemPrecoreferencia['si02_taxa']                  = $oItemPrecoReferencia->si02_taxa;
            $aItemPrecoreferencia['si02_criterioadjudicacao']   = $oItemPrecoReferencia->si02_criterioadjudicacao;
            $aItemPrecoreferencia['si02_mediapercentual']       = $oItemPrecoReferencia->si02_mediapercentual;
            $aItemPrecoreferencia['si02_vltotalprecoreferencia']= $si02_vltotalprecoreferencia;
            $this->salvarItemprecoreferencia($aItemPrecoreferencia);

            $pcorcamitemproc = [];
            $pcorcamitemproc['pc31_orcamitem'] = $oPcorcamitem->pc22_orcamitem;
            $pcorcamitemproc['pc31_pcprocitem'] = $oPcprocitem->pc81_codprocitem;
            $this->salvarPcorcamitemproc($pcorcamitemproc);

            foreach ($oOrcamentos as $oOrcamento) {
                $pcorcamval = [];
                $pcorcamval['pc23_orcamforne']          = $oOrcamento->pc23_orcamforne;
                $pcorcamval['pc23_orcamitem']           = $oPcorcamitem->pc22_orcamitem;
                $pcorcamval['pc23_valor']               = $oOrcamento->pc23_valor;
                $pcorcamval['pc23_quant']               = $oItem->qtdCota;
                $pcorcamval['pc23_obs']                 = $oOrcamento->pc23_obs;
                $pcorcamval['pc23_vlrun']               = $oOrcamento->pc23_vlrun;
                $pcorcamval['pc23_validmin']            = $oOrcamento->pc23_validmin;
                $pcorcamval['pc23_percentualdesconto']  = $oOrcamento->pc23_percentualdesconto;
                $pcorcamval['pc23_perctaxadesctabela']  = $oOrcamento->pc23_perctaxadesctabela;
                $this->salvarPcorcamval($pcorcamval);
            }
        }

        return $solicitem;
    }

    private function salvarSolicitemvinculo(array $aSolicitemvinculo): Solicitemvinculo
    {
        return $this->solicitemvinculoRepository->insert($aSolicitemvinculo);
    }

    private function salvarSolicitemregistropreco(array $aSolicitemregistropreco) : Solicitemregistropreco
    {
        return $this->solicitemregistroprecoRepository->insert($aSolicitemregistropreco);
    }

    private function salvarPcorcamitemproc(array $aPcorcamitemproc)
    {
        return $this->pcorcamitemprocRepository->insert($aPcorcamitemproc);
    }

    private function salvarPcorcamval(array $pcorcamval)
    {
        return $this->pcorcamvalRepository->insert($pcorcamval);
    }

    private function salvarPcorcamitem(array $aPcorcamitem): Pcorcamitem
    {
        return $this->pcorcamitemRepository->insert($aPcorcamitem);
    }

    private function salvarItemprecoreferencia(array $aItem): Itemprecoreferencia
    {
        return $this->itemprecoreferenciaRepository->insert($aItem);
    }

    private function excluirItemCota(object $oItem, int $pc80_codproc):bool
    {

        $aItemCota = $this->itemCota($oItem->pc01_codmater, $pc80_codproc);

        foreach ($aItemCota['excluir'] as $itemExcluir) {

            try {
                $processocompraloteitem = $this->excluirProcessocompraloteitem($itemExcluir->pc81_codprocitem);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $itemprecoreferencia = $this->excluirItemprecoreferencia($itemExcluir->pc23_orcamitem);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $pcorcamval = $this->excluirPcorcamval($itemExcluir->pc23_orcamitem);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $pcorcamitemproc = $this->excluirPcorcamitemproc($itemExcluir->pc31_orcamitem);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $pcorcamitem = $this->excluirPcorcamitem($itemExcluir->pc23_orcamitem);
            } catch (\Exception $e) {
                echo $e;
                return false;
            }

            try {
                $where = "pc81_codprocitem IN ($itemExcluir->pc81_codprocitem)";
                $pcprocitem = $this->excluirPcprocitem($where);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $solicitempcmater = $this->excluirsolicitempcmater($itemExcluir->pc11_codigo);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $solicitemunid = $this->excluirsolicitemunid($itemExcluir->pc11_codigo);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $pcdotac = $this->excluirPcdotac($itemExcluir->pc11_codigo);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $solicitem = $this->excluirSolicitem($itemExcluir->pc11_codigo);
            } catch (\Exception $e) {
                return false;
            }

            $this->atualizarSeqItensExclusao($itemExcluir->pc11_seq,$itemExcluir->pc11_numero);
        }

        foreach ($aItemCota['update'] as $itemupdate){

            try {
                $updateSolicitem = $this->updateSolicitem($itemupdate);
            } catch (\Exception $e) {
                return $e;
            }

            try {
                $updateItemprecoreferencia = $this->updateitemprecoreferencia($itemupdate);
            } catch (\Exception $e) {
                return $e;
            }

            try {
                $updatePcorcamval = $this->updatePcorcamval($itemupdate);
            } catch (\Exception $e) {
                return $e;
            }

            try {
                $updateitemprecoreferencia = $this->updateitemprecoreferencia($itemupdate);
            } catch (\Exception $e) {
                return $e;
            }

        }

        return true;
    }
    private function excluirItemCotaRegistroPreco(object $itemExcluir, bool $isCompilacao):bool
    {
        foreach ($itemExcluir->orcamentos as $orcamento) {

            try {
                $pcorcamval = $this->excluirPcorcamval($orcamento->pc23_orcamitem);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $pcorcamitemproc = $this->excluirPcorcamitemproc($orcamento->pc23_orcamitem);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $processocompraloteitem = $this->excluirProcessocompraloteitem($itemExcluir->pc81_codprocitem);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $itemprecoreferencia = $this->excluirItemprecoreferencia($orcamento->pc23_orcamitem);
            } catch (\Exception $e) {
                return false;
            }

            try {
                $pcorcamitem = $this->excluirPcorcamitem($orcamento->pc23_orcamitem);
            } catch (\Exception $e) {
                echo $e;
                return false;
            }
        }

        try {
            $solicitempcmater = $this->excluirsolicitempcmater($itemExcluir->pc11_codigo);
        } catch (\Exception $e) {
            return false;
        }

        try {
            $solicitemunid = $this->excluirsolicitemunid($itemExcluir->pc11_codigo);
        } catch (\Exception $e) {
            return false;
        }

        try {
            $solicitemvinculo = $this->excluirSolicitemvinculo($itemExcluir->pc11_codigo);
        } catch (\Exception $e) {
            return false;
        }

        try {
            $solicitemregistropreco = $this->excluirSolicitemregistropreco($itemExcluir->pc11_codigo);
        } catch (\Exception $e) {
            return false;
        }

        if($isCompilacao = true){
            try {
                $where = "pc81_solicitem = $itemExcluir->pc11_codigo";
                $pcprocitem = $this->excluirPcprocitem($where);
            } catch (\Exception $e) {
                return false;
            }
        }

        try {
            $solicitem = $this->excluirSolicitem($itemExcluir->pc11_codigo);
        } catch (\Exception $e) {
            return false;
        }

        $this->atualizarSeqItensExclusao($itemExcluir->pc11_seq,$itemExcluir->pc11_numero);

        return true;
    }

    private function excluirItemCotaRegistrodePreco(array $estimativas, object $item, int $compilacao):bool
    {
        foreach ($estimativas as $estimativa) {
            $oItemdaEstimativa = $this->solicitemRepository->getItens("pc10_numero = $estimativa->pc53_solicitafilho AND pc16_codmater = $item->pc01_codmater and pc11_reservado='f'");
            $oItemdaEstimativaCota = $this->solicitemRepository->getItens("pc10_numero = $estimativa->pc53_solicitafilho AND pc16_codmater = $item->pc01_codmater and pc11_reservado='t'");
            $qtdItem = $oItemdaEstimativaCota->pc11_quant + $oItemdaEstimativa->pc11_quant;
            $isUpdate = $this->updateQtdItem($oItemdaEstimativa->pc11_codigo,$qtdItem,null);

            $aOrcamentos = $this->getOrcamento($item->pc81_codprocitem);
            $oItemdaEstimativaCota->orcamentos = $aOrcamentos;
            $oItemdaEstimativaCota->pc81_codprocitem = $item->pc81_codprocitem;

            if($isUpdate){
                $isDelete = $this->excluirItemCotaRegistroPreco($oItemdaEstimativaCota,false);
            }
        }
        return $isDelete;
    }

    private function excluirItemCotaCompilacao(object $item, int $compilacao):bool
    {
        $oItemPrecoReferencia = $this->getItemPrecoreferencia($item->pc80_codproc,$item->pc01_codmater,false);
        $oItemCompilacao = $this->solicitemRepository->getItens("pc10_numero = $compilacao AND pc16_codmater = $item->pc01_codmater and pc11_reservado='f'");
        $oItemCompilacaoCota = $this->solicitemRepository->getItens("pc10_numero = $compilacao AND pc16_codmater = $item->pc01_codmater and pc11_reservado='t'");
        $qtdItemCompilacao = $oItemCompilacao->pc11_quant + $oItemCompilacaoCota->pc11_quant;
        $isUpdate = $this->updateQtdItem($oItemCompilacao->pc11_codigo,$qtdItemCompilacao,$oItemPrecoReferencia);

        if($isUpdate){
            $isDelete = $this->excluirItemCotaRegistroPreco($oItemCompilacaoCota,true);
        }
        return $isDelete;
    }
    private function salvarItemSolicitem(array $aNovoItem): Solicitem
    {
        return $this->solicitemRepository->insert($aNovoItem);
    }

    private function excluirsolicitemunid(int $pc17_codigo):bool
    {
        return $this->solicitemunidRepository->excluir($pc17_codigo);
    }

    private function excluirSolicitem(int $pc11_codigo): bool
    {
        return $this->solicitemRepository->excluir($pc11_codigo);
    }

    private function excluirPcdotac(int $pc11_codigo): bool
    {
        return $this->pcdotacRepository->excluir($pc11_codigo);
    }

    private function excluirSolicitemregistropreco(int $pc11_codigo): bool
    {
        return $this->solicitemregistroprecoRepository->excluir($pc11_codigo);
    }

    private function excluirSolicitemvinculo(int $pc11_codigo): bool
    {
        return $this->solicitemvinculoRepository->excluir($pc11_codigo);
    }

    private function excluirsolicitempcmater(int $pc16_solicitem):bool
    {
        return $this->solicitempcmaterRepository->excluir($pc16_solicitem);
    }

    private function excluirPcprocitem(string $where):bool
    {
        return $this->pcprocitemRepository->excluir($where);
    }

    private function excluirPcorcamval(int $pc23_orcamitem)
    {
        return $this->pcorcamvalRepository->excluir($pc23_orcamitem);
    }

    private function excluirPcorcamitem(int $pc22_orcamitem)
    {
        return $this->pcorcamitemRepository->excluir($pc22_orcamitem);
    }

    private function excluirItemprecoreferencia(int $pc23_orcamitem)
    {
        return $this->itemprecoreferenciaRepository->excluir($pc23_orcamitem);
    }

    private function excluirProcessocompraloteitem(int $pc69_pcprocitem)
    {
        return $this->processocompraloteitemRepository->excluir($pc69_pcprocitem);
    }

    private function excluirPcorcamitemproc(int $pc31_pcprocitem)
    {
        return $this->pcorcamitemprocRepository->excluir($pc31_pcprocitem);
    }
    private function salvarItemSolicitempcmater(array $aSolicitempcmater): bool
    {
        return $this->solicitempcmaterRepository->insert($aSolicitempcmater);
    }

    private function salvarItemSolicitemunid(array $aSolicitemunid): Solicitemunid
    {
        return $this->solicitemunidRepository->insert($aSolicitemunid);
    }

    private function salvarPcprocitem(array $aPcprocitem): Pcprocitem
    {
        return $this->pcprocitemRepository->insert($aPcprocitem);
    }

    public function processarItensCotaNormal(array $aItens): bool
    {

        foreach ($aItens as $oItem) {
            $isSave = $oItem->exclusivo == "1"
                ? $this->salvarExclusivo($oItem)
                : $this->salvarItemCota($oItem, true, false,false,false);
            if (!$isSave) {
                return false;
            }
        }

        return true;
    }

    public function processarItensCotaRegistrodePreco(array $aItens, int $pc80_codproc):bool
    {
        $compilacao = $this->getCompilacao($pc80_codproc);
        $abertura = $this->getAbertura($compilacao);
        if ($abertura == 0) {
            return false;
        }
        $estimativas = $this->getEstimativas($abertura);
        DB::beginTransaction();
            foreach ($aItens as $item) {
                if ($item->exclusivo == "1") {
                  $this->salvarItemCotaExclusivoEstimativas($estimativas, $item);
                  $this->salvarExclusivo($item);
                } else {
                  $itemCotaNaCompilacao = $this->salvarItemCota($item, true, false, false, true);
                  $item->pc55_solicitemfilho = $itemCotaNaCompilacao->pc11_codigo;
                  $item->compilacao = $compilacao->pc11_numero;
                  $this->salvarCotaRegistropreco($estimativas, $item);
                }
            }
        DB::commit();
        return true;
    }
    private function salvarItemCotaExclusivoEstimativas(array $estimativas, object $oItem): bool
    {
        foreach ($estimativas as $estimativa) {
            $solicitem = $this->solicitemRepository->getItens("pc10_numero = $estimativa->pc53_solicitafilho AND pc16_codmater = $oItem->pc01_codmater");
            $update = $this->salvarExclusivoRegistrodePreco($solicitem);
        }
        return $update;
    }

    private function salvarExclusivoRegistrodePreco(object $oItemSolicitem): bool
    {
        $oItemSolicitem->pc11_reservado = 't';
        $oItemSolicitem->pc11_exclusivo = 't';
        unset($oItemSolicitem->pc10_numero);
        unset($oItemSolicitem->pc10_data);
        unset($oItemSolicitem->pc10_resumo);
        unset($oItemSolicitem->pc10_depto);
        unset($oItemSolicitem->pc10_log);
        unset($oItemSolicitem->pc10_instit);
        unset($oItemSolicitem->pc10_correto);
        unset($oItemSolicitem->pc10_login);
        unset($oItemSolicitem->pc10_solicitacaotipo);
        unset($oItemSolicitem->pc16_codmater);
        unset($oItemSolicitem->pc16_solicitem);
        return $this->solicitemRepository->update($oItemSolicitem->pc11_codigo,get_object_vars($oItemSolicitem));
    }

    //salvar itens nas estimativas
    private function salvarCotaRegistropreco(array $estimativas, object $oItem): bool
    {

        $qtdCota = $oItem->qtdCota;
        foreach ($estimativas as $estimativa) {
            $oItem->pc11_numero = $estimativa->pc53_solicitafilho;

            $oItemdaEstimativa = $this->solicitemRepository->getItens("pc10_numero = $estimativa->pc53_solicitafilho AND pc16_codmater = $oItem->pc01_codmater");
            $solicitem = $this->salvarItemCota($oItem, false, true, true,false);

            if (empty($solicitem->pc11_codigo)) {
                return false;
            }

            if ($qtdCota > 0) {
                $qtdCota = $this->atualizarQuantidades($oItemdaEstimativa->pc11_codigo,$solicitem->pc11_codigo,$oItemdaEstimativa->pc11_quant,$qtdCota);
            }

        }
        return true;
    }

    private function atualizarQuantidades(int $item, int $itemCota, int $quantidadeEstimativa, int $qtdCota)
    {
        if ($qtdCota > $quantidadeEstimativa) {
            $qtdCota -= $quantidadeEstimativa;
            $this->zerarQtdItem($item);
            $this->updateQtdItem($itemCota,$quantidadeEstimativa,null);
        } else {
            $quantidadeEstimativa -= $qtdCota;
            $this->updateQtdItem($item,$quantidadeEstimativa,null);
            $this->updateQtdItem($itemCota,$qtdCota,null);
            $qtdCota = 0;
        }
        return $qtdCota;
    }

    private function zerarQtdItem($pc11_codigo)
    {
        $aSolicitem = [];
        $aSolicitem['pc11_quant'] = 0;
        $this->solicitemRepository->update($pc11_codigo,$aSolicitem);

        $aSolicitemregistropreco = [];
        $aSolicitemregistropreco['pc57_quantmax'] = 0;
        $this->solicitemregistroprecoRepository->updateOnSolicitem($pc11_codigo,$aSolicitemregistropreco);
    }

    private function updateQtdItem($pc11_codigo, $qtdItem,$oItemPrecoReferencia):bool
    {
        $aSolicitem = [];
        $aSolicitem['pc11_quant'] = $qtdItem;
        try {
            $this->solicitemRepository->update($pc11_codigo,$aSolicitem);
        } catch (\Exception $e) {
            return false;
        }

        $aSolicitemregistropreco = [];
        $aSolicitemregistropreco['pc57_quantmax'] = $qtdItem;
        try {
            $this->solicitemregistroprecoRepository->updateOnSolicitem($pc11_codigo,$aSolicitemregistropreco);
        } catch (\Exception $e) {
            return false;
        }

        if($oItemPrecoReferencia){
            $oItemPrecoReferencia->si02_qtditem = $qtdItem;
            $oItemPrecoReferencia->si02_vltotalprecoreferencia = $qtdItem * $oItemPrecoReferencia->si02_vlprecoreferencia;
            try {
                $itemprecoreferencia = $this->itemprecoreferenciaRepository->update($oItemPrecoReferencia->si02_sequencial,get_object_vars($oItemPrecoReferencia));
            } catch (\Exception $e) {
                return false;
            }
        }

        return true;
    }

    public function processarExclusaoItensCotaNormal(array $aItens, int $pc80_codproc): bool
    {
        foreach ($aItens as $oItem) if ($oItem->exclusivo == "1") {
            $isDelete = $this->excluirItemCotaExclusivo($oItem);
        }else{
            $isDelete = $this->excluirItemCota($oItem, $pc80_codproc);
        }
        return $isDelete;
    }

    public function processarExclusaoItensRegistroPreco(array $aItens, int $pc80_codproc): bool
    {
        $compilacao = $this->getCompilacao($pc80_codproc);
        $abertura = $this->getAbertura($compilacao);
        $estimativas = $this->getEstimativas($abertura);
        DB::beginTransaction();
        foreach ($aItens as $item){
            if($item->exclusivo == "1"){
                $this->excluirItemCotaExclusivoEstimativas($estimativas,$item);
                $isDeleteCompilacao = $this->excluirItemCotaExclusivo($item);
            }else{
                $item->compilacao = $compilacao->pc11_numero;
                //delete das estimativas
                $item->pc80_codproc = $pc80_codproc;
                $isDeleteEstimativa = $this->excluirItemCotaRegistrodePreco($estimativas, $item, $compilacao);
                if($isDeleteEstimativa){
                    //delete da compilacao
                    $isDeleteCompilacao = $this->excluirItemCotaCompilacao($item, $compilacao);
                }
            }
        }
        DB::commit();
        return $isDeleteCompilacao;
    }

    private function excluirItemCotaExclusivoEstimativas(array $estimativas, object $oItem): bool
    {
        foreach ($estimativas as $estimativa) {
            $solicitem = $this->solicitemRepository->getItens("pc10_numero = $estimativa->pc53_solicitafilho AND pc16_codmater = $oItem->pc01_codmater");
            $update = $this->excluirExclusivoRegistrodePreco($solicitem);
        }
        return $update;
    }

    private function excluirExclusivoRegistrodePreco(object $oItemSolicitem): bool
    {
        $oItemSolicitem->pc11_reservado = 'f';
        $oItemSolicitem->pc11_exclusivo = 'f';
        unset($oItemSolicitem->pc10_numero);
        unset($oItemSolicitem->pc10_data);
        unset($oItemSolicitem->pc10_resumo);
        unset($oItemSolicitem->pc10_depto);
        unset($oItemSolicitem->pc10_log);
        unset($oItemSolicitem->pc10_instit);
        unset($oItemSolicitem->pc10_correto);
        unset($oItemSolicitem->pc10_login);
        unset($oItemSolicitem->pc10_solicitacaotipo);
        unset($oItemSolicitem->pc16_codmater);
        unset($oItemSolicitem->pc16_solicitem);
        return $this->solicitemRepository->update($oItemSolicitem->pc11_codigo,get_object_vars($oItemSolicitem));
    }

    private function getCompilacao(int $pc80_codproc): int
    {
        $compilacao = $this->pcprocRepository->getEstimativasOnPcproc($pc80_codproc);
        return $compilacao[0]->pc11_numero;
    }

    private function getAbertura(int $compilacao)
    {
        $abertura = $this->solicitavinculoRepository->getAbertura($compilacao);
        return $abertura[0]->pc53_solicitapai;
    }

    private function getEstimativas($abertura): array
    {
        return $this->solicitavinculoRepository->getEstimativas($abertura);
    }

    private function atualizarSeqItensInclusao(int $inicio, int $pc11_numero)
    {
        $aItens = $this->solicitemRepository->getAllItens("pc11_codigo,pc11_seq","pc11_numero = $pc11_numero AND pc11_seq >= $inicio");

        foreach ($aItens as $item){
            $item->pc11_seq = $item->pc11_seq + 1;
            $this->solicitemRepository->update($item->pc11_codigo,get_object_vars($item));
        }
    }

    private function atualizarSeqItensExclusao(int $inicio, int $pc11_numero)
    {
        $aItens = $this->solicitemRepository->getAllItens("pc11_codigo,pc11_seq","pc11_numero = $pc11_numero AND pc11_seq >= $inicio");

        foreach ($aItens as $item){
            $item->pc11_seq = $item->pc11_seq - 1;
            $this->solicitemRepository->update($item->pc11_codigo,get_object_vars($item));
        }
    }

}
