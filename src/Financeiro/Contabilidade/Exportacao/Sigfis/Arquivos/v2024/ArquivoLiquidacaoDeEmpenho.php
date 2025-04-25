<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Xsd\XSDFactory;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;


class ArquivoLiquidacaoDeEmpenho extends ArquivoBase
{
    protected $sNomeArquivo  = 'LiquidacaoDeEmpenho';
    /**
    * Busca os dados para gerar o Arquivo de Unidade Oramentria
    */

    
    public function voltaRetencoes($codord){
        $sql = pg_query("SELECT distinct e48_cgm, tabrec.*, retencaotiporec.*, retencaoreceitas.*, e27_empagemov, e27_principal, retencaoreceitasadicionais.*, tiposerviconotafiscal.e18_descricao, retencaoreceitasprodutorrural.*, emptiposervicoobra.* from retencaoreceitas inner join retencaotiporec on retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec inner join retencaopagordem on retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem inner join tabrec on tabrec.k02_codigo = retencaotiporec.e21_receita inner join retencaotipocalc on retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc inner join pagordem on pagordem.e50_codord = retencaopagordem.e20_pagordem inner join pagordemnota on pagordem.e50_codord = pagordemnota.e71_codord inner join empnota on pagordemnota.e71_codnota = empnota.e69_codnota inner join retencaoempagemov on e23_sequencial = e27_retencaoreceitas left join empagemovslips on e27_empagemov = k107_empagemov left join slipempagemovslips on k107_sequencial = k108_empagemovslips left join retencaoreceitasadicionais on e23_sequencial = e19_retencaoreceitas left join tiposerviconotafiscal on e19_tiposerviconotafiscal = e18_sequencial left join retencaotiporeccgm on e48_retencaotiporec = retencaotiporec.e21_sequencial left join retencaoreceitasprodutorrural on e23_sequencial = e158_retencaoreceitas left join emptiposervicoobra on empnota.e69_numemp = e154_numemp where e20_pagordem = {$codord} and e23_ativo = true and e71_anulado = false and e27_principal is true order by e21_sequencial;");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function gerarDados()
    {
        $fontessubelemento = array(            
            31900805 => 33900805,
            31900901 => 33900856,
            33901899 => 33901801,
            33904022 => 33904014,
            33901414 => 33901401,
            33903006 => 33903007,
            33903696 => 33903699,
            44905212 => 44905208,
            33909218 => 33901801,
            44905206 => 44905208,
            33903937 => 33903936,
            33903981 => 33903999,
            333903645 => 33903645,
            333904712 => 33904706,
            333903607 => 33903607,
            331909199 => 31909291,
            333903101 => 33903101,
            46907106 => 46907101,
            46907104 => 46907101,
            46907301 => 46907399,
            32902104 => 32902101,
            32902106 => 32902101,
            33901499 => 33901401,
            33904713 => 33904705,
            33909299 => 33901801,
            46907101 => 46907101,
            33903004 => 33903001,
            33903096 => 33903094,
            33904002 => 33904001,
            33903058 => 33903024,
            44905235 => 44905299,
            33909600 => 33909601,            
            33904004 => 33904001,
            33903054 => 33903024,
            33904011 => 33904012,
            33904003 => 33904012,
            33903022 => 33903021,
            33903992 => 33903990,
            33903057 => 33903024,
            44905234 => 44905299,
            33904801 => 33904899,
            33903966 => 33903906,
            33904603 => 33904601,
            33904602 => 33904601,
            33903055 => 33903024,
            33903056 => 33903024,
            44905237 => 44905299,
            33904013 => 33904014,
            33903499 => 33903400,
            33904007 => 33904099,
            13220011 => 13220101,
            13999901 => 19999921,
            13999901 => 19999921,
            19909912 => 19999922,
            19909912 => 19999922,
            19909913 => 19999923,
            46907105 => 46907101,
            33904715 => 33904705,
            46907110 => 46907101,
            46907111 => 46907101,
            46907112 => 46907101,
            33903401 => 33903400,
            46907108 => 46907101,
            46907109 => 46907101,
            13900011 => 13999901,
            33903400 => 33903401,
            44904005 => 44904099,
            33904501 => 33904599,
            33903054 => 33903099,
            33903003 => 33903001,
            33903058 => 33903024,
            33903004 => 33903001,
            44905210 => 44905208,
            33903914 => 33903913,
            44905204 => 44905208,
            33903906 => 33903906,
            33903401 => 33903499,
            33903946 => 33903906,
            33903499 => 33903401,
            44905238 => 44905299,
            44905238 => 44905299,
            44905238 => 44905299,
            33904017 => 33904099,
            33903400 => 33903401,
            33904023 => 33904099,
            33903400 => 33903401,
            33904017 => 33904099,
            33904007 => 33904099,
            33904013 => 33904014,
            33904017 => 33904099,
            33904013 => 33904014,
            33904021 => 33904099,
            44905239 => 44905299,        
            44905204 => 44905299,
            44905226 => 44905299,
            45906109 => 45906199,
            44905233 => 44905208,
            45906109 => 45909261,
            44906100 => 44906101,
            31909106 => 31909126,
            33903659 => 33903649,
            44905230 => 44905299,
            33504103 => 33504199,
            33904600 => 33904601,
            31903400 => 31900499,
            44905251 => 44905242,
            44905230 => 44905299,
            33903050 => 33903099,
            44905241 => 44905299,
            44905228 => 44905299,
            44906107 => 44906103,
            44717000 => 44717001,
            33717000 => 33717001,
            44905192 => 44905103,
            17235040 => 17235001,
            33727000 => 33729901,
            33904800 => 33904899,
            33723900 => 33723901,
            32902105 => 32902101,
            33903929 => 33903923,
            46907107 => 46907101,
            33909205 => 33909299,
            44905233 => 44905208
        );        

        $campos = [
            'e60_numemp', 'e60_codemp', 'e60_anousu', 'e50_codord',
            'e69_codnota', 'e69_dtinclusao', 'e70_valor','e177_codigo','o58_orgao','o58_unidade',
            'z01_cgccpf as cpf', 'c60_estrut'
        ];

        $empenhos = DB::table('empempenho')
            ->select($campos)
            ->join('empnota', 'empnota.e69_numemp', 'empempenho.e60_numemp')
            ->join('empnotaele', 'e70_codnota', 'e69_codnota')
            ->join('pagordem', 'e50_numemp', 'e69_numemp')
            ->join('pagordemnota', function (JoinClause $join) {
                $join->on('e71_codnota', 'e69_codnota')
                    ->on('e71_codord', 'e50_codord');
            })
            ->join('db_usuacgm', 'id_usuario', 'e50_id_usuario')
            ->join('cgm', 'z01_numcgm', 'cgmlogin')
            //@todo - remover  LEFT JOIN TEMPORARIO POR FALTA DE REGISTROS. Apenas para teste
            ->leftJoin(
                'empnotasigfistipodocliquidacao',
                'empnotasigfistipodocliquidacao.e178_empnota',
                'empnota.e69_codnota'
            )
            ->leftJoin(
                'sigfistipodocliquidacao',
                'sigfistipodocliquidacao.e177_sequencial',
                'empnotasigfistipodocliquidacao.e178_sigfistipodocliquidacao'
            )
            ->join('orcdotacao', function ($join) {
                $join->on('e60_anousu', 'o58_anousu')
                    ->on('e60_coddot', 'o58_coddot');
            })
            ->join('empelemento', 'empelemento.e64_numemp', 'empempenho.e60_numemp')
            ->join('conplanoorcamento', function ($join) {
                $join->on('conplanoorcamento.c60_codcon', 'empelemento.e64_codele');
                $join->on('conplanoorcamento.c60_anousu', 'empempenho.e60_anousu');
            })            
            ->where('e60_instit', $this->instit)
            ->where('e60_anousu', $this->iAnoUsu)
            ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
            ->get();
            

        if ($empenhos->isEmpty()) {
            throw new \Exception('Nao foi encontrardo nenhum empenho para o arquivo de Remessa ');
        }

        $obj = new \stdClass();
        $obj->LiquidacoesDeEmpenho = [];
        $guarda = array();
        foreach ($empenhos as $empenho) {
            if($empenho->e69_codnota == 451825){continue;}
            $xdoc = (!empty($empenho->e177_codigo)) ? $empenho->e177_codigo : 1;
            $chave = $empenho->e60_codemp.$empenho->e60_anousu.$empenho->o58_unidade.$empenho->o58_orgao.$xdoc.$empenho->e69_codnota.$empenho->e69_dtinclusao;

            if(in_array($chave, $guarda)){
                continue;
            }
            array_push($guarda, $chave);
            
            $empenho->natureza_despesa = substr($empenho->c60_estrut, 1, 8);
            

            $consignacaoRetencoes = new stdClass();
            $consignacaoRetencoes->ConsignacaoRetencao  = (object) [
                'Identificador' => 999,
                'TipoConsignacaoRetencao' => 999,
                'ContaContabil' => 999,
                'Valor' => 999,
                'NomeCredor' => 'XXX',
                'NaturezaCredor' => 999,
                'CnpjCpfCredor' => 999
            ];

            $responsavelLiquidacao = new stdClass();
            $responsavelLiquidacao->Responsavel = [];
            $responsavelLiquidacao->Responsavel[] = (object) [
                'Identificador' => $empenho->e69_codnota.substr($empenho->cpf, 1, 3),
                'CPF' => $empenho->cpf
            ];
            
            if($fontessubelemento[$empenho->natureza_despesa]){
                $empenho->natureza_despesa2 = $fontessubelemento[$empenho->natureza_despesa];
            }else{
                $empenho->natureza_despesa2 = $empenho->natureza_despesa;
            }

            $subElementos = new stdClass();
            $subElementos->LiquidacaoDeEmpenhoSubElemento = [];
            $subElementos->LiquidacaoDeEmpenhoSubElemento[] = (object) [
                'Identificador' => $empenho->e69_codnota.substr($empenho->natureza_despesa, 1, 3),
                'Valor' => $empenho->e70_valor,
                'NaturezaDespesa' => $empenho->natureza_despesa2
            ];

            
            $dadosLiquidacaoEmpenho = (object)[                
                'Identificador' => $empenho->e69_codnota,
                'CodigoUnidadeGestora' => $this->sCodigoTribunal,
                'Ano' => $this->iAnoUsu,
                'Competencia' => $this->competencia,
                'NumeroLiquidacaoEmpenho' =>  $empenho->e69_codnota,
                'DataLiquidacao' => $empenho->e69_dtinclusao,
                'ValorBrutoLiquidacao' => $empenho->e70_valor,
                'NumeroEmpenho' => $empenho->e60_codemp,
                'AnoEmpenho' => $empenho->e60_anousu,                
                'TipoDocumento' => (!empty($empenho->e177_codigo)) ? $empenho->e177_codigo : 1,
                'CodigoOrgao' => $empenho->o58_orgao,
                'CodigoUnidadeOrcamentaria' => $empenho->o58_unidade,
                'ResponsaveisLiquidacao' => $responsavelLiquidacao,
                'SubElemento' => $subElementos,
                'ConsignacoesRetencoes' => $consignacaoRetencoess,
            ];

            $obj->LiquidacoesDeEmpenho[] = (object)['LiquidacaoDeEmpenho' => $dadosLiquidacaoEmpenho];
        }
        
        
        $this->aDados =  $obj;
    }
}
