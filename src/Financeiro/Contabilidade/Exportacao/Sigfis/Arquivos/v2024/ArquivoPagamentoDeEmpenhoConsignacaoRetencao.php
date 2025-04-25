<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use BusinessException;
use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Common\Helper;
use Illuminate\Database\Capsule\Manager as DB;
use stdClass;

class ArquivoPagamentoDeEmpenhoConsignacaoRetencao extends ArquivoBase
{
    protected $sNomeArquivo = 'PagamentoDeEmpenhoConsignacaoRetencao';

    public function buscaCPFordenador($cgm){
        $sql = pg_query("SELECT z01_cgccpf FROM cgm WHERE z01_numcgm = {$cgm}");
        $resultado = pg_fetch_all($sql);
        return $resultado[0]["z01_cgccpf"];
    }
    

        public function buscaRetencoesPorEmpenho($numemp, $numnota){
        $inst = $this->instit;
        $ano = $this->iAnoUsu;
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal;


        $sql = pg_query("SELECT e60_numemp, e23_sequencial as IdentificadorRetencao, empempenho.e60_codemp as NumeroEmpenho, empempenho.e60_anousu as AnoEmpenho, orcdotacao.o58_orgao as CodigoOrgao, orcdotacao.o58_unidade as CodigoUnidadeOrcamentaria, empnota.e69_codnota as NumeroNota, k12_data as DataPagamento, e32_sequencial as Tipo, e23_valorretencao as ValorPago, cgm.z01_nome as NomeCredor, cgm.z01_cgccpf as CNPJCredor, cgmusu.z01_cgccpf as CPFResponsavel, empnota.e69_codnota as IdentificadorLiquidacao, empnota.e69_anousu as AnoLiquidacaoEmpenho, db89_db_bancos as Banco, db89_codagencia as Agencia, db83_conta as ContaBancaria from retencaoreceitas inner join retencaotiporec on e21_sequencial = e23_retencaotiporec inner join retencaotipocalc on e32_sequencial = e21_retencaotipocalc inner join retencaotiporeccgm on e48_retencaotiporec = e21_sequencial inner join retencaopagordem on e23_retencaopagordem = e20_sequencial inner join retencaoempagemov on e27_retencaoreceitas = e23_sequencial inner join empagemov on e81_codmov = e27_empagemov inner join pagordem on e50_codord = e20_pagordem inner join pagordemnota on e71_codord = e50_codord inner join empempenho on e60_numemp = e50_numemp inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join empnota on e69_codnota = e71_codnota inner join db_usuacgm on db_usuacgm.id_usuario = e50_id_usuario inner join cgm as cgmusu on cgmlogin = cgmusu.z01_numcgm inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm left join empagemovslips on k107_retencao = e23_sequencial left join slipempagemovslips on k108_empagemovslips = k107_sequencial left join slip on k17_codigo = k108_slip left join empageformacgm on e28_numcgm = empempenho.e60_numcgm left join empagetipo on e83_codtipo = empageformacgm.e28_empagetipo inner join saltes on saltes.k13_conta = coalesce(slip.k17_credito, e83_conta) inner join conplanocontabancaria on c56_reduz = saltes.k13_reduz and c56_anousu = e60_anousu inner join contabancaria on contabancaria.db83_sequencial = conplanocontabancaria.c56_contabancaria inner join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia inner join retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial inner join corgrupocorrente on k105_sequencial = e47_corgrupocorrente inner join corrente on k105_sequencial = e47_corgrupocorrente and k105_id = k12_id and k105_autent = k12_autent and k105_data = k12_data where e60_instit = {$inst} and e60_anousu = {$ano} and k12_estorn = false and k12_data between '{$di}' and '{$df}' AND e60_codemp = '{$numemp}' AND e69_numero = '{$numnota}' ");

        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function verificaDatas($numemp){
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal;
        $sql = pg_query("SELECT c53_coddoc, c70_codlan, c70_data, c53_descr, case when c127_conlancam is not null then 'Sim' else 'Não' end as dl_Lançamento_Retenção, c70_valor, c82_reduz, c60_descr, c72_complem, e69_numero as dl_Nota_Fiscal, e50_codord, e50_data, nomeinstabrev as dl_Ente from conlancamemp inner join conlancam on c70_codlan = c75_codlan inner join empempenho on c75_numemp = e60_numemp inner join conlancamordem on conlancamordem.c03_codlan = conlancam.c70_codlan left outer join conlancampag on c82_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c53_coddoc = c71_coddoc left join conlancamcompl on c72_codlan =c70_codlan left join conlancamnota on c66_codlan =c70_codlan left join conlancamord on c80_codlan =c70_codlan left join empnota on c66_codnota = e69_codnota left join conplanoreduz on c61_reduz = conlancampag.c82_reduz and c61_anousu=c70_anousu left join conplano on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=c61_anousu left join pagordem on e50_codord = c80_codord inner join conlancaminstit on c02_codlan = c70_codlan inner join db_config on c02_instit = db_config.codigo left join conlancamretencao on c127_conlancam = c70_codlan where c75_numemp = {$numemp} AND c70_data between '{$di}' AND '{$df}' AND c53_coddoc in(161, 6004)order by c75_data, c03_ordem, c75_codlan");
        $resultado = pg_fetch_all($sql);
        return $resultado;
        //in(6004, 161)
    }

    public function buscaBanco($seqempenho, $codord){
        $sql1 = pg_query("SELECT c82_reduz from conlancamemp inner join conlancam on c70_codlan = c75_codlan inner join empempenho on c75_numemp = e60_numemp inner join conlancamordem on conlancamordem.c03_codlan = conlancam.c70_codlan left outer join conlancampag on c82_codlan = c70_codlan inner join conlancamdoc on c71_codlan = c70_codlan inner join conhistdoc on c53_coddoc = c71_coddoc left join conlancamcompl on c72_codlan =c70_codlan left join conlancamnota on c66_codlan =c70_codlan left join conlancamord on c80_codlan =c70_codlan left join empnota on c66_codnota = e69_codnota left join conplanoreduz on c61_reduz = conlancampag.c82_reduz and c61_anousu=c70_anousu left join conplano on c60_codcon = conplanoreduz.c61_codcon and c60_anousu=c61_anousu left join pagordem on e50_codord = c80_codord inner join conlancaminstit on c02_codlan = c70_codlan inner join db_config on c02_instit = db_config.codigo left join conlancamretencao on c127_conlancam = c70_codlan where c75_numemp = {$seqempenho} AND e50_codord = {$codord} AND c82_reduz is not null order by c75_data, c03_ordem, c75_codlan");
        $reduzido = pg_fetch_all($sql1);
        $reduzido = $reduzido[0]["c82_reduz"];
        

        $sql = pg_query("SELECT db89_db_bancos, db89_codagencia, db83_conta, db89_digito FROM conplanocontabancaria INNER JOIN contabancaria ON c56_contabancaria = db83_sequencial INNER JOIN bancoagencia ON db83_bancoagencia = db89_sequencial WHERE c56_reduz = {$reduzido} AND c56_anousu = {$this->iAnoUsu}");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    

    

    public function gerarDados()
    {
        $ugs = $this->pegaCgmOrdenador();
        $ugs = $this->buscaCPFordenador($ugs);        

        $retencoes = $this->getRetencoes();        

        if ($retencoes->isEmpty()) {
            throw new BusinessException('Não há retenções para a competência informada.');
        }

        $EmpenhoConsignacaoRetencao = new stdClass();
        $EmpenhoConsignacaoRetencao->PagamentosDeEmpenhoConsignacoesRetencoes = [];
        
        $guardaempenho = array();
        
        foreach ($retencoes as $retencao) {            
            $pula = false;
            $verifica = $this->verificaDatas($retencao->e60_numemp);
            
            if(!$verifica){
                continue;
            }
            

            if(in_array($retencao->NumeroNota, $guardaempenho)){
                continue;
            }
            array_push($guardaempenho, $retencao->NumeroNota);

            $dados = $this->buscaRetencoesPorEmpenho($retencao->NumeroEmpenho, $retencao->NumeroNota);

            $somavalor = 0;
            foreach ($dados as $linha) {
                $somavalor += $linha['valorpago'];
                $verificadentro = $this->verificaDatas($linha["e60_numemp"]);
                $guardado = array();
                foreach ($verificadentro as $linha2) {
                    $vd = $linha2["c70_valor"];
                    array_push($guardado, $vd);
                }                
                if(!in_array($linha['valorpago'], $guardado)){                    
                    $pula = true;
                    continue;
                }else{
                    $pula = false;
                }
                if($pula){continue;}
            }
            
            
            $ConsigRet = new stdClass();
            $ConsigRet->Identificador = $retencao->IdentificadorRetencao;
            $ConsigRet->CodigoUnidadeGestora = $this->sCodigoTribunal;
            $ConsigRet->Competencia = $this->competencia;
            $ConsigRet->NumeroEmpenho = $retencao->NumeroEmpenho; //$retencao->e60_numemp;
            $ConsigRet->NumeroNotaPagamentoDeEmpenhoConsignacaoRetencao = $retencao->NumeroNota;
            $ConsigRet->DataPagamento = $retencao->DataPagamento;
            $ConsigRet->TipoConsignacaoRetencaoPaga = $this->convertTipoRetencao($retencao->Tipo);
            $ConsigRet->ValorPago = $somavalor;//$retencao->ValorPago;
            $ConsigRet->CPF = $ugs;
            $ConsigRet->NomeCredor = Helper::convertAndLimit($retencao->NomeCredor, 255);
            $ConsigRet->NaturezaCredor = (strlen($retencao->CNPJCredor) == 11) ? 2 : 1;
            $ConsigRet->CpfCnpjCredor = $retencao->CNPJCredor;
            $ConsigRet->CodigoOrgao = $retencao->CodigoOrgao;
            $ConsigRet->CodigoUnidadeOrcamentaria = $retencao->CodigoUnidadeOrcamentaria;            
            $ConsigRet->AnoEmpenho = $retencao->AnoEmpenho;
            $ConsigRet->LiquidacoesDePagamento = [];
            

            
            $idx = 1;
            
            $dadosbancarios = $this->buscaBanco($retencao->e60_numemp, $retencao->e50_codord);
            
            foreach ($dados as $linha) {                
                $verificadentro = $this->verificaDatas($linha["e60_numemp"]);
                
                $guardado = array();
                foreach ($verificadentro as $linha2) {
                    $vd = $linha2["c70_valor"];
                    array_push($guardado, $vd);
                }
                
                $Liquidacao = new stdClass();
                $Liquidacao->Identificador = $linha["identificadorliquidacao"].$idx;
                $Liquidacao->NumeroLiquidacaoEmpenho = $linha["numeronota"];
                $Liquidacao->AnoLiquidacaoEmpenho = $linha["anoempenho"];
                $Liquidacao->ValorConsignadoRetidoLiquidacao = $linha["valorpago"];
                
                $Conta = new stdClass();
                $Conta->Identificador = $linha["identificadorretencao"].$idx;
                $Conta->ValorContaPagadora = $linha["valorpago"];
                
                if(empty($dadosbancarios["db89_db_bancos"]) && empty($dadosbancarios["db89_codagencia"]) && empty($dadosbancarios["db83_conta"])){
                    $Conta->Banco = $linha["banco"];
                    $Conta->Agencia = $linha["agencia"];
                    $Conta->ContaBancaria = $linha["contabancaria"];
                }else{
                    $Conta->Banco = $dadosbancarios["db89_db_bancos"];
                    $Conta->Agencia = $dadosbancarios["db89_codagencia"];
                    $Conta->ContaBancaria = $dadosbancarios["db83_conta"];
                }
                
                
                

                $ConsigRet->LiquidacoesDePagamento[] = (object) [
                    'PagamentoDeEmpenhoConsignacaoRetencaoLiquidacaoPagamento' => $Liquidacao
                ];


                $Liquidacao->ContasPagadoras[] = (object) [
                    'PagamentoDeEmpenhoConsignacaoRetencaoLiquidacaoPagamentoContaPagadora' => $Conta
                ];
                $idx++;
            }
            
            $EmpenhoConsignacaoRetencao->PagamentosDeEmpenhoConsignacoesRetencoes[] = (object) [
                'PagamentoDeEmpenhoConsignacaoRetencao' => $ConsigRet
            ];
        }

        
        $this->aDados = $EmpenhoConsignacaoRetencao;
    }

    private function getRetencoes()
    {
        $query = DB::table('retencaoreceitas')
            ->select([
                'e23_sequencial as IdentificadorRetencao',
                'empempenho.e60_codemp as NumeroEmpenho',
                'empempenho.e60_anousu as AnoEmpenho',
                'orcdotacao.o58_orgao as CodigoOrgao',
                'orcdotacao.o58_unidade as CodigoUnidadeOrcamentaria',
                'empnota.e69_numero as NumeroNota',
                'k12_data as DataPagamento',
                'e32_sequencial as Tipo',
                'e23_valorretencao as ValorPago',
                'cgm.z01_nome as NomeCredor',
                'cgm.z01_cgccpf as CNPJCredor',
                'cgmusu.z01_cgccpf as CPFResponsavel',
                'empnota.e69_codnota as IdentificadorLiquidacao',
                'empnota.e69_anousu as AnoLiquidacaoEmpenho',
                'db89_db_bancos as Banco',
                'db89_codagencia as Agencia',
                'db83_conta as ContaBancaria',
                'e60_numemp',
                'e50_codord',
            ])
            ->join('retencaotiporec', 'e21_sequencial', 'e23_retencaotiporec')
            ->join('retencaotipocalc', 'e32_sequencial', 'e21_retencaotipocalc')
            ->join('retencaotiporeccgm', 'e48_retencaotiporec', 'e21_sequencial')
            ->join('retencaopagordem', 'e23_retencaopagordem', 'e20_sequencial')
            ->join('retencaoempagemov', 'e27_retencaoreceitas', 'e23_sequencial')
            ->join('empagemov', 'e81_codmov', 'e27_empagemov')
            ->join('pagordem', 'e50_codord', 'e20_pagordem')
            ->join('pagordemnota', 'e71_codord', 'e50_codord')
            ->join('empempenho', 'e60_numemp', 'e50_numemp')
            ->join('orcdotacao', function ($join) {
                $join->on('o58_anousu', 'e60_anousu')->on('o58_coddot', 'e60_coddot');
            })
            ->join('empnota', 'e69_codnota', 'e71_codnota')
            ->join('db_usuacgm', 'db_usuacgm.id_usuario', 'e50_id_usuario')
            ->join('cgm as cgmusu', 'cgmlogin', 'cgmusu.z01_numcgm')
            ->join('cgm', 'cgm.z01_numcgm', 'empempenho.e60_numcgm')
            ->leftJoin('empagemovslips', 'k107_retencao', 'e23_sequencial')
            ->leftJoin('slipempagemovslips', 'k108_empagemovslips', 'k107_sequencial')
            ->leftJoin('slip', 'k17_codigo', 'k108_slip')
            ->leftJoin('empageformacgm', 'e28_numcgm', 'empempenho.e60_numcgm')
            ->leftJoin('empagetipo', 'e83_codtipo', 'empageformacgm.e28_empagetipo')
            ->join('saltes', 'saltes.k13_conta', '=', DB::raw("coalesce(slip.k17_credito, e83_conta)"))
            ->join('conplanocontabancaria', function ($join) {
                $join->on('c56_reduz', 'saltes.k13_reduz')->on('c56_anousu', 'e60_anousu');
            })
            ->join('contabancaria', 'contabancaria.db83_sequencial', 'conplanocontabancaria.c56_contabancaria')
            ->join('bancoagencia', 'bancoagencia.db89_sequencial', 'contabancaria.db83_bancoagencia')
            ->join('retencaocorgrupocorrente', 'e47_retencaoreceita', 'e23_sequencial')
            ->join('corgrupocorrente', 'k105_sequencial', 'e47_corgrupocorrente')
            ->join('corrente', function ($join) {
                $join->on('k105_sequencial', 'e47_corgrupocorrente')
                    ->on('k105_id', 'k12_id')
                    ->on('k105_autent', 'k12_autent')
                    ->on('k105_data', 'k12_data');
            })
            ->where('e60_instit', $this->instit)
            ->where('e60_anousu', $this->iAnoUsu)
            ->where('k12_estorn', false)
            ->whereBetween('k12_data', [$this->dtDataInicial, $this->dtDataFinal])
            ->orderBy('e60_numemp')
            ->orderBy('e69_codnota');

        return $query->get();
    }

    private function convertTipoRetencao($tipo)
    {
        switch ($tipo) {
            // Imposto de Renda Retido na Fonte (IRRF)
            case '1':
            case '2':
                return 6;
                // Contribuições Previdenciárias
            case '3':
            case '4':
            case '7':
                return 3;
                // Imposto Sobre Serviços
            case '5':
                return 7;
                // Outras Retenções
            default:
                return 8;
        }
    }
}
