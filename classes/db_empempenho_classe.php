<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

//MODULO: empenho
//CLASSE DA ENTIDADE empempenho
class cl_empempenho
{
    // cria variaveis de erro
    var $rotulo     = null;
    var $query_sql  = null;
    var $numrows    = 0;
    var $numrows_incluir = 0;
    var $numrows_alterar = 0;
    var $numrows_excluir = 0;
    var $erro_status = null;
    var $erro_sql   = null;
    var $erro_banco = null;
    var $erro_msg   = null;
    var $erro_campo = null;
    var $pagina_retorno = null;
    var $obriga_divida = null;
    // cria variaveis do arquivo
    var $e60_numemp = 0;
    var $e60_codemp = null;
    var $e60_anousu = 0;
    var $e60_coddot = 0;
    var $e60_numcgm = 0;
    var $e60_emiss_dia = null;
    var $e60_emiss_mes = null;
    var $e60_emiss_ano = null;
    var $e60_emiss = null;
    var $e60_vencim_dia = null;
    var $e60_vencim_mes = null;
    var $e60_vencim_ano = null;
    var $e60_vencim = null;
    var $e60_vlrorc = 0;
    var $e60_vlremp = 0;
    var $e60_vlrliq = 0;
    var $e60_vlrpag = 0;
    var $e60_vlranu = 0;
    var $e60_codtipo = 0;
    var $e60_resumo = null;
    var $e60_informacaoop = null;
    var $e60_destin = null;
    var $e60_salant = 0;
    var $e60_instit = 0;
    var $e60_codcom = 0;
    var $e60_tipol = null;
    var $e60_numerol = null;
    var $migra_elemento = null;
    var $e60_concarpeculiar = null;
    var $e60_convenio = null;
    var $e60_numconvenio = null;
    var $e60_dataconvenio = null;
    var $e60_dataconvenio_dia = null;
    var $e60_dataconvenio_mes = null;
    var $e60_dataconvenio_ano = null;
    /*OC4604 - LQD*/
    var $e60_datasentenca = null;
    var $e60_datasentenca_dia = null;
    var $e60_datasentenca_mes = null;
    var $e60_datasentenca_ano = null;
    var $e60_tipodespesa = null;
    /*FIM OC4604 - LQD*/
    /*OC4401*/
    var $e60_id_usuario = null;
    var $e60_vlrutilizado = 0;
    /*FIM - OC4401*/
    /** OC19656 */
    var $e60_emendaparlamentar = null;
    var $e60_esferaemendaparlamentar = null;
    /** FIM - OC19656 */
    var $e60_codco = null;
    var $e60_id_documento_assinado = null;
    var $e60_node_id_libresing = null;
    var $op01_numerocontratoopc = null;
    var $e60_dividaconsolidada = null;
    var $e60_numcgmordenador = null;

    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 e60_numemp = int4 = N�mero
                 e60_codemp = varchar(15) = Empenho
                 e60_anousu = int4 = Exerc�cio
                 e60_coddot = int4 = Dota��o
                 e60_numcgm = int4 = Numcgm
                 e60_emiss = date = Data Emiss�o
                 e60_vencim = date = Vencimento
                 e60_vlrorc = float8 = Valor Or�ado
                 e60_vlremp = float8 = Valor Empenho
                 e60_vlrliq = float8 = Valor Liquidado
                 e60_vlrpag = float8 = Valor Pago
                 e60_vlranu = float8 = Valor Anulado
                 e60_codtipo = int4 = Tipo Empenho
                 e60_resumo = text = Observa��o
                 e60_informacaoop = text = Informa��es da OP
                 e60_destin = varchar(40) = Destino
                 e60_salant = float8 = Saldo anterior
                 e60_instit = int4 = codigo da instituicao
                 e60_codcom = int4 = Tipo de compra
                 e60_tipol = char(1) = Tipo da Licitacao
                 e60_numerol = char(8) = Numero Licita��o
                 migra_elemento = varchar(12) = Migra Elemento
                 e60_concarpeculiar = varchar(100) = Caracteristica Peculiar
                 e60_convenio = int8 = Conv�nio
                 e60_numconvenio = int8 = N�mero Conv�nio
                 e60_dataconvenio = date = Data Conv�nio
                 e60_datasentenca = date = Data Sente�a Judicial
                 e60_id_usuario = int4 = N�mero
                 e60_tipodespesa = int8 = tipo de despesa
                 e60_emendaparlamentar = int8 = emenda parlamentar
                 e60_esferaemendaparlamentar = int8 = esfera emenda parlamentar
                 e60_vlrutilizado = float8 = Valor utilizado
                 e60_codco = varchar(4) = codigo co
                 e60_id_documento_assinado = varchar = documento assinado
                 e60_node_id_libresing = varchar = e60_node_id_libresing
                 e60_dividaconsolidada = int8 = D�vida Consolidada
                 e60_numcgmordenador = int8 = Numero CGM do Ordenador
                 ";
    //funcao construtor da classe
    function cl_empempenho()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("empempenho");
        $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
    }
    //funcao erro
    function erro($mostra, $retorna)
    {
        if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
            echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
            if ($retorna == true) {
                echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
            }
        }
    }
    // funcao para atualizar campos
    function atualizacampos($exclusao = false)
    {
        if ($exclusao == false) {
            $this->e60_numemp = ($this->e60_numemp == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_numemp"] : $this->e60_numemp);
            $this->e60_codemp = ($this->e60_codemp == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_codemp"] : $this->e60_codemp);
            $this->e60_anousu = ($this->e60_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_anousu"] : $this->e60_anousu);
            $this->e60_coddot = ($this->e60_coddot == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_coddot"] : $this->e60_coddot);
            $this->e60_numcgm = ($this->e60_numcgm == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_numcgm"] : $this->e60_numcgm);
            $this->e60_numcgmordenador = ($this->e60_numcgmordenador == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_numcgmordenador"] : $this->e60_numcgmordenador);
            $this->e60_vlrutilizado = ($this->e60_vlrutilizado == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_vlrutilizado"] : $this->e60_vlrutilizado);
            if ($this->e60_emiss == "") {
                $this->e60_emiss_dia = ($this->e60_emiss_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_emiss_dia"] : $this->e60_emiss_dia);
                $this->e60_emiss_mes = ($this->e60_emiss_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_emiss_mes"] : $this->e60_emiss_mes);
                $this->e60_emiss_ano = ($this->e60_emiss_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_emiss_ano"] : $this->e60_emiss_ano);
                if ($this->e60_emiss_dia != "") {
                    $this->e60_emiss = $this->e60_emiss_ano . "-" . $this->e60_emiss_mes . "-" . $this->e60_emiss_dia;
                }
            }
            if ($this->e60_vencim == "") {
                $this->e60_vencim_dia = ($this->e60_vencim_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_vencim_dia"] : $this->e60_vencim_dia);
                $this->e60_vencim_mes = ($this->e60_vencim_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_vencim_mes"] : $this->e60_vencim_mes);
                $this->e60_vencim_ano = ($this->e60_vencim_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_vencim_ano"] : $this->e60_vencim_ano);
                if ($this->e60_vencim_dia != "") {
                    $this->e60_vencim = $this->e60_vencim_ano . "-" . $this->e60_vencim_mes . "-" . $this->e60_vencim_dia;
                }
            }
            $this->e60_vlrorc = ($this->e60_vlrorc == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_vlrorc"] : $this->e60_vlrorc);
            $this->e60_vlremp = ($this->e60_vlremp == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_vlremp"] : $this->e60_vlremp);
            $this->e60_vlrliq = ($this->e60_vlrliq == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_vlrliq"] : $this->e60_vlrliq);
            $this->e60_vlrpag = ($this->e60_vlrpag == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_vlrpag"] : $this->e60_vlrpag);
            $this->e60_vlranu = ($this->e60_vlranu == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_vlranu"] : $this->e60_vlranu);
            $this->e60_codtipo = ($this->e60_codtipo == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_codtipo"] : $this->e60_codtipo);
            $this->e60_resumo = ($this->e60_resumo == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_resumo"] : $this->e60_resumo);
            $this->e60_informacaoop = ($this->e60_informacaoop == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_informacaoop"] : $this->e60_informacaoop);
            $this->e60_destin = ($this->e60_destin == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_destin"] : $this->e60_destin);
            $this->e60_salant = ($this->e60_salant == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_salant"] : $this->e60_salant);
            $this->e60_instit = ($this->e60_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_instit"] : $this->e60_instit);
            $this->e60_codcom = ($this->e60_codcom == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_codcom"] : $this->e60_codcom);
            $this->e60_tipol = ($this->e60_tipol == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_tipol"] : $this->e60_tipol);
            $this->e60_numerol = ($this->e60_numerol == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_numerol"] : $this->e60_numerol);
            $this->migra_elemento = ($this->migra_elemento == "" ? @$GLOBALS["HTTP_POST_VARS"]["migra_elemento"] : $this->migra_elemento);
            $this->e60_concarpeculiar = ($this->e60_concarpeculiar == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_concarpeculiar"] : $this->e60_concarpeculiar);
            $this->e60_convenio = ($this->e60_convenio == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_convenio"] : $this->e60_convenio);
            $this->e60_numconvenio = ($this->e60_numconvenio == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_numconvenio"] : $this->e60_numconvenio);
            if ($this->e60_dataconvenio == "") {
                $this->e60_dataconvenio_dia = ($this->e60_dataconvenio_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_dataconvenio_dia"] : $this->e60_dataconvenio_dia);
                $this->e60_dataconvenio_mes = ($this->e60_dataconvenio_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_dataconvenio_mes"] : $this->e60_dataconvenio_mes);
                $this->e60_dataconvenio_ano = ($this->e60_dataconvenio_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_dataconvenio_ano"] : $this->e60_dataconvenio_ano);
                if ($this->e60_dataconvenio_dia != "") {
                    $this->e60_dataconvenio = $this->e60_dataconvenio_ano . "-" . $this->e60_dataconvenio_mes . "-" . $this->e60_dataconvenio_dia;
                }
            }
            if ($this->e60_datasentenca == "") {
                $this->e60_datasentenca_dia = ($this->e60_datasentenca_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_datasentenca_dia"] : $this->e60_datasentenca_dia);
                $this->e60_datasentenca_mes = ($this->e60_datasentenca_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_datasentenca_mes"] : $this->e60_datasentenca_mes);
                $this->e60_datasentenca_ano = ($this->e60_datasentenca_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_datasentenca_ano"] : $this->e60_datasentenca_ano);
                if ($this->e60_datasentenca_dia != "") {
                    $this->e60_datasentenca = $this->e60_datasentenca_ano . "-" . $this->e60_datasentenca_mes . "-" . $this->e60_datasentenca_dia;
                }
            }
        } else {
            $this->e60_numemp = ($this->e60_numemp == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_numemp"] : $this->e60_numemp);
        }
        $this->e60_tipodespesa = ($this->e60_tipodespesa == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_tipodespesa"] : $this->e60_tipodespesa);
        $this->e60_emendaparlamentar = ($this->e60_emendaparlamentar == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_emendaparlamentar"] : $this->e60_emendaparlamentar);
        $this->e60_esferaemendaparlamentar = ($this->e60_esferaemendaparlamentar == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_esferaemendaparlamentar"] : $this->e60_esferaemendaparlamentar);
        $this->e60_codco = ($this->e60_codco == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_codco"] : $this->e60_codco);
        $this->e60_id_documento_assinado = ($this->e60_id_documento_assinado == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_id_documento_assinado"] : $this->e60_id_documento_assinado);
        $this->e60_node_id_libresing = ($this->e60_node_id_libresing == "" ? @$GLOBALS["HTTP_POST_VARS"]["e60_node_id_libresing"] : $this->e60_node_id_libresing);
        $this->e60_dividaconsolidada = ($this->e60_dividaconsolidada == "" ? @$GLOBALS["HTTP_POST_VARS"]["op01_numerocontratoopc"] : $this->e60_dividaconsolidada);
        $this->obriga_divida = ($this->obriga_divida == "" ? @$GLOBALS["HTTP_POST_VARS"]["obriga_divida"] : $this->obriga_divida);

    }
    // funcao para inclusao
    function incluir($e60_numemp)
    {
        $this->atualizacampos();
        if ($this->e60_codemp == null) {
            $this->erro_sql = " Campo Empenho nao Informado.";
            $this->erro_campo = "e60_codemp";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_anousu == null) {
            $this->erro_sql = " Campo Exerc�cio nao Informado.";
            $this->erro_campo = "e60_anousu";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_coddot == null) {
            $this->erro_sql = " Campo Dota��o nao Informado.";
            $this->erro_campo = "e60_coddot";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_numcgm == null) {
            $this->erro_sql = " Campo Numcgm nao Informado.";
            $this->erro_campo = "e60_numcgm";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_numcgmordenador == null) {
            $this->erro_sql = " Campo Numcgm Ordenador nao Informado.";
            $this->erro_campo = "e60_numcgmordenador";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_emiss == null) {
            $this->erro_sql = " Campo Data Emiss�o nao Informado.";
            $this->erro_campo = "e60_emiss_dia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_vencim == null) {
            $this->erro_sql = " Campo Vencimento nao Informado.";
            $this->erro_campo = "e60_vencim_dia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_vlrorc == null) {
            $this->erro_sql = " Campo Valor Or�ado nao Informado.";
            $this->erro_campo = "e60_vlrorc";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_vlremp == null) {
            $this->erro_sql = " Campo Valor Empenho nao Informado.";
            $this->erro_campo = "e60_vlremp";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_vlrliq == null) {
            $this->erro_sql = " Campo Valor Liquidado nao Informado.";
            $this->erro_campo = "e60_vlrliq";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_vlrpag == null) {
            $this->erro_sql = " Campo Valor Pago nao Informado.";
            $this->erro_campo = "e60_vlrpag";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_vlranu == null) {
            $this->erro_sql = " Campo Valor Anulado nao Informado.";
            $this->erro_campo = "e60_vlranu";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_codtipo == null) {
            $this->erro_sql = " Campo Tipo Empenho nao Informado.";
            $this->erro_campo = "e60_codtipo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        $controlaDivida = $this->getControlaDividaParam(db_getsession("DB_anousu"));

        if(($this->e60_dividaconsolidada == null || $this->e60_dividaconsolidada == 'null') && ($this->obriga_divida == 'sim' && $controlaDivida == 't')) {

            $this->erro_sql   = " Campo D�vida Consolidada � de preenchimento obrigat�rio!";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        
        if($this->e60_dividaconsolidada == null) {
            $this->e60_dividaconsolidada = 'null';
        }

        if ($this->e60_salant == null) {
            $this->e60_salant = "0";
        }
        if ($this->e60_instit == null) {
            $this->erro_sql = " Campo codigo da instituicao nao Informado.";
            $this->erro_campo = "e60_instit";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_codcom == null) {
            $this->erro_sql = " Campo Tipo de compra nao Informado.";
            $this->erro_campo = "e60_codcom";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_concarpeculiar == null) {
            $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
            $this->erro_campo = "e60_concarpeculiar";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_numconvenio == null && $this->e60_convenio == 1) {
            $this->erro_sql = " Campo N�mero Conv�nio nao Informado.";
            $this->erro_campo = "e60_numconvenio";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->e60_dataconvenio == null && $this->e60_convenio == 1) {
            $this->erro_sql = " Campo Data Conv�nio nao Informado.";
            $this->erro_campo = "e60_dataconvenio_dia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($e60_numemp == "" || $e60_numemp == null) {
            $result = db_query("select nextval('empempenho_e60_numemp_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: empempenho_e60_numemp_seq do campo: e60_numemp";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->e60_numemp = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from empempenho_e60_numemp_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $e60_numemp)) {
                $this->erro_sql = " Campo e60_numemp maior que �ltimo n�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este n�mero.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->e60_numemp = $e60_numemp;
            }
        }
        if (($this->e60_numemp == null) || ($this->e60_numemp == "")) {
            $this->erro_sql = " Campo e60_numemp nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        /*OC4401*/
        if ($this->e60_id_usuario == null) {
            $this->erro_sql = " Ocorreu um erro ao buscar o ID do usu�rio!";
            $this->erro_campo = "e60_id_usuario";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        /*FIM - OC4401*/
        if ($this->e60_vlrutilizado == null) {
            $this->e60_vlrutilizado = 0;
        }
        if ($this->e60_emendaparlamentar == null) {
            $this->e60_emendaparlamentar = 0;
        }
        if ($this->e60_esferaemendaparlamentar == null) {
            $this->e60_esferaemendaparlamentar = 0;
        }
        if ($this->e60_codco == null) {
            $this->e60_codco = '0000';
        }
        $sql = "insert into empempenho(
                                       e60_numemp
                                      ,e60_codemp
                                      ,e60_anousu
                                      ,e60_coddot
                                      ,e60_numcgm
                                      ,e60_emiss
                                      ,e60_vencim
                                      ,e60_vlrorc
                                      ,e60_vlremp
                                      ,e60_vlrliq
                                      ,e60_vlrpag
                                      ,e60_vlranu
                                      ,e60_codtipo
                                      ,e60_resumo
                                      ,e60_informacaoop
                                      ,e60_destin
                                      ,e60_salant
                                      ,e60_instit
                                      ,e60_codcom
                                      ,e60_tipol
                                      ,e60_numerol
                                      ,migra_elemento
                                      ,e60_concarpeculiar
                                      ,e60_convenio
                                      ,e60_numconvenio
                                      ,e60_dataconvenio
                                      ,e60_datasentenca
                                      ,e60_id_usuario
                                      ,e60_tipodespesa
                                      ,e60_emendaparlamentar
                                      ,e60_esferaemendaparlamentar
                                      ,e60_vlrutilizado
                                      ,e60_codco
                                      ,e60_id_documento_assinado
                                      ,e60_node_id_libresing
                                      ,e60_dividaconsolidada
                                      ,e60_numcgmordenador
                       )
                values (
                                $this->e60_numemp
                               ,'$this->e60_codemp'
                               ,$this->e60_anousu
                               ,$this->e60_coddot
                               ,$this->e60_numcgm
                               ," . ($this->e60_emiss == "null" || $this->e60_emiss == "" ? "null" : "'" . $this->e60_emiss . "'") . "
                               ," . ($this->e60_vencim == "null" || $this->e60_vencim == "" ? "null" : "'" . $this->e60_vencim . "'") . "
                               ,$this->e60_vlrorc
                               ,$this->e60_vlremp
                               ,$this->e60_vlrliq
                               ,$this->e60_vlrpag
                               ,$this->e60_vlranu
                               ,$this->e60_codtipo
                               ,'$this->e60_resumo'
                               ,'$this->e60_informacaoop'
                               ,'$this->e60_destin'
                               ,$this->e60_salant
                               ,$this->e60_instit
                               ,$this->e60_codcom
                               ,'$this->e60_tipol'
                               ,'$this->e60_numerol'
                               ,'$this->migra_elemento'
                               ,'$this->e60_concarpeculiar'
                               ," . ($this->e60_convenio == "" ? "2" : $this->e60_convenio) . "
                               ," . ($this->e60_numconvenio == "" ? "null" : $this->e60_numconvenio) . "
                               ," . ($this->e60_dataconvenio == "null" || $this->e60_dataconvenio == "" ? "null" : "'" . $this->e60_dataconvenio . "'") . "
                               ," . ($this->e60_datasentenca == "null" || $this->e60_datasentenca == "" ? "null" : "'" . $this->e60_datasentenca . "'") . "
                               ,$this->e60_id_usuario
                               ,$this->e60_tipodespesa
                               ,$this->e60_emendaparlamentar
                               ,$this->e60_esferaemendaparlamentar
                               ,$this->e60_vlrutilizado
                               ,'$this->e60_codco'
                               ,'$this->e60_id_documento_assinado'
                               ,'$this->e60_node_id_libresing'
                               ,$this->e60_dividaconsolidada
                               ,$this->e60_numcgmordenador
                      )"; 
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "Empenhos na prefeitura ($this->e60_numemp) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Empenhos na prefeitura j� Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "Empenhos na prefeitura ($this->e60_numemp) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->e60_numemp;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $resaco = $this->sql_record($this->sql_query_file($this->e60_numemp));
        if (($resaco != false) || ($this->numrows != 0)) {
            $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
            $acount = pg_result($resac, 0, 0);
            $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
            $resac = db_query("insert into db_acountkey values($acount,5594,'$this->e60_numemp','I')");
            $resac = db_query("insert into db_acount values($acount,889,5594,'','" . AddSlashes(pg_result($resaco, 0, 'e60_numemp')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5595,'','" . AddSlashes(pg_result($resaco, 0, 'e60_codemp')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5596,'','" . AddSlashes(pg_result($resaco, 0, 'e60_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5597,'','" . AddSlashes(pg_result($resaco, 0, 'e60_coddot')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5598,'','" . AddSlashes(pg_result($resaco, 0, 'e60_numcgm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5599,'','" . AddSlashes(pg_result($resaco, 0, 'e60_emiss')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5600,'','" . AddSlashes(pg_result($resaco, 0, 'e60_vencim')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5656,'','" . AddSlashes(pg_result($resaco, 0, 'e60_vlrorc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5657,'','" . AddSlashes(pg_result($resaco, 0, 'e60_vlremp')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5658,'','" . AddSlashes(pg_result($resaco, 0, 'e60_vlrliq')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5659,'','" . AddSlashes(pg_result($resaco, 0, 'e60_vlrpag')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5660,'','" . AddSlashes(pg_result($resaco, 0, 'e60_vlranu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5661,'','" . AddSlashes(pg_result($resaco, 0, 'e60_codtipo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5662,'','" . AddSlashes(pg_result($resaco, 0, 'e60_resumo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5662,'','" . AddSlashes(pg_result($resaco, 0, 'e60_informacaoop')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5679,'','" . AddSlashes(pg_result($resaco, 0, 'e60_destin')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5684,'','" . AddSlashes(pg_result($resaco, 0, 'e60_salant')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5663,'','" . AddSlashes(pg_result($resaco, 0, 'e60_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5889,'','" . AddSlashes(pg_result($resaco, 0, 'e60_codcom')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5890,'','" . AddSlashes(pg_result($resaco, 0, 'e60_tipol')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,5891,'','" . AddSlashes(pg_result($resaco, 0, 'e60_numerol')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,7383,'','" . AddSlashes(pg_result($resaco, 0, 'migra_elemento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,889,10817,'','" . AddSlashes(pg_result($resaco, 0, 'e60_concarpeculiar')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        return true;
    }
    // funcao para alteracao
    function alterar($e60_numemp = null)
    {
        $this->atualizacampos();
        $sql = " update empempenho set ";
        $virgula = "";
        if (trim($this->e60_numemp) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_numemp"])) {
            $sql  .= $virgula . " e60_numemp = $this->e60_numemp ";
            $virgula = ",";
            if (trim($this->e60_numemp) == null) {
                $this->erro_sql = " Campo N�mero nao Informado.";
                $this->erro_campo = "e60_numemp";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_codemp) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_codemp"])) {
            $sql  .= $virgula . " e60_codemp = '$this->e60_codemp' ";
            $virgula = ",";
            if (trim($this->e60_codemp) == null) {
                $this->erro_sql = " Campo Empenho nao Informado.";
                $this->erro_campo = "e60_codemp";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_anousu) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_anousu"])) {
            $sql  .= $virgula . " e60_anousu = $this->e60_anousu ";
            $virgula = ",";
            if (trim($this->e60_anousu) == null) {
                $this->erro_sql = " Campo Exerc�cio nao Informado.";
                $this->erro_campo = "e60_anousu";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_coddot) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_coddot"])) {
            $sql  .= $virgula . " e60_coddot = $this->e60_coddot ";
            $virgula = ",";
            if (trim($this->e60_coddot) == null) {
                $this->erro_sql = " Campo Dota��o nao Informado.";
                $this->erro_campo = "e60_coddot";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_numcgm) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_numcgm"])) {
            $sql  .= $virgula . " e60_numcgm = $this->e60_numcgm ";
            $virgula = ",";
            if (trim($this->e60_numcgm) == null) {
                $this->erro_sql = " Campo Numcgm nao Informado.";
                $this->erro_campo = "e60_numcgm";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }  
        if (trim($this->e60_numcgmordenador) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_numcgmordenador"])  &&  ($GLOBALS["HTTP_POST_VARS"]["e60_numcgmordenador"] != "")) {
            $sql  .= $virgula . " e60_numcgmordenador = $this->e60_numcgmordenador ";
            $virgula = ",";

        }
        if (trim($this->e60_emiss) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_emiss_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e60_emiss_dia"] != "")) {
            $sql  .= $virgula . " e60_emiss = '$this->e60_emiss' ";
            $virgula = ",";
            if (trim($this->e60_emiss) == null) {
                $this->erro_sql = " Campo Data Emiss�o nao Informado.";
                $this->erro_campo = "e60_emiss_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["e60_emiss_dia"])) {
                $sql  .= $virgula . " e60_emiss = null ";
                $virgula = ",";
                if (trim($this->e60_emiss) == null) {
                    $this->erro_sql = " Campo Data Emiss�o nao Informado.";
                    $this->erro_campo = "e60_emiss_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        if (trim($this->e60_vencim) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vencim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e60_vencim_dia"] != "")) {
            $sql  .= $virgula . " e60_vencim = '$this->e60_vencim' ";
            $virgula = ",";
            if (trim($this->e60_vencim) == null) {
                $this->erro_sql = " Campo Vencimento nao Informado.";
                $this->erro_campo = "e60_vencim_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["e60_vencim_dia"])) {
                $sql  .= $virgula . " e60_vencim = null ";
                $virgula = ",";
                if (trim($this->e60_vencim) == null) {
                    $this->erro_sql = " Campo Vencimento nao Informado.";
                    $this->erro_campo = "e60_vencim_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        if (trim($this->e60_vlrorc) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrorc"])) {
            $sql  .= $virgula . " e60_vlrorc = $this->e60_vlrorc ";
            $virgula = ",";
            if (trim($this->e60_vlrorc) == null) {
                $this->erro_sql = " Campo Valor Or�ado nao Informado.";
                $this->erro_campo = "e60_vlrorc";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_vlremp) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vlremp"])) {
            $sql  .= $virgula . " e60_vlremp = $this->e60_vlremp ";
            $virgula = ",";
            if (trim($this->e60_vlremp) == null) {
                $this->erro_sql = " Campo Valor Empenho nao Informado.";
                $this->erro_campo = "e60_vlremp";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_vlrliq) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrliq"])) {
            $sql  .= $virgula . " e60_vlrliq = $this->e60_vlrliq ";
            $virgula = ",";
            if (trim($this->e60_vlrliq) == null) {
                $this->erro_sql = " Campo Valor Liquidado nao Informado.";
                $this->erro_campo = "e60_vlrliq";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_vlrpag) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrpag"])) {
            $sql  .= $virgula . " e60_vlrpag = $this->e60_vlrpag ";
            $virgula = ",";
            if (trim($this->e60_vlrpag) == null) {
                $this->erro_sql = " Campo Valor Pago nao Informado.";
                $this->erro_campo = "e60_vlrpag";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_vlranu) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_vlranu"])) {
            $sql  .= $virgula . " e60_vlranu = $this->e60_vlranu ";
            $virgula = ",";
            if (trim($this->e60_vlranu) == null) {
                $this->erro_sql = " Campo Valor Anulado nao Informado.";
                $this->erro_campo = "e60_vlranu";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_codtipo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_codtipo"])) {
            $sql  .= $virgula . " e60_codtipo = $this->e60_codtipo ";
            $virgula = ",";
            if (trim($this->e60_codtipo) == null) {
                $this->erro_sql = " Campo Tipo Empenho nao Informado.";
                $this->erro_campo = "e60_codtipo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_resumo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_resumo"])) {
            $sql  .= $virgula . " e60_resumo = '$this->e60_resumo' ";
            $virgula = ",";
        }
        if (trim($this->e60_informacaoop) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_informacaoop"])) {
            $sql  .= $virgula . " e60_informacaoop = '$this->e60_informacaoop' ";
            $virgula = ",";
        }
        if (trim($this->e60_destin) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_destin"])) {
            $sql  .= $virgula . " e60_destin = '$this->e60_destin' ";
            $virgula = ",";
        }
        if (trim($this->e60_salant) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_salant"])) {
            if (trim($this->e60_salant) == "" && isset($GLOBALS["HTTP_POST_VARS"]["e60_salant"])) {
                $this->e60_salant = "0";
            }
            $sql  .= $virgula . " e60_salant = $this->e60_salant ";
            $virgula = ",";
        }
        if (trim($this->e60_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_instit"])) {
            $sql  .= $virgula . " e60_instit = $this->e60_instit ";
            $virgula = ",";
            if (trim($this->e60_instit) == null) {
                $this->erro_sql = " Campo codigo da instituicao nao Informado.";
                $this->erro_campo = "e60_instit";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_codcom) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_codcom"])) {
            $sql  .= $virgula . " e60_codcom = $this->e60_codcom ";
            $virgula = ",";
            if (trim($this->e60_codcom) == null) {
                $this->erro_sql = " Campo Tipo de compra nao Informado.";
                $this->erro_campo = "e60_codcom";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_tipol) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_tipol"])) {
            $sql  .= $virgula . " e60_tipol = '$this->e60_tipol' ";
            $virgula = ",";
        }
        if (trim($this->e60_numerol) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_numerol"])) {
            $sql  .= $virgula . " e60_numerol = '$this->e60_numerol' ";
            $virgula = ",";
        }
        if (trim($this->migra_elemento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["migra_elemento"])) {
            $sql  .= $virgula . " migra_elemento = '$this->migra_elemento' ";
            $virgula = ",";
        }
        if (trim($this->e60_concarpeculiar) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_concarpeculiar"])) {
            $sql  .= $virgula . " e60_concarpeculiar = '$this->e60_concarpeculiar' ";
            $virgula = ",";
            if (trim($this->e60_concarpeculiar) == null) {
                $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
                $this->erro_campo = "e60_concarpeculiar";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_convenio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_convenio"])) {
            $sql  .= $virgula . " e60_convenio = " . ($this->e60_convenio == '' ? 'null' : $this->e60_convenio);
            $virgula = ",";
        }
        if (trim($this->e60_numconvenio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_numconvenio"])) {
            $sql  .= $virgula . " e60_numconvenio = " . ($this->e60_numconvenio == '' ? 'null' : $this->e60_numconvenio);
            $virgula = ",";
            if (trim($this->e60_numconvenio) == null && $this->e60_convenio == 1) {
                $this->erro_sql = " Campo N�mero Conv�nio nao Informado.";
                $this->erro_campo = "e60_numconvenio";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_tipodespesa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_tipodespesa"])) {
            $sql  .= $virgula . " e60_tipodespesa = " . ($this->e60_tipodespesa == '' ? 'null' : $this->e60_tipodespesa);
            $virgula = ",";
            if (trim($this->e60_tipodespesa) == null) {
                $this->erro_sql = " Campo Tipo de Despesa n�o Informado.";
                $this->erro_campo = "e60_tipodespesa";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_emendaparlamentar) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_emendaparlamentar"])) {
            $sql  .= $virgula . " e60_emendaparlamentar = " . ($this->e60_emendaparlamentar == '' ? 'null' : $this->e60_emendaparlamentar);
            $virgula = ",";
            if (trim($this->e60_emendaparlamentar) == null) {
                $this->erro_sql = " Campo Emenda Parlamentar n�o Informado.";
                $this->erro_campo = "e60_emendaparlamentar";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_esferaemendaparlamentar) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_esferaemendaparlamentar"])) {
            $sql  .= $virgula . " e60_esferaemendaparlamentar = " . ($this->e60_esferaemendaparlamentar == '' ? 'null' : $this->e60_esferaemendaparlamentar);
            $virgula = ",";
            if (trim($this->e60_emendaparlamentar) == null) {
                $this->erro_sql = " Campo Esfera Emenda Parlamentar n�o Informado.";
                $this->erro_campo = "e60_esfera emendaparlamentar";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->e60_codco) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_codco"])) {
            $sql  .= $virgula . " e60_codco = '$this->e60_codco'";
            $virgula = ",";
        }
        if (trim($this->e60_dataconvenio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_dataconvenio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e60_dataconvenio_dia"] != "")) {
            $sql  .= $virgula . " e60_dataconvenio = '$this->e60_dataconvenio' ";
            $virgula = ",";
            if (trim($this->e60_dataconvenio) == null && $this->e60_convenio == 1) {
                $this->erro_sql = " Campo Data Conv�nio nao Informado.";
                $this->erro_campo = "e60_dataconvenio_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["e60_dataconvenio_dia"])) {
                $sql  .= $virgula . " e60_dataconvenio = null ";
                $virgula = ",";
                if (trim($this->e60_dataconvenio) == null && $this->e60_convenio == 1) {
                    $this->erro_sql = " Campo Data Conv�nio nao Informado.";
                    $this->erro_campo = "e60_dataconvenio_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }

        $controlaDivida = $this->getControlaDividaParam(db_getsession("DB_anousu"));

        if(($this->e60_dividaconsolidada == null || $this->e60_dividaconsolidada == 'null') && ($this->obriga_divida == 'sim' && $controlaDivida == 't')) {

            $this->erro_sql = " Campo D�vida Consolidada � de preenchimento obrigat�rio!";
            $this->erro_banco = "";
            $this->erro_campo = "e60_dividaconsolidada";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;

        } else {

            if (trim($this->e60_dividaconsolidada) != "" || !empty($GLOBALS["HTTP_POST_VARS"]["op01_numerocontratoopc"])) {
                $sql  .= $virgula . " e60_dividaconsolidada = '$this->e60_dividaconsolidada' ";
                $virgula = ",";
            } else {
                if (isset($GLOBALS["HTTP_POST_VARS"]["op01_numerocontratoopc"])) {
                    $sql  .= $virgula . " e60_dividaconsolidada = null ";
                    $virgula = ",";
                }
            }
        }

        if (trim($this->e60_datasentenca) != "" || isset($GLOBALS["HTTP_POST_VARS"]["e60_datasentenca_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["e60_datasentenca_dia"] != "")) {
            $sql  .= $virgula . " e60_datasentenca = '$this->e60_datasentenca' ";
            $virgula = ",";
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["e60_datasentenca_dia"])) {
                $sql  .= $virgula . " e60_datasentenca = null ";
                $virgula = ",";
            }
        }
        $sql .= " where ";
        if ($e60_numemp != null) {
            $sql .= " e60_numemp = $this->e60_numemp";
        }
        $resaco = $this->sql_record($this->sql_query_file($this->e60_numemp));
        if ($this->numrows > 0) {
            for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,5594,'$this->e60_numemp','A')");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_numemp"]) || $this->e60_numemp != "")
                    $resac = db_query("insert into db_acount values($acount,889,5594,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_numemp')) . "','$this->e60_numemp'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_codemp"]) || $this->e60_codemp != "")
                    $resac = db_query("insert into db_acount values($acount,889,5595,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_codemp')) . "','$this->e60_codemp'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_anousu"]) || $this->e60_anousu != "")
                    $resac = db_query("insert into db_acount values($acount,889,5596,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_anousu')) . "','$this->e60_anousu'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_coddot"]) || $this->e60_coddot != "")
                    $resac = db_query("insert into db_acount values($acount,889,5597,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_coddot')) . "','$this->e60_coddot'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_numcgm"]) || $this->e60_numcgm != "")
                    $resac = db_query("insert into db_acount values($acount,889,5598,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_numcgm')) . "','$this->e60_numcgm'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_emiss"]) || $this->e60_emiss != "")
                    $resac = db_query("insert into db_acount values($acount,889,5599,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_emiss')) . "','$this->e60_emiss'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_vencim"]) || $this->e60_vencim != "")
                    $resac = db_query("insert into db_acount values($acount,889,5600,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_vencim')) . "','$this->e60_vencim'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrorc"]) || $this->e60_vlrorc != "")
                    $resac = db_query("insert into db_acount values($acount,889,5656,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_vlrorc')) . "','$this->e60_vlrorc'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_vlremp"]) || $this->e60_vlremp != "")
                    $resac = db_query("insert into db_acount values($acount,889,5657,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_vlremp')) . "','$this->e60_vlremp'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrliq"]) || $this->e60_vlrliq != "")
                    $resac = db_query("insert into db_acount values($acount,889,5658,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_vlrliq')) . "','$this->e60_vlrliq'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_vlrpag"]) || $this->e60_vlrpag != "")
                    $resac = db_query("insert into db_acount values($acount,889,5659,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_vlrpag')) . "','$this->e60_vlrpag'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_vlranu"]) || $this->e60_vlranu != "")
                    $resac = db_query("insert into db_acount values($acount,889,5660,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_vlranu')) . "','$this->e60_vlranu'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_codtipo"]) || $this->e60_codtipo != "")
                    $resac = db_query("insert into db_acount values($acount,889,5661,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_codtipo')) . "','$this->e60_codtipo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_resumo"]) || $this->e60_resumo != "")
                    $resac = db_query("insert into db_acount values($acount,889,5662,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_resumo')) . "','$this->e60_resumo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_informacaoop"]) || $this->e60_informacaoop != "")
                    $resac = db_query("insert into db_acount values($acount,889,5662,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_informacaoop')) . "','$this->e60_informacaoop'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_destin"]) || $this->e60_destin != "")
                    $resac = db_query("insert into db_acount values($acount,889,5679,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_destin')) . "','$this->e60_destin'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_salant"]) || $this->e60_salant != "")
                    $resac = db_query("insert into db_acount values($acount,889,5684,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_salant')) . "','$this->e60_salant'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_instit"]) || $this->e60_instit != "")
                    $resac = db_query("insert into db_acount values($acount,889,5663,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_instit')) . "','$this->e60_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_codcom"]) || $this->e60_codcom != "")
                    $resac = db_query("insert into db_acount values($acount,889,5889,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_codcom')) . "','$this->e60_codcom'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_tipol"]) || $this->e60_tipol != "")
                    $resac = db_query("insert into db_acount values($acount,889,5890,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_tipol')) . "','$this->e60_tipol'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_numerol"]) || $this->e60_numerol != "")
                    $resac = db_query("insert into db_acount values($acount,889,5891,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_numerol')) . "','$this->e60_numerol'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["migra_elemento"]) || $this->migra_elemento != "")
                    $resac = db_query("insert into db_acount values($acount,889,7383,'" . AddSlashes(pg_result($resaco, $conresaco, 'migra_elemento')) . "','$this->migra_elemento'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["e60_concarpeculiar"]) || $this->e60_concarpeculiar != "")
                    $resac = db_query("insert into db_acount values($acount,889,10817,'" . AddSlashes(pg_result($resaco, $conresaco, 'e60_concarpeculiar')) . "','$this->e60_concarpeculiar'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }

        $result = db_query($sql);

        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Empenhos na prefeitura nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->e60_numemp;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Empenhos na prefeitura nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->e60_numemp;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->e60_numemp;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    function excluir($e60_numemp = null, $dbwhere = null)
    {
        if ($dbwhere == null || $dbwhere == "") {
            $resaco = $this->sql_record($this->sql_query_file($e60_numemp));
        } else {
            $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
        }
        if (($resaco != false) || ($this->numrows != 0)) {
            for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,5594,'$e60_numemp','E')");
                $resac = db_query("insert into db_acount values($acount,889,5594,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_numemp')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5595,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_codemp')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5596,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5597,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_coddot')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5598,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_numcgm')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5599,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_emiss')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5600,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_vencim')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5656,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_vlrorc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5657,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_vlremp')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5658,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_vlrliq')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5659,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_vlrpag')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5660,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_vlranu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5661,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_codtipo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5662,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_resumo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5662,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_informacaoop')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5679,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_destin')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5684,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_salant')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5663,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5889,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_codcom')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5890,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_tipol')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,5891,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_numerol')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,7383,'','" . AddSlashes(pg_result($resaco, $iresaco, 'migra_elemento')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,889,10817,'','" . AddSlashes(pg_result($resaco, $iresaco, 'e60_concarpeculiar')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        $sql = " delete from empempenho
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($e60_numemp != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " e60_numemp = $e60_numemp ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Empenhos na prefeitura nao Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_sql .= "Valores : " . $e60_numemp;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Empenhos na prefeitura nao Encontrado. Exclus�o n�o Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $e60_numemp;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $e60_numemp;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao do recordset
    function sql_record($sql)
    {
        $result = db_query($sql);
        if ($result == false) {
            $this->numrows    = 0;
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Erro ao selecionar os registros";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_numrows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:empempenho";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql
    function sql_query($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "", $filtroempelemento = "", $limit = '')
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join empelemento  on  empempenho.e60_numemp = empelemento.e64_numemp";
        if ($filtroempelemento == 1) {
            $sql .= "      inner join orcelemento elementoempenho  on  elementoempenho.o56_codele = empelemento.e64_codele  and elementoempenho.o56_anousu = empempenho.e60_anousu";
        }
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele  and orcelemento.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      left  join empcontratos on si173_empenho::varchar = e60_codemp and e60_anousu = si173_anoempenho";
        $sql .= "      left join contratos on si173_codcontrato = si172_sequencial";
        $sql .= "      LEFT JOIN aditivoscontratos on extract(year from si174_dataassinaturacontoriginal) = si172_exerciciocontrato and (si174_nrocontrato = si172_nrocontrato)";
        $sql .= "       left join empempaut            on empempenho.e60_numemp  = empempaut.e61_numemp   ";
        $sql .= "       left join empautoriza          on empempaut.e61_autori   = empautoriza.e54_autori ";
        $sql .= "       left join db_depart            on empautoriza.e54_autori = db_depart.coddepto ";
        $sql .= "       left join empempenhocontrato   on empempenho.e60_numemp = empempenhocontrato.e100_numemp ";
        $sql .= "       left join acordo   on empempenhocontrato.e100_acordo = acordo.ac16_sequencial ";
        $sql .= "       left join convconvenios on convconvenios.c206_sequencial = empempenho.e60_numconvenio ";
        $sql .= "       left join empresto on e91_numemp = e60_numemp";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        if ($limit) {
            $sql .= " limit $limit ";
        }
        return $sql;
    }

    function sql_query_inclusaoempenho($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "", $filtroempelemento = "", $limit = '')
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join empelemento  on  empempenho.e60_numemp = empelemento.e64_numemp";
        if ($filtroempelemento == 1) {
            $sql .= "      inner join orcelemento elementoempenho  on  elementoempenho.o56_codele = empelemento.e64_codele  and elementoempenho.o56_anousu = empempenho.e60_anousu";
        }
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele  and orcelemento.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      left  join empcontratos on si173_empenho::varchar = e60_codemp and e60_anousu = si173_anoempenho";
        $sql .= "      left join contratos on si173_codcontrato = si172_sequencial";
        $sql .= "      LEFT JOIN aditivoscontratos on extract(year from si174_dataassinaturacontoriginal) = si172_exerciciocontrato and (si174_nrocontrato = si172_nrocontrato)";
        $sql .= "       left join empempaut            on empempenho.e60_numemp  = empempaut.e61_numemp   ";
        $sql .= "       left join empautoriza          on empempaut.e61_autori   = empautoriza.e54_autori ";
        $sql .= "       left join db_depart            on empautoriza.e54_autori = db_depart.coddepto ";
        $sql .= "       left join empempenhocontrato   on empempenho.e60_numemp = empempenhocontrato.e100_numemp ";
        $sql .= "       left join acordo   on empempenhocontrato.e100_acordo = acordo.ac16_sequencial ";
        $sql .= "       left join convconvenios on convconvenios.c206_sequencial = empempenho.e60_numconvenio ";
        $sql .= "       left join empresto on e91_numemp = e60_numemp ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = "  $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        if ($limit) {
            $sql .= " limit $limit ";
        }
        return $sql;
    }

    function getControlaDividaParam($e60_anousu) {

        $sql    = "SELECT e30_obrigadivida FROM empenho.empparametro  WHERE e39_anousu = $e60_anousu;";
        $result = db_query($sql);
    
        if ($result != false) {
            $result = pg_result($result, 0, 0);
        }
    
        return $result;
    }

    // funcao do sql
    function sql_query_file($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_codord($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join empempitem on e62_numemp = e60_numemp";
        $sql .= "      left join matordemitem on m52_numemp          =  e60_numemp";
        $sql .= "      left join matordem     on m51_codordem        = m52_codordem";
        $sql .= "      inner join cgm          on matordem.m51_numcgm = cgm.z01_numcgm ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_consulta($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      inner join db_permemp on 	db20_anousu  	= o58_anousu 	and";
        $sql .= "    				db20_orgao   	= o58_orgao 	and";
        $sql .= "    				db20_unidade 	= o58_unidade	and";
        $sql .= "    				db20_funcao  	= o58_funcao	and";
        $sql .= "    				db20_subfuncao 	= o58_subfuncao and";
        $sql .= "    				db20_programa	= o58_programa	and";
        $sql .= "    				db20_projativ	= o58_projativ	and";
        $sql .= "    				db20_codele	= o58_codele    and";
        $sql .= "    				db20_codigo	= o58_codigo";
        $sql .= "      inner join db_usupermemp on db21_codperm = db20_codperm and db21_id_usuario = " . db_getsession("DB_id_usuario");
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_valorutilizado($numemp = null)
    {
        $sql = " update empempenho set e60_vlrutilizado = $this->e60_vlrutilizado where e60_numemp = $numemp";

        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Empenhos na prefeitura nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->e60_numemp;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        }
    }
    function sql_query_doc($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }

        $sql .= " from empempenho ";
        $sql .= "      left outer join conlancamemp on c75_numemp = e60_numemp  ";
        $sql .= "      left outer join conlancam on c70_codlan = conlancamemp.c75_codlan  ";
        $sql .= "      left outer join conlancamdoc on c71_codlan = conlancam.c70_codlan  ";
        $sql .= "      left outer join conhistdoc  on c53_coddoc = conlancamdoc.c71_coddoc  ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu
                                          and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      left join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      left join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      left join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      left join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      left join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      left join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu
                                         and  orcprograma.o54_programa = orcdotacao.o58_programa";
        $sql .= "      left join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele";
        $sql .= "      left join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu
                                         and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      left join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu
                                         and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      left join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu
                                           and  orcunidade.o41_orgao = orcdotacao.o58_orgao
                                         and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp ";
        $sql .= "      left join emphist on emphist.e40_codhist = empemphist.e63_codhist";
        $sql2 = "";

        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_empnome($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join empelemento  on  e64_numemp = e60_numemp";
        $sql .= "      left join empempenholiberado  on  e22_numemp = e60_numemp";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    /**
     * @param  string $sCampos
     * @param  string $sOrdem
     * @param  string $sWhere
     * @return string
     */
    function sql_query_empresto($sCampos = "*", $sOrdem = null, $sWhere = "")
    {

        $sSql  = " select {$sCampos}                                    ";
        $sSql .= "  from empempenho                                     ";
        $sSql .= "       inner join empresto on e60_numemp = e91_numemp ";

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere} ";
        }

        if (!empty($sOrdem)) {
            $sSql .= " order by {$sOrdem} ";
        }
        return $sSql;
    }

    function sql_query_encerramento_empresto($sCampos = "*", $sOrdem = null, $sWhere = "")
    {

        $sSql  = " select {$sCampos}                                    ";
        $sSql .= "  from empempenho                                     ";
        $sSql .= "       left join empresto on e60_numemp = e91_numemp ";

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere} ";
        }

        if (!empty($sOrdem)) {
            $sSql .= " order by {$sOrdem} ";
        }
        return $sSql;
    }
    function sql_query_hist($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot
     = empempenho.e60_coddot";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa
     = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ
     = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao
     = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao
     = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom ";
        $sql .= "      left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp ";
        $sql .= "      left join emphist on emphist.e40_codhist = empemphist.e63_codhist";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_impconsulta($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join empempitem  on  empempitem.e62_numemp = empempenho.e60_numemp";
        $sql .= "      inner join pcmater     on  pcmater.pc01_codmater = empempitem.e62_item";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_itemmaterial($pc01_codmater = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join empempitem on e62_numemp = e60_numemp   ";
        $sql .= "      inner join pcmater on pcmater.pc01_codmater = empempitem.e62_item ";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      left join pagordem  on  pagordem.e50_numemp = empempenho.e60_numemp";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($pc01_codmater != null) {
                $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_itensadquiridos($pc01_codmater = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join empempitem on e62_numemp = e60_numemp   ";
        $sql .= "      inner join pcmater on pcmater.pc01_codmater = empempitem.e62_item ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele  and orcelemento.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "      inner join pagordem  on  pagordem.e50_numemp = empempenho.e60_numemp";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($pc01_codmater != null) {
                $sql2 .= " where pcmater.pc01_codmater = $pc01_codmater ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_liberarempenho($e60_numemp = null, $campos = "*", $ordem = "", $dbwhere = "")
    {

        if (isset($ordem) && $ordem != "") {
            $ordem = " order by {$ordem}";
        }

        $sSql  = "   select {$campos}                                                                                    ";
        $sSql .= "     from empempenho                                                                                   ";
        $sSql .= "          left  join empempenholiberado on e22_numemp = e60_numemp                                     ";
        $sSql .= "          inner join cgm                on z01_numcgm = e60_numcgm                                     ";
        $sSql .= "    where {$dbwhere} {$ordem}                                                                          ";

        return $sSql;
    }
    function sql_query_relatorio($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "", $sqlanulado = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa     = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu ";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp ";
        $sql .= "      left join emphist on emphist.e40_codhist = empemphist.e63_codhist";
        $sql .= "      inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom ";
        $sql .= "   /* pesquisa no relatorio de empenhos por �tem */

                 inner join empempitem on e62_numemp = e60_numemp
                 LEFT JOIN cflicita on  pc50_pctipocompratribunal = l03_pctipocompratribunal and pc50_codcom = l03_codcom and l03_instit = " . db_getsession("DB_instit") . "

                 LEFT JOIN liclicita ON ltrim(((string_to_array(e60_numerol, '/'))[1])::varchar,'0') = l20_numero::varchar
				      AND l20_anousu::varchar = ((string_to_array(e60_numerol, '/'))[2])::varchar
				      AND l03_codigo = l20_codtipocom
                  ";

        $sql .= " INNER JOIN empempaut ON e61_numemp = e60_numemp ";
        $sql .= " INNER JOIN empautoriza ON e54_autori = e61_autori ";
        $sql .= " INNER JOIN db_depart ON e54_gestaut = coddepto ";

        $sql .= " left join empempenhocontrato on  empempenho.e60_numemp = empempenhocontrato.e100_numemp    ";
        $sql .= " left join acordo ON ac16_sequencial = e100_acordo ";

        $sql .= " left join convconvenios on c206_sequencial = empempenho.e60_numconvenio ";
        $sql .= " left join emppresta on e45_numemp = empempenho.e60_numemp";
        $sql .= " left join empprestatip on e44_tipo = e45_tipo";

        $sql .= $sqlanulado;

        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_resto($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empresto ";
        $sql .= "      inner join empempenho on e91_numemp = e60_numemp";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot
     = empempenho.e60_coddot";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa
     = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele  and orcelemento.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ
     = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao
     = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao
     = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp ";
        $sql .= "      left join emphist on emphist.e40_codhist = empemphist.e63_codhist";
        $sql .= "      inner join pctipocompra on pctipocompra.pc50_codcom = empempenho.e60_codcom ";
        $sql2 = "";

        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empresto.e91_anousu = " . db_getsession("DB_anousu") . " and empresto.e91_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_saldo($e60_numemp, $e64_codele = 0)
    {
        $sql = "select
                    		substr(saldo,16,12)::float8 as e60_vlremp,
                    		substr(saldo,29,12)::float8 as e60_vlranu,
                    		substr(saldo,42,12)::float8 as e60_vlrliq,
                    		substr(saldo,55,12)::float8 as e60_vlrpag,
                	    	substr(saldo,68,12)::float8 as vlr_proc,
              	        	substr(saldo,81,12)::float8 as vlr_nproc
                	  from ( select fc_empsaldo(" . $e60_numemp . "," . $e64_codele . ") as saldo ) as x  ";

        return $sql;
    }

    /**
     * Retorna o sql com os saldos por empenho.
     * Query usada para encerramento do exercicio PCASP.
     * Contabilidade->Procedimentos->Utilitarios da Contabilidade->Encerramento do Exercicios PCASP
     * @author: Rodrigo@contass
     * @return string
     */
    function sql_query_saldo_encerramento_rp()
    {

        $sql = "SELECT * FROM
                    (SELECT e60_numemp,
                            (vlremp - vlranu - (vlrliq + vlremliqliquidado) - (vlremliq - vlremliqliquidado)) AS valor_nao_processado,
                            (vlremliq - vlremliqliquidado) AS valor_em_liquidacao,
                            (vlremliqliquidado + vlrliq - vlrpag) AS valor_processado
                     FROM
                         (SELECT e60_numemp,
                                 sum(CASE
                                         WHEN c71_coddoc IN
                                                  (SELECT c53_coddoc FROM conhistdoc
                                                   WHERE c53_tipo = 10) THEN round(c70_valor,2)
                                         ELSE 0
                                     END) AS vlremp,
                                 sum(CASE
                                         WHEN c71_coddoc IN
                                                  (SELECT c53_coddoc FROM conhistdoc
                                                   WHERE c53_tipo = 11) THEN round(c70_valor,2)
                                         ELSE 0
                                     END) AS vlranu,
                                 sum(CASE
                                         WHEN c71_coddoc IN (502,412,84,310,506,306,23,3) THEN round(c70_valor,2)
                                         WHEN c71_coddoc IN (4,24,25,85,307,311,413,503,507) THEN round(c70_valor,2) *-1
                                         ELSE 0
                                     END) AS vlrliq,
                                 sum(CASE
                                         WHEN c71_coddoc IN (208,210,212,200) THEN round(c70_valor,2)
                                         WHEN c71_coddoc IN (209,211,213,201) THEN round(c70_valor,2) *-1
                                         ELSE 0
                                     END) AS vlremliq,
                                 sum(CASE
                                         WHEN c71_coddoc IN (202,204,206) THEN round(c70_valor,2)
                                         WHEN c71_coddoc IN (203,205,207) THEN round(c70_valor,2) *-1
                                         ELSE 0
                                     END) AS vlremliqliquidado,
                                 sum(CASE
                                         WHEN c71_coddoc IN
                                                  (SELECT c53_coddoc FROM conhistdoc
                                                   WHERE c53_tipo = 30) THEN round(c70_valor,2)
                                         WHEN c71_coddoc IN
                                                  (SELECT c53_coddoc FROM conhistdoc
                                                   WHERE c53_tipo = 31) THEN round(c70_valor,2) *-1
                                         ELSE 0
                                     END) AS vlrpag
                          FROM empempenho
                          INNER JOIN conlancamemp ON e60_numemp = c75_numemp
                          INNER JOIN cgm ON e60_numcgm = z01_numcgm
                          INNER JOIN conlancamdoc ON c75_codlan = c71_codlan
                          INNER JOIN conlancam ON c75_codlan = c70_codlan
                          INNER JOIN orcdotacao ON e60_coddot = o58_coddot AND e60_anousu = o58_anousu
                          INNER JOIN orcelemento ON o58_codele = o56_codele AND o58_anousu = o56_anousu
                          INNER JOIN orctiporec ON o58_codigo = o15_codigo
                          INNER JOIN db_config ON codigo = e60_instit
                          INNER JOIN orcunidade ON o58_orgao = o41_orgao AND o58_unidade = o41_unidade AND o41_anousu = o58_anousu
                          INNER JOIN orcorgao ON o40_orgao = o41_orgao AND o40_anousu = o41_anousu
                          LEFT JOIN infocomplementaresinstit ON codigo = si09_instit
                          WHERE e60_anousu = " . db_getsession("DB_anousu") . "
                            AND e60_instit = " . db_getsession("DB_instit") . "
                            AND c70_data BETWEEN '" . db_getsession("DB_anousu") . "-01-01' AND '" . db_getsession("DB_anousu") . "-12-31'
                          GROUP BY e60_numemp ) AS restos) AS x
                WHERE valor_nao_processado > 0
                    OR valor_processado > 0
                    OR valor_em_liquidacao > 0";

        return $sql;
    }

    /**
     * Retorna o sql com os saldos por empenho.
     * Query usada para abertura do exercicio PCASP.
     * @author: igor@contass
     * @return string
     */
    function sql_query_saldo_abertura_rp($anousu, $numemp)
    {
        $sqlperiodo = " SELECT (vlremp - vlrliq) AS valor_nao_processado,
                                (vlrliq - vlrpag) AS valor_processado
                           FROM
                            (SELECT sum(round(CASE WHEN c53_tipo = 10 THEN c70_valor ELSE
                                             (CASE WHEN c53_tipo = 11 THEN c70_valor * -1 ELSE 0 END) END,2)) AS vlremp,
                                    sum(round(CASE WHEN c53_tipo = 20 THEN c70_valor ELSE
                                             (CASE WHEN c53_tipo = 21 THEN c70_valor * -1 ELSE 0 END) END,2)) AS vlrliq,
                                    sum(round(CASE WHEN c53_tipo = 30 THEN c70_valor ELSE
                                             (CASE WHEN c53_tipo = 31 THEN c70_valor * -1 ELSE 0 END) END,2)) AS vlrpag
                             FROM empempenho
                             INNER JOIN conlancamemp ON c75_numemp=e60_numemp
                             INNER JOIN conlancamdoc ON c71_codlan = c75_codlan
                             INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                             INNER JOIN conlancam ON c70_codlan = c75_codlan
                             INNER JOIN empresto ON e91_numemp = e60_numemp
                             WHERE e91_anousu = " . db_getsession("DB_anousu") . "
                               AND c70_data BETWEEN e60_emiss AND '" . $anousu . "-12-31'
                               AND e60_instit = " . db_getsession("DB_instit") . "
                               AND e60_numemp= $numemp
                            ) AS X ";


        return $sqlperiodo;
    }

    function sql_query_buscaempenhos($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " FROM empempenho                                                                ";
        $sql .= "   INNER JOIN db_config   ON db_config.codigo         = empempenho.e60_instit   ";
        $sql .= "   INNER JOIN orcdotacao  ON orcdotacao.o58_anousu    = empempenho.e60_anousu   ";
        $sql .= "                         AND orcdotacao.o58_coddot    = empempenho.e60_coddot   ";
        $sql .= "   INNER JOIN cgm         ON cgm.z01_numcgm           = empempenho.e60_numcgm   ";
        $sql .= "   INNER JOIN orcprojativ ON orcprojativ.o55_anousu   = orcdotacao.o58_anousu   ";
        $sql .= "                         AND orcprojativ.o55_projativ = orcdotacao.o58_projativ ";
        $sql .= "   INNER JOIN orcelemento ON orcelemento.o56_codele   = orcdotacao.o58_codele   ";
        $sql .= "                         AND orcelemento.o56_anousu   = orcdotacao.o58_anousu   ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_buscaestornos($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " FROM empempenho                                                           ";
        $sql .= "   INNER JOIN db_config   ON db_config.codigo      = empempenho.e60_instit ";
        $sql .= "   INNER JOIN empanulado  ON empanulado.e94_numemp = empempenho.e60_numemp ";
        $sql .= "   INNER JOIN orcdotacao  ON orcdotacao.o58_anousu = empempenho.e60_anousu ";
        $sql .= "                         AND orcdotacao.o58_coddot = empempenho.e60_coddot ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_query_buscaliquidacoes($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " FROM empempenho                                                                ";
        $sql .= "   INNER JOIN conlancamemp ON conlancamemp.c75_numemp = empempenho.e60_numemp   ";
        $sql .= "   INNER JOIN conlancam    ON conlancam.c70_codlan    = conlancamemp.c75_codlan ";
        $sql .= "   INNER JOIN conlancamdoc ON conlancamdoc.c71_codlan = conlancamemp.c75_codlan ";
        $sql .= "   INNER JOIN conhistdoc   ON conhistdoc.c53_coddoc   = conlancamdoc.c71_coddoc ";
        $sql .= "   INNER JOIN orcdotacao   ON orcdotacao.o58_anousu   = empempenho.e60_anousu   ";
        $sql .= "                          AND orcdotacao.o58_coddot   = empempenho.e60_coddot   ";
        $sql .= "   INNER JOIN db_config    ON db_config.codigo        = empempenho.e60_instit   ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    /**
     * metodo para consultar empenhos vinculados a contrados
     */

    function sql_query_empenhocontrato($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " FROM empempenho                                                                            ";
        $sql .= " left join empempaut            on empempenho.e60_numemp  = empempaut.e61_numemp            ";
        $sql .= " left join empautoriza          on empempaut.e61_autori   = empautoriza.e54_autori          ";
        $sql .= " left join empempenhocontrato on  empempenho.e60_numemp = empempenhocontrato.e100_numemp    ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }


    function sql_query_itens_consulta_empenho($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= "  from fc_saldoitensempenho({$e60_numemp}) ";
        $sql .= "       inner join empempitem                         on ricoditem                          = e62_sequencial ";
        $sql .= "       inner join empempenho                         on e62_numemp                         = e60_numemp ";
        $sql .= "       inner join orcelemento                        on e62_codele                         = o56_codele ";
        $sql .= "                                                    and e60_anousu                         = o56_anousu ";
        $sql .= "       left join empempaut                           on empempaut.e61_numemp               = empempenho.e60_numemp ";
        $sql .= "       left join empautitem                          on empautitem.e55_autori              = empempaut.e61_autori ";
        $sql .= "                                                    and empautitem.e55_sequen              = empempitem.e62_sequen";
        $sql .= "       left join empautitempcprocitem                on empautitempcprocitem.e73_autori    = empautitem.e55_autori ";
        $sql .= "                                                    and empautitempcprocitem.e73_sequen    = empautitem.e55_sequen ";
        $sql .= "       left join pcprocitem                          on pcprocitem.pc81_codprocitem        = empautitempcprocitem.e73_pcprocitem ";
        $sql .= "       left join solicitem                           on solicitem.pc11_codigo              = pcprocitem.pc81_solicitem ";
        $sql .= "       left join liclicitem                          on liclicitem.l21_codpcprocitem       = empautitempcprocitem.e73_pcprocitem ";
        $sql .= "       left join pcorcamitemlic                      on pcorcamitemlic.pc26_liclicitem     = liclicitem.l21_codigo ";
        $sql .= "       left join pcorcamjulg                         on pcorcamjulg.pc24_orcamitem         = pcorcamitemlic.pc26_orcamitem ";
        $sql .= "                                                    and pcorcamjulg.pc24_pontuacao         = 1 ";
        $sql .= "       left join pcorcamval                          on pcorcamval.pc23_orcamitem          = pcorcamjulg.pc24_orcamitem ";
        $sql .= "                                                    and pcorcamval.pc23_orcamforne         = pcorcamjulg.pc24_orcamforne ";
        $sql .= "       left join solicitemvinculo                    on solicitemvinculo.pc55_solicitemfilho = solicitem.pc11_codigo ";
        $sql .= "       left join solicitem       as solicitempai     on solicitempai.pc11_codigo             = solicitemvinculo.pc55_solicitempai ";
        $sql .= "       left join pcprocitem      as pcprocitempai    on pcprocitempai.pc81_solicitem         = solicitempai.pc11_codigo ";
        $sql .= "       left join liclicitem      as liclicitempai    on liclicitempai.l21_codpcprocitem      = pcprocitempai.pc81_codprocitem ";
        $sql .= "       left join pcorcamitemlic as pcorcamitemlicpai on pcorcamitemlicpai.pc26_liclicitem    = liclicitempai.l21_codigo ";
        $sql .= "       left join pcorcamjulg    as pcorcamjulgpai    on pcorcamjulgpai.pc24_orcamitem      = pcorcamitemlicpai.pc26_orcamitem ";
        $sql .= "                                                    and pcorcamjulgpai.pc24_pontuacao      = 1 ";
        $sql .= "       left join pcorcamval     as pcorcamvalpai     on pcorcamvalpai.pc23_orcamitem       = pcorcamjulgpai.pc24_orcamitem ";
        $sql .= "                                                    and pcorcamvalpai.pc23_orcamforne      = pcorcamjulgpai.pc24_orcamforne ";
        $sql .= "       left join orcdotacao on o58_coddot = e60_coddot and o58_anousu = e60_anousu";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    function sql_queryMovimentacaoPatrimonial($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele  and orcelemento.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      inner join conlancamemp on empempenho.e60_numemp = conlancamemp.c75_numemp";
        $sql .= "      inner join conlancam on conlancamemp.c75_codlan = conlancam.c70_codlan";
        $sql .= "      inner join conlancamdoc on conlancam.c70_codlan = conlancamdoc.c71_codlan";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }


    function sql_queryProcessoAdministrativo($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "        inner join empempaut   on e61_numemp = e60_numemp ";
        $sql .= "        inner join empautorizaprocesso on e61_autori = e150_empautoriza ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }


    public function sql_query_cota_mensal($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = "select {$campos} ";
        $sql .= " from empempenho ";
        $sql .= "     inner join empenhocotamensal on empenhocotamensal.e05_numemp = empempenho.e60_numemp ";
        $sql .= "     inner join orcdotacao        on orcdotacao.o58_coddot = empempenho.e60_coddot ";
        $sql .= "                                 and orcdotacao.o58_anousu = empempenho.e60_anousu ";

        if (empty($dbwhere) && !empty($e60_numemp)) {
            $sql .= " where empempenho.e60_numemp = {$e60_numemp} ";
        } else if (!empty($dbwhere)) {
            $sql .= " where {$dbwhere}";
        }

        if (!empty($ordem)) {
            $sql .= " order by {$ordem} ";
        }
        return $sql;
    }
    public function sql_query_classificacao_credor($sCampos = "*", $sWhere = null)
    {

        $sSql  = " select {$sCampos} ";
        $sSql .= "   from empempenho ";
        $sSql .= "        inner join cgm on z01_numcgm = e60_numcgm";
        $sSql .= "        left join classificacaocredoresempenho on cc31_empempenho = e60_numemp";
        $sSql .= "        left join classificacaocredores on cc31_classificacaocredores = cc30_codigo";

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere}";
        }
        return $sSql;
    }

    public function sql_query_contrato_empenho($iItem, $iAcordo)
    {

        $sql = "select sum(e62_quant) as qtdempenhada from acordoposicao
            inner join acordoitem on ac20_acordoposicao = ac26_sequencial
            inner join acordoitemexecutado on ac29_acordoitem = ac20_sequencial
            inner join acordoitemexecutadoempautitem on ac19_acordoitemexecutado = ac29_sequencial
            inner join empautitem on (e55_autori, e55_sequen) = (ac19_autori, ac19_sequen)
            inner join empautoriza on e55_autori = e54_autori
            inner join empempaut on e61_autori = e54_autori
            inner join empempenho on e60_numemp = e61_numemp
            inner join empempitem on e62_numemp = e60_numemp and e62_item = $iItem
            where ac26_acordo = $iAcordo and e62_item = $iItem";

        return $sql;
    }

    public function sql_query_empenho_classificacao_nota($sCampos = "*", $sWhere = null)
    {

        $sSql  = " select {$sCampos} ";
        $sSql .= "   from empempenho ";
        $sSql .= "        inner join empnota      on empnota.e69_numemp = empempenho.e60_numemp ";
        $sSql .= "        inner join pagordemnota on pagordemnota.e71_codnota = empnota.e69_codnota ";
        $sSql .= "        inner join pagordemele  on pagordemele.e53_codord = pagordemnota.e71_codord ";
        $sSql .= "        inner join pagordem     on pagordem.e50_codord    = pagordemele.e53_codord ";
        $sSql .= "        inner join cgm          on cgm.z01_numcgm     = empempenho.e60_numcgm ";
        $sSql .= "        inner join classificacaocredoresempenho on classificacaocredoresempenho.cc31_empempenho = empempenho.e60_numemp ";
        $sSql .= "        inner join classificacaocredores        on cc31_classificacaocredores = cc30_codigo ";
        $sSql .= "        inner join empord on empord.e82_codord = pagordem.e50_codord ";
        $sSql .= "        inner join empagemov on empagemov.e81_codmov = empord.e82_codmov ";
        $sSql .= "        left  join corempagemov on corempagemov.k12_codmov = empagemov.e81_codmov ";
        $sSql .= "        left  join empagemovjustificativa on empagemovjustificativa.e09_codmov = empagemov.e81_codmov ";

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere}";
        }
        return $sSql;
    }

    public function verificaConvenioSicomMSC($codigo, $ano, $dados)
    {

        $codigo = $dados->sigla == 'e60' ? "'{$codigo}'" :  $codigo;

        $sSQL = " SELECT {$dados->campo}
                    FROM {$dados->tabela}
                  JOIN orcdotacao ON (o58_coddot, o58_anousu) = ({$dados->sigla}_coddot, {$dados->sigla}_anousu)
                  WHERE {$dados->campo} = {$codigo} AND {$dados->sigla}_anousu = {$ano} ";

        $and = " AND o58_codigo IN (122, 123, 124, 142, 163, 171, 172, 173, 176, 177, 178, 181, 182, 183)";

        if ($ano > 2022) {
            $and = " AND o58_codigo IN (15700000, 16310000, 17000000, 16650000, 17130070, 15710000, 15720000, 15750000, 16320000, 16330000, 16360000, 17010000, 17020000, 17030000)";
        }

        $sSQL .= $and;

        return pg_num_rows(db_query($sSQL));
    }

    function sql_manut_dados($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "", $filtroempelemento = "", $limit = '')
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join empelemento  on  empempenho.e60_numemp = empelemento.e64_numemp";
        if ($filtroempelemento == 1) {
            $sql .= "      inner join orcelemento elementoempenho  on  elementoempenho.o56_codele = empelemento.e64_codele  and elementoempenho.o56_anousu = empempenho.e60_anousu";
        }
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele  and orcelemento.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      left  join empcontratos on si173_empenho::varchar = e60_codemp and e60_anousu = si173_anoempenho";
        $sql .= "      left join contratos on si173_codcontrato = si172_sequencial";
        $sql .= "      LEFT JOIN aditivoscontratos on extract(year from si174_dataassinaturacontoriginal) = si172_exerciciocontrato and (si174_nrocontrato = si172_nrocontrato)";
        $sql .= "       left join empempaut            on empempenho.e60_numemp  = empempaut.e61_numemp   ";
        $sql .= "       left join empautoriza          on empempaut.e61_autori   = empautoriza.e54_autori ";
        $sql .= "       left join db_depart            on empautoriza.e54_autori = db_depart.coddepto ";
        $sql .= "       left join empempenhocontrato   on empempenho.e60_numemp = empempenhocontrato.e100_numemp ";
        $sql .= "       left join acordo   on empempenhocontrato.e100_acordo = acordo.ac16_sequencial ";
        $sql .= "       left join convconvenios on convconvenios.c206_sequencial = empempenho.e60_numconvenio ";
        //$sql .= "       join condataconf ON (c99_anousu, c99_instit) = (e60_anousu, e60_instit) ";
        $sql .= "   inner join empanulado  ON empanulado.e94_numemp = empempenho.e60_numemp ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        if ($limit) {
            $sql .= " limit $limit ";
        }
        return $sql;
    }

    public function getDespesasCusteadosComSuperavit($iAnoUsu, $sDataInicial, $sDataFinal, $sInstituicoes, $sCampos = '*', $sWhere = '', $sOrder = '')
    {

        $sSql  = "select $sCampos                                                                                                                                           ";
        $sSql .= " from (                                                                                                                                                   ";
        $sSql .= "   select                                                                                                                                                 ";
        $sSql .= "        e60_numemp,                                                                                                                                       ";
        $sSql .= "        e60_anousu,                                                                                                                                       ";
        $sSql .= "        c70_anousu,                                                                                                                                       ";
        $sSql .= "        e60_numcgm,                                                                                                                                       ";
        $sSql .= "        e60_coddot,                                                                                                                                       ";
        $sSql .= "        e60_instit,                                                                                                                                       ";
        $sSql .= "        coalesce(e60_vlremp,0) as e60_vlremp,                                                                                                             ";
        $sSql .= "        coalesce(e60_vlranu,0) as e60_vlranu,                                                                                                             ";
        $sSql .= "        coalesce(e60_vlrliq,0) as e60_vlrliq,                                                                                                             ";
        $sSql .= "        coalesce(e60_vlrpag,0) as e60_vlrpag,                                                                                                             ";
        $sSql .= "        coalesce(vlranu,0) as vlranu,                                                                                                                     ";
        $sSql .= "        coalesce(vlrliq,0) as  vlrliq,                                                                                                                    ";
        $sSql .= "        coalesce(vlrpag,0) as vlrpag                                                                                                                     ";
        $sSql .= "   from empempenho                                                                                                                                          ";
        $sSql .= "        left outer join (                                                                                                                                 ";
        $sSql .= "        select c75_numemp, c70_anousu,                                                                                                                     ";
        $sSql .= "            sum( round( case when c53_tipo   = 11 then c70_valor else 0 end,2) ) as vlranu,                                                               ";
        $sSql .= "            sum( round( case when c53_tipo   = 20 then c70_valor else ( case when c53_tipo = 21 then c70_valor*-1 else  0 end) end,2) ) as vlrliq,         ";
        $sSql .= "            sum( round( case when c53_tipo = 30 then c70_valor else ( case when c53_tipo = 31 then c70_valor*-1 else  0 end) end,2) ) as vlrpag       ";
        $sSql .= "        from conlancamemp                                                                                                                                 ";
        $sSql .= "             inner join conlancamdoc on c71_codlan = c75_codlan                                                                                            ";
        $sSql .= "             inner join conhistdoc   on c53_coddoc = c71_coddoc                                                                                            ";
        $sSql .= "             inner join conlancam    on c70_codlan = c75_codlan                                                                                            ";
        $sSql .= "             inner join empempenho   on e60_numemp = c75_numemp                                                                                            ";
        $sSql .= "        where e60_anousu = $iAnoUsu and e60_emiss between '$sDataInicial' and '$sDataFinal'                                                                ";
        $sSql .= "             and  e60_instit in ($sInstituicoes)                                                                                                          ";
        $sSql .= "        group by c75_numemp, c70_anousu                                                                                                                    ";
        $sSql .= "        ) as x on x.c75_numemp = e60_numemp                                                                                                               ";
        $sSql .= "      where e60_anousu = $iAnoUsu                                                                                                                         ";
        $sSql .= " ) as x                                                                                                                                                   ";
        $sSql .= "      inner join empelemento  on e64_numemp    = e60_numemp                                                                                               ";
        $sSql .= "      inner join cgm          on z01_numcgm    = e60_numcgm                                                                                               ";
        $sSql .= "      inner join orcdotacao   on o58_coddot    = e60_coddot and o58_anousu  = e60_anousu and o58_instit = e60_instit                                      ";
        $sSql .= "      inner join orcorgao     on o40_orgao     = o58_orgao  and o40_anousu  = o58_anousu                                                                  ";
        $sSql .= "      inner join orcunidade   on o41_anousu    = o58_anousu and o41_orgao   = o58_orgao and o41_unidade = o58_unidade                                     ";
        $sSql .= "      inner join orcfuncao    on o52_funcao    = orcdotacao.o58_funcao                                                                                    ";
        $sSql .= "      inner join orcsubfuncao on o53_subfuncao = orcdotacao.o58_subfuncao                                                                                 ";
        $sSql .= "      inner join orcprograma  on o54_programa  = o58_programa and o54_anousu = orcdotacao.o58_anousu                                                      ";
        $sSql .= "      inner join orcprojativ  on o55_projativ  = o58_projativ and o55_anousu = orcdotacao.o58_anousu                                                      ";
        $sSql .= "      inner join orcelemento  on o58_codele    = o56_codele and o58_anousu   = o56_anousu                                                                 ";
        $sSql .= "      inner join empempaut    on e61_numemp    = e60_numemp                                                                                               ";
        $sSql .= "      inner join orctiporec on o15_codigo = o58_codigo                                                                                                    ";

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere} ";
        }

        if (!empty($sOrder)) {
            $sSql .= " order by {$sOrder} ";
        }
        return db_utils::getColectionByRecord(db_query($sSql));
    }

    public function getEmpenhosMovimentosPeriodo($iAnoUsu, $sDataInicial, $sDataFinal, $sInstituicoes, $sCampos = '*', $sWhere = '', $sOrder = '')
    {

        $sSql  = "select $sCampos                                                                                                                                           ";
        $sSql .= " from (                                                                                                                                                   ";
        $sSql .= "   select                                                                                                                                                 ";
        $sSql .= "        e60_numemp,                                                                                                                                       ";
        $sSql .= "        e60_anousu,                                                                                                                                       ";
        $sSql .= "        c70_anousu,                                                                                                                                       ";
        $sSql .= "        e60_numcgm,                                                                                                                                       ";
        $sSql .= "        o58_coddot,                                                                                                                                       ";
        $sSql .= "        e60_instit,                                                                                                                                       ";
        $sSql .= "        o58_funcao,                                                                                                                                       ";
        $sSql .= "        o58_subfuncao,                                                                                                                                    ";
        $sSql .= "        o53_descr as descsubfuncao,                                                                                                                       ";
        $sSql .= "        o58_programa,                                                                                                                                     ";
        $sSql .= "        o54_descr as descprograma,                                                                                                                        ";
        $sSql .= "        o15_codtri,                                                                                                                                       ";
        $sSql .= "        o15_codigo,                                                                                                                                       ";
        $sSql .= "        o56_elemento,                                                                                                                                     ";
        $sSql .= "        coalesce(e60_vlremp,0) as e60_vlremp,                                                                                                             ";
        $sSql .= "        coalesce(e60_vlranu,0) as e60_vlranu,                                                                                                             ";
        $sSql .= "        coalesce(e60_vlrliq,0) as e60_vlrliq,                                                                                                             ";
        $sSql .= "        coalesce(e60_vlrpag,0) as e60_vlrpag,                                                                                                             ";
        $sSql .= "        coalesce(vlranu,0) as vlranu,                                                                                                                     ";
        $sSql .= "        coalesce(vlrliq,0) as  vlrliq,                                                                                                                    ";
        $sSql .= "        coalesce(vlrpag,0) as vlrpag                                                                                                                      ";
        $sSql .= "   from empempenho                                                                                                                                        ";
        $sSql .= "        left outer join (                                                                                                                                 ";
        $sSql .= "          select c75_numemp, c70_anousu,                                                                                                                    ";
        $sSql .= "              sum( round( case when c53_tipo   = 11 then c70_valor else 0 end,2) ) as vlranu,                                                               ";
        $sSql .= "              sum( round( case when c53_tipo   = 20 then c70_valor else ( case when c53_tipo = 21 then c70_valor*-1 else  0 end) end,2) ) as vlrliq,        ";
        $sSql .= "              sum( round( case when c53_tipo = 30 then c70_valor else ( case when c53_tipo = 31 then c70_valor*-1 else  0 end) end,2) ) as vlrpag           ";
        $sSql .= "          from conlancamemp                                                                                                                                 ";
        $sSql .= "              inner join conlancamdoc on c71_codlan = c75_codlan                                                                                           ";
        $sSql .= "              inner join conhistdoc   on c53_coddoc = c71_coddoc                                                                                           ";
        $sSql .= "              inner join conlancam    on c70_codlan = c75_codlan                                                                                           ";
        $sSql .= "              inner join empempenho   on e60_numemp = c75_numemp                                                                                           ";
        $sSql .= "          where e60_anousu = $iAnoUsu and c75_data between '$sDataInicial' and '$sDataFinal'                                                                ";
        $sSql .= "               and  e60_instit in ($sInstituicoes)                                                                                                          ";
        $sSql .= "          group by c75_numemp, c70_anousu                                                                                                                   ";
        $sSql .= "       ) as x on x.c75_numemp = e60_numemp                                                                                                               ";
        $sSql .= "       inner join cgm          on z01_numcgm    = e60_numcgm                                                                                              ";
        $sSql .= "       inner join orcdotacao   on o58_coddot    = e60_coddot and o58_anousu  = e60_anousu and o58_instit = e60_instit                                     ";
        $sSql .= "       inner join orcorgao     on o40_orgao     = o58_orgao  and o40_anousu  = o58_anousu                                                                 ";
        $sSql .= "       inner join orcunidade   on o41_anousu    = o58_anousu and o41_orgao   = o58_orgao and o41_unidade = o58_unidade                                    ";
        $sSql .= "       inner join orcfuncao    on o52_funcao    = orcdotacao.o58_funcao                                                                                   ";
        $sSql .= "       inner join orcsubfuncao on o53_subfuncao = orcdotacao.o58_subfuncao                                                                                ";
        $sSql .= "       inner join orcprograma  on o54_programa  = o58_programa and o54_anousu = orcdotacao.o58_anousu                                                     ";
        $sSql .= "       inner join orcprojativ  on o55_projativ  = o58_projativ and o55_anousu = orcdotacao.o58_anousu                                                     ";
        $sSql .= "       inner join orcelemento  on o58_codele    = o56_codele and o58_anousu   = o56_anousu                                                                ";
        $sSql .= "       inner join empempaut    on e61_numemp    = e60_numemp                                                                                              ";
        $sSql .= "       inner join orctiporec on o15_codigo = o58_codigo                                                                                                   ";
        $sSql .= "   where e60_anousu = $iAnoUsu                                                                                                                            ";
        $sSql .= " ) as x                                                                                                                                                   ";

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere} ";
        }

        if (!empty($sOrder)) {
            $sSql .= " order by {$sOrder} ";
        }
        return db_utils::getColectionByRecord(db_query($sSql));
    }

    public function slq_ultimo_empenho($dtini, $dtfim, $instits)
    {
        $sSql  = " select max(e60_codemp::int8) as e60_codemp";
        $sSql .= "   from empempenho ";
        $sSql .= "    where e60_emiss between '{$dtini}' and '{$dtfim}' and e60_instit in ($instits) ";
        return $sSql;
    }

    public function sql_prox_data_empenho($anoUsu, $instit, $where, $order)
    {
        $sSql  = "select e60_codemp as codemp, e60_emiss as emiss from empempenho ";
        $sSql .= " where e60_instit = {$instit} and e60_anousu={$anoUsu} ";
        if ($where != "") {
            $sSql .= " and  {$where}";
        }
        $sSql .= $order;
        $sSql .= " limit 1";

        return $sSql;
    }

    public function verificaSaldoEmpenho($numEmpenho, $dataLiquidacao){
        $sSql = " WITH total_liquidado AS";
        $sSql .= " (SELECT CASE WHEN count(*) = 0 THEN 0 ELSE sum(c70_valor) END AS total_liquidado ";
        $sSql .= " FROM conlancam";
        $sSql .= " JOIN conlancamdoc ON c70_codlan = c71_codlan";
        $sSql .= " JOIN conhistdoc ON c71_coddoc = c53_coddoc";
        $sSql .= " JOIN conlancamemp  ON c70_codlan = c75_codlan ";
        $sSql .= " WHERE c75_numemp  = {$numEmpenho} AND c53_tipo = 20 AND c70_data <= '$dataLiquidacao'),";

        $sSql .= " total_estornado AS ";
        $sSql .= " (SELECT CASE WHEN count(*) = 0 THEN 0 ELSE sum(c70_valor) END AS total_estornado ";
        $sSql .= " FROM conlancam";
        $sSql .= " JOIN conlancamdoc ON c70_codlan = c71_codlan";
        $sSql .= " JOIN conhistdoc ON c71_coddoc = c53_coddoc";
        $sSql .= " JOIN conlancamemp  ON c70_codlan = c75_codlan ";
        $sSql .= " WHERE c75_numemp  = {$numEmpenho} AND c53_tipo = 21 AND c70_data <= '$dataLiquidacao'),";

        $sSql .= " valor_empenho AS ";
        $sSql .= " (SELECT e60_vlremp AS valor_empenho FROM empempenho WHERE e60_numemp = {$numEmpenho})";

        $sSql .= " SELECT valor_empenho - total_liquidado + total_estornado AS saldo_empenho ";
        $sSql .= " FROM total_liquidado, total_estornado, valor_empenho;";

        return $sSql;
    }

    public function alteraData($e60_numemp, $atualDataEmpenho, $novaDataEmpenho, $mesAtual)
    {
        $atualDataEmpenhoMes = date('m', strtotime($atualDataEmpenho));
        $novaDataEmpenhoMes = date('m', strtotime($novaDataEmpenho));
        $novaDataEmpenhoAno = date('Y', strtotime($novaDataEmpenho));

        if ($novaDataEmpenhoMes > $atualDataEmpenhoMes){
            $mesMenor = $atualDataEmpenhoMes;
        } else {
            $mesMenor = $novaDataEmpenhoMes;
        }

        $sSql = "SELECT fc_startsession();";
        $sSql .= " CREATE TEMP TABLE cod_lan ON COMMIT DROP AS";
        $sSql .= " SELECT DISTINCT c75_codlan";
        $sSql .= " FROM conlancamemp ";
        $sSql .= " JOIN conlancamdoc on c71_codlan = c75_codlan";
        $sSql .= " JOIN conhistdoc on c71_coddoc = c53_coddoc";
        $sSql .= " WHERE c75_numemp = {$e60_numemp} AND c53_tipo = 10;";

        $sSql .= " CREATE TEMP TABLE saldo_ctas ON COMMIT DROP AS ";
        $sSql .= " SELECT DISTINCT conplanoexesaldo.*, deb.c69_data c69_data ";
        $sSql .= " FROM conplanoexesaldo ";
        $sSql .= " JOIN conlancamval deb ON (deb.c69_debito, deb.c69_anousu, EXTRACT (MONTH FROM deb.c69_data)::integer) = (c68_reduz, c68_anousu, c68_mes) ";
        $sSql .= " WHERE deb.c69_codlan IN (SELECT c75_codlan from cod_lan) ";
        $sSql .= " UNION ALL ";
        $sSql .= " SELECT DISTINCT conplanoexesaldo.*, cred.c69_data c69_data ";
        $sSql .= " FROM conplanoexesaldo ";
        $sSql .= " JOIN conlancamval cred ON (cred.c69_credito, cred.c69_anousu, EXTRACT (MONTH FROM cred.c69_data)::integer) = (c68_reduz, c68_anousu, c68_mes)";
        $sSql .= " WHERE cred.c69_codlan IN (SELECT c75_codlan from cod_lan);";

        $sSql .= " CREATE TEMP TABLE alt_emp ON COMMIT DROP AS";
        $sSql .= " SELECT e60_numemp AS nro_emp,";
        $sSql .= "     e60_emiss AS data_emp,";
        $sSql .= "     e61_autori AS autoriza";
        $sSql .= " FROM empempenho";
        $sSql .= " INNER JOIN empempaut ON e61_numemp = e60_numemp";
        $sSql .= " INNER JOIN conlancamemp ON e60_numemp = c75_numemp";
        $sSql .= " WHERE e60_numemp IN ({$e60_numemp})";
        $sSql .= " AND c75_codlan IN (SELECT c75_codlan from cod_lan);";

        $sSql .= " CREATE TEMP TABLE w_lancamentos ON COMMIT DROP AS";
        $sSql .= " SELECT * FROM conlancamval";
        $sSql .= " JOIN conlancamdoc ON c71_codlan = c69_codlan";
        $sSql .= " WHERE c69_codlan IN";
        $sSql .= "     (SELECT c75_codlan FROM conlancamemp";
        $sSql .= "     WHERE c75_numemp IN ";
        $sSql .= "         (SELECT nro_emp FROM alt_emp))";
        $sSql .= " AND c71_codlan IN (SELECT c75_codlan from cod_lan);";

        $sSql .= " ALTER TABLE conlancamval DISABLE TRIGGER ALL;";

        $sSql .= " UPDATE conlancamval";
        $sSql .= " SET c69_data = '{$novaDataEmpenho}'";
        $sSql .= " WHERE c69_codlan IN";
        $sSql .= "     (SELECT c69_codlan FROM w_lancamentos);";

        $sSql .= " ALTER TABLE conlancamval ENABLE TRIGGER ALL;";

        $sSql .= " UPDATE conlancamemp";
        $sSql .= " SET c75_data = '{$novaDataEmpenho}'";
        $sSql .= " WHERE c75_codlan IN";
        $sSql .= "     (SELECT c71_codlan FROM w_lancamentos);";

        $sSql .= " UPDATE conlancam";
        $sSql .= " SET c70_data = '{$novaDataEmpenho}'";
        $sSql .= " WHERE c70_codlan IN";
        $sSql .= "     (SELECT c71_codlan FROM w_lancamentos);";

        $sSql .= " UPDATE conlancamdot";
        $sSql .= " SET c73_data = '{$novaDataEmpenho}'";
        $sSql .= " WHERE c73_codlan IN";
        $sSql .= "     (SELECT c71_codlan FROM w_lancamentos);";

        $sSql .= " UPDATE conlancamdoc";
        $sSql .= " SET c71_data = '{$novaDataEmpenho}'";
        $sSql .= " WHERE c71_codlan IN";
        $sSql .= "     (SELECT c71_codlan FROM w_lancamentos);";

        $sSql .= " UPDATE conlancamcorrente";
        $sSql .= " SET c86_data = '{$novaDataEmpenho}'";
        $sSql .= " WHERE c86_conlancam IN ";
        $sSql .= "     (SELECT c71_codlan FROM w_lancamentos);";

        $sSql .= " UPDATE empempenho";
        $sSql .= " SET e60_emiss = '{$novaDataEmpenho}',";
        $sSql .= "     e60_vencim = '{$novaDataEmpenho}'";
        $sSql .= " WHERE e60_numemp IN";
        $sSql .= "     (SELECT nro_emp FROM alt_emp);";

        $sSql .= " UPDATE empautoriza";
        $sSql .= " SET e54_emiss = '{$novaDataEmpenho}'";
        $sSql .= " WHERE e54_autori IN";
        $sSql .= "     (SELECT autoriza FROM alt_emp);";

        for($i = $mesMenor; $i <= $mesAtual; $i++){
            $sSql .= " DELETE FROM conplanoexesaldo";
            $sSql .= " USING saldo_ctas";
            $sSql .= " WHERE (saldo_ctas.c68_reduz, saldo_ctas.c68_anousu) = (conplanoexesaldo.c68_reduz, conplanoexesaldo.c68_anousu)";
            $sSql .= " AND conplanoexesaldo.c68_mes = {$i};";

            $sSql .= " CREATE TEMP TABLE landeb".$i." ON COMMIT DROP AS";
            $sSql .= " SELECT c69_anousu,";
            $sSql .= "     c69_debito,";
            $sSql .= "     to_char(conlancamval.c69_data,'MM')::integer,";
            $sSql .= "     sum(round(c69_valor,2)),0::float8";
            $sSql .= " FROM conlancamval";
            $sSql .= " JOIN saldo_ctas ON (saldo_ctas.c68_reduz, saldo_ctas.c68_anousu) = (conlancamval.c69_debito, conlancamval.c69_anousu)";
            $sSql .= " WHERE conlancamval.c69_anousu = {$novaDataEmpenhoAno}";
            $sSql .= " AND EXTRACT (MONTH FROM conlancamval.c69_data)::integer = {$i}";
            $sSql .= " GROUP BY conlancamval.c69_anousu, conlancamval.c69_debito, to_char(conlancamval.c69_data,'MM')::integer;";

            $sSql .= " CREATE TEMP TABLE lancre".$i." ON COMMIT DROP AS";
            $sSql .= " SELECT c69_anousu,";
            $sSql .= "     c69_credito,";
            $sSql .= "     to_char(conlancamval.c69_data,'MM')::integer as c69_data,";
            $sSql .= "     0::float8,";
            $sSql .= "     sum(round(c69_valor,2))";
            $sSql .= " FROM conlancamval";
            $sSql .= " JOIN saldo_ctas ON (saldo_ctas.c68_reduz, saldo_ctas.c68_anousu) = (conlancamval.c69_credito, conlancamval.c69_anousu)";
            $sSql .= " WHERE conlancamval.c69_anousu = {$novaDataEmpenhoAno}";
            $sSql .= " AND EXTRACT (MONTH FROM conlancamval.c69_data)::integer = {$i}";
            $sSql .= " GROUP BY conlancamval.c69_anousu, conlancamval.c69_credito, to_char(conlancamval.c69_data,'MM')::integer;";

            $sSql .= " INSERT INTO conplanoexesaldo";
            $sSql .= " SELECT * FROM landeb".$i."";
            $sSql .= " WHERE c69_anousu = {$novaDataEmpenhoAno};";

            $sSql .= " UPDATE conplanoexesaldo";
            $sSql .= " SET c68_credito = lancre".$i.".sum";
            $sSql .= " FROM lancre".$i."";
            $sSql .= " WHERE c68_anousu = lancre".$i.".c69_anousu";
            $sSql .= " AND c68_reduz = lancre".$i.".c69_credito";
            $sSql .= " AND c68_mes = lancre".$i.".c69_data";
            $sSql .= " AND c68_anousu = {$novaDataEmpenhoAno};";

            $sSql .= " DELETE FROM lancre".$i."";
            $sSql .= " USING conplanoexesaldo";
            $sSql .= " WHERE lancre".$i.".c69_anousu = conplanoexesaldo.c68_anousu";
            $sSql .= " AND conplanoexesaldo.c68_reduz = lancre".$i.".c69_credito";
            $sSql .= " AND conplanoexesaldo.c68_mes = lancre".$i.".c69_data";
            $sSql .= " AND c68_anousu = {$novaDataEmpenhoAno};";

            $sSql .= " INSERT INTO conplanoexesaldo";
            $sSql .= " SELECT * FROM lancre".$i."";
            $sSql .= " WHERE c69_anousu = {$novaDataEmpenhoAno};";
        }
        return $sSql;

    }

    public function verificaSaldoEmpenhoPosterior($numEmpenho, $saldoData, $codOrd, $tipoDoc){
        $sSql = "";
        $sSql .= " WITH lancamento AS";
        $sSql .= " (SELECT c70_codlan AS cod_lan, c70_valor AS valor_lan";
        $sSql .= " FROM conlancam";
        $sSql .= " JOIN conlancamord ON c70_codlan = c80_codlan";
        $sSql .= " JOIN conlancamdoc ON c71_codlan = c80_codlan";
        $sSql .= " JOIN conhistdoc ON c53_coddoc = c71_coddoc";
        $sSql .= " WHERE c80_codord = {$codOrd} AND c53_tipo = {$tipoDoc}), ";

        $tipoLiqui = $tipoDoc == 20 ? "AND c70_codlan not in (cod_lan)), " : "),";
        $sSql .= " total_liquidado AS";
        $sSql .= " (SELECT CASE WHEN count(*) = 0 THEN 0 ELSE sum(c70_valor) END AS total_liquidado ";
        $sSql .= " FROM lancamento, conlancam";
        $sSql .= " JOIN conlancamdoc ON c70_codlan = c71_codlan";
        $sSql .= " JOIN conhistdoc ON c71_coddoc = c53_coddoc";
        $sSql .= " JOIN conlancamemp  ON c70_codlan = c75_codlan ";
        $sSql .= " WHERE c75_numemp = {$numEmpenho} AND c53_tipo = 20";
        $sSql .= " AND c70_data <= '{$saldoData}' ".$tipoLiqui;

        $tipoAnul = $tipoDoc == 21 ? "AND c70_codlan not in (cod_lan)), " : "),";
        $sSql .= " total_estornado AS ";
        $sSql .= " (SELECT CASE WHEN count(*) = 0 THEN 0 ELSE sum(c70_valor) END AS total_estornado ";
        $sSql .= " FROM lancamento, conlancam";
        $sSql .= " JOIN conlancamdoc ON c70_codlan = c71_codlan";
        $sSql .= " JOIN conhistdoc ON c71_coddoc = c53_coddoc";
        $sSql .= " JOIN conlancamemp  ON c70_codlan = c75_codlan ";
        $sSql .= " WHERE c75_numemp  = {$numEmpenho} AND c53_tipo = 21";
        $sSql .= " AND c70_data <= '{$saldoData}' ".$tipoAnul;

        $sSql .= " valor_empenho AS ";
        $sSql .= " (SELECT e60_vlremp AS valor_empenho FROM empempenho WHERE e60_numemp = {$numEmpenho})";

        $sSql .= " SELECT (valor_empenho - total_liquidado + total_estornado) - valor_lan AS saldo_empenho ";
        $sSql .= " FROM total_liquidado, total_estornado, valor_empenho, lancamento;";

        return $sSql;
    }

    function alteraHistorico($e60_numemp){
        $sSql  = "UPDATE empempenho SET e60_informacaoop = '{$this->e60_informacaoop}'";
        $sSql .= " WHERE e60_numemp = {$e60_numemp}";

        $result = db_query($sSql);

        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Historico do empenho nao Alterado. Alteracao Abortada.\\n";
            $this->erro_msg   =  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Empenho nao encontrado.\\n";
                $this->erro_msg   =  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return false;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                $this->erro_msg   =  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    function sqlQueryValidacaoEmpenhoAnoAnterior($e60_numemp = null, $campos = "*", $ordem = null, $dbwhere = "", $filtroempelemento = "", $limit = '')
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from empempenho ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
        $sql .= "      inner join empelemento  on  empempenho.e60_numemp = empelemento.e64_numemp";
        $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
        $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
        $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
        $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
        $sql .= "      inner join db_config  as a on   a.codigo = orcdotacao.o58_instit";
        $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = orcdotacao.o58_codigo";
        $sql .= "      inner join orcfuncao  on  orcfuncao.o52_funcao = orcdotacao.o58_funcao";
        $sql .= "      inner join orcsubfuncao  on  orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao";
        $sql .= "      inner join orcprograma  on  orcprograma.o54_anousu = orcdotacao.o58_anousu and  orcprograma.o54_programa = orcdotacao.o58_programa";
        $sql .= "      inner join orcelemento  on  orcelemento.o56_codele = e64_codele and orcelemento.o56_anousu = orcdotacao.o58_anousu";
        $sql .= "      inner join orcprojativ  on  orcprojativ.o55_anousu = orcdotacao.o58_anousu and  orcprojativ.o55_projativ = orcdotacao.o58_projativ";
        $sql .= "      inner join orcorgao  on  orcorgao.o40_anousu = orcdotacao.o58_anousu and  orcorgao.o40_orgao = orcdotacao.o58_orgao";
        $sql .= "      inner join orcunidade  on  orcunidade.o41_anousu = orcdotacao.o58_anousu and  orcunidade.o41_orgao = orcdotacao.o58_orgao and  orcunidade.o41_unidade = orcdotacao.o58_unidade";
        $sql .= "      left  join empcontratos on si173_empenho::varchar = e60_codemp and e60_anousu = si173_anoempenho";
        $sql .= "      left join contratos on si173_codcontrato = si172_sequencial";
        $sql .= "      LEFT JOIN aditivoscontratos on extract(year from si174_dataassinaturacontoriginal) = si172_exerciciocontrato and (si174_nrocontrato = si172_nrocontrato)";
        $sql .= "       left join empempaut            on empempenho.e60_numemp  = empempaut.e61_numemp   ";
        $sql .= "       left join empautoriza          on empempaut.e61_autori   = empautoriza.e54_autori ";
        $sql .= "       left join db_depart            on empautoriza.e54_autori = db_depart.coddepto ";
        $sql .= "       left join empempenhocontrato   on empempenho.e60_numemp = empempenhocontrato.e100_numemp ";
        $sql .= "       left join acordo   on empempenhocontrato.e100_acordo = acordo.ac16_sequencial ";
        $sql .= "       left join convconvenios on convconvenios.c206_sequencial = empempenho.e60_numconvenio ";
        $sql .= "       left join empresto on e91_numemp = e60_numemp";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($e60_numemp != null) {
                $sql2 .= " where empempenho.e60_numemp = $e60_numemp ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " order by ";
            $campos_sql = split("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        if ($limit) {
            $sql .= " limit $limit ";
        }
        return $sql;
    }

    function sqlEmpenhosParaAnexar($sWhere){

        return "
        select
            e60_numemp,
            e60_codemp || '/' || e60_anousu as e60_codemp,
            z01_nome,
            z01_cgccpf,
            e60_resumo
        from
            empempenho
        join cgm on
            e60_numcgm = z01_numcgm
        join empempenhopncp on
            e213_contrato = e60_numemp
            where e60_instit = " . db_getsession("DB_instit")  . $sWhere;
    }

}
