<?php


namespace ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Arquivos\v2024;

use ECidade\Financeiro\Contabilidade\Exportacao\Sigfis\Common\Helper;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Query\JoinClause;
use stdClass;

class ArquivoLiquidacaoEmpenhoDiaria extends ArquivoBase
{   
    protected $sNomeArquivo = 'LiquidacaoEmpenhoDiaria';

    public function validaTipo($codnota){
        $sql = pg_query("SELECT e177_codigo, e177_descr FROM sigfistipodocliquidacao INNER JOIN empnotasigfistipodocliquidacao ON e178_sigfistipodocliquidacao = e177_sequencial WHERE e178_empnota = {$codnota}");
        $resultado = pg_fetch_all($sql);
        return ($resultado[0]["e177_codigo"] == 4) ? true : false;
    }

    public function buscaPessoa($codord){
        $sql = pg_query("SELECT z01_nome, e49_codord, e49_numcgm, z01_cgccpf FROM pagordemconta INNER JOIN cgm ON e49_numcgm = z01_numcgm WHERE e49_codord = {$codord}");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function buscaDadosDiaria($codord){
        $sql = pg_query("SELECT e60_numemp, e60_codemp, e60_anousu, o58_orgao, o58_unidade, e69_codnota, e69_anousu, e70_vlrliq, e50_obs, e69_anousu, e82_codmov, (SELECT z01_cgccpf FROM pagordemconta INNER JOIN cgm ON e49_numcgm = z01_numcgm WHERE e49_codord = e50_codord) as cpf, (SELECT z01_nome FROM pagordemconta INNER JOIN cgm ON e49_numcgm = z01_numcgm WHERE e49_codord = e50_codord) as nome, e69_dtinclusao, e60_instit from empempenho inner join empnota on e69_numemp = e60_numemp inner join empnotaele on e70_codnota = e69_codnota inner join pagordem on e50_numemp = e60_numemp inner join pagordemnota on e71_codnota = e69_codnota and e71_codord = e50_codord inner join empenho.empord on e82_codord = e50_codord inner join db_usuacgm on id_usuario = e50_id_usuario inner join cgm as cgmusu on cgmusu.z01_numcgm = cgmlogin inner join orcdotacao on o58_anousu = e60_anousu and o58_coddot = e60_coddot inner join empelemento on e64_numemp = e60_numemp inner join conplanoorcamento on c60_codcon = e64_codele and c60_anousu = e60_anousu where e50_codord = {$codord}");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function buscaMatricula($cpf){
        $ano = $this->iAnoUsu;
        $mes = substr($this->competencia, 4, 2);

        
        $sql = pg_query("SELECT distinct rhpessoal.rh01_regist, rhpessoal.rh01_numcgm, cgm.z01_nome, cgm.z01_cgccpf, rh01_admiss, rh05_recis, rh37_descr, rh30_vinculoemprego::int from rhpessoal left join rhpessoalmov on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist and rhpessoalmov.rh02_anousu = {$ano} and rhpessoalmov.rh02_mesusu = {$mes} left join rhlota on rhlota.r70_codigo = rhpessoalmov.rh02_lota and rhlota.r70_instit = rhpessoalmov.rh02_instit inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm left join rhregime on rh30_codreg = rh02_codreg left join rhfuncao on rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao and rhfuncao.rh37_instit = rhpessoalmov.rh02_instit left join rhpesrescisao on rh02_seqpes = rh05_seqpes left join rhcontratoemergencial on rhcontratoemergencial.rh163_matricula = rhpessoal.rh01_regist where z01_cgccpf = '{$cpf}' AND rh37_descr is not null ORDER BY rh05_recis desc, rh01_admiss desc LIMIT 1");
        $resultado = pg_fetch_all($sql);
        return substr($resultado[0]["rh01_regist"], 2);
        
    }

    public function buscaDadosPlanilha($matricula, $mes){
        $sql = pg_query("SELECT * FROM auxdiariaantigo WHERE FUN_MATRICULA = '{$matricula}' AND GUIA_DATA <= '{$mes}'");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function buscaDadosPlanilha45($matricula, $mes){
        $sql = pg_query("SELECT * FROM auxdiariaantigo45 WHERE FUN_MATRICULA = '{$matricula}' AND GUIA_DATA2 <= '{$mes}'");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function buscaCPFnovo($cpf, $valor){
        $sql = pg_query("SELECT * FROM auxdiariaantigo45 WHERE cpf = '{$cpf}' AND guia_valor = '{$valor}'");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function buscaOutrosDadosPlanilha($matricula, $mes){
        $sql = pg_query("SELECT * FROM auxdiariasetembro WHERE MATRICULA = '{$matricula}' AND DATA <= '{$mes}'");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function buscaDadosPlanilhaNov($matricula, $mes){
        $sql = pg_query("SELECT * FROM auxdiariatodosnov WHERE MATRICULA = '{$matricula}' AND DATA <= '{$mes}'");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function buscaDadosPlanilhaDez($matricula, $mes){
        $sql = pg_query("SELECT * FROM auxdiariadezembro WHERE MATRICULA = '{$matricula}' AND DATA <= '{$mes}'");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }


    public function buscaPlanilha45Setembro($matricula, $mes){
        $sql = pg_query("SELECT * FROM auxdiaria45setembro WHERE FUN_MATRICULA = '{$matricula}' AND GUIA_DATA <= '{$mes}'");
        $resultado = pg_fetch_all($sql);
        return $resultado[0];
    }

    public function buscaTudoPlanilha45SetembroCPF($cpf){
        $sql = pg_query("SELECT * FROM auxdiaria45setembro WHERE cpf = '{$cpf}' ORDER BY id");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function buscaTudoPlanilha45OutubroCPF($cpf){
        $sql = pg_query("SELECT * FROM auxdiaria45outubro WHERE cpf = '{$cpf}' ORDER BY id");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function buscaTudoPlanilha45NovembroCPF($cpf){
        $sql = pg_query("SELECT * FROM auxdiaria45novembro WHERE cpf = '{$cpf}' ORDER BY id");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }

    public function buscaTudoPlanilha45DezembroCPF($cpf){
        $sql = pg_query("SELECT * FROM auxdiaria45dezembro WHERE cpf = '{$cpf}' ORDER BY id");
        $resultado = pg_fetch_all($sql);
        return $resultado;
    }
    
    

    public function arrumaUF($estado) {
        switch (strtoupper($estado)) {
            case 'ACRE':
                return 'AC';
            case 'ALAGOAS':
                return 'AL';
            case 'AMAPÁ':
            case 'AMAPA':
                return 'AP';
            case 'AMAZONAS':
                return 'AM';
            case 'BAHIA':
                return 'BA';
            case 'CEARÁ':
            case 'CEARA':
                return 'CE';
            case 'DISTRITO FEDERAL':
                return 'DF';
            case 'ESPÍRITO SANTO':
            case 'ESPiRITO SANTO':
                return 'ES';
            case 'GOIÁS':
            case 'GOIAS':
                return 'GO';
            case 'MARANHÃO':
            case 'MARANHAO':
                return 'MA';
            case 'MATO GROSSO':
                return 'MT';
            case 'MATO GROSSO DO SUL':
                return 'MS';
            case 'MINAS GERAIS':
                return 'MG';
            case 'PARÁ':
            case 'PARA':
                return 'PA';
            case 'PARAÍBA':
            case 'PARAIBA':
                return 'PB';
            case 'PARANÁ':
            case 'PARANA':
                return 'PR';
            case 'PERNAMBUCO':
                return 'PE';
            case 'PIAUÍ':
            case 'PIAUI':
                return 'PI';
            case 'RIO DE JANEIRO':
                return 'RJ';
            case 'RIO GRANDE DO NORTE':
                return 'RN';
            case 'RIO GRANDE DO SUL':
                return 'RS';
            case 'RONDÔNIA':
            case 'RONDONIA':
                return 'RO';
            case 'RORAIMA':
                return 'RR';
            case 'SANTA CATARINA':
                return 'SC';
            case 'SÃO PAULO':
            case 'SAO PAULO':
                return 'SP';
            case 'SERGIPE':
                return 'SE';
            case 'TOCANTINS':
                return 'TO';
            default:
                return 'RJ';
    }
}



    

    public function gerarDados(){
       $velho = false;
        
        if($this->competencia == 202401 || $this->competencia == 202402 || $this->competencia == 202403 || $this->competencia == 202404 || $this->competencia == 202405 || $this->competencia == 202406 || $this->competencia == 202407 || $this->competencia == 202408 || $this->competencia == 202409 || $this->competencia == 202410 || $this->competencia == 202411 || $this->competencia == 202412 ){
       
            $velho = true;            
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
            ->where('e177_codigo', 4)
            ->where('e70_vlranu', '=', 0)
            ->where('e60_instit', $this->instit)
            ->where('e60_anousu', $this->iAnoUsu)
            ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
            ->orderBy('e69_dtinclusao')
            ->get();

        }else{            
            
            $campos = [
                'e60_numemp',
                'e60_codemp',
                'e60_anousu',
                'o58_orgao',
                'o58_unidade',
                'e69_codnota',
                'e69_anousu',
                'e70_vlrliq',
                'e50_obs',
                'e69_anousu',            
                'cgmusu.z01_cgccpf',            
                'e82_codmov',
                'e46_nome',
                'e446_sequencial',
                'e446_quantidade',
                'e446_datainicio',
                'e446_datafim',
                'e446_tipodiaria',
                'e446_estadodestino',
                'e446_destino',
                'e446_paisdestino',
                'e446_motivo'
            ];
            
            $empenhos = DB::table('empempenho')
                ->select($campos)
                ->join('empnota', 'e69_numemp', 'e60_numemp')
                ->join('empnotaele', 'e70_codnota', 'e69_codnota')
                ->join('pagordem', 'e50_numemp', 'e60_numemp')
                ->join('pagordemnota', function (JoinClause $join) {
                    $join->on('e71_codnota', 'e69_codnota')
                        ->on('e71_codord', 'e50_codord');
                })
                ->join('empenho.empord', 'e82_codord', 'e50_codord')
                ->join('emppresta', 'e45_codmov', 'e82_codmov')
                ->join('empprestaitem', function (JoinClause $join) {
                    $join->on('e46_numemp', 'e60_numemp')
                        ->on('e46_emppresta', 'e45_sequencial');
                })
                ->join('empprestaitemdiaria', 'e446_empprestaitem', 'e46_codigo')            
                ->join('db_usuacgm', 'id_usuario', 'e50_id_usuario')
                ->join('cgm AS cgmusu', 'cgmusu.z01_numcgm', 'cgmlogin')
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
                ->join('orcdotacao', function (JoinClause $join) {
                    $join->on('o58_anousu', 'e60_anousu')
                        ->on('o58_coddot', 'e60_coddot');
                })
                ->join('empelemento', 'e64_numemp', 'e60_numemp')
                ->join('conplanoorcamento', function (JoinClause $join) {
                    $join->on('c60_codcon', 'e64_codele')
                        ->on('c60_anousu', 'e60_anousu');
                })
                ->where('e177_codigo', 4)
                ->where('e70_vlranu', '=', 0)
                ->where('e60_anousu', $this->iAnoUsu)
                ->where('e60_instit', $this->instit)
                ->whereBetween('e69_dtinclusao', [$this->dtDataInicial, $this->dtDataFinal])
                ->where('e70_vlrliq', '!=', 0)
                ->orderBy('e60_numemp')
                ->get();
        }//else da competência            

        if ($empenhos->isEmpty()) {
            throw new \Exception('Não foi encontrado nenhum empenho para o arquivo da Remessa.');
        }

        $obj = new stdClass();
        $obj->LiquidacoesEmpenhoDiarias = [];
        
        $ix = 1;
        $guarda45set = array();
        foreach ($empenhos as $empenho) {            
            
            $dadospessoa = $this->buscaPessoa($empenho->e50_codord);
            $dadosdiaria = $this->buscaDadosDiaria($empenho->e50_codord);
                        
            
            if($velho){

                if($this->instit == 45){
                    $matriculax = $this->buscaMatricula($dadospessoa["z01_cgccpf"]);                    
                                    
                    if(substr($matriculax, 0, 3) == "000"){
                        $matriculax = substr($matriculax, 3);
                    }elseif(substr($matriculax, 0, 2) == "00"){
                        $matriculax = substr($matriculax, 2);
                    }elseif(substr($matriculax, 0, 1) == "0"){                    
                        $matriculax = substr($matriculax, 1);
                    }
                
                    if(substr($this->competencia, 4, 2) == "02"){
                        $diax = "29";
                    }elseif(substr($this->competencia, 4, 2) == "01" ||substr($this->competencia, 4, 2) == "03" ||substr($this->competencia, 4, 2) == "05" ||substr($this->competencia, 4, 2) == "07"){
                        $diax = "31";
                    }else{
                        $diax = "30";
                    }

                    $mesx = substr($this->competencia, 0, 4) ."-". substr($this->competencia, 4, 2) . "-" . $diax;
                    
                    $planilha = $this->buscaDadosPlanilha45($matriculax, $mesx);
                    $planilha = $this->buscaCPFnovo($dadosdiaria["cpf"], $dadosdiaria["e70_vlrliq"]);
                    
                    $empenho->e446_sequencial = $planilha["guia_numero"].$planilha["guia_ano"];
                    $empenho->e446_quantidade = 1;
                    $empenho->e446_datainicio = $planilha["guia_data"];
                    $empenho->e446_datafim = $planilha["guia_dataretorno"];
                    $empenho->e446_estadodestino = $planilha["guia_ufdestino"];
                    $empenho->e446_destino = $planilha["guia_destino"];
                    $empenho->e446_paisdestino = "Brasil";
                    $empenho->e446_motivo = $planilha["guia_motivo"];
                    $empenho->z01_cgccpf = $dadospessoa["z01_cgccpf"];
                    $empenho->e46_nome = $dadospessoa["z01_nome"];
                    $empenho->e70_vlrliq = $dadosdiaria["e70_vlrliq"];

                    if($this->competencia == 202409){
                        
                        
                        array_push($guarda45set, $dadospessoa["z01_cgccpf"]);
                        $conta = array_count_values($guarda45set);
                        $chave = $conta[$dadospessoa["z01_cgccpf"]] - 1;
                        $preplanilha = $this->buscaTudoPlanilha45SetembroCPF($dadospessoa["z01_cgccpf"]);
                        $planilha2 = $preplanilha[$chave];
                                                
                        $empenho->e446_sequencial = $planilha2["guia_numero"];
                        $empenho->e446_quantidade = 1; //$planilha2["diarias"];
                        $empenho->e446_datainicio = $planilha2["guia_data"];
                        $empenho->e446_datafim = $planilha2["guia_dataretorno"];
                        $empenho->e446_estadodestino = $planilha2["uf"];
                        $empenho->e446_destino = $planilha2["guia_destino"];
                        $empenho->e446_paisdestino = "Brasil";
                        $empenho->e446_motivo = $planilha2["guia_motivo"];
                        $empenho->z01_cgccpf = $dadospessoa["z01_cgccpf"];
                        $empenho->e46_nome = $dadospessoa["z01_nome"];
                        $empenho->e70_vlrliq = $planilha2["guia_valor"];
                    }//IF DE SETEMBRO

                    if($this->competencia == 202410){
                        
                        
                        array_push($guarda45set, $dadospessoa["z01_cgccpf"]);
                        $conta = array_count_values($guarda45set);
                        $chave = $conta[$dadospessoa["z01_cgccpf"]] - 1;

                        if(strlen($dadospessoa["z01_cgccpf"]) == 8){
                            $dadospessoa["z01_cgccpf"] = "000" . $dadospessoa["z01_cgccpf"];
                        }elseif(strlen($dadospessoa["z01_cgccpf"]) == 9){
                            $dadospessoa["z01_cgccpf"] = "00" . $dadospessoa["z01_cgccpf"];
                        }elseif(strlen($dadospessoa["z01_cgccpf"]) == 10){
                            $dadospessoa["z01_cgccpf"] = "0" . $dadospessoa["z01_cgccpf"];
                        }

                        $preplanilha = $this->buscaTudoPlanilha45OutubroCPF($dadospessoa["z01_cgccpf"]);
                        $planilha2 = $preplanilha[$chave];

                                                                        
                        $empenho->e446_sequencial = $planilha2["guia_numero"];
                        $empenho->e446_quantidade = $planilha2["qtd_diaris"];
                        $empenho->e446_datainicio = $planilha2["guia_data"];
                        $empenho->e446_datafim = $planilha2["guia_dataretorno"];
                        $empenho->e446_estadodestino = $planilha2["uf"];
                        $empenho->e446_destino = $planilha2["guia_destino"];
                        $empenho->e446_paisdestino = "Brasil";
                        $empenho->e446_motivo = $planilha2["guia_motivo"];
                        $empenho->z01_cgccpf = $dadospessoa["z01_cgccpf"];
                        $empenho->e46_nome = $dadospessoa["z01_nome"];
                        $empenho->e70_vlrliq = $planilha2["guia_valor"];
                    }//IF DE OUTUBRO

                    if($this->competencia == 202411){                        
                        
                        array_push($guarda45set, $dadospessoa["z01_cgccpf"]);
                        $conta = array_count_values($guarda45set);
                        $chave = $conta[$dadospessoa["z01_cgccpf"]] - 1;

                        if(strlen($dadospessoa["z01_cgccpf"]) == 8){
                            $dadospessoa["z01_cgccpf"] = "000" . $dadospessoa["z01_cgccpf"];
                        }elseif(strlen($dadospessoa["z01_cgccpf"]) == 9){
                            $dadospessoa["z01_cgccpf"] = "00" . $dadospessoa["z01_cgccpf"];
                        }elseif(strlen($dadospessoa["z01_cgccpf"]) == 10){
                            $dadospessoa["z01_cgccpf"] = "0" . $dadospessoa["z01_cgccpf"];
                        }

                        $preplanilha = $this->buscaTudoPlanilha45NovembroCPF($dadospessoa["z01_cgccpf"]);
                        $planilha2 = $preplanilha[$chave];

                                                                        
                        $empenho->e446_sequencial = $planilha2["guia_numero"];
                        $empenho->e446_quantidade = $planilha2["qtd_diaris"];
                        $empenho->e446_datainicio = $planilha2["guia_data"];
                        $empenho->e446_datafim = $planilha2["guia_dataretorno"];
                        $empenho->e446_estadodestino = $planilha2["uf"];
                        $empenho->e446_destino = $planilha2["guia_destino"];
                        $empenho->e446_paisdestino = "Brasil";
                        $empenho->e446_motivo = $planilha2["guia_motivo"];
                        $empenho->z01_cgccpf = $dadospessoa["z01_cgccpf"];
                        $empenho->e46_nome = $dadospessoa["z01_nome"];
                        $empenho->e70_vlrliq = $planilha2["guia_valor"];
                    }//IF DE NOVEMBRO

                    if($this->competencia == 202412){                        
                        
                        array_push($guarda45set, $dadospessoa["z01_cgccpf"]);
                        $conta = array_count_values($guarda45set);
                        $chave = $conta[$dadospessoa["z01_cgccpf"]] - 1;

                        if(strlen($dadospessoa["z01_cgccpf"]) == 8){
                            $dadospessoa["z01_cgccpf"] = "000" . $dadospessoa["z01_cgccpf"];
                        }elseif(strlen($dadospessoa["z01_cgccpf"]) == 9){
                            $dadospessoa["z01_cgccpf"] = "00" . $dadospessoa["z01_cgccpf"];
                        }elseif(strlen($dadospessoa["z01_cgccpf"]) == 10){
                            $dadospessoa["z01_cgccpf"] = "0" . $dadospessoa["z01_cgccpf"];
                        }

                        $preplanilha = $this->buscaTudoPlanilha45DezembroCPF($dadospessoa["z01_cgccpf"]);
                        $planilha2 = $preplanilha[$chave];

                                                                        
                        $empenho->e446_sequencial = $planilha2["guia_numero"];
                        $empenho->e446_quantidade = $planilha2["qtd_diaris"];
                        $empenho->e446_datainicio = $planilha2["guia_data"];
                        $empenho->e446_datafim = $planilha2["guia_dataretorno"];
                        $empenho->e446_estadodestino = $planilha2["uf"];
                        $empenho->e446_destino = $planilha2["guia_destino"];
                        $empenho->e446_paisdestino = "Brasil";
                        $empenho->e446_motivo = $planilha2["guia_motivo"];
                        $empenho->z01_cgccpf = $dadospessoa["z01_cgccpf"];
                        $empenho->e46_nome = $dadospessoa["z01_nome"];
                        $empenho->e70_vlrliq = $planilha2["guia_valor"];
                    }//IF DE DEZEMBRO
                    //if da 45
                }else{                    
                    $matriculax = $this->buscaMatricula($dadospessoa["z01_cgccpf"]);

                    if(!$matriculax){
                        //Dados inexistentes na planilha
                    }
                    
                    if(substr($matriculax, 0, 3) == "000"){
                        $matriculax = substr($matriculax, 3);
                    }elseif(substr($matriculax, 0, 2) == "00"){
                        $matriculax = substr($matriculax, 2);
                    }elseif(substr($matriculax, 0, 1) == "0"){                    
                        $matriculax = substr($matriculax, 1);
                    }
                
                    if(substr($this->competencia, 4, 2) == "02"){
                        $diax = "29";
                    }elseif(substr($this->competencia, 4, 2) == "01" ||substr($this->competencia, 4, 2) == "03" ||substr($this->competencia, 4, 2) == "05" ||substr($this->competencia, 4, 2) == "07"){
                        $diax = "31";
                    }else{
                        $diax = "30";
                    }
                    $mesx = substr($this->competencia, 0, 4) ."-". substr($this->competencia, 4, 2) . "-" . $diax;

                    if($this->competencia == 202411){
                        $planilha = $this->buscaDadosPlanilhaNov($matriculax, $mesx);    
                    }

                    if($this->competencia == 202412){
                        $planilha = $this->buscaDadosPlanilhaDez($matriculax, $mesx);
                    }
                
                    
                    $empenho->e446_sequencial = $planilha["numero"].$planilha["ano"];
                    $empenho->e446_quantidade = $planilha["diarias"];
                    $empenho->e446_datainicio = $planilha["data"];
                    $empenho->e446_datafim = $planilha["dataretorno"];
                    $empenho->e446_estadodestino = $planilha["uf"];
                    $empenho->e446_destino = $planilha["destino"];
                    $empenho->e446_paisdestino = "Brasil";
                    $empenho->e446_motivo = $planilha["motivo"];
                    $empenho->z01_cgccpf = $dadospessoa["z01_cgccpf"];
                    $empenho->e46_nome = $dadospessoa["z01_nome"];
                    $empenho->e70_vlrliq = $dadosdiaria["e70_vlrliq"];
                    //$empenho->e69_anousu = $dadosdiaria["ano"];
                    $empenho->e69_anousu = $dadosdiaria["e69_anousu"];
                    

                    if($this->competencia == 2024099){
                        $planilha2 = $this->buscaOutrosDadosPlanilha($matriculax, $mesx);

                        $empenho->e446_sequencial = $planilha2["guia"];
                        $empenho->e446_quantidade = $planilha2["diarias"];
                        $empenho->e446_datainicio = $planilha2["data"];
                        $empenho->e446_datafim = $planilha2["dataretorno"];
                        $empenho->e446_estadodestino = $planilha2["uf"];
                        $empenho->e446_destino = $planilha2["destino"];
                        $empenho->e446_paisdestino = "Brasil";
                        $empenho->e446_motivo = $planilha2["motivo"];
                        $empenho->z01_cgccpf = $dadospessoa["z01_cgccpf"];
                        $empenho->e46_nome = $dadospessoa["z01_nome"];
                        $empenho->e70_vlrliq = $planilha2["valor"];
                    }//IF DE SETEMBRO
                    
                                            
                        
                        
                    
                 
                }
            }//fim if/else 45
                
            $vt = $this->validaTipo($empenho->e69_codnota);
            if(!$vt){continue;}
            $data = (object)[
                "Identificador" => $ix, //$empenho->e69_codnota,
                "CodigoUnidadeGestora" => $this->sCodigoTribunal,
                "NumeroEmpenho" => $empenho->e60_codemp,
                "AnoEmpenho" => $empenho->e60_anousu,
                "AnoLiquidacaoDeEmpenho" => $empenho->e69_anousu,
                "Competencia" => $this->competencia,
                "CPFServidor" => $empenho->z01_cgccpf,
                "NumeroDiaria" => $empenho->e446_sequencial,
                "NumeroLiquidacaoEmpenho" => $empenho->e69_codnota,
                "Nome" => Helper::convertAndLimit($empenho->e46_nome, 255),
                "Valor" => $empenho->e70_vlrliq,
                "QuantidadeDiarias" => $empenho->e446_quantidade,
                "DataSaida" => $empenho->e446_datainicio,
                "DataRetorno" => $empenho->e446_datafim,
                "IndicadorDestino" => $empenho->e446_tipodiaria === 'internacional' ? 2 : 1,
                "EstadoDestino" => $this->arrumaUF(Helper::convertAndLimit($empenho->e446_estadodestino, 50)),
                "CidadeDestino" => Helper::convertAndLimit($empenho->e446_destino, 50),
                "PaisDestino" => Helper::convertAndLimit($empenho->e446_paisdestino, 50),
                "ObjetoDiaria" => Helper::convertAndLimit($empenho->e446_motivo, 4000),
                "CodigoOrgao" => $empenho->o58_orgao,
                "CodigoUnidadeOrcamentaria" => $empenho->o58_unidade,
            ];
            $ix++;
            $obj->LiquidacoesEmpenhoDiarias[] = (object)['LiquidacaoEmpenhoDiaria' => $data];

        }
        
        $this->aDados = $obj;
    }
}
