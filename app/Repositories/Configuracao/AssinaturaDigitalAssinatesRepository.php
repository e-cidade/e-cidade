<?php

namespace App\Repositories\Configuracao;

use App\Repositories\BaseRepository;
use App\Models\Configuracao\AssinaturaDigitalAssinante;
use App\Repositories\Tributario\Arrecadacao\ArDigital\Support\StringUtils;
use Illuminate\Database\Capsule\Manager as DB;

class AssinaturaDigitalAssinatesRepository extends BaseRepository
{
    public function __construct()
    {
        $this->model = new AssinaturaDigitalAssinante();
    }

    public function getAssinantesPorDotacao(int $iDotacao, int $iAnoUsu, int $iDocumento, string $dData): array
    {
        $aAssinantes = DB::table('orcamento.orcdotacao')
            ->join('configuracoes.assinatura_digital_assinante', function($join){
                $join->on('configuracoes.assinatura_digital_assinante.db243_instit', '=','orcamento.orcdotacao.o58_instit');
                $join->on('configuracoes.assinatura_digital_assinante.db243_orgao', '=','orcamento.orcdotacao.o58_orgao');
                $join->on('configuracoes.assinatura_digital_assinante.db243_unidade', '=','orcamento.orcdotacao.o58_unidade');
            })
            ->join('configuracoes.db_usuarios', 'configuracoes.assinatura_digital_assinante.db243_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
            ->join('configuracoes.db_usuacgm', 'configuracoes.db_usuacgm.id_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
            ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'configuracoes.db_usuacgm.cgmlogin')
            ->where('o58_coddot', '=', $iDotacao)
            ->where('o58_anousu', '=', $iAnoUsu)
            ->where('o58_instit', '=', db_getsession("DB_instit"))
            ->where('db243_data_inicio', '<=', $dData)
            ->where('db243_data_final', '>=', $dData)
            ->where('db243_documento', '=', $iDocumento)
           // ->where('configuracoes.db_usuarios.usuarioativo', '=' , true)
            ->select('configuracoes.db_usuarios.login', 'configuracoes.db_usuarios.nome', 'configuracoes.db_usuarios.email', 'protocolo.cgm.z01_cgccpf' , 'configuracoes.assinatura_digital_assinante.db243_cargo')
            ->distinct('login', 'nome', 'z01_cgccpf', 'db243_cargo')
            ->get();

        return $aAssinantes->toArray();

    }

    public function getAssinantesContadorGestor(int $iDocumento, string $dData): array
    {
        $aAssinantes = DB::table('configuracoes.assinatura_digital_assinante')
            ->join('configuracoes.db_usuarios', 'configuracoes.assinatura_digital_assinante.db243_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
            ->join('configuracoes.db_usuacgm', 'configuracoes.db_usuacgm.id_usuario', '=', 'configuracoes.db_usuarios.id_usuario')
            ->join('protocolo.cgm', 'protocolo.cgm.z01_numcgm', '=', 'configuracoes.db_usuacgm.cgmlogin')
            ->where('db243_instit', '=', db_getsession("DB_instit"))
            ->where('db243_data_inicio', '<=', $dData)
            ->where('db243_data_final', '>=', $dData)
            ->where('db243_documento', '=', $iDocumento)
            ->whereIn('db243_cargo', [4, 5, 6, 7])
            // ->where('configuracoes.db_usuarios.usuarioativo', '=' , true)
            ->select('configuracoes.db_usuarios.login', 'configuracoes.db_usuarios.nome', 'configuracoes.db_usuarios.email', 'protocolo.cgm.z01_cgccpf' , 'configuracoes.assinatura_digital_assinante.db243_cargo')
            ->distinct('login', 'nome', 'z01_cgccpf', 'db243_cargo')
            ->get();

        return $aAssinantes->toArray();

    }

    public function getEmpenhosAssinados($oParametros)
    {
        $e60_codemp = $oParametros->e60_codemp;
        $e60_codemp_fim = $oParametros->e60_codemp_fim;
        $dtini_dia = $oParametros->dtini_dia;
        $dtini_mes = $oParametros->dtini_mes;
        $dtini_ano = $oParametros->dtini_ano;
        $dtfim_dia = $oParametros->dtfim_dia;
        $dtfim_mes = $oParametros->dtfim_mes;
        $dtfim_ano = $oParametros->dtfim_ano;
        $dtInicial = $oParametros->dtInicial;
        $dtFinal = $oParametros->dtFinal;

        $aEmpenhos = DB::table('empenho.empempenho');
        $aEmpenhos->where('e60_anousu', '=', db_getsession("DB_anousu"));
        $aEmpenhos->where('e60_instit', '=', db_getsession("DB_instit"));

        if($e60_codemp && $e60_codemp_fim) {
            $aEmpenhos->whereBetween(DB::raw('e60_codemp::integer'), [$e60_codemp, $e60_codemp_fim]);
        }
        if($e60_codemp && !$e60_codemp_fim) {
            $aEmpenhos->where(DB::raw('e60_codemp::integer'), '=',$e60_codemp);
        }
        if($dtInicial && $dtFinal) {
            $aEmpenhos->whereBetween('e60_emiss', ["$dtini_ano-$dtini_mes-$dtini_dia", "$dtfim_ano-$dtfim_mes-$dtfim_dia"]);
        }
        $aEmpenhos->select('empenho.empempenho.e60_id_documento_assinado');
        $aEmpenhos->orderBy(DB::raw('e60_codemp::integer'));
        return $aEmpenhos->get()->toArray();

    }

    public function getLiquidacaoesAssinadas($oParametros)
    {

        $e60_codemp        = $oParametros->e60_codemp_ini;
        $e60_codemp_fim    = $oParametros->e60_codemp_fim;
        $e60_numemp        = $oParametros->e60_numemp;
        $e50_numliquidacao = $oParametros->e50_numliquidacao;
        $dtini_dia         = $oParametros->dtini_dia;
        $dtini_mes         = $oParametros->dtini_mes;
        $dtini_ano         = $oParametros->dtini_ano;
        $dtfim_dia         = $oParametros->dtfim_dia;
        $dtfim_mes         = $oParametros->dtfim_mes;
        $dtfim_ano         = $oParametros->dtfim_ano;
        $dtInicial         = $oParametros->dtini;
        $dtFinal           = $oParametros->dtfim;
        $e50_codord_ini    = $oParametros->e50_codord_ini;
        $e50_codord_fim    = $oParametros->e50_codord_fim;
        $aFornecedores     = $oParametros->aFornecedores;
        $historico         = $oParametros->historico;
        $valor_ordem       = $oParametros->valor_ordem;

        $aLiquidacoes = DB::table('empenho.empnota');
        $aLiquidacoes->join('empenho.pagordemnota', 'e71_codnota', '=', 'e69_codnota');
        $aLiquidacoes->join('empenho.empempenho', 'e69_numemp', '=', 'e60_numemp');
        $aLiquidacoes->join('empenho.pagordem', 'e50_codord', '=', 'e71_codord');
        $aLiquidacoes->where('e60_anousu', '=', db_getsession("DB_anousu"));
        $aLiquidacoes->where('e60_instit', '=', db_getsession("DB_instit"));
        $aLiquidacoes->select('empenho.empnota.e69_id_documento_assinado');
        $aLiquidacoes->distinct('empenho.empnota.e69_id_documento_assinado');

        if($e50_numliquidacao){
            $aLiquidacoes->where('e50_numliquidacao', '=', $e50_numliquidacao);
            return $aLiquidacoes->get()->toArray();
        }
        if($e50_codord_ini && !$e50_codord_fim){
            $aLiquidacoes->where('empenho.pagordemnota.e71_codord', '=', $e50_codord_ini);
            return $aLiquidacoes->get()->toArray();
        }
        if($e50_codord_ini && $e50_codord_fim){
            $aLiquidacoes->whereBetween('empenho.pagordemnota.e71_codord', [$e50_codord_ini, $e50_codord_fim]);
            return $aLiquidacoes->get()->toArray();
        }
        if($e60_numemp){
            $aLiquidacoes->where('e60_numemp', '=', $e60_numemp);
            return $aLiquidacoes->get()->toArray();
        }
        if($e60_codemp && $e60_codemp_fim && !$e60_numemp) {
            $aLiquidacoes->whereBetween(DB::raw('e60_codemp::integer'), [$e60_codemp, $e60_codemp_fim]);
            return $aLiquidacoes->get()->toArray();
        }
        if($e60_codemp && !$e60_codemp_fim && !$e60_numemp) {
            $aLiquidacoes->where(DB::raw('e60_codemp::integer'), '=',$e60_codemp);
            return $aLiquidacoes->get()->toArray();
        }
        if($dtInicial && $dtFinal) {
            $aLiquidacoes->whereBetween('e60_emiss', ["$dtini_ano-$dtini_mes-$dtini_dia", "$dtfim_ano-$dtfim_mes-$dtfim_dia"]);
            return $aLiquidacoes->get()->toArray();
        }

    }

    public function getAnulacaoEmpenhoAssinadas($oParametros)
    {

        $e94_codanu        = $oParametros->e94_codanu_ini;
        $e94_codanu_fim    = $oParametros->e94_codanu_fim;

        $aAnulacoesEmpenho = DB::table('empenho.empanulado');

        if($e94_codanu && $e94_codanu_fim) {
            $aAnulacoesEmpenho->whereBetween(DB::raw('e94_codanu::integer'), [$e94_codanu, $e94_codanu_fim]);
        }
        if($e94_codanu) {
            $aAnulacoesEmpenho->where(DB::raw('e94_codanu::integer'), '=',$e94_codanu);
        }

        $aAnulacoesEmpenho->select('empenho.empanulado.e94_id_documento_assinado');

        return $aAnulacoesEmpenho->get()->toArray();

    }

    public function getOrdemPagamentoAssinadas($oParametros)
    {

        $e81_codmov        = $oParametros->e81_codmov;
        $dtini_dia         = $oParametros->dtini_dia;
        $dtini_mes         = $oParametros->dtini_mes;
        $dtini_ano         = $oParametros->dtini_ano;
        $dtfim_ano         = $oParametros->dtfim_ano;
        $dtfim_mes         = $oParametros->dtfim_mes;
        $dtfim_dia         = $oParametros->dtfim_dia;
        $aOrdens = DB::table('empenho.empord');
        $aOrdens->join('caixa.corempagemov', 'e82_codmov', '=' , 'k12_codmov' );
        if($dtini_ano && $dtfim_ano && !$e81_codmov){
            $dataIni = "$dtini_ano-$dtini_mes-$dtini_dia";
            $dataFim = "$dtfim_ano-$dtfim_mes-$dtfim_dia";
            $aOrdens->whereBetween('caixa.corempagemov.k12_data', [$dataIni, $dataFim]);
        }
        if($e81_codmov){
            $aOrdens->where('e82_codmov', '=',$e81_codmov);
        }
        $aOrdens->select('empenho.empord.e82_id_documento_assinado');
        $aOrdens->distinct('empenho.empord.e82_id_documento_assinado');
        return $aOrdens->get()->toArray();

    }

    public function getSlipAssinadas($oParametros)
    {

        $k17_codigo        = $oParametros->k17_codigo_de;
        $aOrdens = DB::table('caixa.slip');
        $aOrdens->where('k17_codigo', '=',$k17_codigo);
        $aOrdens->select('caixa.slip.k17_id_documento_assinado');

        return $aOrdens->get()->toArray();

    }

    public function getMovimentoByCodOrd($oParametros)
    {
        $e50_codord        = $oParametros->e50_codord_ini;
        $e50_codord_fim    = $oParametros->e50_codord_fim;

        $aOrdens = DB::table('empenho.empord');

        if($e50_codord && $e50_codord_fim) {
            $aOrdens->whereBetween('e82_codord', [$e50_codord, $e50_codord_fim]);
        }
        if($e50_codord) {
            $aOrdens->where('e82_codord', '=',$e50_codord);
        }

        $aOrdens->select('empenho.empord.e82_codmov');

        return $aOrdens->get()->toArray();
    }
}
