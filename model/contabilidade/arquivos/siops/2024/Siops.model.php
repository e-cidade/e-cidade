<?php

require_once("classes/db_orcorgao_classe.php");
require_once("classes/db_orcdotacao_classe.php");
require_once("classes/db_orcreceita_classe.php");
require_once("classes/db_naturdessiops_classe.php");
require_once("classes/db_naturrecsiops_classe.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_libcontabilidade.php");


class Siops {

    //@var string
    public $sInstit;
    //@var integer
    public $iAnoUsu;
    //@var integer
    public $iBimestre;
    //@var string
    public $sFiltros;
    //@var string
    public $dtIni;
    //@var string
    public $dtFim;
    //@var array
    public $aDespesas = array();
    //@var array
    public $aReceitas = array();
     //@var array
     public $aInfoComplementares = array();
    //@var array
    public $aReceitasAnoSeg = array();
    //@var array
    public $aDespesasAnoSeg = array();
    //@var array
    public $aInfoComplementaresAnoSeg = array();
    //@var array
    public $aDespesasAgrupadas = array();
    //@var array
    public $aReceitasAgrupadas = array();
    //@var array
    public $aInfoComplementaresAgrupadas = array();
    //@var array
    public $aReceitasAnoSegAgrupadas = array();
    //@var array
    public $aInfoComplementaresAnoSegAgrupadas = array();
    //@var array
    public $aDespesasAnoSegAgrupadas = array();
    //@var array
    public $aDespesasAgrupadasFinal = array();
    //@var boolean
    public $aReceitasAgrupadasFinal = array();
    //@var boolean
    public $aInfoComplementaresAgrupadasFinal = array();
    //@var boolean
    public $lOrcada;
    //@var string
    public $sNomeArquivo;
    //@var array
    public $aNomesArquivos;
    //@var integer
    public $iErroSQL;
    //@var integer
    public $status;
    //@var string
    public $sMensagem;


    public function gerarSiopsDespesa() {

        if (file_exists("model/contabilidade/arquivos/siops/".db_getsession("DB_anousu")."/SiopsIMPT.model.php")) {

            require_once("model/contabilidade/arquivos/siops/" . db_getsession("DB_anousu") . "/SiopsIMPT.model.php");

            $iContador = 1;

            foreach ($this->aDespesasAgrupadasFinal as $cod_plan => $aDados) {

                $sContador = str_pad($iContador++, 2, '0', STR_PAD_LEFT);

                $sNomeArquivo = $sContador .'_'. str_replace(" ","_",preg_replace("/&([a-z])[a-z]+;/i", "$1", htmlentities(trim($this->getNomeArquivoByCod($cod_plan)))));
                $this->setNomesArquivos($sNomeArquivo);
                $impt = new SiopeIMPT;
                $impt->setNomeArquivo($sNomeArquivo);
                $impt->gerarArquivoIMPT($aDados, 1);

            }

        }

    }

    public function gerarSiopsReceita() {

        $aDados = $this->aReceitasAgrupadasFinal;

        if (file_exists("model/contabilidade/arquivos/siops/".db_getsession("DB_anousu")."/SiopsIMPT.model.php")) {

            require_once("model/contabilidade/arquivos/siops/" . db_getsession("DB_anousu") . "/SiopsIMPT.model.php");

            $impt = new SiopeIMPT;
            $impt->setNomeArquivo($this->getNomeArquivo());
            $impt->gerarArquivoIMPT($aDados, 2);

        }

    }

    public function gerarsiopsInfoComplementares() {

        $aDados = $aDados = $this->aInfoComplementaresAgrupadasFinal;
   
        if (file_exists("model/contabilidade/arquivos/siops/".db_getsession("DB_anousu")."/SiopsIMPT.model.php")) {

            require_once("model/contabilidade/arquivos/siops/" . db_getsession("DB_anousu") . "/SiopsIMPT.model.php");

            $impt = new SiopeIMPT;
            $impt->setNomeArquivo($this->getNomeArquivo());
            $impt->gerarArquivoIMPT($aDados, 3);

        }

    }

    public function getNomeArquivoByCod($cod_plan) {

        $sql = "select c228_nomearquivo from nomearqdessiops where (c228_codplanilha, c228_anousu) = ('$cod_plan', $this->iAnoUsu)";
        $result = db_query($sql);

        if (pg_num_rows($result) > 0) {
            return db_utils::fieldsMemory($result, 0)->c228_nomearquivo;
        } else {
            return 'Siops gen�rico';
        }

    }
 
    public function setNomeArquivo($sNomeArquivo) {
        $this->sNomeArquivo = $sNomeArquivo;
    }

    public function getNomeArquivo() {
        return $this->sNomeArquivo;
    }

    public function setNomesArquivos($sNomeArquivo) {

        $this->aNomesArquivos[] = $sNomeArquivo;

    }

    public function getNomesArquivos() {
        return $this->aNomesArquivos;
    }

    public function setAno($iAnoUsu) {
        $this->iAnoUsu = $iAnoUsu;
    }

    public function setInstit() {

        $sql = 'select id_instit 
                    from db_config 
                        join db_userinst on db_config.codigo = db_userinst.id_instit 
                        join db_usuarios on db_usuarios.id_usuario=db_userinst.id_usuario 
                    where db_usuarios.id_usuario = '.db_getsession("DB_id_usuario");

        $result = db_query($sql);

        $filtro = "";
        if (pg_num_rows($result) > 0) {

            for ($i = 0; $i < pg_numrows($result); $i++) {
                $filtro .= db_utils::fieldsMemory($result, $i)->id_instit;
                if($i+1 < pg_num_rows($result)) {
                    $filtro .= '-';
                }
            }
        }

        $this->sInstit = $filtro;

    }

    public function setBimestre($iBimestre) {
        $this->iBimestre = $iBimestre;
    }

    public function getErroSQL() {
        return $this->iErroSQL;
    }

    public function setErroSQL($iErroSQL) {
        $this->iErroSQL = $iErroSQL;
    }

    /**
     * Adiciona filtros da institui��o, fun��o 12 (Educa��o) e todos os org�os
     */
    public function setFiltrosDespesa() {

        $clorcorgao       = new cl_orcorgao;
        $instits          = str_replace('-', ', ', $this->sInstit);
        $result           = db_query($clorcorgao->sql_query_file('', '', 'o40_orgao', 'o40_orgao asc', 'o40_instit in ('.$instits.') and o40_anousu = '.$this->iAnoUsu));
        $this->sFiltros    = "instit_{$this->sInstit}-funcao_10-";

        if (pg_num_rows($result) > 0) {
            for ($i = 0; $i < pg_numrows($result); $i++) {
                $this->sFiltros .= "orgao_".db_utils::fieldsMemory($result, $i)->o40_orgao."-";
            }
        } else {
            $this->sFiltros = 'geral';
        }

    }

    /**
     * Adiciona filtros de todas as institui��es
     */
    public function setFiltrosReceita() {

        $sql = 'select id_instit 
                    from db_config 
                        join db_userinst on db_config.codigo = db_userinst.id_instit 
                        join db_usuarios on db_usuarios.id_usuario=db_userinst.id_usuario 
                    where db_usuarios.id_usuario = '.db_getsession("DB_id_usuario");

        $result = db_query($sql);

        if (pg_num_rows($result) > 0) {
            $filtro = 'o70_instit in (';

            for ($i = 0; $i < pg_numrows($result); $i++) {
                $filtro .= db_utils::fieldsMemory($result, $i)->id_instit;
                if($i+1 < pg_num_rows($result)) {
                    $filtro .= ',';
                }
            }
            $filtro .= ')';
        }

        $this->sFiltros = $filtro;

    }

