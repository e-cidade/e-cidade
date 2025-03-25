<?php

namespace App\Repositories\Configuracao;

use App\Repositories\BaseRepository;
use App\Models\Configuracao\AssinaturaDigitalAssinante;
use Illuminate\Support\Facades\DB;

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
            //->whereIn('db243_cargo', [4, 5, 6, 7])
            // ->where('configuracoes.db_usuarios.usuarioativo', '=' , true)
            ->select('configuracoes.db_usuarios.login', 'configuracoes.db_usuarios.nome', 'configuracoes.db_usuarios.email', 'protocolo.cgm.z01_cgccpf' , 'configuracoes.assinatura_digital_assinante.db243_cargo')
            ->distinct('login', 'nome', 'z01_cgccpf', 'db243_cargo')
            ->get();

        return $aAssinantes->toArray();

    }

    public function getEmpenhosAssinados($oParametros)
    {
        if (str_contains($oParametros->e60_codemp, "/")) {
            $aCodemp = explode('/', $oParametros->e60_codemp);
            $e60_codemp = $aCodemp[0];
            $e60_anousu = $aCodemp[1];
        } else {
            $e60_codemp = $oParametros->e60_codemp;
        }

        if (str_contains($oParametros->e60_codemp_fim, "/")) {
            $aCodemp_fim = explode('/', $oParametros->e60_codemp_fim);
            $e60_codemp_fim = $aCodemp_fim[0];
        } else {
            $e60_codemp_fim = $oParametros->e60_codemp_fim;
        }

        if(!$e60_anousu){
            $e60_anousu = db_getsession("DB_anousu");
        }
        $dtini_dia = $oParametros->dtini_dia;
        $dtini_mes = $oParametros->dtini_mes;
        $dtini_ano = $oParametros->dtini_ano;
        $dtfim_dia = $oParametros->dtfim_dia;
        $dtfim_mes = $oParametros->dtfim_mes;
        $dtfim_ano = $oParametros->dtfim_ano;
        $dtInicial = $oParametros->dtInicial;
        $dtFinal   = $oParametros->dtFinal;
        $aEmpenhos = DB::table('empenho.empempenho');
        $aEmpenhos->where('e60_anousu', '=', $e60_anousu);
        $aEmpenhos->where('e60_instit', '=', db_getsession("DB_instit"));

        if($e60_codemp && $e60_codemp_fim) {
            $aEmpenhos->whereBetween(DB::raw('e60_codemp::integer'), [$e60_codemp, $e60_codemp_fim]);
        } else if($e60_codemp && !$e60_codemp_fim) {
            $aEmpenhos->where(DB::raw('e60_codemp::integer'), '=',$e60_codemp);
        }
        if($dtInicial && $dtFinal) {
            $aEmpenhos->whereBetween('e60_emiss', ["$dtini_ano-$dtini_mes-$dtini_dia", "$dtfim_ano-$dtfim_mes-$dtfim_dia"]);
        } else if($dtInicial && !$dtFinal) {
            $aEmpenhos->where('e60_emiss', "$dtini_ano-$dtini_mes-$dtini_dia");
        }
        $aEmpenhos->select('empenho.empempenho.e60_id_documento_assinado');
        $aEmpenhos->orderBy(DB::raw('e60_codemp::integer'));
        return $aEmpenhos->get()->toArray();

    }

    public function getLiquidacaoesAssinadas($oParametros)
    {
        $processaCodEmp = function($codemp) {
            return str_contains($codemp, "/") ? explode('/', $codemp) : [$codemp, null];
        };
        [$e60_codemp, $e60_anousu] = $processaCodEmp($oParametros->e60_codemp_ini);
        [$e60_codemp_fim] = $processaCodEmp($oParametros->e60_codemp_fim);
               
        if ($oParametros->dtini){
            $aDataInicial = explode("X", $oParametros->dtini);
            $sDataInicial = $aDataInicial[0].'-'.$aDataInicial[1].'-'.$aDataInicial[2];
        }

        if ($oParametros->dtfim){
            $aDataFinal = explode("X", $oParametros->dtfim);
            $sDataFinal = $aDataFinal[0].'-'.$aDataFinal[1].'-'.$aDataFinal[2];
        }

        $aFornecedores = $oParametros->aFornecedores ?? [];
        $aRecursos = isset($oParametros->aRecursos) ? explode(',', $oParametros->aRecursos) : [];
        $e60_anousu ??= db_getsession("DB_anousu");

        $aLiquidacoes = DB::table('empenho.pagordem')
        ->join('empenho.pagordemnota', 'e71_codord', '=', 'e50_codord')
        ->join('empenho.empnota', 'e71_codnota', '=', 'e69_codnota')
        ->join('empenho.empempenho', 'e50_numemp', '=', 'e60_numemp')
        ->join('orcdotacao', fn($q) =>
            $q->on('o58_anousu', 'e60_anousu')
            ->on('o58_coddot', 'e60_coddot')
        )
        ->select('empenho.empnota.e69_id_documento_assinado')
        ->orderBy(DB::raw('e60_anousu, e60_codemp::bigint, e50_numliquidacao'))
        ->when(!$sDataInicial, fn($q) => 
            $q->where('e60_anousu', $e60_anousu))
        ->when($oParametros->e50_numliquidacao, fn($q) => 
            $q->where('e50_numliquidacao', $oParametros->e50_numliquidacao))
        ->when($oParametros->e50_codord_ini && !$oParametros->e50_codord_fim, fn($q) => 
            $q->where('e50_codord', $oParametros->e50_codord_ini))
        ->when($oParametros->e50_codord_ini && $oParametros->e50_codord_fim, fn($q) => 
            $q->whereBetween('e50_codord', [$oParametros->e50_codord_ini,$oParametros->e50_codord_fim]))
        ->when($oParametros->e60_numemp, fn($q) => 
            $q->where('e60_numemp', $oParametros->e60_numemp))
        ->when($e60_codemp && $e60_codemp_fim && !$oParametros->e60_numemp, fn($q) => 
            $q->whereBetween(DB::raw('e60_codemp::bigint'), [$e60_codemp, $e60_codemp_fim]))
        ->when($e60_codemp && !$e60_codemp_fim && !$oParametros->e60_numemp, fn($q) => 
            $q->where(DB::raw('e60_codemp::bigint'), $e60_codemp))
        ->when($sDataInicial && $sDataFinal, fn($q) => 
            $q->whereBetween('e50_data', [$sDataInicial, $sDataFinal]))
        ->when($sDataInicial && !$sDataFinal, fn($q) => 
            $q->where('e50_data', $sDataInicial))
        ->when(!empty($aFornecedores), fn($q) => 
            $q->whereIn('e60_numcgm', $aFornecedores))
        ->when(!empty($aRecursos), fn($q) => 
            $q->whereIn('o58_codigo', $aRecursos))
        ->get()
        ->unique()
        ->toArray();

        return $aLiquidacoes;
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

        $e81_codmov        = $oParametros->codmov;
        $e60_numemp        = $oParametros->e60_numemp;
        $dtini_dia         = $oParametros->dtini_dia;
        $dtini_mes         = $oParametros->dtini_mes;
        $dtini_ano         = $oParametros->dtini_ano;
        $dtfim_ano         = $oParametros->dtfim_ano;
        $dtfim_mes         = $oParametros->dtfim_mes;
        $dtfim_dia         = $oParametros->dtfim_dia;
        $e60_codemp_ini    = $oParametros->e60_codemp_ini;
        $e60_codemp_fim    = $oParametros->e60_codemp_fim;
        $e50_codord_ini    = $oParametros->e50_codord_ini;
        $e50_codord_fim    = $oParametros->e50_codord_fim;
        $aRecursos         = $oParametros->aRecursos == "" ? [] : explode(",", $oParametros->aRecursos);
        $aFornecedores     = $oParametros->aFornecedores;
        $e50_numliquidacao = $oParametros->e50_numliquidacao;

        $aOrdens = DB::table('empenho.empord');
        $aOrdens->join('empenho.pagordem', 'e50_codord', '=' , 'e82_codord' );
        $aOrdens->join('empenho.empempenho', 'e60_numemp', '=' , 'e50_numemp' );
        $aOrdens->join('orcamento.orcdotacao', function($join) {
            $join->on('orcamento.orcdotacao.o58_anousu', '=','empenho.empempenho.e60_anousu');
            $join->on('orcamento.orcdotacao.o58_instit', '=','empenho.empempenho.e60_instit');
            $join->on('orcamento.orcdotacao.o58_coddot', '=','empenho.empempenho.e60_coddot');
        }
        );
        $aOrdens->join('caixa.corempagemov', 'e82_codmov', '=' , 'k12_codmov' );
        $aOrdens->join('empenho.empagemov', 'e81_codmov', '=' , 'k12_codmov' );
        $aOrdens->whereNull('empenho.empagemov.e81_cancelado' );
        $aOrdens->where('empenho.empempenho.e60_instit', '=', db_getsession("DB_instit"));

        if(count($aFornecedores) > 0){
            $aOrdens->whereIn('empenho.empempenho.e60_numcgm', $aFornecedores);
        }

        if($e50_numliquidacao && $e60_codemp_ini){
            $aOrdens->where('empenho.pagordem.e50_numliquidacao', '=',$e50_numliquidacao);
        }
        if(count($aRecursos) > 0){
            $aOrdens->whereIn('orcamento.orcdotacao.o58_codigo', $aRecursos);
        }

        if($dtini_ano && $dtfim_ano && !$e81_codmov){
            $dataIni = "$dtini_ano-$dtini_mes-$dtini_dia";
            $dataFim = "$dtfim_ano-$dtfim_mes-$dtfim_dia";
            $aOrdens->whereBetween('caixa.corempagemov.k12_data', [$dataIni, $dataFim]);
        }

        if($e81_codmov){
            $aOrdens->where('e82_codmov', '=',$e81_codmov);
        }

        if($e60_numemp){
            $aOrdens->where('empenho.empempenho.e60_numemp', '=',$e60_numemp);
        }

        if($e60_codemp_ini && $e60_codemp_fim && !$e60_numemp && !$e60_codemp_ini){
            $aOrdens->where(DB::raw('(e60_codemp)::bigint'), '>=',$e60_codemp_ini);
            $aOrdens->where(DB::raw('(e60_codemp)::bigint'), '<=',$e60_codemp_fim);
        }

        if($e60_codemp_ini && !$e60_codemp_fim && !$e60_codemp_ini){
            $aOrdens->where(DB::raw('(e60_codemp)::bigint'), '=',$e60_codemp_ini);
        }

        if($e50_codord_ini && $e50_codord_fim){
            $aOrdens->where(DB::raw('(e50_codord)::bigint'), '>=',$e50_codord_ini);
            $aOrdens->where(DB::raw('(e50_codord)::bigint'), '<=',$e50_codord_fim);
        }

        if($e50_codord_ini && !$e50_codord_fim){
            $aOrdens->where(DB::raw('(e50_codord)::bigint'), '=',$e50_codord_ini);
        }

        $aOrdens->select('empenho.empord.e82_id_documento_assinado');
        $aOrdens->orderBy(DB::raw('e60_anousu, (e60_codemp)::bigint, e50_codord') );

        return $aOrdens->get()->unique()->toArray();

    }

    public function getOrdemPagamento($oParametros)
    {

        $e81_codmov        = $oParametros->codmov;
        $e60_numemp        = $oParametros->e60_numemp;
        $dtini_dia         = $oParametros->dtini_dia;
        $dtini_mes         = $oParametros->dtini_mes;
        $dtini_ano         = $oParametros->dtini_ano;
        $dtfim_ano         = $oParametros->dtfim_ano;
        $dtfim_mes         = $oParametros->dtfim_mes;
        $dtfim_dia         = $oParametros->dtfim_dia;
        $e60_codemp_ini    = $oParametros->e60_codemp_ini;
        $e60_codemp_fim    = $oParametros->e60_codemp_fim;
        $e50_codord_ini    = $oParametros->e50_codord_ini;
        $e50_codord_fim    = $oParametros->e50_codord_fim;
        $aRecursos         = $oParametros->aRecursos == "" ? [] : explode(",", $oParametros->aRecursos);
        $aFornecedores     = $oParametros->aFornecedores;
        $e50_numliquidacao = $oParametros->e50_numliquidacao;
        $aOrdens = DB::table('empenho.empord');
        $aOrdens->join('empenho.pagordem', 'e50_codord', '=' , 'e82_codord' );
        $aOrdens->join('empenho.empempenho', 'e60_numemp', '=' , 'e50_numemp' );
        $aOrdens->join('orcamento.orcdotacao', function($join) {
            $join->on('orcamento.orcdotacao.o58_anousu', '=','empenho.empempenho.e60_anousu');
            $join->on('orcamento.orcdotacao.o58_instit', '=','empenho.empempenho.e60_instit');
            $join->on('orcamento.orcdotacao.o58_coddot', '=','empenho.empempenho.e60_coddot');
        }
        );
        $aOrdens->join('caixa.corempagemov', 'e82_codmov', '=' , 'k12_codmov' );
        $aOrdens->where('empenho.empempenho.e60_instit', '=', db_getsession("DB_instit"));
        $aOrdens->orderBy(DB::raw('e60_codemp::integer'));
        $aOrdens->orderBy(DB::raw('e60_anousu'));
        $aOrdens->orderBy('e50_codord');

        if($dtini_ano && $dtfim_ano){
            $dataIni = "$dtini_ano-$dtini_mes-$dtini_dia";
            $dataFim = "$dtfim_ano-$dtfim_mes-$dtfim_dia";
            $aOrdens->whereBetween('caixa.corempagemov.k12_data', [$dataIni, $dataFim]);

        }

        if(count($aFornecedores) > 0){
            $aOrdens->whereIn('empenho.empempenho.e60_numcgm', $aFornecedores);
        }

        if(count($aRecursos) > 0){
            $aOrdens->whereIn('orcamento.orcdotacao.o58_codigo', $aRecursos);
        }

        if($e81_codmov){
            $aOrdens->where('e82_codmov', '=',$e81_codmov);
            $aOrdens->select('empenho.empord.e82_codmov');
        }

        if($e60_numemp){
            $aOrdens->where('empenho.empempenho.e60_numemp', '=',$e60_numemp);
        }

        if($e60_codemp_ini && $e60_codemp_fim && !$e60_numemp && !$e50_numliquidacao){
            $aOrdens->where(DB::raw('(e60_codemp)::bigint'), '>=',$e60_codemp_ini);
            $aOrdens->where(DB::raw('(e60_codemp)::bigint'), '<=',$e60_codemp_fim);
        }

        if($e50_numliquidacao && $e60_codemp_ini){
            $aOrdens->where('empenho.pagordem.e50_numliquidacao', '=',$e50_numliquidacao);
        }

        if($e60_codemp_ini && !$e60_codemp_fim && !$e50_numliquidacao){
            $aOrdens->where(DB::raw('(e60_codemp)::bigint'), '=',$e60_codemp_ini);
        }

        if($e50_codord_ini && $e50_codord_fim){
            $aOrdens->where(DB::raw('(e50_codord)::bigint'), '>=',$e50_codord_ini);
            $aOrdens->where(DB::raw('(e50_codord)::bigint'), '<=',$e50_codord_fim);
        }

        if($e50_codord_ini && !$e50_codord_fim){
            $aOrdens->where(DB::raw('(e50_codord)::bigint'), '=',$e50_codord_ini);
        }

        $aOrdens->select('empenho.empord.e82_codmov');
        return $aOrdens->get()->unique()->toArray();
    }

    public function getSlipAssinadas($oParametros)
    {

        $k17_codigo        = $oParametros->k17_codigo_de;
        $k17_codigo_ate    = $oParametros->k17_codigo_ate;
        $dtini    = $oParametros->dtini;
        $dtfim    = $oParametros->dtfim;
        $aOrdens = DB::table('caixa.slip');
        $aOrdens->select('caixa.slip.k17_id_documento_assinado');
        if($k17_codigo && !$k17_codigo_ate){
            $aOrdens->where('k17_codigo', '=', $k17_codigo);
            return $aOrdens->get()->toArray();
        }
        if($k17_codigo && $k17_codigo_ate){
            $aOrdens->whereBetween('k17_codigo', [$k17_codigo, $k17_codigo_ate]);
            return $aOrdens->get()->toArray();
        }
        if($dtini && $dtfim){
            $aOrdens->whereBetween('k17_data', [$dtini, $dtfim]);
            return $aOrdens->get()->toArray();
        }




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

    public function getDocumentosSemAssinatura($uuid)
    {

        $oEmpenhos = DB::table('empenho.empempenho');
        $oEmpenhos->select(DB::raw("e60_numemp::varchar as documento"), DB::raw("'emp' as tipo_doc"));
        $oEmpenhos->where('e60_id_documento_assinado', $uuid);

        $oAnulacao = DB::table('empenho.empanulado');
        $oAnulacao->select(DB::raw("e94_codanu::varchar as documento"), DB::raw("'anl' as tipo_doc"));
        $oAnulacao->where('e94_id_documento_assinado', $uuid);
        $oEmpenhos->unionAll($oAnulacao);

        $oLiquidacao = DB::table('empenho.empnota');
        $oLiquidacao->join('empenho.pagordemnota', 'e71_codnota', '=', 'e69_codnota');
        $oLiquidacao->join('empenho.pagordem', 'e50_codord', '=', 'e71_codord');
        $oLiquidacao->select(DB::raw("e50_codord::varchar as documento"), DB::raw("'lqd' as tipo_doc"));
        $oLiquidacao->where('e69_id_documento_assinado', $uuid);
        $oEmpenhos->unionAll($oLiquidacao);

        $oPagamento = DB::table('empenho.empord');
        $oPagamento->join('empenho.pagordem as pagordememp', 'pagordememp.e50_codord', '=', 'empenho.empord.e82_codord');
        $oPagamento->select(DB::raw("empord.e82_codord::varchar||'-'||empord.e82_codmov::varchar as documento"), DB::raw("'ops' as tipo_doc"));
        $oPagamento->where('empenho.empord.e82_id_documento_assinado', $uuid);
        $oEmpenhos->unionAll($oPagamento);

        $oSlip = DB::table('caixa.slip');
        $oSlip->select(DB::raw("k17_codigo::varchar as documento"), DB::raw("'slip' as tipo_doc"));
        $oSlip->where('k17_id_documento_assinado', $uuid);
        $oEmpenhos->unionAll($oSlip);

        return $oEmpenhos->first();
    }
}
