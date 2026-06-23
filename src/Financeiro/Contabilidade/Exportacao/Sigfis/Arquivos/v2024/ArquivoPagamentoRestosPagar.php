<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;

class ArquivoPagamentoRestosPagar extends ArquivoBase
{
    protected $sNomeArquivo = 'PagamentoRestosPagar';    

    public function buscaCPFordenador($cgm){
        $sql = pg_query("SELECT z01_cgccpf FROM cgm WHERE z01_numcgm = {$cgm}");
        $resultado = pg_fetch_all($sql);
        return $resultado[0]["z01_cgccpf"];
    }

    public function buscaPags($empenho, $lancamento){
        $sql = pg_query("SELECT c70_codlan as Identificador, e60_anousu as AnoEmpenho, e60_codemp as NumeroEmpenho, e69_codnota as NumeroLiquidacaoRestosPagar, c70_anousu as AnoLiquidacaoRestosPagar, o58_orgao AS CodigoOrgao, o58_unidade as CodigoUnidadeOrcamentaria, c70_valor AS ValorPagamentoLiquidacao from conlancamemp inner join conlancam on c70_codlan = c75_codlan inner join empempenho on c75_numemp = e60_numemp inner join conlancamordem on conlancamordem.c03_codlan = conlancam.c70_codlan left outer join conlancampag on c82_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c53_coddoc = c71_coddoc left join conlancamcompl on c72_codlan =c70_codlan left join conlancamnota on c66_codlan =c70_codlan left join conlancamord on c80_codlan =c70_codlan left join empnota on c66_codnota = e69_codnota left join conplanoreduz on c61_reduz = conlancampag.c82_reduz and c61_anousu=c70_anousu left join conplano on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=c61_anousu left join pagordem on e50_codord = c80_codord inner join conlancaminstit on c02_codlan = c70_codlan INNER JOIN orcdotacao ON o58_anousu = e60_anousu AND o58_coddot = e60_coddot inner join db_config on c02_instit = db_config.codigo left join conlancamretencao on c127_conlancam = c70_codlan where c75_numemp = {$empenho} AND c70_codlan = {$lancamento} AND c53_coddoc = 35 order by c75_data, c03_ordem, c75_codlan");

        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function pegaValor($codnota, $valor, $codlan){
        $sql = pg_query("SELECT c70_valor, c70_codlan, c70_data FROM empempenho inner join empnota on e69_numemp = e60_numemp inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join conlancamemp on c75_numemp = e60_numemp inner join conlancam on c75_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conlancamcompl on c72_codlan = c70_codlan inner join lancamentoscontabeislog as log on codlan = c70_codlan and tipo_movimento = 1 inner join db_usuacgm on db_usuacgm.id_usuario = log.id_usuario inner join cgm as cgmusu on cgmusu.z01_numcgm = cgmlogin where c71_coddoc in (35, 37) and e69_codnota = {$codnota} AND c70_valor = {$valor} AND c70_codlan = {$codlan}");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function buscaPorNota($numnota){
        $inst = $this->instit;
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal; 
        
        $sql = pg_query("SELECT DISTINCT e60_numemp, e60_codemp, e60_anousu, e69_codnota, e69_numero, c70_codlan, c70_data, c70_valor, c70_anousu, c72_complem, e69_anousu, z01_nome, z01_cgccpf, db90_codban, db89_codagencia, db89_digito, db83_conta, db83_dvconta, o58_orgao, c80_codord, c80_data from empresto inner join empempenho on e60_numemp = e91_numemp inner join empnota on e69_numemp = e60_numemp inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join pagordemnota on e71_codnota = e69_codnota inner join conlancamemp on c75_numemp = e60_numemp inner join conlancam on c75_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conlancamcompl on c72_codlan = c70_codlan inner join conlancamord on c80_codlan = c70_codlan and c80_codord = e71_codord inner join lancamentoscontabeislog as log on codlan = c70_codlan and tipo_movimento = 1 inner join db_usuacgm on db_usuacgm.id_usuario = log.id_usuario inner join cgm as cgmusu on cgmusu.z01_numcgm = cgmlogin inner join conlancampag on c82_codlan = c70_codlan inner join contabilidade.conplanoreduz on c61_reduz = c82_reduz and c61_anousu = c82_anousu inner join contabilidade.conplanocontabancaria on c56_reduz = c61_reduz and c56_anousu = c61_anousu inner join configuracoes.contabancaria on c56_contabancaria = db83_sequencial inner join configuracoes.bancoagencia on db89_sequencial = db83_bancoagencia inner join configuracoes.db_bancos on db90_codban = db89_db_bancos where c71_coddoc in (35, 37) and e60_instit = {$inst} and c70_data between '{$di}' and '{$df}' AND e69_numero = '{$numnota}' order by e69_numero, e60_numemp asc");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function gerarDados()
    {

        $ugs = $this->pegaCgmOrdenador();
        $ugs = $this->buscaCPFordenador($ugs);

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
            'o58_orgao',
            'c80_codord',
            'c80_data'
        ];

        $empenhos = DB::table('empresto')
            ->select($campos)
            ->join('empempenho', 'e60_numemp', 'e91_numemp')
            ->join('empnota', 'e69_numemp', 'e60_numemp')
            ->join('orcdotacao', function (JoinClause $join) {
                $join->on('o58_anousu', 'e60_anousu')
                    ->on('o58_coddot', 'e60_coddot');
            })
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
            ->whereIn('c71_coddoc', [35,37])
            //->whereIn('c71_coddoc', [35])
            ->where('e60_instit', $this->instit)
            ->whereBetween('c70_data', [$this->dtDataInicial, $this->dtDataFinal])
            ->orderBy('e69_numero')
            ->orderBy('e60_numemp')
            ->distinct()
            ->get();
            

        if ($empenhos->isEmpty()) {
            throw new \Exception(sprintf(
                'Não foi encontrado nenhum registro para o arquivo da Remessa de %s',
                'Pagamento de Empenho'
            ));
        }

        $obj = new stdClass();
        $obj->PagamentosRestosPagar = [];
        $ix = 1;
        $ixsub = 1;
        $ixsub2 = 3;
        
        $guardador = array();
        foreach ($empenhos as $empenho) {            
            

            $contanotas = $this->buscaPorNota($empenho->e69_numero);            
            $valorao = 0;
            if(count($contanotas) > 1){                
                foreach ($contanotas as $notax){
                    $valorao += $notax["c70_valor"];
                }
            }
            
            $agencia = $empenho->db89_codagencia;
            $conta = $empenho->db83_conta;

            
            $valorsomado = 0;
            foreach ($this->liquidacaosoma($empenho->e69_codnota, $pagamento, $empenho->c70_codlan) as $liquidacaox) {
                $valorsomado += $liquidacaox->ValorPagamentoLiquidacao;
            }            
            $novosvalores = $this->pegaValor($empenho->e69_codnota, $empenho->c70_valor, $empenho->c70_codlan);

            $pagamento = (object)[
                'Identificador' => $ixsub2,
                'ValorContaPagadora' => $empenho->c70_valor,
                'Banco' => $empenho->db90_codban,
                'Agencia' => $agencia,
                'ContaBancaria' => $conta
            ];


            $guardinha = $empenho->e69_numero;
            if(in_array($guardinha, $guardador)){
                continue;
            }
            array_push($guardador, $guardinha);

            if(count($contanotas) > 1){
                $data = (object)[
                    "Identificador" => $ix, //."". $empenho->c70_codlan,
                    "CodigoUnidadeGestora" => $this->sCodigoTribunal,
                    "Competencia" => $this->competencia,
                    "NumeroNotaPagamento" => $empenho->e69_numero,
                    "AnoPagamento" => $empenho->c70_anousu,
                    "DataPagamento" => $empenho->c70_data,
                    "ValorPagamento" => $valorao,
                    "CPFResponsavel" => $ugs,
                    "LiquidacoesPagamentos" => []
                ];
            }else{
                $data = (object)[
                    "Identificador" => $ix,
                    "CodigoUnidadeGestora" => $this->sCodigoTribunal,
                    "Competencia" => $this->competencia,
                    "NumeroNotaPagamento" => $empenho->e69_numero,
                    "AnoPagamento" => $empenho->c70_anousu,
                    "DataPagamento" => $empenho->c70_data,
                    "ValorPagamento" => $valorsomado,
                    "CPFResponsavel" => $ugs,
                    "LiquidacoesPagamentos" => []
                ];    
            }

            $pagamentos = array();
            $c = 0;
            
            
            foreach ($contanotas as $nv) {
                $pagamento = (object)[
                    'Identificador' => $ixsub2,
                    'ValorContaPagadora' => $nv["c70_valor"],
                    'Banco' => $empenho->db90_codban,
                    'Agencia' => $agencia,
                    'ContaBancaria' => $conta
                ];
                $pagamentos[$c] = $pagamento;
                $c++;
                $ixsub2++;
            }            
            
            $liquidacaox = array();
            for ($i=0; $i < count($pagamentos); $i++) {
                $idx = $ix . $ixsub;
                array_push($liquidacaox, $this->liquidacao3($contanotas[$i]["c70_valor"], $contanotas[$i]["e69_codnota"], $pagamentos[$i]));
                $ixsub++;
            }
            
            
            $liquidacao = array();
            for ($i = 0; $i < count($liquidacaox); $i++) {
                $liquidacao[] = (object)$liquidacaox[$i][0];
            }

            $liquidacoesPagamentos = array();
            foreach ($liquidacao as $item) {
                $liquidacoesPagamentos[] = (object)['LiquidacaoPagamento' => [$item]];
            }
            $data->LiquidacoesPagamentos = $liquidacoesPagamentos;
            
            

            $ix++;
            $obj->PagamentosRestosPagar[] = (object) ['PagamentoRestosPagar' => $data];            
        }

        $this->aDados = $obj;
        
    }

    
    private function liquidacao($codnota, $pagamento, $idx, $codlan){        
        
        $campos = [
            'o58_orgao',
            'o58_unidade',
            'e69_codnota',
            'e69_anousu',
            'c70_codlan',
            'e60_codemp',
            'e60_anousu',
            'c70_data',
            'c70_valor',
            'c70_anousu',
            'e69_anousu',
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
            ->join('lancamentoscontabeislog AS log', function (JoinClause $join) {
                $join->on('codlan', 'c70_codlan')
                    ->where('tipo_movimento', 1);
            })
            ->join('db_usuacgm', 'db_usuacgm.id_usuario', 'log.id_usuario')
            ->join('cgm AS cgmusu', 'cgmusu.z01_numcgm', 'cgmlogin')
            ->whereIn('c71_coddoc', [35,37])
            //->whereIn('c71_coddoc', [35])
            ->where('e69_codnota', $codnota)
            ->where('c70_codlan', $codlan)
            ->whereBetween('c70_data', [$this->dtDataInicial, $this->dtDataFinal])
            ->orderBy('e60_numemp')            
            ->get()
            ->map(function ($nota) use ($pagamento, $idx) {
                $identificador = $idx;               

                
                return (object)[
                    "Identificador" => $identificador,
                    "AnoEmpenho" => $nota->e60_anousu,
                    "NumeroEmpenho" => $nota->e60_codemp,
                    'NumeroLiquidacaoRestosPagar' => $nota->e69_codnota,
                    'AnoLiquidacaoRestosPagar' => $nota->e69_anousu,
                    'CodigoOrgao' => $nota->o58_orgao,
                    'CodigoUnidadeOrcamentaria' => $nota->o58_unidade,
                    'ValorPagamentoLiquidacao' => $nota->c70_valor,
                    'ContasPagadoras' => [(object)['ContaPagadora' => $pagamento]]
                ];
            })
            ->toArray();
    }



private function liquidacaosoma($codnota, $pagamento, $codlan){
        $campos = [
            'o58_orgao',
            'o58_unidade',
            'e69_codnota',
            'e69_anousu',
            'c70_codlan',
            'e60_codemp',
            'e60_anousu',
            'c70_data',
            'c70_valor',
            'c70_anousu',
            'e69_anousu',
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
            ->join('lancamentoscontabeislog AS log', function (JoinClause $join) {
                $join->on('codlan', 'c70_codlan')
                    ->where('tipo_movimento', 1);
            })
            ->join('db_usuacgm', 'db_usuacgm.id_usuario', 'log.id_usuario')
            ->join('cgm AS cgmusu', 'cgmusu.z01_numcgm', 'cgmlogin')
            ->whereIn('c71_coddoc', [35,37])
            ->where('e69_codnota', $codnota)
            ->where('c70_codlan', $codlan)
            ->whereBetween('c70_data', [$this->dtDataInicial, $this->dtDataFinal])
            ->orderBy('e60_numemp')            
            ->get()
            ->map(function ($nota) use ($pagamento) {                
                
                return (object)[
                    "Identificador" => $nota->c70_codlan,
                    "AnoEmpenho" => $nota->e60_anousu,
                    "NumeroEmpenho" => $nota->e60_codemp,
                    'NumeroLiquidacaoRestosPagar' => $nota->e69_codnota,
                    'AnoLiquidacaoRestosPagar' => $nota->e69_anousu,
                    'CodigoOrgao' => $nota->o58_orgao,
                    'CodigoUnidadeOrcamentaria' => $nota->o58_unidade,
                    'ValorPagamentoLiquidacao' => $nota->c70_valor,
                    'ContasPagadoras' => [(object)['ContaPagadora' => $pagamento]]
                ];
            })
            ->toArray();
    }


    private function liquidacao3($valor, $codnota, $pagamento){
        $campos = [
            'o58_orgao',
            'o58_unidade',
            'e69_codnota',
            'e69_anousu',
            'c70_codlan',
            'e60_codemp',
            'e60_anousu',
            'c70_data',
            'c70_valor',
            'c70_anousu',
            'e69_anousu',
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
            ->join('lancamentoscontabeislog AS log', function (JoinClause $join) {
                $join->on('codlan', 'c70_codlan')
                    ->where('tipo_movimento', 1);
            })
            ->join('db_usuacgm', 'db_usuacgm.id_usuario', 'log.id_usuario')
            ->join('cgm AS cgmusu', 'cgmusu.z01_numcgm', 'cgmlogin')
            ->whereIn('c71_coddoc', [35,37])
            //->whereIn('c71_coddoc', [35])
            ->where('e69_codnota', $codnota)
            ->where('c70_valor', $valor)
            ->whereBetween('c70_data', [$this->dtDataInicial, $this->dtDataFinal])
            ->orderBy('e60_numemp')
            ->get()
            ->map(function ($nota) use ($pagamento) {                
                
                return (object)[
                    "Identificador" => $nota->c70_codlan,
                    "AnoEmpenho" => $nota->e60_anousu,
                    "NumeroEmpenho" => $nota->e60_codemp,
                    'NumeroLiquidacaoRestosPagar' => $nota->e69_codnota,
                    'AnoLiquidacaoRestosPagar' => $nota->e69_anousu,
                    'CodigoOrgao' => $nota->o58_orgao,
                    'CodigoUnidadeOrcamentaria' => $nota->o58_unidade,
                    'ValorPagamentoLiquidacao' => $nota->c70_valor,
                    'ContasPagadoras' => [(object)['ContaPagadora' => $pagamento]]
                ];
            })
            ->toArray();
            
    }



}    