    /**
     * Retorna datas correspondente ao per�odo do bimestre, sempre cumulativo.
     */
    public function setPeriodo() {

        $iBimestre  = $this->iBimestre;
        $dtData     = new \DateTime("{$this->iAnoUsu}-01-01");
        $dtIni      = new \DateTime("{$this->iAnoUsu}-01-01");


        if($iBimestre == 1) {
            $dtData->modify('last day of next month');
        } elseif($iBimestre == 2) {
            $dtData->modify('last day of April');
        } elseif($iBimestre == 3) {
            $dtData->modify('last day of June');
        } elseif($iBimestre == 4) {
            $dtData->modify('last day of August');
        } elseif($iBimestre == 5) {
            $dtData->modify('last day of October');
        } elseif($iBimestre == 6) {
            $dtData->modify('last day of December');
        }

        $this->dtIni = $dtIni->format('Y-m-d');
        $this->dtFim = $dtData->format('Y-m-d');

    }

    /**
     * Busca as despesas conforme
     */
    public function setDespesas() {

        $clselorcdotacao = new cl_selorcdotacao();
        $clselorcdotacao->setDados($this->sFiltros);

        $instits          = str_replace('-', ', ', $this->sInstit);
        $sele_work  = $clselorcdotacao->getDados(false, true) . " and o58_instit in ($instits) and  o58_anousu=$this->iAnoUsu  ";
        $sqlprinc   = db_dotacaosaldo(8, 1, 4, true, $sele_work, $this->iAnoUsu, $this->dtIni, $this->dtFim, 8, 0, true);
        $result     = db_query($sqlprinc) or die(pg_last_error());

        if (pg_num_rows($result) == 0) {
            throw new Exception ("Nenhum registro encontrado.");
        }

        /**
         * Organiza despesas com respectivos desdobramentos.
         * Realiza De/Para da despesa com natureza siope.
         */
        for ($i = 0; $i < pg_numrows($result); $i++) {

            $oDespesa = db_utils::fieldsMemory($result, $i);

            if ($oDespesa->o58_codigo > 0) {

                if ($oDespesa->o58_elemento != "") {

                    $sele_work2         = " 1=1 and o58_orgao in ({$oDespesa->o58_orgao}) and ( ( o58_orgao = {$oDespesa->o58_orgao} and o58_unidade = {$oDespesa->o58_unidade} ) ) and o58_funcao in ({$oDespesa->o58_funcao}) and o58_subfuncao in ({$oDespesa->o58_subfuncao}) and o58_programa in ({$oDespesa->o58_programa}) and o58_projativ in ({$oDespesa->o58_projativ}) and (o56_elemento like '" . substr($oDespesa->o58_elemento, 0, 7) . "%') and o58_codigo in ({$oDespesa->o58_codigo}) and o58_instit in ({$instits}) and o58_anousu={$this->iAnoUsu} ";
                    $cldesdobramento    = new cl_desdobramento();
                    $resDepsMes         = db_query($cldesdobramento->sql($sele_work2, $this->dtIni, $this->dtFim, "({$instits})",'')) or die($cldesdobramento->sql($sele_work2, $this->dtIni, $this->dtFim, "({$instits})",'') . pg_last_error());
                    $oNaturdessiope     = $this->getNaturDesSiops($oDespesa->o58_elemento);
                    $sHashDesp          = $oDespesa->o58_elemento;
                    $aDadosAgrupados    = array();

                    if (!isset($aDadosAgrupados[$sHashDesp])) {

                        $aArrayTemp     = array();

                        $aArrayTemp['cod_planilha']     = $this->getCodPlanilha($oDespesa);
                        $aArrayTemp['elemento_siops']   = $oNaturdessiope->c227_eledespsiops;
                        $aArrayTemp['campo_siops']      = $oNaturdessiope->c227_campo;
                        $aArrayTemp['linha_siops']      = $oNaturdessiope->c227_linha;
                        $aArrayTemp['dot_inicial']      = $oDespesa->dot_ini;
                        $aArrayTemp['dot_atualizada']   = ($oDespesa->dot_ini + $oDespesa->suplementado_acumulado - $oDespesa->reduzido_acumulado);
                        $aArrayTemp['inscritas_rpnp']   = 0;
                        $aArrayTemp['empenhado']        = 0;
                        $aArrayTemp['liquidado']        = 0;
                        $aArrayTemp['pagamento']        = 0;

                        array_push($this->aDespesas, $aArrayTemp);

                    }

                    for ($contDesp = 0; $contDesp < pg_num_rows($resDepsMes); $contDesp++) {

                        $oDadosMes          = db_utils::fieldsMemory($resDepsMes, $contDesp);
                        $oNaturdessiopeDesd = $this->getNaturDesSiops($oDadosMes->o56_elemento);
                        $sHashDespDesd      = $oDadosMes->o56_elemento;

                        if (isset($aDadosAgrupados[$sHashDesp])) {

                            $aArrayDesdTemp = array();

                            $aArrayDesdTemp['cod_planilha']     = $this->getCodPlanilha($oDespesa);
                            $aArrayDesdTemp['elemento_siops']   = $oNaturdessiopeDesd->c227_eledespsiops;
                            $aArrayDesdTemp['campo_siops']      = $oNaturdessiopeDesd->c227_campo;
                            $aArrayDesdTemp['linha_siops']      = $oNaturdessiopeDesd->c227_linha;
                            $aArrayDesdTemp['dot_inicial']      = 0;
                            $aArrayDesdTemp['dot_atualizada']   = 0;
                            $aArrayDesdTemp['inscritas_rpnp']   = $this->lOrcada ? round((round(($oDadosMes->empenhado - $oDadosMes->empenhado_estornado), 2) - round(($oDadosMes->liquidado - $oDadosMes->liquidado_estornado), 2)), 2) : 0;
                            $aArrayDesdTemp['empenhado']        = ($oDadosMes->empenhado - $oDadosMes->empenhado_estornado);
                            $aArrayDesdTemp['liquidado']        = ($oDadosMes->liquidado - $oDadosMes->liquidado_estornado);
                            $aArrayDesdTemp['pagamento']        = ($oDadosMes->pagamento - $oDadosMes->pagamento_estornado);

                            array_push($this->aDespesas, $aArrayDesdTemp);

                        } else {

                            $aArrayDesdTemp = array();

                            $aArrayDesdTemp['cod_planilha']     = $this->getCodPlanilha($oDespesa);
                            $aArrayDesdTemp['elemento_siops']   = $oNaturdessiopeDesd->c227_eledespsiops;
                            $aArrayDesdTemp['campo_siops']      = $oNaturdessiopeDesd->c227_campo;
                            $aArrayDesdTemp['linha_siops']      = $oNaturdessiopeDesd->c227_linha;
                            $aArrayDesdTemp['dot_inicial']      = 0;
                            $aArrayDesdTemp['dot_atualizada']   = 0;
                            $aArrayDesdTemp['inscritas_rpnp']   = $this->lOrcada ? round((round(($oDadosMes->empenhado - $oDadosMes->empenhado_estornado), 2) - round(($oDadosMes->liquidado - $oDadosMes->liquidado_estornado), 2)), 2) : 0;
                            $aArrayDesdTemp['empenhado']        = ($oDadosMes->empenhado - $oDadosMes->empenhado_estornado);
                            $aArrayDesdTemp['liquidado']        = ($oDadosMes->liquidado - $oDadosMes->liquidado_estornado);
                            $aArrayDesdTemp['pagamento']        = ($oDadosMes->pagamento - $oDadosMes->pagamento_estornado);

                            array_push($this->aDespesas, $aArrayDesdTemp);
                        }

                    }

                }

            }

        }

        /**
         * Organiza despesas do ano subsequente.
         */
        if ($this->lOrcada) {

            /**
             * Caso seja 6� Bimestre, campo OR�ADO ser� alimentado atrav�s do relat�rio Balancete da Despesa no exerc�cio subsequente ao de refer�ncia.
             */
            $iAnoSeg          = $this->iAnoUsu+1;
            $sele_workAnoSeg  = $clselorcdotacao->getDados(false, true) . " and o58_instit in ($instits) and  o58_anousu=$iAnoSeg  ";
            $sqlprincAnoSeg   = db_dotacaosaldo(8, 1, 4, true, $sele_workAnoSeg, $iAnoSeg, "$iAnoSeg-01-01", "$iAnoSeg-01-01", 8, 0, true);
            $resultAnoSeg     = db_query($sqlprincAnoSeg) or die(pg_last_error());

            for ($i = 0; $i < pg_numrows($resultAnoSeg); $i++) {

                $oDespesaAnoSeg = db_utils::fieldsMemory($resultAnoSeg, $i);

                if ($oDespesaAnoSeg->o58_codigo > 0) {

                    if ($oDespesaAnoSeg->o58_elemento != "") {

                        $sele_work2 = " 1=1 and o58_orgao in ({$oDespesaAnoSeg->o58_orgao}) and ( ( o58_orgao = {$oDespesaAnoSeg->o58_orgao} and o58_unidade = {$oDespesaAnoSeg->o58_unidade} ) ) and o58_funcao in ({$oDespesaAnoSeg->o58_funcao}) and o58_subfuncao in ({$oDespesaAnoSeg->o58_subfuncao}) and o58_programa in ({$oDespesaAnoSeg->o58_programa}) and o58_projativ in ({$oDespesaAnoSeg->o58_projativ}) and (o56_elemento like '" . substr($oDespesaAnoSeg->o58_elemento, 0, 7) . "%') and o58_codigo in ({$oDespesaAnoSeg->o58_codigo}) and o58_instit in ({$instits}) and o58_anousu={$this->iAnoUsu} ";
                        $cldesdobramento = new cl_desdobramento();
                        $resDepsMes = db_query($cldesdobramento->sql($sele_work2, $this->dtIni, $this->dtFim, "({$instits})", '')) or die($cldesdobramento->sql($sele_work2, $this->dtIni, $this->dtFim, "({$instits})", '') . pg_last_error());
                        $oNaturdessiops = $this->getNaturDesSiops($oDespesaAnoSeg->o58_elemento);
                        $sHashDesp = $oDespesaAnoSeg->o58_elemento;
                        $aDadosAgrupados = array();

                        if (!isset($aDadosAgrupados[$sHashDesp])) {

                            $aArrayTemp = array();

                            $aArrayTemp['cod_planilha']     = $this->getCodPlanilha($oDespesaAnoSeg);
                            $aArrayTemp['elemento_siops']   = $oNaturdessiops->c227_eledespsiops;
                            $aArrayTemp['campo_siops']      = $oNaturdessiops->c227_campo;
                            $aArrayTemp['linha_siops']      = $oNaturdessiops->c227_linha;
                            $aArrayTemp['desp_orcada']      = $oDespesaAnoSeg->dot_ini;

                            array_push($this->aDespesasAnoSeg, $aArrayTemp);

                        }

                    }

                }

            }

        }

    }

