<?php
namespace App\Services\Patrimonial\compras;
use App\Repositories\Patrimonial\Compras\PcprocitemRepository;
use App\Repositories\Patrimonial\Compras\PcprocRepository;
use stdClass;

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("std/DBDate.php");

class PcprocService
{
    private $pcprocRepository;
    private $pcprocitemRepository;

    public function __construct()
    {
        $this->pcprocRepository = new pcprocRepository();
        $this->pcprocitemRepository = new pcprocitemRepository();
    }

    public function getProcessData($codproc)
    {
        $rsPcproc = $this->pcprocRepository->getDadosProcesso($codproc);

        $pcproc = array();
        $pcproc['pcproc'] = new stdClass();
        $pcproc['pcproc']->pc80_data = $this->formatDate($rsPcproc[0]->pc80_data);
        $pcproc['pcproc']->pc80_resumo = utf8_encode($rsPcproc[0]->pc80_resumo);
        $pcproc['pcproc']->usuario = utf8_encode($rsPcproc[0]->nome);

        $rsPcprocItem = $this->pcprocitemRepository->getItensLicitacao($codproc);

        $pcproc['itens'] = $this->formatItems($rsPcprocItem);

        return $pcproc;
    }

    private function formatDate($date)
    {
        return implode('/', array_reverse(explode('-', $date)));
    }

    private function formatItems($items)
    {
        $pcprocitem = array();

        foreach ($items as $item) {
            $oItem = new stdClass();
            $oItem->pc01_codmater = $item->pc01_codmater;
            $oItem->pc11_seq = $item->pc11_seq;
            $oItem->pc01_descrmater = utf8_encode($item->pc01_descrmater);
            $oItem->pc01_complmater = utf8_encode($item->pc01_complmater);
            $oItem->pc11_quant = $item->pc11_quant;
            $oItem->m61_descr = utf8_encode($item->m61_descr);
            $oItem->pc11_reservado = $item->pc11_reservado == '' ? 'false' : $item->pc11_reservado;
            $oItem->pc81_codprocitem = $item->pc81_codprocitem;
            $pcprocitem[] = $oItem;
        }
        return $pcprocitem;
    }
}
