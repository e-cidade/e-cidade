<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;

class ArquivoRPLiquidacao extends ArquivoBase
{
    protected $sNomeArquivo = 'LiquidacaoRestosPagar';

    
    public function buscaDadosPorEmpenho($codemp, $codnota){
        $inst = $this->instit;
        $di = $this->dtDataInicial;
        $df = $this->dtDataFinal;
        $ano = $this->iAnoUsu;

        $sql = pg_query("SELECT e60_numemp, e60_anousu, e60_codemp, e91_anousu, o58_orgao, o58_unidade, e69_codnota, e69_dtinclusao, e69_numero, e70_valor, e50_codord, e178_sigfistipodocliquidacao, c60_estrut, z01_numcgm as cgm_responsaval, z01_cgccpf as cpf_responsaval from empresto inner join empenho.empempenho on e60_numemp = e91_numemp inner join orcamento.orcdotacao on e60_anousu = o58_anousu and e60_coddot = o58_coddot inner join empnota on empnota.e69_numemp = empempenho.e60_numemp inner join empnotaele on e70_codnota = e69_codnota inner join pagordem on e50_numemp = e69_numemp inner join pagordemnota on e71_codnota = e69_codnota and e71_codord = e50_codord inner join db_usuacgm on id_usuario = e50_id_usuario inner join cgm on z01_numcgm = cgmlogin inner join empelemento on e64_numemp = e60_numemp inner join conplanoorcamento on c60_codcon = e64_codele and c60_anousu = (case when e60_anousu < 2023 then 2023 else e60_anousu end) left join empnotasigfistipodocliquidacao on empnotasigfistipodocliquidacao.e178_empnota = empnota.e69_codnota where e91_anousu = {$ano} and e60_instit = {$inst} and e69_dtinclusao between '{$di}' and '{$df}' AND e60_codemp = '{$codemp}' AND e69_codnota = '{$codnota}'");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function tabelaSigfis($tipo){
        switch ($tipo) {
            case '1':
                return 1;
            case '2':
            case '3':
                return 2;
            case '4':
            case '5':
                return 3;
            case '6':
                return 4;
            case '7':
                return 5;
        }
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
            33904501 => 33904500,
            33904004 => 33904001,
            33903981 => 33903906,
            //44905235 => 44905200,
            //33903054 => 33903099,
            44905235 => 44905299,
            33904501 => 33904599,
            33903937 => 33903936,
            33903981 => 33903999,
            46907106 => 46907101,
            46907104 => 46907101,
            46907301 => 46907399,
            32902104 => 32902101,
            32902106 => 32902101,
            33901499 => 33901401,
            33904713 => 33904705,
            33903004 => 33903001,
            33903096 => 33903094,
            33904002 => 33904001,
            33903058 => 33903024,
            33909600 => 33909601,
            33903054 => 33903024,
            33904011 => 33904012,
            33904003 => 33904012,
            33903022 => 33903021,
            33903992 => 33903990,
            33903057 => 33903024,
            44905234 => 44905299,
            33904603 => 33904601,
            33904602 => 33904601,
            33903055 => 33903024,
            33903056 => 33903024,
            33504301 => 33504306,
            44905237 => 44905299,
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
            'e60_numemp', 'e60_anousu', 'e60_codemp',
            'e91_anousu',
            'o58_orgao', 'o58_unidade',
            'e69_codnota', 'e69_dtinclusao', 'e69_numero',
            'e70_valor', 'e50_codord',
            'e178_sigfistipodocliquidacao',
            'c60_estrut',
            'z01_numcgm as cgm_responsaval', 'z01_cgccpf as cpf_responsaval'
        ];
        $empenhos = DB::table('empresto')
            ->select($campos)
            ->join('empenho.empempenho', 'e60_numemp', 'e91_numemp')
            ->join('orcamento.orcdotacao', function (JoinClause $join) {
                $join->on('e60_anousu', 'o58_anousu')
                    ->on('e60_coddot', 'o58_coddot');
            })
            ->join('empnota', 'empnota.e69_numemp', 'empempenho.e60_numemp')
            ->join('empnotaele', 'e70_codnota', 'e69_codnota')
            ->join('pagordem', 'e50_numemp', 'e69_numemp')
            ->join('pagordemnota', function (JoinClause $join) {
                $join->on('e71_codnota', 'e69_codnota')
                    ->on('e71_codord', 'e50_codord');
            })
            ->join('db_usuacgm', 'id_usuario', 'e50_id_usuario')
            ->join('cgm', 'z01_numcgm', 'cgmlogin')
            ->join('empelemento', 'e64_numemp', 'e60_numemp')
            ->join('conplanoorcamento', function (JoinClause $join) {
                $join->on('c60_codcon', 'e64_codele')
                    ->whereRaw('c60_anousu = (case when e60_anousu < 2023 then 2023 else e60_anousu end)');
            })            
            ->leftJoin(
                'empnotasigfistipodocliquidacao',
                'empnotasigfistipodocliquidacao.e178_empnota',
                'empnota.e69_codnota'
            )
            ->where('e91_anousu', $this->iAnoUsu)
            ->where('e60_instit', $this->instit)
            ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
            ->get();            
                    

        if ($empenhos->isEmpty()) {
            throw new \Exception(sprintf(
                'Não foi encontrado nenhum registro para o arquivo da Remessa de %s',
                'Restos a Pagar - Inscrição'
            ));
        }

        $obj = new stdClass();
        $obj->LiquidacoesRestosPagar = [];
        $guarda = array();
        $guardaempenho = array();
        $ix = 1;
        
        
        foreach ($empenhos as $empenho) {
            $guardinha = $empenho->e91_anousu.$empenho->o58_unidade.$empenho->o58_orgao.$empenho->e60_codemp.$empenho->e60_anousu.$empenho->e50_codord;
                        
            if(in_array($guardinha, $guarda)){continue;}
            array_push($guarda, $guardinha);
            
            $dados = $this->buscaDadosPorEmpenho($empenho->e60_codemp, $empenho->e69_codnota);
                    
            
            $somavalor = 0;
            foreach ($dados as $linha) {                
                $somavalor += $linha['e70_valor'];
            }
            
            $tipoDocumento = $this->tabelaSigfis($empenho->e178_sigfistipodocliquidacao);                        


            $data = (object)[
                'Identificador' => $ix, //$empenho->e69_codnota,
                'CodigoUnidadeGestora' => $this->sCodigoTribunal,
                'Competencia' => $this->competencia,
                'NumeroEmpenho' => $empenho->e60_codemp,
                'AnoEmpenho' => $empenho->e60_anousu,
                'NumeroNotaLiquidacao' => $empenho->e69_codnota, //$empenho->e69_numero,
                'AnoLiquidacao' => $empenho->e91_anousu,
                'DataLiquidacao' => $empenho->e69_dtinclusao,
                'ValorLiquidacao' => $somavalor, //$empenho->e70_valor,
                'TipoDocumento' => $tipoDocumento,
                'CodigoOrgao' => $empenho->o58_orgao,
                'CodigoUnidadeOrcamentaria' => $empenho->o58_unidade
            ];

            $data->ResponsaveisLiquidacao = [];
            $data->SubElementos = [];            
            $ix++;
            $vv = 0;
            foreach ($dados as $linha){
                $subelenatdes = substr($linha["c60_estrut"], 1, 8);
                if($fontessubelemento[substr($linha["c60_estrut"], 1, 8)]){
                    $subelenatdes = $fontessubelemento[substr($linha["c60_estrut"], 1, 8)];
                }
                $vv += $linha["e70_valor"];
                
            }
            $responsavel = new stdClass();    
                $responsavel->Identificador = $linha['cgm_responsaval'];
                

                    if(strlen($linha['cpf_responsaval']) > 11){
                        $linha['cpf_responsaval'] = "81885520700";
                    }
                $responsavel->CPFResponsavel = $linha['cpf_responsaval'];

                $subelemento = new stdClass();
                $subelemento->Identificador = $linha['e69_codnota'];
                $subelemento->Valor = $vv;
                $subelemento->NaturezaDespesa = $subelenatdes;
                
                
                $data->ResponsaveisLiquidacao[] = (object)['Responsavel' => $responsavel];
                $data->SubElementos[] = (object)['LiquidacaoRestosPagarSubelemento' => $subelemento];
                $data->ConsignacoesRetencoes = null;

            $obj->LiquidacoesRestosPagar[] = (object)['LiquidacaoRestosPagar' => $data];
        }

        $this->aDados = $obj;
    }
}