    /**
     * Agrupa despesas somando valores pelo C�DIGO DE PLANILHA e ELEMENTO DA DESPESA iguais.
     */
    public function agrupaDespesas() {

        $aDespAgrup = array();

        foreach ($this->aDespesas as $row) {

            list($cod_planilha, $elemento_siops, $campo_siops, $linha_siops, $dot_inicial, $dot_atualizada, $inscritas_rpnp, $empenhado, $liquidado, $pagamento) = array_values($row);

            $iSubTotalDotIni = isset($aDespAgrup[$cod_planilha][$elemento_siops]['dot_inicial']) ? $aDespAgrup[$cod_planilha][$elemento_siops]['dot_inicial'] : 0;
            $iSubTotalDotAtu = isset($aDespAgrup[$cod_planilha][$elemento_siops]['dot_atualizada']) ? $aDespAgrup[$cod_planilha][$elemento_siops]['dot_atualizada'] : 0;
            $iSubTotalInsc   = isset($aDespAgrup[$cod_planilha][$elemento_siops]['inscritas_rpnp']) ? $aDespAgrup[$cod_planilha][$elemento_siops]['inscritas_rpnp'] : 0;
            $iSubTotalEmp    = isset($aDespAgrup[$cod_planilha][$elemento_siops]['empenhado']) ? $aDespAgrup[$cod_planilha][$elemento_siops]['empenhado'] : 0;
            $iSubTotalLiq    = isset($aDespAgrup[$cod_planilha][$elemento_siops]['liquidado']) ? $aDespAgrup[$cod_planilha][$elemento_siops]['liquidado'] : 0;
            $iSubTotalPag    = isset($aDespAgrup[$cod_planilha][$elemento_siops]['pagamento']) ? $aDespAgrup[$cod_planilha][$elemento_siops]['pagamento'] : 0;

            $aDespAgrup[$cod_planilha][$elemento_siops]['cod_planilha']     = $cod_planilha;
            $aDespAgrup[$cod_planilha][$elemento_siops]['elemento_siops']   = $elemento_siops;
            $aDespAgrup[$cod_planilha][$elemento_siops]['campo_siops']      = $campo_siops;
            $aDespAgrup[$cod_planilha][$elemento_siops]['linha_siops']      = $linha_siops;
            $aDespAgrup[$cod_planilha][$elemento_siops]['dot_inicial']      = ($iSubTotalDotIni + $dot_inicial);
            $aDespAgrup[$cod_planilha][$elemento_siops]['dot_atualizada']   = ($iSubTotalDotAtu + $dot_atualizada);
            $aDespAgrup[$cod_planilha][$elemento_siops]['inscritas_rpnp']   = ($iSubTotalInsc + $inscritas_rpnp);
            $aDespAgrup[$cod_planilha][$elemento_siops]['empenhado']        = ($iSubTotalEmp + $empenhado);
            $aDespAgrup[$cod_planilha][$elemento_siops]['liquidado']        = ($iSubTotalLiq + $liquidado);
            $aDespAgrup[$cod_planilha][$elemento_siops]['pagamento']        = ($iSubTotalPag + $pagamento);
            $aDespAgrup[$cod_planilha][$elemento_siops]['desp_orcada']      = 0;

        }

        if ($this->lOrcada) {

            $aDespAgrupAnoSeg = array();

            foreach ($this->aDespesasAnoSeg as $row) {

                list($cod_planilha, $elemento_siops, $campo_siops, $linha_siops, $desp_orcada) = array_values($row);

                $iSubTotalDespOrc = isset($aDespAgrupAnoSeg[$cod_planilha][$elemento_siops]['desp_orcada']) ? $aDespAgrupAnoSeg[$cod_planilha][$elemento_siops]['desp_orcada'] : 0;

                $aDespAgrupAnoSeg[$cod_planilha][$elemento_siops]['cod_planilha']      = $cod_planilha;
                $aDespAgrupAnoSeg[$cod_planilha][$elemento_siops]['elemento_siops']    = $elemento_siops;
                $aDespAgrupAnoSeg[$cod_planilha][$elemento_siops]['campo_siops']       = $campo_siops;
                $aDespAgrupAnoSeg[$cod_planilha][$elemento_siops]['linha_siops']       = $linha_siops;
                $aDespAgrupAnoSeg[$cod_planilha][$elemento_siops]['desp_orcada']       = ($iSubTotalDespOrc + $desp_orcada);

            }

            /**
             * Une os dois arrays do ano corrente com o ano seguinte.
             * ***Pode haver registros no ano seguinte que n�o est�o no ano corrente.***
             */

            foreach ($aDespAgrup as $cod => $despesa) {

                foreach ($despesa as $elem => $desp) {

                    if (isset($aDespAgrupAnoSeg[$cod][$elem])) {
                        $desp['desp_orcada'] = $aDespAgrupAnoSeg[$cod][$elem]['desp_orcada'];
                        $aDespAgrupAnoSeg[$cod][$elem]['flag'] = 1;
                        $this->aDespesasAgrupadasFinal[$cod][$elem] = $desp;
                    } else {
                        $aDespAgrupAnoSeg[$cod][$elem]['flag'] = 1;
                        $desp['desp_orcada'] = 0;
                        $this->aDespesasAgrupadasFinal[$cod][$elem] = $desp;
                    }

                }

            }

            foreach ($aDespAgrupAnoSeg as $cod => $despesa) {

                foreach ($despesa as $elem => $desp) {

                    if (!isset($desp['flag']) || $desp['flag'] != 1) {
                        $desp['dot_inicial'] = $aDespAgrup[$cod][$elem]['dot_inicial'];
                        $desp['dot_atualizada'] = $aDespAgrup[$cod][$elem]['dot_atualizada'];
                        $desp['inscritas_rpnp'] = $aDespAgrup[$cod][$elem]['inscritas_rpnp'];
                        $desp['empenhado'] = $aDespAgrup[$cod][$elem]['empenhado'];
                        $desp['liquidado'] = $aDespAgrup[$cod][$elem]['liquidado'];
                        $desp['pagamento'] = $aDespAgrup[$cod][$elem]['pagamento'];
                        $this->aDespesasAgrupadasFinal[$cod][$elem] = $desp;
                    }

                }

            }

        } else {

            $this->aDespesasAgrupadasFinal = $aDespAgrup;

        }

    }

