<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;

class ArquivoPagamentoEmpenho extends ArquivoBase
{
    protected $sNomeArquivo = 'PagamentoDeEmpenho';    

    public function buscaDados($codempenho, $codnota){
        $inst = $this->instit;
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal;
        $ano = $this->iAnoUsu;        
        
        $sql = pg_query("SELECT e60_numemp, e60_codemp, e60_anousu, e69_codnota, e69_numero, c70_codlan, c70_data, c70_valor, c70_anousu, c72_complem, e69_anousu, z01_nome, z01_cgccpf, db90_codban, db89_codagencia, db89_digito, db83_conta, db83_dvconta from empempenho inner join empnota on e69_numemp = e60_numemp inner join pagordemnota on e71_codnota = e69_codnota inner join conlancamemp on c75_numemp = e60_numemp inner join conlancam on c75_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conlancamcompl on c72_codlan = c70_codlan inner join conlancamord on c80_codlan = c70_codlan and c80_codord = e71_codord inner join lancamentoscontabeislog as log on codlan = c70_codlan and tipo_movimento = 1 inner join db_usuacgm on db_usuacgm.id_usuario = log.id_usuario inner join cgm as cgmusu on cgmusu.z01_numcgm = cgmlogin inner join conlancampag on c82_codlan = c70_codlan inner join contabilidade.conplanoreduz on c61_reduz = c82_reduz and c61_anousu = c82_anousu inner join contabilidade.conplanocontabancaria on c56_reduz = c61_reduz and c56_anousu = c61_anousu inner join configuracoes.contabancaria on c56_contabancaria = db83_sequencial inner join configuracoes.bancoagencia on db89_sequencial = db83_bancoagencia inner join configuracoes.db_bancos on db90_codban = db89_db_bancos where c71_coddoc in (5) and e60_anousu = {$ano} and e60_instit = {$inst} and c70_data between '{$di}' and '{$df}' AND e60_codemp = '{$codempenho}' AND e69_codnota = '{$codnota}' order by e60_numemp asc");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function buscaDados2($codempenho){
        $inst = $this->instit;
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal;
        $ano = $this->iAnoUsu;        
        
        $sql = pg_query("SELECT e60_numemp, e60_codemp, e60_anousu, e69_codnota, e69_numero, c70_codlan, c70_data, c70_valor, c70_anousu, c72_complem, e69_anousu, z01_nome, z01_cgccpf, db90_codban, db89_codagencia, db89_digito, db83_conta, db83_dvconta from empempenho inner join empnota on e69_numemp = e60_numemp inner join pagordemnota on e71_codnota = e69_codnota inner join conlancamemp on c75_numemp = e60_numemp inner join conlancam on c75_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conlancamcompl on c72_codlan = c70_codlan inner join conlancamord on c80_codlan = c70_codlan and c80_codord = e71_codord inner join lancamentoscontabeislog as log on codlan = c70_codlan and tipo_movimento = 1 inner join db_usuacgm on db_usuacgm.id_usuario = log.id_usuario inner join cgm as cgmusu on cgmusu.z01_numcgm = cgmlogin inner join conlancampag on c82_codlan = c70_codlan inner join contabilidade.conplanoreduz on c61_reduz = c82_reduz and c61_anousu = c82_anousu inner join contabilidade.conplanocontabancaria on c56_reduz = c61_reduz and c56_anousu = c61_anousu inner join configuracoes.contabancaria on c56_contabancaria = db83_sequencial inner join configuracoes.bancoagencia on db89_sequencial = db83_bancoagencia inner join configuracoes.db_bancos on db90_codban = db89_db_bancos where c71_coddoc in (5) and e60_anousu = {$ano} and e60_instit = {$inst} and c70_data between '{$di}' and '{$df}' AND e60_codemp = '{$codempenho}' order by e60_numemp asc");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function verificaAnulacao($codnota, $codlan){
    $sql1 = pg_query("SELECT c80_codord, c71_coddoc, c70_codlan, c70_valor from empempenho inner join empnota on e69_numemp = e60_numemp inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join conlancamemp on c75_numemp = e60_numemp inner join conlancam on c75_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conlancamcompl on c72_codlan = c70_codlan inner join conlancamnota on c66_codlan = c70_codlan and c66_codnota = e69_codnota inner join lancamentoscontabeislog as log on codlan = c70_codlan and tipo_movimento = 1 inner join db_usuacgm on db_usuacgm.id_usuario = log.id_usuario inner join cgm as cgmusu on cgmusu.z01_numcgm = cgmlogin INNER JOIN conlancamord ON c80_codlan = c70_codlan where e69_codnota = {$codnota} AND c70_codlan = {$codlan} order by e60_numemp asc");
    $r1 = pg_fetch_all($sql1);
    $codord = $r1[0]["c80_codord"];

    $sql2 = pg_query("SELECT c80_codord, c71_coddoc, c70_codlan, c70_valor from empempenho inner join empnota on e69_numemp = e60_numemp inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join conlancamemp on c75_numemp = e60_numemp inner join conlancam on c75_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conlancamcompl on c72_codlan = c70_codlan inner join conlancamnota on c66_codlan = c70_codlan and c66_codnota = e69_codnota inner join lancamentoscontabeislog as log on codlan = c70_codlan and tipo_movimento = 1 inner join db_usuacgm on db_usuacgm.id_usuario = log.id_usuario inner join cgm as cgmusu on cgmusu.z01_numcgm = cgmlogin INNER JOIN conlancamord ON c80_codlan = c70_codlan where e69_codnota = {$codnota} AND c80_codord = {$codord} AND c71_coddoc = 205 order by e60_numemp asc");
    $resultado = pg_fetch_all($sql2);
    return $resultado;
}

public function buscaValores($seqemp, $codnota){
    $sql = pg_query("SELECT e69_codnota, e69_numero, e69_dtnota, e69_dtinclusao, e69_dtservidor, e70_valor, e70_vlrliq, e70_vlranu, e53_vlrpag from empnota inner join empnotaele on e70_codnota = e69_codnota left join pagordemnota on e70_codnota = e71_codnota and e71_anulado is false left join pagordemele on e71_codord = e53_codord where e69_numemp = {$seqemp} AND e69_codnota = {$codnota} order by e69_dtnota");
    $resultado = pg_fetch_all($sql);
    return $resultado[0];
}

public function retornaCodlan($seqemp, $numnota, $codord){
    $sql = pg_query("SELECT c53_tipo, e50_codord, c70_codlan, c70_data, c53_descr, case when c127_conlancam is not null then 'Sim' else 'Não' end as dl_Lançamento_Retenção, c70_valor, c82_reduz, c60_descr, c72_complem, e69_numero as dl_Nota_Fiscal, e50_codord, e50_data, nomeinstabrev as dl_Ente from conlancamemp inner join conlancam on c70_codlan = c75_codlan inner join empempenho on c75_numemp = e60_numemp inner join conlancamordem on conlancamordem.c03_codlan = conlancam.c70_codlan left outer join conlancampag on c82_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c53_coddoc = c71_coddoc left join conlancamcompl on c72_codlan =c70_codlan left join conlancamnota on c66_codlan =c70_codlan left join conlancamord on c80_codlan =c70_codlan left join empnota on c66_codnota = e69_codnota left join conplanoreduz on c61_reduz = conlancampag.c82_reduz and c61_anousu=c70_anousu left join conplano on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=c61_anousu left join pagordem on e50_codord = c80_codord inner join conlancaminstit on c02_codlan = c70_codlan inner join db_config on c02_instit = db_config.codigo left join conlancamretencao on c127_conlancam = c70_codlan where c75_numemp = {$seqemp} AND e69_numero = '{$numnota}' AND e50_codord = {$codord} order by c75_data, c03_ordem, c75_codlan");
    $resultado = pg_fetch_all($sql);
    return $resultado[0]["c70_codlan"];
    
}

    public function gerarDados()
    {
        $campos = [
            'e60_numemp',
            'e60_codemp',
            'e60_anousu',
            'e69_codnota',
            'e69_numero',
            'c70_codlan',
            'c70_data',
            'c70_valor',
            'c70_anousu',
            'c72_complem',
            'e69_anousu',
            'z01_nome',
            'z01_cgccpf',
            'db90_codban',
            'db89_codagencia',
            'db89_digito',
            'db83_conta',
            'db83_dvconta',
            'c80_codord'
        ];

        $empenhos = DB::table('empempenho')
            ->select($campos)
            ->join('empnota', 'e69_numemp', 'e60_numemp')
            ->join('pagordemnota', 'e71_codnota', 'e69_codnota')
            ->join('conlancamemp', 'c75_numemp', 'e60_numemp')
            ->join('conlancam', 'c75_codlan', 'c70_codlan')
            ->join('conlancamdoc', 'c71_codlan', 'c70_codlan')
            ->join('conlancamcompl', 'c72_codlan', 'c70_codlan')
            ->join('conlancamord', function (JoinClause $join) {
                $join->on('c80_codlan', 'c70_codlan')
                    ->on('c80_codord', 'e71_codord');
            })
            ->join('lancamentoscontabeislog AS log', function (JoinClause $join) {
                $join->on('codlan', 'c70_codlan')
                    ->where('tipo_movimento', 1);
            })
            ->join('db_usuacgm', 'db_usuacgm.id_usuario', 'log.id_usuario')
            ->join('cgm AS cgmusu', 'cgmusu.z01_numcgm', 'cgmlogin')

            // conta do pagamento
            ->join('conlancampag', 'c82_codlan', 'c70_codlan')
            ->join('contabilidade.conplanoreduz', function (JoinClause $join) {
                $join->on('c61_reduz', 'c82_reduz')
                    ->on('c61_anousu', 'c82_anousu');
            })
            ->join('contabilidade.conplanocontabancaria', function (JoinClause $join) {
                $join->on('c56_reduz', 'c61_reduz')
                    ->on('c56_anousu', 'c61_anousu');
            })
            ->join('configuracoes.contabancaria', 'c56_contabancaria', 'db83_sequencial')
            ->join('configuracoes.bancoagencia', 'db89_sequencial', 'db83_bancoagencia')
            ->join('configuracoes.db_bancos', 'db90_codban', 'db89_db_bancos')
            ->whereIn('c71_coddoc', [5])
            ->where('e60_anousu', $this->iAnoUsu)
            ->where('e60_instit', $this->instit)
            ->whereBetween('c70_data', [$this->dtDataInicial, $this->dtDataFinal])
            ->orderBy('e60_numemp')
            ->orderBy('e69_codnota')
            ->get();            

        if ($empenhos->isEmpty()) {
            throw new \Exception(sprintf(
                'Não foi encontrado nenhum registro para o arquivo da Remessa de %s',
                'Pagamento de Empenho'
            ));
        }
       
         
        $obj = new stdClass();
        $obj->PagamentosDeEmpenho = [];
        $guardaempenho = array();
        
        foreach ($empenhos as $empenho) {            
            $retcodlan = $this->retornaCodlan($empenho->e60_numemp, $empenho->e69_numero, $empenho->c80_codord);                        
            
            $chave = $empenho->e60_numemp . $empenho->e60_anousu . $empenho->e69_numero;
            if(in_array($chave, $guardaempenho)){
                continue;
            }
            array_push($guardaempenho, $chave);
            

            $novosvalores = $this->buscaDados($empenho->e60_codemp, $empenho->e69_codnota);
            
            $valorsomado = 0;
            foreach ($novosvalores as $linha) {
                $valorsomado += $linha["c70_valor"];
            }

            $agencia = $empenho->db89_codagencia;
            $conta = $empenho->db83_conta;

            
            $data = (object)[
                "Identificador" => $empenho->c70_codlan,
                "CodigoUnidadeGestora" => $this->sCodigoTribunal,
                "Competencia" => $this->competencia,
                "NumeroEmpenho" => $empenho->e60_codemp,
                "Ano" => $empenho->c70_anousu,
                "NumeroNotaPagamentoEmpenho" => $empenho->e69_numero,
                "DataPagamento" => $empenho->c70_data,
                "ValorPago" => $empenho->c70_valor,
                "CpfResponsavel" => $empenho->z01_cgccpf,
                "LiquidacoesPagamento" => []
            ];
            
            $pagamentos = array();
            $c = 0;            
            
            $guardacodnota = "";
            foreach ($novosvalores as $nv) {                
                if($nv["e69_codnota"] == $guardacodnota){continue;}
                $guardacodnota = $nv["e69_codnota"];
                $pagamento = (object)[
                    'Identificador' => $nv["c70_codlan"],
                    'ValorContaPagadora' => $nv["c70_valor"],
                    'Banco' => $nv["db90_codban"],
                    'Agencia' => $nv["db89_codagencia"],
                    'ContaBancaria' => $nv["db83_conta"]
                ];
                $pagamentos[$c] = $pagamento;
                $c++;
            }
                        
            
            $liquidacaox = array();
            for ($i=0; $i < count($pagamentos); $i++) {                
                array_push($liquidacaox, $this->liquidacao2($novosvalores[$i]["e69_codnota"], $pagamentos[$i], $retcodlan));
            }
            
            
            if(count($pagamentos) == 1){
                $valorbase = $data->ValorPago;
                $valormeio = $liquidacaox[0][0]->ValorPagamentoLiquidacao;
                if($valorbase != $valormeio){
                    $data->ValorPago = $liquidacaox[0][0]->ValorPagamentoLiquidacao;
                    $liquidacaox[0][0]->ContasPagadoras[0]->ContaPagadora->ValorContaPagadora = $data->ValorPago;
                }
            
            }else{
                $valorbase = $data->ValorPago;
                $cx = 0;
                $totalzao = 0;
                $valorpagoempenho = 0;
            
                foreach ($liquidacaox as $linhax) {
                    
                    $dadospagamento = $this->buscaValores($empenho->e60_numemp, $liquidacaox[$cx][0]->NumeroLiquidacaoEmpenho);
                    
                    $valorliquidado = ((float)$dadospagamento["e70_vlrliq"] == 0) ? (float)$dadospagamento["e70_vlranu"] : (float)$dadospagamento["e70_vlrliq"];
                    $valorpago = (float)$dadospagamento["e53_vlrpag"];
                    
                    
                    $valormeio = $liquidacaox[$cx][0]->ContasPagadoras[0]->ContaPagadora->ValorContaPagadora;                    
                    $totalzao += $valormeio;
                    
                    $valorpagoempenho += $valorliquidado;                    

                    $liquidacaox[$cx][0]->ValorPagamentoLiquidacao = $valorliquidado;
                    $liquidacaox[$cx][0]->ContasPagadoras[0]->ContaPagadora->ValorContaPagadora = $valorliquidado; //$valorpago;
                    $cx++;
                }                
                $data->ValorPago = $valorpagoempenho;
            }
            
            

            
            $liquidacao = array();
            for ($i=0; $i < count($liquidacaox); $i++) {                 
                array_push($liquidacao, $liquidacaox[$i][$i]);
            }            
            
            foreach($liquidacaox as $liquidacao){
                $data->LiquidacoesPagamento[] = (object) ['LiquidacaoPagamento' => $liquidacao];
            }

            $obj->PagamentosDeEmpenho[] = (object)['PagamentoDeEmpenho' => $data];
            
        }
        
        $this->aDados = $obj;
    }

    private function liquidacao2($codnota, $pagamento, $codlan){
        $campos = [
            'o58_orgao',
            'o58_unidade',
            'e69_codnota',
            'e69_anousu',
            'c70_codlan',
            'c70_data',
            'c70_valor',
            'c70_anousu',
        ];
        return DB::table('empempenho')
            ->select($campos)
            ->join('empnota', 'e69_numemp', 'e60_numemp')
            ->join('orcdotacao', function (JoinClause $join) {
                $join->on('o58_anousu', 'e60_anousu')
                    ->on('o58_coddot', 'e60_coddot');
            })
            ->join('conlancamemp', 'c75_numemp', 'e60_numemp')
            ->join('conlancam', 'c75_codlan', 'c70_codlan')
            ->join('conlancamdoc', 'c71_codlan', 'c70_codlan')
            ->join('conlancamcompl', 'c72_codlan', 'c70_codlan')
            ->join('conlancamnota', function (JoinClause $join) {
                $join->on('c66_codlan', 'c70_codlan')
                    ->on('c66_codnota', 'e69_codnota');
            })
            ->join('lancamentoscontabeislog AS log', function (JoinClause $join) {
                $join->on('codlan', 'c70_codlan')
                    ->where('tipo_movimento', 1);
            })
            ->join('db_usuacgm', 'db_usuacgm.id_usuario', 'log.id_usuario')
            ->join('cgm AS cgmusu', 'cgmusu.z01_numcgm', 'cgmlogin')
            ->whereIn('c71_coddoc', [3, 23, 84, 202, 204, 206, 412, 306, 310, 502, 506])
            ->where('e69_codnota', $codnota)
            ->where('c70_codlan', $codlan)
            ->orderBy('e60_numemp')            
            ->get()
            ->map(function ($nota) use ($pagamento) {
                return (object)[
                    "Identificador" => $nota->c70_codlan,
                    'NumeroLiquidacaoEmpenho' => $nota->e69_codnota,
                    'AnoLiquidacaoEmpenho' => $nota->c70_anousu,
                    'CodigoOrgao' => $nota->o58_orgao,
                    'CodigoUnidadeOrcamentaria' => $nota->o58_unidade,
                    'ValorPagamentoLiquidacao' => $nota->c70_valor,
                    'ContasPagadoras' => [
                        (object)['ContaPagadora' => $pagamento]
                    ]
                ];
            })
            ->toArray();
    }

    
}