    public function agrupaReceitas() {

        $aReceitaAgrup = array();

        foreach ($this->aReceitas as $row) {

            list($rec_realizada, $ded_receita, $rec_asps, $ded_fundeb, $natureza, $campo, $linha, $prev_inicial, $prev_atualizada, $total_receitas) = array_values($row);

            $iSubRecRealizada       = isset($aReceitaAgrup[$natureza]['rec_realizada']) ? $aReceitaAgrup[$natureza]['rec_realizada'] : 0;
            $iSubDedReceita         = isset($aReceitaAgrup[$natureza]['ded_receita']) ? $aReceitaAgrup[$natureza]['ded_receita'] : 0;
            $iSubRecAsps            = isset($aReceitaAgrup[$natureza]['rec_asps']) ? $aReceitaAgrup[$natureza]['rec_asps'] : 0;
            $iSubDedFundeb          = isset($aReceitaAgrup[$natureza]['ded_fundeb']) ? $aReceitaAgrup[$natureza]['ded_fundeb'] : 0;
            $iSubPrevInicial        = isset($aReceitaAgrup[$natureza]['prev_inicial']) ? $aReceitaAgrup[$natureza]['prev_inicial'] : 0;
            $iSubPrevAtualizada     = isset($aReceitaAgrup[$natureza]['prev_atualizada']) ? $aReceitaAgrup[$natureza]['prev_atualizada'] : 0;
            $iSubTotReceitas        = isset($aReceitaAgrup[$natureza]['total_receitas']) ? $aReceitaAgrup[$natureza]['total_receitas'] : 0;

            $aReceitaAgrup[$natureza]['rec_realizada']      = ($iSubRecRealizada + $rec_realizada);
            $aReceitaAgrup[$natureza]['ded_receita']        = ($iSubDedReceita + $ded_receita);
            $aReceitaAgrup[$natureza]['rec_asps']           = ($iSubRecAsps + $rec_asps);
            $aReceitaAgrup[$natureza]['ded_fundeb']         = ($iSubDedFundeb + $ded_fundeb);
            $aReceitaAgrup[$natureza]['prev_inicial']       = ($iSubPrevInicial + $prev_inicial);
            $aReceitaAgrup[$natureza]['prev_atualizada']    = ($iSubPrevAtualizada + $prev_atualizada);
            $aReceitaAgrup[$natureza]['total_receitas']     = ($iSubTotReceitas + $total_receitas);
            $aReceitaAgrup[$natureza]['natureza']           = $natureza;
            $aReceitaAgrup[$natureza]['campo']              = $campo;
            $aReceitaAgrup[$natureza]['linha']              = $linha;
            $aReceitaAgrup[$natureza]['rec_orcada']         = 0;

        }

        if ($this->lOrcada) {

            $aRecAgrupAnoSeg = array();

            foreach ($this->aReceitasAnoSeg as $row) {

                list($natureza, $campo, $linha, $rec_orcada) = array_values($row);

                $iSubTotalRecOrc = isset($aRecAgrupAnoSeg[$natureza]['rec_orcada']) ? $aRecAgrupAnoSeg[$natureza]['rec_orcada'] : 0;

                $aRecAgrupAnoSeg[$natureza]['natureza']     = $natureza;
                $aRecAgrupAnoSeg[$natureza]['campo']        = $campo;
                $aRecAgrupAnoSeg[$natureza]['linha']        = $linha;
                $aRecAgrupAnoSeg[$natureza]['rec_orcada']   = ($iSubTotalRecOrc + $rec_orcada);

            }

            /**
             * Une os dois arrays do ano corrente com o ano seguinte.
             * ***Pode haver registros no ano seguinte que n�o est�o no ano corrente.***
             */

            foreach ($aReceitaAgrup as $cod => $receita) {

                if (isset($aRecAgrupAnoSeg[$cod])) {
                    $receita['rec_orcada']                  = $aRecAgrupAnoSeg[$cod]['rec_orcada'];
                    $aRecAgrupAnoSeg[$cod]['flag']          = 1;
                    $this->aReceitasAgrupadasFinal[$cod]    = $receita;
                } else {
                    $aRecAgrupAnoSeg[$cod]['flag']          = 1;
                    $receita['rec_orcada']                  = 0;
                    $this->aReceitasAgrupadasFinal[$cod]    = $receita;
                }

            }

            foreach ($aRecAgrupAnoSeg as $cod => $receita) {

                if (!isset($receita['flag']) || $receita['flag'] != 1) {
                    $receita['rec_realizada']               = $aReceitaAgrup[$cod]['rec_realizada'];
                    $receita['ded_receita']                 = $aReceitaAgrup[$cod]['ded_receita'];
                    $receita['rec_asps']                    = $aReceitaAgrup[$cod]['rec_asps'];
                    $receita['ded_fundeb']                  = $aReceitaAgrup[$cod]['ded_fundeb'];
                    $receita['prev_inicial']                = $aReceitaAgrup[$cod]['prev_inicial'];
                    $receita['prev_atualizada']             = $aReceitaAgrup[$cod]['prev_atualizada'];
                    $receita['total_receitas']              = $aReceitaAgrup[$cod]['total_receitas'];
                    $this->aReceitasAgrupadasFinal[$cod]    = $receita;
                }

            }

        } else {

            $this->aReceitasAgrupadasFinal = $aReceitaAgrup;

        }
    }

    public function agrupaInfoComplementares() {

        $aInfoComplementaresAgrup = array();
        
        usort($this->aInfoComplementares, function($a, $b) {
            $numA = (int) filter_var($a['linha'], FILTER_SANITIZE_NUMBER_INT);
            $numB = (int) filter_var($b['linha'], FILTER_SANITIZE_NUMBER_INT);
            return $numA - $numB;
        });
        
        foreach ($this->aInfoComplementares as $row) {

            list($rec_realizada, $natureza, $campo, $prev_inicial, $prev_atualizada, $coluna, $linha) = array_values($row);

            $iSubRecRealizada       = isset($aInfoComplementaresAgrup[$natureza.$campo]['rec_realizada']) ? $aInfoComplementaresAgrup[$natureza.$campo]['rec_realizada'] : 0;
            $iSubPrevInicial        = isset($aInfoComplementaresAgrup[$natureza.$campo]['prev_inicial']) ? $aInfoComplementaresAgrup[$natureza.$campo]['prev_inicial'] : 0;
            $iSubPrevAtualizada     = isset($aInfoComplementaresAgrup[$natureza.$campo]['prev_atualizada']) ? $aInfoComplementaresAgrup[$natureza.$campo]['prev_atualizada'] : 0;
  
            $aInfoComplementaresAgrup[$natureza.$campo]['rec_realizada']      = ($iSubRecRealizada + $rec_realizada);
            $aInfoComplementaresAgrup[$natureza.$campo]['prev_inicial']       = ($iSubPrevInicial + $prev_inicial);
            $aInfoComplementaresAgrup[$natureza.$campo]['prev_atualizada']    = ($iSubPrevAtualizada + $prev_atualizada);
            $aInfoComplementaresAgrup[$natureza.$campo]['natureza']           = $natureza;
            $aInfoComplementaresAgrup[$natureza.$campo]['campo']              = $campo;
            $aInfoComplementaresAgrup[$natureza.$campo]['coluna']             = $coluna;
            $aInfoComplementaresAgrup[$natureza.$campo]['linha']              = $linha;
            
        }
      
        if ($this->lOrcada) {

            $aInfoComplementaresAgrupAnoSeg = array();

           foreach ($this->aInfoComplementaresAnoSeg as $row) {

              list($natureza, $campo, $linha, $rec_orcada) = array_values($row);

              $aInfoComplementaresAgrupAnoSeg[$natureza.$campo]['natureza']     = $natureza;
              $aInfoComplementaresAgrupAnoSeg[$natureza.$campo]['campo']        = $campo;
              $aInfoComplementaresAgrupAnoSeg[$natureza.$campo]['coluna']       = $coluna;
              $aInfoComplementaresAgrupAnoSeg[$natureza.$campo]['linha']        = $linha;

          }

          /**
           * Une os dois arrays do ano corrente com o ano seguinte.
           * ***Pode haver registros no ano seguinte que n�o est�o no ano corrente.***
           */

          foreach ($aInfoComplementaresAgrup as $cod => $receita) {

              if (isset($aInfoComplementaresAgrupAnoSeg[$cod])) {
                  $receita['rec_orcada']                  = $aInfoComplementaresAgrupAnoSeg[$cod]['rec_orcada'];
                  $aInfoComplementaresAgrupAnoSeg[$cod]['flag']          = 1;
                  $this->aInfoComplementaresAgrupadasFinal[$cod]    = $receita;
              } else {
                  $aInfoComplementaresAgrupAnoSeg[$cod]['flag']          = 1;
                  $receita['rec_orcada']                  = 0;
                  $this->aInfoComplementaresAgrupadasFinal[$cod]    = $receita;
              }

          }

          foreach ($aInfoComplementaresAgrupAnoSeg as $cod => $receita) {

              if (!isset($receita['flag']) || $receita['flag'] != 1) {
                  $receita['rec_realizada']               = $aInfoComplementaresAgrup[$cod]['rec_realizada'];
                  $receita['prev_inicial']                = $aInfoComplementaresAgrup[$cod]['prev_inicial'];
                  $receita['prev_atualizada']             = $aInfoComplementaresAgrup[$cod]['prev_atualizada'];
                  $this->aInfoComplementaresAgrupadasFinal[$cod]    = $receita;
              }

          }

      } else { 
          $this->aInfoComplementaresAgrupadasFinal = $aInfoComplementaresAgrup;

      }

    }

    public function getDespesas() {
        return $this->aDespesas;
    }

    /**
     * Busca as receitas conforme
     */
    public function setReceitas() {

        $result = db_receitasaldo(11,1,3,true,$this->sFiltros,$this->iAnoUsu,$this->dtIni,$this->dtFim,false,' * ',true,0);

        for ($i = 0; $i < pg_num_rows($result); $i++) {

            $oReceita = db_utils::fieldsMemory($result, $i);
            
            if ($oReceita->o70_codrec > 0) {
                
                $oNaturrecsiops = $this->getNaturRecSiops($oReceita->o57_fonte);

                $aReceita = array();

                $aReceita['rec_realizada']      = 0;
                $aReceita['ded_receita']        = 0;
                $aReceita['rec_asps']           = 0;
                $aReceita['ded_fundeb']         = 0;

                if(in_array(substr($oReceita->o57_fonte, 0, 2), array('41', '42', '47', '48'))) {
                    $aReceita['rec_realizada']  = abs($oReceita->saldo_arrecadado);
                }

                if(in_array(substr($oReceita->o57_fonte, 0, 3), array('491', '492', '493', '496', '498', '499'))) {
                    $aReceita['ded_receita']    = abs($oReceita->saldo_arrecadado);
                }

                if(in_array($oNaturrecsiops->c231_elerecsiops, array('11120100', '11120200', '11120431', '11120434', '11120800', '11130501', '11130502', '11130600', '17210102', '17210105', '17213600', '17220101', '17220102', '17220104', '19110800', '19113800', '19113900', '19114000', '19114400', '19130800', '19131100', '19131200', '19131300', '19132500', '19310400', '19311100', '19311200', '19311300', '19312100', '71120100', '71120400', '71130600'))) {
                    $aReceita['rec_asps']       = abs(($aReceita['rec_realizada'] - $aReceita['ded_receita']));
                }

                if(substr($oReceita->o57_fonte, 0, 3) == '495') {
                    $aReceita['ded_fundeb']     = abs($oReceita->saldo_arrecadado);
                }

                $aReceita['natureza']           = $oNaturrecsiops->c231_elerecsiops;
                $aReceita['campo']              = $oNaturrecsiops->c231_campo;
                $aReceita['linha']              = $oNaturrecsiops->c231_linha;
                
                if (!in_array(substr($oReceita->o57_fonte, 1, 2), array('91', '92', '93', '95', '96', '98', '99'))) {

                    $aReceita['prev_inicial']       = $oReceita->saldo_inicial;
                    $aReceita['prev_atualizada']    = ($oReceita->saldo_inicial + $oReceita->saldo_prevadic_acum);
                    $aReceita['total_receitas']     = ($aReceita['rec_realizada'] - ($aReceita['ded_receita'] + $aReceita['ded_fundeb']));
                }

                array_push($this->aReceitas, $aReceita);

            }

        }

        /**
         * Caso seja 6� Bimestre, campo OR�ADO ser� alimentado atrav�s do relat�rio Balancete da Receita no exerc�cio subsequente ao de refer�ncia.
         */
        if ($this->lOrcada) {

            db_query('drop table work_receita');
            $iAnoSeg = $this->iAnoUsu+1;
            $resultAnoSeg = db_receitasaldo(11, 1, 3, true, $this->sFiltros, $iAnoSeg, "{$iAnoSeg}-01-01", "{$iAnoSeg}-01-01", false, ' * ', true, 0);

            for ($i = 0; $i < pg_num_rows($resultAnoSeg); $i++) {

                $oReceitaAnoSeg = db_utils::fieldsMemory($resultAnoSeg, $i);

                if ($oReceitaAnoSeg->o70_codrec > 0) {

                    $oNaturrecsiops = $this->getNaturRecSiops($oReceitaAnoSeg->o57_fonte);

                    $aReceitaAnoSeg = array();

                    $aReceitaAnoSeg['natureza']     = $oNaturrecsiops->c231_elerecsiops;
                    $aReceitaAnoSeg['campo']        = $oNaturrecsiops->c231_campo;
                    $aReceitaAnoSeg['linha']        = $oNaturrecsiops->c231_linha;
                    $aReceitaAnoSeg['rec_orcada']   = $oReceitaAnoSeg->saldo_inicial;

                    array_push($this->aReceitasAnoSeg, $aReceitaAnoSeg);

                }

            }

        }

    }

     /**
     * Busca as receitas conforme
     */
    public function setInfoComplementares() {

        $result = db_receitasaldo(11,1,3,true,$this->sFiltros,$this->iAnoUsu,$this->dtIni,$this->dtFim,false,' * ',true,0);

        for ($i = 0; $i < pg_num_rows($result); $i++) {

            $oReceita = db_utils::fieldsMemory($result, $i);
            
            if ($oReceita->o70_codrec > 0) {
                
                $oNaturrecsiops = $this->getNaturInfoComplementaresSiops($oReceita->o57_fonte,$this->iAnoUsu,$oReceita->o70_codigo);
  
                $resultado = verificarReceita(substr($oReceita->o57_fonte,1,6), substr($oReceita->o70_codigo,0,4));
      
                $aInfoComplementar = array();
                if ($resultado) {
                    $aInfoComplementar['rec_realizada']      = 0;

                    $aInfoComplementar['natureza']           = $oNaturrecsiops->c231_elerecsiops;
                    $aInfoComplementar['campo']              = $resultado['codCampo'];
                    $aInfoComplementar['prev_inicial']       = $oReceita->saldo_inicial;
                    $aInfoComplementar['prev_atualizada']    = ($oReceita->saldo_inicial + $oReceita->saldo_prevadic_acum);
                    $aInfoComplementar['rec_realizada']      = abs($oReceita->saldo_arrecadado);
                    $aInfoComplementar['coluna']             = $resultado['coluna'];
                    $aInfoComplementar['linha']              = $resultado['linha'];
                  
    
                    array_push($this->aInfoComplementares, $aInfoComplementar);
                }
               
            }
            
        }
        
        /**
         * Caso seja 6� Bimestre, campo OR�ADO ser� alimentado atrav�s do relat�rio Balancete da Receita no exerc�cio subsequente ao de refer�ncia.
         */
        if ($this->lOrcada) {

            db_query('drop table work_receita');
            $iAnoSeg = $this->iAnoUsu+1;
            $resultAnoSeg = db_receitasaldo(11, 1, 3, true, $this->sFiltros, $iAnoSeg, "{$iAnoSeg}-01-01", "{$iAnoSeg}-01-01", false, ' * ', true, 0);

            for ($i = 0; $i < pg_num_rows($resultAnoSeg); $i++) {

                $oReceitaAnoSeg = db_utils::fieldsMemory($resultAnoSeg, $i);

                if ($oReceitaAnoSeg->o70_codrec > 0) {

                    $oNaturrecsiops = $this->getNaturInfoComplementaresSiops($oReceita->o57_fonte,$this->iAnoUsu,$oReceita->o70_codigo);

                    $resultado = verificarReceita(substr($oReceita->o57_fonte,1,6), substr($oReceita->o70_codigo,0,4));

                    $aInfoComplementarAnoSeg = array();

                    $aInfoComplementarAnoSeg['natureza']     = $oNaturrecsiops->c231_elerecsiops;
                    $aInfoComplementarAnoSeg['campo']        = $oNaturrecsiops->c231_campo;
                    $aInfoComplementarAnoSeg['linha']        = $oNaturrecsiops->c231_linha;
                    $aInfoComplementarAnoSeg['rec_orcada']   = $oReceitaAnoSeg->saldo_inicial;
                    $aInfoComplementarAnoSeg['coluna']       = $resultado['coluna'];
                    $aInfoComplementarAnoSeg['linha']        = $resultado['linha'];

                    array_push($this->aInfoComplementaresAnoSeg, $aInfoComplementarAnoSeg);

                }

            }

        }

    }

    /**
     * C�d Planilha recebe valor de acordo com fonte de recursos, subfun��o, tipo de ensino siope e tipo de pasta siope.
     */
    public function getCodPlanilha($oDespesa)
    {

        $iCod = '10_';

        if (in_array($oDespesa->o58_codigo, array(15000000, 25000000))) {
            $iCod = '3_';
        }

        if (in_array($oDespesa->o58_codigo, array(15000002, 25000002))) {
            $iCod = '4_';
        }

        if (in_array($oDespesa->o58_codigo, array(16210000, 26210000))) {
            $iCod = '6_';
        }

        if (in_array($oDespesa->o58_codigo, array(16310000, 16320000, 16330000, 16360000, 26310000, 26320000, 26330000, 26360000))) {
            $iCod = '7_';
        }

        if (in_array($oDespesa->o58_codigo, array(16340000, 17540000, 17540000, 26340000, 27540000, 27540000))) {
            $iCod = '8_';
        }

        if (in_array($oDespesa->o58_codigo, array(16350000, 26350000))) {
            $iCod = '9_';
        }

        if (in_array($oDespesa->o58_codigo, array(16590000, 16000000, 26590000, 26000000))) {

            $iCod = '86_';
        }

        if (in_array($oDespesa->o58_codigo, array(16010000, 26010000))) {

            $iCod = '88_';
        }

        if (in_array($oDespesa->o58_codigo, array(16040000, 26040000))) {
            $iCod = '94_';
        }
        if (in_array($oDespesa->o58_codigo, array(16050000, 26050000))) {
            $iCod = '95_';
        }

        if ((strpos(strtolower($oDespesa->o55_descr), 'covid'))) {
            
            if (in_array($oDespesa->o58_codigo, array(16590000, 16000000, 26590000, 26000000))) {
                $iCod = '87_';
            }

            if (in_array($oDespesa->o58_codigo, array(16010000, 26010000))) {
                $iCod = '89_';
            }
        }

        if (in_array($oDespesa->o58_codigo, array(17070000, 27070000)) && $oDespesa->o58_funcao == '10') {
            $iCod = '90_';
        }

        if (in_array($oDespesa->o58_subfuncao, array(121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131))) {
            return $iCod . '11';
        } elseif ($oDespesa->o58_subfuncao == 301) {
            return $iCod . '12';
        } elseif ($oDespesa->o58_subfuncao == 302) {
            return $iCod . '13';
        } elseif ($oDespesa->o58_subfuncao == 303) {
            return $iCod . '14';
        } elseif ($oDespesa->o58_subfuncao == 304) {
            return $iCod . '15';
        } elseif ($oDespesa->o58_subfuncao == 305) {
            return $iCod . '16';
        } elseif ($oDespesa->o58_subfuncao == 306) {
            return $iCod . '17';
        } else {
            return $iCod . '18';
        }
    }


    /**
     * Realiza De/Para da Natureza da despesa com tabela eledessiope composta pelo C�d Elemento e Descri��o
     */
    public function getNaturDesSiops($elemento) {

        $clnaturdessiops    = new cl_naturdessiops();
        $rsNaturdessiops    = db_query($clnaturdessiops->sql_query_siops(substr($elemento, 1, 10),"", $this->iAnoUsu));

        if (pg_num_rows($rsNaturdessiops) > 0) {
            $oNaturdessiops = db_utils::fieldsMemory($rsNaturdessiops, 0);
            return $oNaturdessiops;
        } else {
            $this->status = 2;
            if (strpos($this->sMensagem, $elemento) === false){
                $this->sMensagem .= "{$elemento} ";
            }
        }

    }

    /**
     * Realiza De/Para da Natureza da despesa com tabela elerecsiops composta pela Natureza Receita e Descri��o
     */
    public function getNaturRecSiops($natureza)
    {
        $sSqlNatRec = "SELECT c231_elerecsiops, c231_campo, c231_linha FROM elerecsiops
                       INNER JOIN naturrecsiops on c230_natrecsiops = c231_elerecsiops and c230_anousu = c231_anousu 
                       WHERE c231_anousu = {$this->iAnoUsu}
                         AND c230_natrececidade = substr('{$natureza}',2,length(rtrim(c231_elerecsiops,'')))
                       ORDER BY 1 DESC LIMIT 1";

        $rsNewNaturrecsiops = db_query($sSqlNatRec);

        if (pg_num_rows($rsNewNaturrecsiops) > 0) {
            
            $oNaturrecsiops = db_utils::fieldsMemory($rsNewNaturrecsiops, 0);        
            return $oNaturrecsiops;
            
        } else {
            
            $clnaturrecsiops    = new cl_naturrecsiops();
             if (substr($natureza,1,1) == '9')
                $natureza = substr($natureza,3,14);
            $rsNaturrecsiops    = db_query($clnaturrecsiops->sql_query_siops(substr($natureza, 0, 8),"", $this->iAnoUsu));
    
            if (pg_num_rows($rsNaturrecsiops) > 0) {
                $oNaturrecsiops = db_utils::fieldsMemory($rsNaturrecsiops, 0);
                if (substr($oNaturrecsiops->c230_natrecsiops,0,1) == '9') {
                    $oNaturrecsiops->c231_elerecsiops = substr($oNaturrecsiops->c230_natrecsiops,2,8);
                } else {
                    $oNaturrecsiops->c231_elerecsiops = substr($oNaturrecsiops->c230_natrecsiops,0,8);
                }                
                return $oNaturrecsiops;
            } else {
                $this->status = 2;
                if (strpos($this->sMensagem, $natureza) === false){
                    $this->sMensagem .= "{$natureza} ";
                }
            }
        }
        

    }

      /**
     * Realiza De/Para da Natureza da despesa com tabela elerecsiops composta pela Natureza Receita e Descri��o
     */
    public function getNaturInfoComplementaresSiops($natureza,$anouso,$fonte)
    {
  
        $sSqlNatRec = "SELECT c231_elerecsiops, c231_campo, c231_linha FROM elerecsiops
        INNER JOIN naturrecsiops on c230_natrecsiops = c231_elerecsiops and c230_anousu = c231_anousu 
        WHERE c231_anousu = {$this->iAnoUsu}
          AND c230_natrececidade = substr('{$natureza}',2,length(rtrim(c231_elerecsiops,'')))
        ORDER BY 1 DESC LIMIT 1";

        $rsNewNaturrecsiops = db_query($sSqlNatRec);
       
        if (pg_num_rows($rsNewNaturrecsiops) > 0) {
            $oNaturrecsiops = db_utils::fieldsMemory($rsNewNaturrecsiops, 0);        
            return $oNaturrecsiops;
            
        } 
        
        if (pg_num_rows($rsNewNaturrecsiops) > 0) {
            
            $oNaturrecsiops = db_utils::fieldsMemory($rsNewNaturrecsiops, 0);        
            return $oNaturrecsiops;
            
        } else {
            
            $clnaturrecsiops    = new cl_naturrecsiops();
             if (substr($natureza,1,1) == '9')
                $natureza = substr($natureza,3,14);
            $rsNaturrecsiops    = db_query($clnaturrecsiops->sql_query_siops(substr($natureza, 0, 8),"", $this->iAnoUsu));
    
            if (pg_num_rows($rsNaturrecsiops) > 0) {
                $oNaturrecsiops = db_utils::fieldsMemory($rsNaturrecsiops, 0);
                if (substr($oNaturrecsiops->c230_natrecsiops,0,1) == '9') {
                    $oNaturrecsiops->c231_elerecsiops = substr($oNaturrecsiops->c230_natrecsiops,2,8);
                } else {
                    $oNaturrecsiops->c231_elerecsiops = substr($oNaturrecsiops->c230_natrecsiops,0,8);
                }                
                return $oNaturrecsiops;
            } else {
                $this->status = 2;
                if (strpos($this->sMensagem, $natureza) === false){
                    $this->sMensagem .= "{$natureza} ";
                }
            }
        }
    }

    /**
     * Se 6� bimestre, set true para buscar os valores dos anos seguintes.
     */
    public function setOrcado() {

        if($this->iBimestre == 6) {
            $this->lOrcada = true;
        } else {
            $this->lOrcada = false;
        }

    }

    public function getElementoFormat($elemento) {
        return substr($elemento, 0, 1).".".substr($elemento, 1, 1).".".substr($elemento, 2, 2).".".substr($elemento, 4, 2).".".substr($elemento, 6, 2).".".substr($elemento, 8, 2);
    }

    public function getNaturezaFormat($natureza) {
        return substr($natureza, 0, 1).".".substr($natureza, 1, 1).".".substr($natureza, 2, 2).".".substr($natureza, 4, 2).".".substr($natureza, 6, 2);
    }

    public function getNaturInfoComplementaresFormat($natureza) {
        return substr($natureza, 0, 1).".".substr($natureza, 1, 1).".".substr($natureza, 2, 1).".".substr($natureza, 3, 1).".".substr($natureza, 4, 2).".".substr($natureza, 6, 1).".".substr($natureza, 7, 1);
    }

}
function verificarReceita($codReceita, $codFonte) {

    $criterios = [
        ['codReceita' => '192201', 'codFonte' => ['1631'], 'linha' => '#L3', 'coluna' => '#C3', 'codCampo' => 2539],
        ['codReceita' => '132101', 'codFonte' => ['1600', '1601', '1602', '1603', '1604', '1605', '1631'], 'linha' => '#L5', 'coluna' => '#C3', 'codCampo' => 2541],
        ['codReceita' => '132102', 'codFonte' => ['1600', '1601', '1602', '1603', '1604', '1605', '1631'], 'linha' => '#L6', 'coluna' => '#C3', 'codCampo' => 2542],
        ['codReceita' => '132103', 'codFonte' => ['1600', '1601', '1602', '1603', '1604', '1605', '1631'], 'linha' => '#L7', 'coluna' => '#C3', 'codCampo' => 2543],
        ['codReceita' => '132105', 'codFonte' => ['1600', '1601', '1602', '1603', '1604', '1605', '1631'], 'linha' => '#L8', 'coluna' => '#C3', 'codCampo' => 2544],
        ['codReceita' => '1329', 'codFonte' => ['1600', '1601', '1602', '1603', '1604', '1605', '1631'], 'linha' => '#L9', 'coluna' => '#C3', 'codCampo' => 2545],
        ['codReceita' => '171399', 'codFonte' => ['1600', '1601', '1602', '1603', '1604', '1605', '1631'], 'linha' => '#L10', 'coluna' => '#C3', 'codCampo' => 2546],
        ['codReceita' => '192250', 'codFonte' => ['1600', '1601', '1602', '1603', '1604', '1605', '1631'], 'linha' => '#L11', 'coluna' => '#C3', 'codCampo' => 2547],
        ['codReceita' => '241199', 'codFonte' => ['1600', '1601', '1602', '1603', '1604', '1605', '1631'], 'linha' => '#L12', 'coluna' => '#C3', 'codCampo' => 2548],
        ['codReceita' => '192201', 'codFonte' => ['1632'], 'linha' => '#L15', 'coluna' => '#C3', 'codCampo' => 2551],
        ['codReceita' => '132101', 'codFonte' => ['1621', '1632'], 'linha' => '#L17', 'coluna' => '#C3', 'codCampo' => 2553],
        ['codReceita' => '132102', 'codFonte' => ['1621', '1632'], 'linha' => '#L18', 'coluna' => '#C3', 'codCampo' => 2554],
        ['codReceita' => '132103', 'codFonte' => ['1621', '1632'], 'linha' => '#L19', 'coluna' => '#C3', 'codCampo' => 2555],
        ['codReceita' => '132105', 'codFonte' => ['1621', '1632'], 'linha' => '#L20', 'coluna' => '#C3', 'codCampo' => 2556],
        ['codReceita' => '1329', 'codFonte' => ['1621', '1632'], 'linha' => '#L21', 'coluna' => '#C3', 'codCampo' => 2557],
        ['codReceita' => '192250', 'codFonte' => ['1621', '1632'], 'linha' => '#L22', 'coluna' => '#C3', 'codCampo' => 2558],
        ['codReceita' => '192201', 'codFonte' => ['1633'], 'linha' => '#L25', 'coluna' => '#C3', 'codCampo' => 2561],
        ['codReceita' => '132101', 'codFonte' => ['1622', '1633'], 'linha' => '#L27', 'coluna' => '#C3', 'codCampo' => 2563],
        ['codReceita' => '132102', 'codFonte' => ['1622', '1633'], 'linha' => '#L28', 'coluna' => '#C3', 'codCampo' => 2564],
        ['codReceita' => '132103', 'codFonte' => ['1622', '1633'], 'linha' => '#L29', 'coluna' => '#C3', 'codCampo' => 2565],
        ['codReceita' => '132105', 'codFonte' => ['1622', '1633'], 'linha' => '#L30', 'coluna' => '#C3', 'codCampo' => 2566],
        ['codReceita' => '1329', 'codFonte' => ['1622', '1633'], 'linha' => '#L31', 'coluna' => '#C3', 'codCampo' => 2567],
        ['codReceita' => '192250', 'codFonte' => ['1622', '1633'], 'linha' => '#L32', 'coluna' => '#C3', 'codCampo' => 2568],
        ['codReceita' => '192299', 'codFonte' => ['1634'], 'linha' => '#L35', 'coluna' => '#C3', 'codCampo' => 2571],
        ['codReceita' => '1712521', 'codFonte' => ['1635'], 'linha' => '#L38', 'coluna' => '#C3', 'codCampo' => 2574],
        ['codReceita' => '1712522', 'codFonte' => ['1635'], 'linha' => '#L39', 'coluna' => '#C3', 'codCampo' => 2575],
        ['codReceita' => '1712523', 'codFonte' => ['1635'], 'linha' => '#L40', 'coluna' => '#C3', 'codCampo' => 2576],
        ['codReceita' => '1712524', 'codFonte' => ['1635'], 'linha' => '#L41', 'coluna' => '#C3', 'codCampo' => 2577],
        ['codReceita' => '172252', 'codFonte' => ['1635'], 'linha' => '#L42', 'coluna' => '#C3', 'codCampo' => 2578],
        ['codReceita' => '171999', 'codFonte' => ['1636', '1659'], 'linha' => '#L44', 'coluna' => '#C3', 'codCampo' => 2580],
        ['codReceita' => '241999', 'codFonte' => ['1636', '1659'], 'linha' => '#L45', 'coluna' => '#C3', 'codCampo' => 2581],
        ['codReceita' => '172999', 'codFonte' => ['1636', '1659'], 'linha' => '#L46', 'coluna' => '#C3', 'codCampo' => 2582],
        ['codReceita' => '242999', 'codFonte' => ['1636', '1659'], 'linha' => '#L47', 'coluna' => '#C3', 'codCampo' => 2583],
        ['codReceita' => '173999', 'codFonte' => ['1636', '1659'], 'linha' => '#L48', 'coluna' => '#C3', 'codCampo' => 2584],
        ['codReceita' => '243999', 'codFonte' => ['1636', '1659'], 'linha' => '#L49', 'coluna' => '#C3', 'codCampo' => 2585],
        ['codReceita' => '192299', 'codFonte' => ['1636', '1659'], 'linha' => '#L50', 'coluna' => '#C3', 'codCampo' => 2586],
        ['codReceita' => '192201', 'codFonte' => ['1636'], 'linha' => '#L52', 'coluna' => '#C3', 'codCampo' => 2588],
        ['codReceita' => '131', 'codFonte' => ['1600', '1601', '1602', '1603', '1604', '1605', '1621', '1622', '1631', '1632', '1633'], 'linha' => '#L54', 'coluna' => '#C3', 'codCampo' => 2591],
    ];

    foreach ($criterios as $criterio) {
        if (strpos($codReceita, $criterio['codReceita']) === 0) {
            if (in_array($codFonte, $criterio['codFonte'])) {
                return [
                    'linha' => $criterio['linha'],
                    'coluna' => $criterio['coluna'],
                    'codCampo' => $criterio['codCampo']
                ];
            }
        }
    }

    return null;
}