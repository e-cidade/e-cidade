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

//MODULO: compras
//CLASSE DA ENTIDADE pcproc
class cl_pcproc
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
  // cria variaveis do arquivo
  var $pc80_codproc = 0;
  var $pc80_data_dia = null;
  var $pc80_data_mes = null;
  var $pc80_data_ano = null;
  var $pc80_data = null;
  var $pc80_usuario = 0;
  var $pc80_depto = 0;
  var $pc80_resumo = null;
  var $pc80_situacao = 0;
  var $pc80_tipoprocesso = 0;
  var $pc80_criterioadjudicacao = null;
  var $pc80_numdispensa = null;
  var $pc80_dispvalor = null;
  var $pc80_orcsigiloso = null;
  var $pc80_subcontratacao = null;
  var $pc80_dadoscomplementares = null;
  var $pc80_amparolegal = null;
  var $pc80_categoriaprocesso = null;
  var $pc80_modalidadecontratacao = null;

  var $pc80_criteriojulgamento = null;
  // cria propriedade com as variaveis do arquivo
  var $campos = "
                 pc80_codproc = int8 = C�digo do Processo de Compras
                 pc80_data = date = Data do Processo de Compras
                 pc80_usuario = int4 = Cod. Usu�rio
                 pc80_depto = int4 = Departamento
                 pc80_resumo = text = Resumo do Processo de Compras
                 pc80_situacao = int4 = Situa��o
                 pc80_tipoprocesso = int4 = Tipo de Processo
                 pc80_criterioadjudicacao = int4 = Criterio de Adjuducacao
                 pc80_numdispensa = numero da dispensa por valor
                 pc80_dispvalor = dispensa por valor
                 pc80_orcsigiloso = orcamento sigiloso
                 pc80_subcontratacao = possui subcontratacao
                 pc80_dadoscomplementares = dados complementares
                 pc80_amparolegal = amparo legal
                 pc80_categoriaprocesso = categoria do processo
                 pc80_modalidadecontratacao = int4 = modalidade de contratacao
                 pc80_criteriojulgamento = int4 = criterio de julgamento
                 ";
  //funcao construtor da classe
  function cl_pcproc()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("pcproc");
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
      $this->pc80_codproc = ($this->pc80_codproc == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_codproc"] : $this->pc80_codproc);
      if ($this->pc80_data == "") {
        $this->pc80_data_dia = ($this->pc80_data_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_data_dia"] : $this->pc80_data_dia);
        $this->pc80_data_mes = ($this->pc80_data_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_data_mes"] : $this->pc80_data_mes);
        $this->pc80_data_ano = ($this->pc80_data_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_data_ano"] : $this->pc80_data_ano);
        if ($this->pc80_data_dia != "") {
          $this->pc80_data = $this->pc80_data_ano . "-" . $this->pc80_data_mes . "-" . $this->pc80_data_dia;
        }
      }
      $this->pc80_usuario = ($this->pc80_usuario == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_usuario"] : $this->pc80_usuario);
      $this->pc80_depto = ($this->pc80_depto == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_depto"] : $this->pc80_depto);
      $this->pc80_resumo = ($this->pc80_resumo == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_resumo"] : $this->pc80_resumo);
      $this->pc80_situacao = ($this->pc80_situacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_situacao"] : $this->pc80_situacao);
      $this->pc80_tipoprocesso = ($this->pc80_tipoprocesso == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_tipoprocesso"] : $this->pc80_tipoprocesso);
      $this->pc80_criterioadjudicacao = ($this->pc80_criterioadjudicacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_criterioadjudicacao"] : $this->pc80_criterioadjudicacao);
      $this->pc80_numdispensa = ($this->pc80_numdispensa == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_numdispensa"] : $this->pc80_numdispensa);
      $this->pc80_dispvalor = ($this->pc80_dispvalor == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_dispvalor"] : $this->pc80_dispvalor);
      $this->pc80_orcsigiloso = ($this->pc80_orcsigiloso == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_orcsigiloso"] : $this->pc80_orcsigiloso);
      $this->pc80_subcontratacao = ($this->pc80_subcontratacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_subcontratacao"] : $this->pc80_subcontratacao);
      $this->pc80_dadoscomplementares = ($this->pc80_dadoscomplementares == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_dadoscomplementares"] : $this->pc80_dadoscomplementares);
      $this->pc80_amparolegal = ($this->pc80_amparolegal == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_amparolegal"] : $this->pc80_amparolegal);
      $this->pc80_categoriaprocesso = ($this->pc80_categoriaprocesso == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_categoriaprocesso"] : $this->pc80_categoriaprocesso);
      $this->pc80_modalidadecontratacao = ($this->pc80_modalidadecontratacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_modalidadecontratacao"] : $this->pc80_modalidadecontratacao);
      $this->pc80_criteriojulgamento = ($this->pc80_criteriojulgamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_criteriojulgamento"] : $this->pc80_criteriojulgamento);
    } else {
      $this->pc80_codproc = ($this->pc80_codproc == "" ? @$GLOBALS["HTTP_POST_VARS"]["pc80_codproc"] : $this->pc80_codproc);
    }
  }
  // funcao para inclusao
  function incluir($pc80_codproc)
  {
    $this->atualizacampos();
    if ($this->pc80_data == null) {
      $this->erro_sql = " Campo Data do Processo de Compras n�o informado.";
      $this->erro_campo = "pc80_data_dia";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->pc80_usuario == null) {
      $this->erro_sql = " Campo Cod. Usu�rio n�o informado.";
      $this->erro_campo = "pc80_usuario";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->pc80_depto == null) {
      $this->erro_sql = " Campo Departamento n�o informado.";
      $this->erro_campo = "pc80_depto";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->pc80_situacao == null) {
      $this->erro_sql = " Campo Situa��o n�o informado.";
      $this->erro_campo = "pc80_situacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->pc80_tipoprocesso == null) {
      $this->pc80_tipoprocesso = "1";
    }
    if ($this->pc80_criterioadjudicacao == null) {
      $this->erro_sql = " Campo Crit�rio de Adjudica��o n�o informado.";
      $this->erro_campo = "pc80_criterioadjudicacao";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->pc80_dispvalor == null) {
      $this->pc80_dispvalor = "null";
    }

    if ($this->pc80_numdispensa == null) {
      $this->pc80_numdispensa = "null";
    }

    if ($this->pc80_dispvalor == "f") {
      $this->pc80_numdispensa = 0;
    }

    if ($this->pc80_orcsigiloso == null && $this->pc80_dispvalor != "null" && $this->pc80_dispvalor == "t") {
      $this->erro_sql = " Campo orcamento sigiloso nao Informado.";
      $this->erro_campo = "pc80_depto";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    if ($this->pc80_subcontratacao == null && $this->pc80_dispvalor != "null" && $this->pc80_dispvalor == "t") {
      $this->erro_sql = " Campo subcontratacao nao Informado.";
      $this->erro_campo = "pc80_depto";
      $this->erro_banco = "";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }

    if ($this->pc80_amparolegal == null) {
      $this->pc80_amparolegal = "null";
    }

    if ($this->pc80_categoriaprocesso == null) {
      $this->pc80_categoriaprocesso = "null";
    }

    if ($this->pc80_modalidadecontratacao == null) {
        $this->pc80_modalidadecontratacao = "null";
    }

    if ($this->pc80_criteriojulgamento == null) {
        $this->pc80_criteriojulgamento = "null";
    }


    if ($pc80_codproc == "" || $pc80_codproc == null) {
      $result = db_query("select nextval('pcproc_pc80_codproc_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: pcproc_pc80_codproc_seq do campo: pc80_codproc";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->pc80_codproc = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from pcproc_pc80_codproc_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $pc80_codproc)) {
        $this->erro_sql = " Campo pc80_codproc maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->pc80_codproc = $pc80_codproc;
      }
    }
    if (($this->pc80_codproc == null) || ($this->pc80_codproc == "")) {
      $this->erro_sql = " Campo pc80_codproc nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $sql = "insert into pcproc(
                                       pc80_codproc
                                      ,pc80_data
                                      ,pc80_usuario
                                      ,pc80_depto
                                      ,pc80_resumo
                                      ,pc80_situacao
                                      ,pc80_tipoprocesso
                                      ,pc80_criterioadjudicacao
                                      ,pc80_numdispensa
                                      ,pc80_dispvalor
                                      ,pc80_orcsigiloso
                                      ,pc80_subcontratacao
                                      ,pc80_dadoscomplementares
                                      ,pc80_amparolegal
                                      ,pc80_categoriaprocesso
                                      ,pc80_modalidadecontratacao
                                      ,pc80_criteriojulgamento
                       )
                values (
                                $this->pc80_codproc
                               ," . ($this->pc80_data == "null" || $this->pc80_data == "" ? "null" : "'" . $this->pc80_data . "'") . "
                               ,$this->pc80_usuario
                               ,$this->pc80_depto
                               ,'$this->pc80_resumo'
                               ,$this->pc80_situacao
                               ,$this->pc80_tipoprocesso
                               ,$this->pc80_criterioadjudicacao
                               ,$this->pc80_numdispensa
                               ," . ($this->pc80_dispvalor == "null" || $this->pc80_dispvalor == "" ? "false" : "'" . $this->pc80_dispvalor . "'") . "
                               ," . ($this->pc80_orcsigiloso == "null" || $this->pc80_orcsigiloso == "" ? "false" : "'" . $this->pc80_orcsigiloso . "'") . "
                               ," . ($this->pc80_subcontratacao == "null" || $this->pc80_subcontratacao == "" ? "false" : "'" . $this->pc80_subcontratacao . "'") . "
                               ,'$this->pc80_dadoscomplementares'
                               ,$this->pc80_amparolegal
                               ,$this->pc80_categoriaprocesso
                               ,$this->pc80_modalidadecontratacao
                               ,$this->pc80_criteriojulgamento
                      )";
    $result = db_query($sql);

    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql   = "Processo de compras ($this->pc80_codproc) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_banco = "Processo de compras j� Cadastrado";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      } else {
        $this->erro_sql   = "Processo de compras ($this->pc80_codproc) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
    $this->erro_sql .= "Valores : " . $this->pc80_codproc;
    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
      && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->pc80_codproc));
      if (($resaco != false) || ($this->numrows != 0)) {

        $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
        $acount = pg_result($resac, 0, 0);
        $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
        $resac = db_query("insert into db_acountkey values($acount,6380,'$this->pc80_codproc','I')");
        $resac = db_query("insert into db_acount values($acount,1042,6380,'','" . AddSlashes(pg_result($resaco, 0, 'pc80_codproc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1042,6381,'','" . AddSlashes(pg_result($resaco, 0, 'pc80_data')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1042,6382,'','" . AddSlashes(pg_result($resaco, 0, 'pc80_usuario')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1042,6383,'','" . AddSlashes(pg_result($resaco, 0, 'pc80_depto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1042,6384,'','" . AddSlashes(pg_result($resaco, 0, 'pc80_resumo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1042,18603,'','" . AddSlashes(pg_result($resaco, 0, 'pc80_situacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        $resac = db_query("insert into db_acount values($acount,1042,20753,'','" . AddSlashes(pg_result($resaco, 0, 'pc80_tipoprocesso')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
      }
    }
    return true;
  }
  // funcao para alteracao
  public function alterar($pc80_codproc = null)
  {
    $this->atualizacampos();
    $sql = " update pcproc set ";
    $virgula = "";
    if (trim($this->pc80_codproc) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_codproc"])) {
      $sql  .= $virgula . " pc80_codproc = $this->pc80_codproc ";
      $virgula = ",";
      if (trim($this->pc80_codproc) == null) {
        $this->erro_sql = " Campo C�digo do Processo de Compras n�o informado.";
        $this->erro_campo = "pc80_codproc";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->pc80_data) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc80_data_dia"] != "")) {
      $sql  .= $virgula . " pc80_data = '$this->pc80_data' ";
      $virgula = ",";
      if (trim($this->pc80_data) == null) {
        $this->erro_sql = " Campo Data do Processo de Compras n�o informado.";
        $this->erro_campo = "pc80_data_dia";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_data_dia"])) {
        $sql  .= $virgula . " pc80_data = null ";
        $virgula = ",";
        if (trim($this->pc80_data) == null) {
          $this->erro_sql = " Campo Data do Processo de Compras n�o informado.";
          $this->erro_campo = "pc80_data_dia";
          $this->erro_banco = "";
          $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }
      }
    }
    if (trim($this->pc80_usuario) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_usuario"])) {
      $sql  .= $virgula . " pc80_usuario = $this->pc80_usuario ";
      $virgula = ",";
      if (trim($this->pc80_usuario) == null) {
        $this->erro_sql = " Campo Cod. Usu�rio n�o informado.";
        $this->erro_campo = "pc80_usuario";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->pc80_depto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_depto"])) {
      $sql  .= $virgula . " pc80_depto = $this->pc80_depto ";
      $virgula = ",";
      if (trim($this->pc80_depto) == null) {
        $this->erro_sql = " Campo Departamento n�o informado.";
        $this->erro_campo = "pc80_depto";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->pc80_resumo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_resumo"])) {
      $sql  .= $virgula . " pc80_resumo = '$this->pc80_resumo' ";
      $virgula = ",";
    }
    if (trim($this->pc80_situacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_situacao"])) {
      $sql  .= $virgula . " pc80_situacao = $this->pc80_situacao ";
      $virgula = ",";
      if (trim($this->pc80_situacao) == null) {
        $this->erro_sql = " Campo Situa��o n�o informado.";
        $this->erro_campo = "pc80_situacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }
    if (trim($this->pc80_tipoprocesso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_tipoprocesso"])) {
      if (trim($this->pc80_tipoprocesso) == "" && isset($GLOBALS["HTTP_POST_VARS"]["pc80_tipoprocesso"])) {
        $this->pc80_tipoprocesso = "1";
      }
      $sql  .= $virgula . " pc80_tipoprocesso = $this->pc80_tipoprocesso ";
      $virgula = ",";
    }
    if (trim($this->pc80_criterioadjudicacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_criterioadjudicacao"])) {
      if (trim($this->pc80_criterioadjudicacao) == "") {
        $this->erro_sql = " Campo Crit�rio de Adjudica��o n�o informado.";
        $this->erro_campo = "pc80_criterioadjudicacao";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      $sql  .= $virgula . " pc80_criterioadjudicacao = $this->pc80_criterioadjudicacao ";
      $virgula = ",";
    }

    if (trim($this->pc80_numdispensa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_numdispensa"])) {
      $sql  .= $virgula . " pc80_numdispensa = $this->pc80_numdispensa ";
      $virgula = ",";
      if (trim($this->pc80_numdispensa) == null) {
        $this->erro_sql = " Campo numero da dispensa nao Informado.";
        $this->erro_campo = "pc80_numdispensa";
        $this->erro_banco = "";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
    }

    if (trim($this->pc80_dispvalor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_dispvalor"])) {
      $sql  .= $virgula . " pc80_dispvalor = '$this->pc80_dispvalor' ";
      $virgula = ",";
    }

    if (trim($this->pc80_modalidadecontratacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_modalidadecontratacao"])) {
        $sql  .= $virgula . " pc80_modalidadecontratacao = $this->pc80_modalidadecontratacao";
        $virgula = ",";
    }

    if (trim($this->pc80_orcsigiloso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_orcsigiloso"])) {
      $sql  .= $virgula . " pc80_orcsigiloso = '$this->pc80_orcsigiloso' ";
      $virgula = ",";
    }

    if (trim($this->pc80_subcontratacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_subcontratacao"])) {
      $sql  .= $virgula . " pc80_subcontratacao = '$this->pc80_subcontratacao' ";
      $virgula = ",";
    }

    if (trim($this->pc80_dadoscomplementares) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_dadoscomplementares"])) {
      $sql  .= $virgula . " pc80_dadoscomplementares = '$this->pc80_dadoscomplementares' ";
      $virgula = ",";
    }

    if (trim($this->pc80_amparolegal) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_amparolegal"])) {
      $sql  .= $virgula . " pc80_amparolegal = '$this->pc80_amparolegal' ";
      $virgula = ",";
    }

    if (trim($this->pc80_categoriaprocesso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_categoriaprocesso"])) {
      $sql  .= $virgula . " pc80_categoriaprocesso = '$this->pc80_categoriaprocesso' ";
      $virgula = ",";
    }

    if (trim($this->pc80_criteriojulgamento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["pc80_criteriojulgamento"])) {
        $sql  .= $virgula . " pc80_criteriojulgamento = $this->pc80_criteriojulgamento";
        $virgula = ",";
    }

    $sql .= " where ";
    if ($pc80_codproc != null) {
      $sql .= " pc80_codproc = $this->pc80_codproc";
    }
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
      && ($lSessaoDesativarAccount === false))) {

      $resaco = $this->sql_record($this->sql_query_file($this->pc80_codproc));
      if ($this->numrows > 0) {

        for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

          $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac, 0, 0);
          $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
          $resac = db_query("insert into db_acountkey values($acount,6380,'$this->pc80_codproc','A')");
          if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_codproc"]) || $this->pc80_codproc != "")
            $resac = db_query("insert into db_acount values($acount,1042,6380,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc80_codproc')) . "','$this->pc80_codproc'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_data"]) || $this->pc80_data != "")
            $resac = db_query("insert into db_acount values($acount,1042,6381,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc80_data')) . "','$this->pc80_data'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_usuario"]) || $this->pc80_usuario != "")
            $resac = db_query("insert into db_acount values($acount,1042,6382,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc80_usuario')) . "','$this->pc80_usuario'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_depto"]) || $this->pc80_depto != "")
            $resac = db_query("insert into db_acount values($acount,1042,6383,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc80_depto')) . "','$this->pc80_depto'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_resumo"]) || $this->pc80_resumo != "")
            $resac = db_query("insert into db_acount values($acount,1042,6384,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc80_resumo')) . "','$this->pc80_resumo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_situacao"]) || $this->pc80_situacao != "")
            $resac = db_query("insert into db_acount values($acount,1042,18603,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc80_situacao')) . "','$this->pc80_situacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          if (isset($GLOBALS["HTTP_POST_VARS"]["pc80_tipoprocesso"]) || $this->pc80_tipoprocesso != "")
            $resac = db_query("insert into db_acount values($acount,1042,20753,'" . AddSlashes(pg_result($resaco, $conresaco, 'pc80_tipoprocesso')) . "','$this->pc80_tipoprocesso'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
      }
    }
    $result = db_query($sql);
    if (!$result) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Processo de compras nao Alterado. Alteracao Abortada.\\n";
      $this->erro_sql .= "Valores : " . $this->pc80_codproc;
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Processo de compras nao foi Alterado. Alteracao Executada.\\n";
        $this->erro_sql .= "Valores : " . $this->pc80_codproc;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->pc80_codproc;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao para exclusao
  public function excluir($pc80_codproc = null, $dbwhere = null)
  {

    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
      && ($lSessaoDesativarAccount === false))) {

      if (empty($dbwhere)) {

        $resaco = $this->sql_record($this->sql_query_file($pc80_codproc));
      } else {
        $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
      }
      if (($resaco != false) || ($this->numrows != 0)) {

        for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

          $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
          $acount = pg_result($resac, 0, 0);
          $resac  = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
          $resac  = db_query("insert into db_acountkey values($acount,6380,'$pc80_codproc','E')");
          $resac  = db_query("insert into db_acount values($acount,1042,6380,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc80_codproc')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1042,6381,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc80_data')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1042,6382,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc80_usuario')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1042,6383,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc80_depto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1042,6384,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc80_resumo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1042,18603,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc80_situacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
          $resac  = db_query("insert into db_acount values($acount,1042,20753,'','" . AddSlashes(pg_result($resaco, $iresaco, 'pc80_tipoprocesso')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
      }
    }
    $sql = " delete from pcproc
                    where ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($pc80_codproc)) {
        if (!empty($sql2)) {
          $sql2 .= " and ";
        }
        $sql2 .= " pc80_codproc = $pc80_codproc ";
      }
    } else {
      $sql2 = $dbwhere;
    }
    $result = db_query($sql . $sql2);
    if ($result == false) {
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Processo de compras nao Exclu�do. Exclus�o Abortada.\\n";
      $this->erro_sql .= "Valores : " . $pc80_codproc;
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;
      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "Processo de compras nao Encontrado. Exclus�o n�o Efetuada.\\n";
        $this->erro_sql .= "Valores : " . $pc80_codproc;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;
        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $pc80_codproc;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_excluir = pg_affected_rows($result);
        return true;
      }
    }
  }
  // funcao do recordset
  public function sql_record($sql)
  {
    $result = db_query($sql);
    if (!$result) {
      $this->numrows    = 0;
      $this->erro_banco = str_replace("\n", "", @pg_last_error());
      $this->erro_sql   = "Erro ao selecionar os registros.";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    $this->numrows = pg_num_rows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql   = "Record Vazio na Tabela:pcproc";
      $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
      $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
      $this->erro_status = "0";
      return false;
    }
    return $result;
  }
  // funcao do sql
  public function sql_query($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {

    $sql  = "select {$campos}";
    $sql .= "  from pcproc ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcproc.pc80_usuario";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto and db_depart.instit = " . db_getsession("DB_instit");
    $sql .= "      inner join pcprocitem  on  pcprocitem.pc81_codproc = pcproc.pc80_codproc";
    $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
    $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($pc80_codproc)) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }
  // funcao do sql
  public function sql_query_file($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {

    $sql  = "select {$campos} ";
    $sql .= "  from pcproc ";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($pc80_codproc)) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if (!empty($dbwhere)) {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if (!empty($ordem)) {
      $sql .= " order by {$ordem}";
    }
    return $sql;
  }

  function sql_query_autitem($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "      inner join db_usuarios          on db_usuarios.id_usuario              = pcproc.pc80_usuario";
    $sql .= "      inner join db_depart            on db_depart.coddepto                  = pcproc.pc80_depto";
    $sql .= "      inner join pcprocitem           on pcprocitem.pc81_codproc             = pcproc.pc80_codproc";
    $sql .= "      left  join acordopcprocitem     on pcprocitem.pc81_codprocitem         = acordopcprocitem.ac23_pcprocitem";
    $sql .= "      inner join solicitem            on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
    $sql .= "      inner join solicita             on solicita.pc10_numero                = solicitem.pc11_numero";
    $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
    $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
    $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
    $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori";
    $sql .= "      left  join pcorcamitemproc      on pcorcamitemproc.pc31_pcprocitem     = pcprocitem.pc81_codprocitem";
    $sql .= "      left join liclicitem            on pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_aut($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario = pcproc.pc80_usuario";
    $sql .= "      inner join db_depart    on db_depart.coddepto     = pcproc.pc80_depto";
    $sql .= "      inner join pcprocitem   on pcproc.pc80_codproc    = pcprocitem.pc81_codproc";
    $sql .= "      left  join empautitem   on empautitem.e55_sequen  = pcprocitem.pc81_codprocitem ";
    $sql .= "      left  join empautoriza  on empautoriza.e54_autori = empautitem.e55_autori ";
    $sql .= "      left  join solicitem    on solicitem.pc11_codigo  = pcprocitem.pc81_solicitem ";
    $sql .= "      left  join solicita     on solicita.pc10_numero   = solicitem.pc11_numero ";
    $sql .= "      left  join solicitaregistropreco on solicita.pc10_numero  = solicitaregistropreco.pc54_solicita ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_proc($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario              = pcproc.pc80_usuario ";
    $sql .= "      inner join db_depart             on db_depart.coddepto                  = pcproc.pc80_depto ";
    $sql .= "      inner join db_departorg          on db_departorg.db01_coddepto          = db_depart.coddepto ";
    $sql .= "                                      and db_departorg.db01_anousu            = " . db_getsession("DB_anousu");
    $sql .= "      inner join orcorgao              on orcorgao.o40_orgao                  = db_departorg.db01_orgao ";
    $sql .= "                                      and orcorgao.o40_anousu                 = db_departorg.db01_anousu ";
    $sql .= "      inner join pcprocitem            on pcprocitem.pc81_codproc             = pcproc.pc80_codproc ";
    $sql .= "      left  join solicitem             on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
    $sql .= "      left  join solicita              on solicita.pc10_numero                = solicitem.pc11_numero";
    $sql .= "      left  join liclicitem            on pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem ";
    $sql .= "      left  join empautitempcprocitem  on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
    $sql .= "      left  join empautitem            on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
    $sql .= "                                      and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
    $sql .= "      left  join empautoriza           on empautoriza.e54_autori              = empautitem.e55_autori ";
    $sql .= "      left  join cgm                   on empautoriza.e54_numcgm              = cgm.z01_numcgm ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_proc_solicita($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "     inner join pcprocitem on pc81_codproc = pc80_codproc";
    $sql .= "     inner join db_depart  on pc80_depto   = coddepto";
    $sql .= "     inner join solicitem  on pc11_codigo  = pc81_solicitem";
    $sql .= "     inner join solicita   on pc10_numero = pc11_numero";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_proc_and($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "      inner join db_usuarios          on db_usuarios.id_usuario              = pcproc.pc80_usuario";
    $sql .= "      inner join db_depart            on db_depart.coddepto                  = pcproc.pc80_depto and db_depart.instit = " . db_getsession("DB_instit");
    $sql .= "      inner join db_departorg         on db_departorg.db01_coddepto          = db_depart.coddepto";
    $sql .= "                                     and db_departorg.db01_anousu            = " . db_getsession("DB_anousu");
    $sql .= "      inner join orcorgao             on orcorgao.o40_orgao                  = db_departorg.db01_orgao";
    $sql .= "                                     and orcorgao.o40_anousu                 = db_departorg.db01_anousu";
    $sql .= "      inner join pcprocitem           on pcprocitem.pc81_codproc             = pcproc.pc80_codproc";
    $sql .= "      left  join acordopcprocitem     on pcprocitem.pc81_codprocitem         = acordopcprocitem.ac23_pcprocitem";
    $sql .= "      left  join solicitem            on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
    $sql .= "      left  join solicitemprot        on solicitemprot.pc49_solicitem        = solicitem.pc11_codigo";
    $sql .= "      left  join proctransferproc     on proctransferproc.p63_codproc        = solicitemprot.pc49_protprocesso";
    $sql .= "      left  join proctransfer         on proctransfer.p62_codtran            = proctransferproc.p63_codtran";
    $sql .= "      left  join proctransand         on proctransand.p64_codtran            = proctransfer.p62_codtran";
    $sql .= "      left  join solicita             on solicita.pc10_numero                = solicitem.pc11_numero";
    $sql .= "      left  join liclicitem           on pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem";
    $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
    $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
    $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
    $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_proc_orc($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "      inner join db_usuarios          on db_usuarios.id_usuario              = pcproc.pc80_usuario";
    $sql .= "      inner join db_depart            on db_depart.coddepto                  = pcproc.pc80_depto and db_depart.instit = " . db_getsession("DB_instit");
    $sql .= "      inner join db_departorg         on db_departorg.db01_coddepto          = db_depart.coddepto";
    $sql .= "                                     and db_departorg.db01_anousu            = " . db_getsession("DB_anousu");
    $sql .= "      inner join orcorgao             on orcorgao.o40_orgao                  = db_departorg.db01_orgao";
    $sql .= "                                     and orcorgao.o40_anousu                 = db_departorg.db01_anousu";
    $sql .= "      inner join pcprocitem           on pcprocitem.pc81_codproc             = pcproc.pc80_codproc";
    $sql .= "      inner join precoreferencia      on si01_processocompra = pcproc.pc80_codproc";
    $sql .= "      left  join acordopcprocitem     on pcprocitem.pc81_codprocitem         = acordopcprocitem.ac23_pcprocitem";
    $sql .= "      left  join solicitem            on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
    $sql .= "      left  join solicitemprot        on solicitemprot.pc49_solicitem        = solicitem.pc11_codigo";
    $sql .= "      left  join proctransferproc     on proctransferproc.p63_codproc        = solicitemprot.pc49_protprocesso";
    $sql .= "      left  join proctransfer         on proctransfer.p62_codtran            = proctransferproc.p63_codtran";
    $sql .= "      left  join proctransand         on proctransand.p64_codtran            = proctransfer.p62_codtran";
    $sql .= "      left  join solicita             on solicita.pc10_numero                = solicitem.pc11_numero";
    $sql .= "      left  join liclicitem           on pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem";
    $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
    $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
    $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
    $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_usudepart($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcproc.pc80_usuario";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto and db_depart.instit = " . db_getsession("DB_instit");
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_soland($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= " from pcproc                                                                                                      ";
    $sql .= "      inner join pcprocitem            on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc            ";
    $sql .= "      left  join solicitem             on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem          ";
    $sql .= "      left  join solicita              on solicita.pc10_numero                = solicitem.pc11_numero              ";
    $sql .= "      left  join solicitaregistropreco on solicita.pc10_numero  = solicitaregistropreco.pc54_solicita ";
    $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem        ";
    $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori    ";
    $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen    ";
    $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori              ";
    $sql .= "      inner join solandam             on solandam.pc43_solicitem             = pcprocitem.pc81_solicitem          ";
    $sql .= "      inner join ( select max(pc43_codigo) as codigo,                                                             ";
    $sql .= "                          pc43_solicitem                                                                          ";
    $sql .= "                     from solandam                                                                                ";
    $sql .= "                 group by pc43_solicitem  )  as  x  on x.codigo = pc43_codigo                                     ";
    $sql2 = "";

    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_leftprocitem($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= "      from pcproc                                                                           ";
    $sql .= "           left join pcprocitem  on pcproc.pc80_codproc = pcprocitem.pc81_codproc      ";
    $sql .= "           left join solicitem   on pc81_solicitem      = pc11_codigo      ";

    $sql2 = "";

    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  public function sql_query_gerautproc($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= "  from pcproc ";
    $sql .= "       inner join pcprocitem           on pcprocitem.pc81_codproc = pcproc.pc80_codproc";
    $sql .= "       inner join solicitem            on solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
    $sql .= "       inner join solicita             on solicita.pc10_numero = solicitem.pc11_numero";
    $sql .= "       inner join solicitempcmater     on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
    $sql .= "       inner join pcmater              on pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
    $sql .= "       inner join solicitemele         on solicitemele.pc18_solicitem = solicitem.pc11_codigo";
    $sql .= "       left  join solicitemunid        on solicitemunid.pc17_codigo = solicitem.pc11_codigo";
    $sql .= "       left  join matunid              on matunid.m61_codmatunid = solicitemunid.pc17_unid";
    $sql .= "       inner join pcdotac              on pcdotac.pc13_codigo = solicitem.pc11_codigo";
    $sql .= "       left  join pcdotaccontrapartida on pcdotaccontrapartida.pc19_pcdotac = pcdotac.pc13_sequencial";
    $sql .= "       inner join orcdotacao           on orcdotacao.o58_anousu = pcdotac.pc13_anousu";
    $sql .= "                                      and orcdotacao.o58_coddot = pcdotac.pc13_coddot";
    $sql .= "       left  join pcorcamitemproc      on pcorcamitemproc.pc31_pcprocitem = pcprocitem.pc81_codprocitem";
    $sql .= "       left  join orcreservasol        on orcreservasol.o82_pcdotac = pcdotac.pc13_sequencial";
    $sql .= "       left  join orcreserva           on orcreserva.o80_codres = orcreservasol.o82_codres";
    $sql .= "       left  join pcorcamitem          on pcorcamitem.pc22_orcamitem = pcorcamitemproc.pc31_orcamitem";
    $sql .= "       left  join pcorcam              on pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
    $sql .= "       left  join pcorcamforne         on pcorcamforne.pc21_codorc = pcorcam.pc20_codorc";
    $sql .= "       left  join cgm                  on cgm.z01_numcgm = pcorcamforne.pc21_numcgm";
    $sql .= "       left  join pcorcamval           on pcorcamval.pc23_orcamforne = pcorcamforne.pc21_orcamforne";
    $sql .= "                                      and pcorcamval.pc23_orcamitem  = pcorcamitem.pc22_orcamitem";
    $sql .= "       left  join pcorcamjulg          on pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne";
    $sql .= "                                      and pcorcamjulg.pc24_orcamitem = pcorcamitem.pc22_orcamitem";
    //$sql .= "                                      and pc24_pontuacao = 1 ";
    $sql .= "       left  join orcelemento          on orcelemento.o56_codele = solicitemele.pc18_codele";
    $sql .= "                                      and o56_anousu = " . db_getsession("DB_anousu");
    $sql .= "       left  join solicitemanul        on solicitemanul.pc28_solicitem = solicitem.pc11_codigo";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  public function sql_query_dados_item($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }

    $sql .= "  from pcproc                                                                                            ";
    $sql .= "       inner join pcprocitem           on pcprocitem.pc81_codproc            = pcproc.pc80_codproc       ";
    $sql .= "       inner join solicitem            on solicitem.pc11_codigo              = pcprocitem.pc81_solicitem ";
    $sql .= "       inner join solicita             on solicita.pc10_numero               = solicitem.pc11_numero     ";
    $sql .= "       inner join solicitempcmater     on solicitempcmater.pc16_solicitem    = solicitem.pc11_codigo     ";
    $sql .= "       inner join pcmater              on pcmater.pc01_codmater              = solicitempcmater.pc16_codmater ";
    $sql .= "       left join solicitemele          on solicitemele.pc18_solicitem        = solicitem.pc11_codigo     ";
    $sql .= "       left  join solicitemunid        on solicitemunid.pc17_codigo          = solicitem.pc11_codigo     ";
    $sql .= "       left  join matunid              on matunid.m61_codmatunid             = solicitemunid.pc17_unid   ";
    $sql .= "       left  join solicitaprotprocesso on solicitaprotprocesso.pc90_solicita = solicita.pc10_numero      ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  public function sql_query_dados_licitacao($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario      = pcproc.pc80_usuario";
    $sql .= "      inner join db_depart    on db_depart.coddepto          = pcproc.pc80_depto and db_depart.instit = " . db_getsession("DB_instit");
    $sql .= "      inner join pcprocitem   on pcprocitem.pc81_codproc     = pcproc.pc80_codproc";
    $sql .= "      inner join liclicitem   on pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
    $sql .= "      inner join liclicita    on liclicitem.l21_codliclicita = liclicita.l20_codigo";
    $sql .= "      inner join cflicita     on cflicita.l03_codigo         = liclicita.l20_codtipocom";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_empenho($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario              = pcproc.pc80_usuario ";
    $sql .= "      inner join db_depart             on db_depart.coddepto                  = pcproc.pc80_depto and db_depart.instit = " . db_getsession("DB_instit");
    $sql .= "      inner join db_departorg          on db_departorg.db01_coddepto          = db_depart.coddepto ";
    $sql .= "                                      and db_departorg.db01_anousu            = " . db_getsession("DB_anousu");
    $sql .= "      inner join orcorgao              on orcorgao.o40_orgao                  = db_departorg.db01_orgao ";
    $sql .= "                                      and orcorgao.o40_anousu                 = db_departorg.db01_anousu ";
    $sql .= "      inner join pcprocitem            on pcprocitem.pc81_codproc             = pcproc.pc80_codproc ";
    $sql .= "      inner  join solicitem             on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
    $sql .= "      inner  join solicita              on solicita.pc10_numero                = solicitem.pc11_numero";
    $sql .= "      inner  join empautitempcprocitem  on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
    $sql .= "      inner  join empautitem            on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
    $sql .= "                                      and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
    $sql .= "      inner  join empautoriza           on empautoriza.e54_autori              = empautitem.e55_autori ";
    $sql .= "      inner  join empempaut             on empautoriza.e54_autori              = empempaut.e61_autori ";
    $sql .= "      inner  join empempenho            on empempenho.e60_numemp               = empempaut.e61_numemp ";
    $sql .= "      inner  join cgm                   on empempenho.e60_numcgm               = cgm.z01_numcgm ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_tipocompra($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "      inner join pcprocitem   on pcprocitem.pc81_codproc = pcproc.pc80_codproc";
    $sql .= "      inner join solicitem    on solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
    $sql .= "      inner join solicita     on solicita.pc10_numero = solicitem.pc11_numero";
    $sql .= "      inner join solicitatipo on solicitatipo.pc12_numero = solicita.pc10_numero";
    $sql .= "      inner join pctipocompra on pctipocompra.pc50_codcom = solicitatipo.pc12_tipo";


    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_proc_solicita_abertura($pc80_codproc = null, $campos = "*", $ordem = null, $dbwhere = "")
  {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = explode("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from pcproc ";
    $sql .= "     inner join pcprocitem on pc81_codproc = pc80_codproc";
    $sql .= "     inner join db_depart  on pc80_depto   = coddepto";
    $sql .= "     inner join solicitem  on pc11_codigo  = pc81_solicitem";
    $sql .= "     inner join solicita  compilacao on compilacao.pc10_numero = pc11_numero and compilacao.pc10_solicitacaotipo = 6";
    $sql .= "     inner join solicitavinculo  on compilacao.pc10_numero = pc53_solicitafilho";
    $sql .= "     inner join solicita abertura on abertura.pc10_numero  = pc53_solicitapai";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($pc80_codproc != null) {
        $sql2 .= " where pcproc.pc80_codproc = $pc80_codproc ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = explode("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  public function sql_query_pncp($pc80_codproc = null)
  {

    $sql  = "select distinct (SELECT CASE
      WHEN o41_subunidade != 0
          OR NOT NULL THEN lpad((CASE WHEN o40_codtri = '0'
              OR NULL THEN o40_orgao::varchar ELSE o40_codtri END),2,0)||lpad((CASE WHEN o41_codtri = '0'
              OR NULL THEN o41_unidade::varchar ELSE o41_codtri END),3,0)||lpad(o41_subunidade::integer,3,0)
      ELSE lpad((CASE WHEN o40_codtri = '0'
          OR NULL THEN o40_orgao::varchar ELSE o40_codtri END),2,0)||lpad((CASE WHEN o41_codtri = '0'
          OR NULL THEN o41_unidade::varchar ELSE o41_codtri END),3,0)
      END AS codunidadesub
      FROM db_departorg
      JOIN infocomplementares ON si08_anousu = db01_anousu
      AND si08_instit = 1
      JOIN orcunidade ON db01_orgao=o41_orgao
      AND db01_unidade=o41_unidade
      AND db01_anousu = o41_anousu
      JOIN orcorgao on o40_orgao = o41_orgao and o40_anousu = o41_anousu
      WHERE db01_coddepto=pc80_depto and db01_anousu=2022 LIMIT 1) AS codigoUnidadeCompradora,
      3 AS tipoInstrumentoConvocatorioId,
      pcproc.pc80_modalidadecontratacao AS modalidadeId,
      5 AS modoDisputaId,
      pcproc.pc80_criteriojulgamento AS criterioJulgamentoId,
      pcproc.pc80_numdispensa AS numeroCompra,
      EXTRACT(YEAR FROM pcproc.pc80_data) AS anoCompra,
      pcproc.pc80_numdispensa||'/'||EXTRACT(YEAR FROM pcproc.pc80_data) AS numeroProcesso,
      pcproc.pc80_resumo AS objetoCompra,
      pcproc.pc80_dadoscomplementares as informacaoComplementar,
      false AS srp,
      pcproc.pc80_orcsigiloso as orcamentoSigiloso,
      pcproc.pc80_data AS dataAberturaProposta,
      pcproc.pc80_data AS dataEncerramentoProposta,
      pcproc.pc80_amparolegal as amparoLegalId,
      null as linkSistemaOrigem
      from pcproc
      join pcprocitem on pc81_codproc=pc80_codproc
      join solicitem on pc11_codigo=pc81_solicitem
      join solicitempcmater on pc16_solicitem=pc11_codigo
      join pcmater on pc16_codmater = pc01_codmater
      join solicitemunid on pc17_codigo=pc11_codigo
      join matunid on m61_codmatunid=pc17_unid
      left JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
      left JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
      left join pcorcam on pc20_codorc = pc22_codorc
      left JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
      left JOIN itemprecoreferencia ON pc23_orcamitem = si02_itemproccompra
      left JOIN precoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial
      where pcproc.pc80_codproc = {$pc80_codproc}";

    return $sql;
  }

  public function sql_query_pncp_itens($pc80_codproc = null)
  {
    $sql  = "SELECT DISTINCT solicitem.pc11_seq AS numeroItem,
                    CASE
                        WHEN pcmater.pc01_servico='t' THEN 'S'
                        ELSE 'M'
                    END AS materialOuServico,
                    5 AS tipoBeneficioId,
                    FALSE AS incentivoProdutivoBasico,
                            pcmater.pc01_descrmater AS descricao,
                            matunid.m61_descr AS unidadeMedida,
                            COALESCE(
                                (
                                    SELECT NULLIF(MIN(pc23_vlrun), NULL)
                                    FROM pcorcamval
                                    WHERE pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem
                                      AND pcorcamval.pc23_vlrun > 0
                                ),
                                solicitem.pc11_vlrun
                            ) AS valorUnitarioEstimado,
                            pcproc.pc80_criteriojulgamento AS criterioJulgamentoId,
                            pcmater.pc01_codmater,
                            solicitem.pc11_numero,
                            solicitem.pc11_reservado,
                            solicitem.pc11_quant,
                            FALSE AS l21_sigilo,
                      CASE
                          WHEN substring(o56_elemento
                                         FROM 0
                                         FOR 8) IN
                                   (SELECT DISTINCT substring(o56_elemento
                                                              FROM 0
                                                              FOR 8)
                                    FROM orcelemento
                                    WHERE o56_elemento LIKE '%3449061%') THEN 1
                          WHEN substring(o56_elemento
                                         FROM 0
                                         FOR 8) IN
                                   (SELECT DISTINCT substring(o56_elemento
                                                              FROM 0
                                                              FOR 8)
                                    FROM orcelemento
                                    WHERE o56_elemento LIKE '%3449052%') THEN 2
                          ELSE 3
                      END AS itemCategoriaId,
                      pcmater.pc01_regimobiliario AS codigoRegistroImobiliario
                    FROM pcproc
                    JOIN pcprocitem ON pc81_codproc=pc80_codproc
                    JOIN solicitem ON pc11_codigo=pc81_solicitem
                    JOIN solicitemele ON pc18_solicitem = pc11_codigo
                    JOIN orcelemento ON o56_codele = pc18_codele
                    AND o56_anousu=EXTRACT(YEAR
                              FROM pcproc.pc80_data)
                    JOIN solicitempcmater ON pc16_solicitem=pc11_codigo
                    JOIN pcmater ON pc16_codmater = pc01_codmater
                    JOIN solicitemunid ON pc17_codigo=pc11_codigo
                    JOIN matunid ON m61_codmatunid=pc17_unid
                    LEFT  JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
                    LEFT  JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
                    LEFT  JOIN pcorcam ON pc22_codorc = pc20_codorc
                    LEFT  JOIN pcorcamforne ON pc20_codorc = pc21_codorc
                    LEFT  JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
                    AND pc23_orcamforne = pc21_orcamforne
                    LEFT JOIN pcorcamjulg ON pc24_orcamitem = pc22_orcamitem
                    AND pc24_orcamforne = pc21_orcamforne
                    WHERE pcproc.pc80_codproc = $pc80_codproc";
    return $sql;
  }

  public function sql_get_dispensa_por_valor($where)
  {
    $sql  = "SELECT DISTINCT pc80_codproc,
    pc80_numdispensa,
    pc80_resumo,
	  l213_numerocontrolepncp AS numerodecontrole
    FROM pcproc
    JOIN pcprocitem ON pc81_codproc=pc80_codproc
    JOIN solicitem ON pc11_codigo=pc81_solicitem
    JOIN solicitempcmater ON pc16_solicitem=pc11_codigo
    JOIN pcmater ON pc16_codmater = pc01_codmater
    JOIN solicitemunid ON pc17_codigo=pc11_codigo
    JOIN db_depart on db_depart.coddepto = pcproc.pc80_depto
    JOIN matunid ON m61_codmatunid=pc17_unid
    LEFT JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
    LEFT JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
    LEFT JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
    LEFT JOIN liccontrolepncp on l213_processodecompras = pc80_codproc
    WHERE pc80_dispvalor='t'
          AND pc80_codproc NOT IN
        (SELECT DISTINCT pc81_codproc
         FROM liclicitem
         INNER JOIN pcprocitem ON pc81_codprocitem = l21_codpcprocitem
         WHERE pc81_codproc = pc80_codproc)
      $where
      and db_depart.instit = " . db_getsession('DB_instit') . "
    ORDER BY pc80_codproc desc";
    return $sql;
  }

  public function sql_query_pncp_itens_resultado($iPcproc, $iPcmater, $iSeq)
  {
    $sql  = "SELECT pcorcamval.pc23_quant AS quantidadeHomologada,
              pcorcamval.pc23_vlrun AS valorUnitarioHomologado,
              pcorcamval.pc23_valor AS valorTotalHomologado,
              pcorcamval.pc23_percentualdesconto AS percentualDesconto,
              CASE
                  WHEN length(trim(cgm.z01_cgccpf)) = 14 THEN 'PJ'
                  ELSE 'PF'
              END AS tipoPessoaId,
              cgm.z01_cgccpf AS niFornecedor,
              cgm.z01_nome AS nomeRazaoSocialFornecedor,
              3 AS porteFornecedorId,
              '0000' AS porteFornecedorId,
              'BRA' AS codigoPais,
              FALSE AS indicadorSubcontratacao, --1 as indicadorSubcontratacao
              pcproc.pc80_data dataResultado
          FROM pcproc
          JOIN pcprocitem ON pc81_codproc=pc80_codproc
          JOIN solicitem ON pc11_codigo=pc81_solicitem
          JOIN solicitempcmater ON pc16_solicitem=pc11_codigo
          JOIN pcmater ON pc16_codmater = pc01_codmater
          JOIN solicitemunid ON pc17_codigo=pc11_codigo
          JOIN matunid ON m61_codmatunid=pc17_unid
          INNER JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
          INNER JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
          INNER JOIN pcorcam ON pc22_codorc = pc20_codorc
          INNER JOIN pcorcamforne ON pc21_codorc = pc20_codorc
          INNER JOIN cgm ON z01_numcgm = pc21_numcgm
          INNER JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
          AND pc23_orcamforne = pc21_orcamforne
          LEFT JOIN pcorcamjulg ON pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
          AND pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
          LEFT JOIN liccontrolepncp ON l213_processodecompras = pc80_codproc
          WHERE pc80_dispvalor='t'
          and pc80_codproc= $iPcproc
          and pc01_codmater= $iPcmater
          and pc11_seq = $iSeq
          AND pc24_pontuacao = 1
          ORDER BY pc11_seq";

    return $sql;
  }

  public function sql_query_pncp_itens_retifica_situacao($iPcproc, $iPcmater, $iSeq)
  {
    $sql  = "SELECT DISTINCT solicitem.pc11_seq AS numeroItem,
                    CASE
                        WHEN pcmater.pc01_servico='t' THEN 'S'
                        ELSE 'M'
                    END AS materialOuServico,
                    5 AS tipoBeneficioId,
                    FALSE AS incentivoProdutivoBasico,
                            pcmater.pc01_descrmater AS descricao,
                            matunid.m61_descr AS unidadeMedida,
                            solicitem.pc11_vlrun AS valorUnitarioEstimado,
                            pcproc.pc80_tipoprocesso AS criterioJulgamentoId,
                            pcproc.pc80_modalidadecontratacao as modalidadeid,
                            pc23_orcamforne,
                            pcmater.pc01_codmater,
                            solicitem.pc11_numero,
                            solicitem.pc11_reservado,
                            solicitem.pc11_quant,
                            FALSE AS l21_sigilo,
                      CASE
                          WHEN substring(o56_elemento
                                         FROM 0
                                         FOR 8) IN
                                   (SELECT DISTINCT substring(o56_elemento
                                                              FROM 0
                                                              FOR 8)
                                    FROM orcelemento
                                    WHERE o56_elemento LIKE '%3449061%') THEN 1
                          WHEN substring(o56_elemento
                                         FROM 0
                                         FOR 8) IN
                                   (SELECT DISTINCT substring(o56_elemento
                                                              FROM 0
                                                              FOR 8)
                                    FROM orcelemento
                                    WHERE o56_elemento LIKE '%3449052%') THEN 2
                          ELSE 3
                      END AS itemCategoriaId,
                      pcmater.pc01_regimobiliario AS codigoRegistroImobiliario,
                      2 as situacaoCompraItemId,
                      pc23_vlrun
                    FROM pcproc
                    JOIN pcprocitem ON pc81_codproc=pc80_codproc
                    JOIN solicitem ON pc11_codigo=pc81_solicitem
                    JOIN solicitemele ON pc18_solicitem = pc11_codigo
                    JOIN orcelemento ON o56_codele = pc18_codele
                    AND o56_anousu=EXTRACT(YEAR
                              FROM pcproc.pc80_data)
                    JOIN solicitempcmater ON pc16_solicitem=pc11_codigo
                    JOIN pcmater ON pc16_codmater = pc01_codmater
                    JOIN solicitemunid ON pc17_codigo=pc11_codigo
                    JOIN matunid ON m61_codmatunid=pc17_unid
                    LEFT  JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
                    LEFT  JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
                    LEFT  JOIN pcorcam ON pc22_codorc = pc20_codorc
                    LEFT  JOIN pcorcamforne ON pc20_codorc = pc21_codorc
                    LEFT  JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
                    AND pc23_orcamforne = pc21_orcamforne
                    LEFT JOIN pcorcamjulg ON pc24_orcamitem = pc22_orcamitem
                    AND pc24_orcamforne = pc21_orcamforne
                    WHERE pcproc.pc80_codproc = $iPcproc
                      AND solicitem.pc11_seq = $iSeq
                      AND pcmater.pc01_codmater = $iPcmater";
    return $sql;
  }

  public function sql_query_item_pncp($pc80_codproc)
  {

    $sql  = " SELECT   pc01_codmater,
                       pc11_seq,
                       pc01_descrmater,
                       NULL AS pc68_nome,
                       cgm.z01_numcgm,
                       cgm.z01_nome,
                       matunid.m61_descr,
                       pcorcamval.pc23_quant,
                       pcorcamval.pc23_valor
                FROM pcproc
                INNER JOIN pcprocitem ON pc81_codproc = pc80_codproc
                INNER JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                INNER JOIN pcorcamitem ON pc22_orcamitem = pc31_orcamitem
                INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                LEFT JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                LEFT JOIN processocompralote ON pc68_pcproc = pc80_codproc
                LEFT JOIN processocompraloteitem ON pc69_processocompralote = pc68_sequencial
                AND pc69_pcprocitem=pc81_codprocitem
                LEFT JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                INNER JOIN pcorcam ON pc20_codorc = pc22_codorc
                INNER JOIN pcorcamforne ON pc21_codorc = pc20_codorc
                INNER JOIN cgm ON pc21_numcgm = z01_numcgm
                INNER JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
                AND pc23_orcamforne=pc21_orcamforne
                INNER JOIN pcorcamjulg ON pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
                AND pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
                WHERE pc80_codproc = $pc80_codproc
                    AND pc24_pontuacao =1
                    AND pc80_tipoprocesso != 2
                UNION
                SELECT pc01_codmater,
                       pc11_seq,
                       pc01_descrmater,
                       pc68_nome,
                       cgm.z01_numcgm,
                       cgm.z01_nome,
                       matunid.m61_descr,
                       pcorcamval.pc23_quant,
                       pcorcamval.pc23_valor
                FROM pcproc
                INNER JOIN pcprocitem ON pc81_codproc = pc80_codproc
                INNER JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                INNER JOIN pcorcamitem ON pc22_orcamitem = pc31_orcamitem
                INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                LEFT JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                LEFT JOIN processocompralote ON pc68_pcproc = pc80_codproc
                LEFT JOIN processocompraloteitem ON pc69_processocompralote = pc68_sequencial
                AND pc69_pcprocitem=pc81_codprocitem
                LEFT JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                INNER JOIN pcorcam ON pc20_codorc = pc22_codorc
                INNER JOIN pcorcamforne ON pc21_codorc = pc20_codorc
                INNER JOIN cgm ON pc21_numcgm = z01_numcgm
                INNER JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
                AND pc23_orcamforne=pc21_orcamforne
                INNER JOIN pcorcamjulg ON pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
                AND pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
                WHERE pc80_codproc = $pc80_codproc
                    AND pc24_pontuacao =1
                    AND pc80_tipoprocesso = 2
                    and pc69_sequencial is not null
                ORDER BY pc11_seq
        ";

    return $sql;
  }

  public function queryDotacao($pc80_codproc){

    $sSql = "SELECT DISTINCT pc13_coddot AS ficha,
                o15_codtri AS fonterecurso,
                o58_projativ AS projetoativ,
                o56_elemento as codorcamentario
                FROM pcproc
                INNER JOIN pcprocitem ON pcprocitem.pc81_codproc = pcproc.pc80_codproc
                INNER JOIN solicitem ON pcprocitem.pc81_solicitem = solicitem.pc11_codigo
                INNER JOIN pcdotac ON pcdotac.pc13_codigo = solicitem.pc11_codigo
                INNER JOIN orcdotacao ON (orcdotacao.o58_anousu,orcdotacao.o58_coddot) = (pcdotac.pc13_anousu,pcdotac.pc13_coddot)
                INNER JOIN orctiporec ON orctiporec.o15_codigo = orcdotacao.o58_codigo
                INNER JOIN orcelemento on (orcelemento.o56_codele,orcelemento.o56_anousu) = (orcdotacao.o58_codele,orcdotacao.o58_anousu)
                WHERE pc80_codproc = $pc80_codproc";

                return $sSql;

  }

  public function getTipoSolicitacao($pc80_codproc){

    $sql = "select
                pc10_solicitacaotipo
              from
                pcproc
              inner join pcprocitem on
                pc81_codproc = pc80_codproc
              inner join solicitem on
                pc11_codigo = pc81_solicitem
              inner join solicita on
                pc10_numero = pc11_numero
              where
                pc80_codproc = $pc80_codproc
              limit 1;";

    $rsTipoSolicitacao = db_query($sql);
    $tipoSolicitacao = db_utils::fieldsMemory($rsTipoSolicitacao, 0)->pc10_solicitacaotipo;

    return $tipoSolicitacao;

  }

  public function queryProcessodeComprasLicitacao(string $where)
  {
      $instituicao = db_getsession('DB_instit');
      $sql = " SELECT DISTINCT pc80_codproc
                FROM pcproc
                INNER JOIN db_usuarios ON db_usuarios.id_usuario = pcproc.pc80_usuario
                INNER JOIN db_depart ON db_depart.coddepto = pcproc.pc80_depto
                INNER JOIN pcprocitem ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                LEFT JOIN empautitem ON empautitem.e55_sequen = pcprocitem.pc81_codprocitem
                LEFT JOIN empautoriza ON empautoriza.e54_autori = empautitem.e55_autori
                LEFT JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                LEFT JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                LEFT JOIN solicitaregistropreco ON solicita.pc10_numero = solicitaregistropreco.pc54_solicita
                WHERE (e55_sequen IS NULL
                       OR (e55_sequen IS NOT NULL
                           AND e54_anulad IS NOT NULL))
                    AND pc10_instit = $instituicao
                    AND pc80_situacao = 2
                    AND NOT EXISTS
                        (SELECT 1
                         FROM acordopcprocitem
                         INNER JOIN acordoitem ON ac23_acordoitem = ac20_sequencial
                         INNER JOIN acordoposicao ON ac20_acordoposicao = ac26_sequencial
                         INNER JOIN acordo ON ac26_acordo = ac16_sequencial
                         WHERE ac23_pcprocitem = pc81_codprocitem
                             AND (ac16_acordosituacao NOT IN (2,3)))
                    AND pc80_codproc IN
                        (SELECT si01_processocompra
                         FROM precoreferencia)
                    AND pc80_criterioadjudicacao = 3
                    AND NOT EXISTS
                        (SELECT *
                         FROM liclicitem
                         INNER JOIN pcprocitem ON pc81_codprocitem = l21_codpcprocitem
                         WHERE pc81_codproc = pc80_codproc)
                    AND pc80_codproc NOT IN
                        (SELECT l213_processodecompras
                         FROM liccontrolepncp
                         WHERE l213_processodecompras IS NOT NULL)
                    AND NOT EXISTS
                        (SELECT 1
                         FROM empautitempcprocitem
                         WHERE e73_pcprocitem = pc81_codprocitem)
                    AND pc80_codproc NOT IN
                        (SELECT si06_processocompra
                         FROM adesaoregprecos)
                    AND pc80_dispvalor = 'f'
                $where
                ORDER BY pc80_codproc
      ";
      return $sql;
  }

  public function queryProcessosdeComprasVinculados($l20_codigo)
  {
      return "select distinct pc81_codproc from liclicitem
            inner join pcprocitem on pc81_codprocitem = l21_codpcprocitem
            where l21_codliclicita = $l20_codigo
            ";
  }

  public function queryDadosAutorizacao($pc80_codproc){

    $tipoSolicitacao = $this->getTipoSolicitacao($pc80_codproc);

    if($tipoSolicitacao == "5"){
      return "select *
      from pcproc
      inner join pcprocitem on pc81_codproc=pc80_codproc
      inner join solicitem filho on filho.pc11_codigo=pc81_solicitem
      inner join solicita solicitaf on solicitaf.pc10_numero=filho.pc11_numero
      inner join solicitavinculo on pc53_solicitafilho=solicitaf.pc10_numero
      inner join solicita on solicita.pc10_numero=pc53_solicitapai
      inner join solicitem on solicitem.pc11_numero=solicita.pc10_numero
      inner join pcprocitem pcprociteml on pcprociteml.pc81_solicitem=solicitem.pc11_codigo
      inner join liclicitem on l21_codpcprocitem=pcprociteml.pc81_codprocitem
      inner join liclicita on l20_codigo=l21_codliclicita
      inner join cflicita on l03_codigo=l20_codtipocom
      inner join pctipocompratribunal on l03_pctipocompratribunal=l44_sequencial
      inner join pctipocompra on pc50_pctipocompratribunal=l44_sequencial and l03_pctipocompratribunal=pc50_pctipocompratribunal
      where pc80_codproc = $pc80_codproc limit 1";
    }

    return "select *
    from pcproc
    left join pcprocitem on pc81_codproc = pc80_codproc
    left join liclicitem on l21_codpcprocitem = pc81_codprocitem
    left join liclicita on l20_codigo = l21_codliclicita
    left join adesaoregprecos on si06_processocompra = pc80_codproc
    left join cflicita on l03_codigo = l20_codtipocom
    where pc80_codproc = $pc80_codproc limit 1";

  }

  public function getDadosProcessoCompras($pc80_codproc)
  {
    return "SELECT *
                    FROM pcproc
                    INNER JOIN db_usuarios ON db_usuarios.id_usuario = pcproc.pc80_usuario
                    INNER JOIN db_depart ON db_depart.coddepto = pcproc.pc80_depto
                    AND db_depart.instit = 1
                    WHERE pcproc.pc80_codproc = $pc80_codproc";
  }


  public function sqlOrigemProcessoDecompras($pc80_codproc)
  {
      return "
          SELECT DISTINCT pc10_solicitacaotipo
            FROM pcprocitem
            INNER JOIN solicitem ON pc11_codigo=pc81_solicitem
            INNER JOIN solicita ON pc11_numero = pc10_numero
          WHERE pc81_codproc=$pc80_codproc
      ";
  }

  public function sqlEstimativas($pc80_codproc)
  {
      return "
            SELECT DISTINCT pc11_numero
                FROM pcprocitem
                INNER JOIN solicitem ON pc11_codigo = pc81_solicitem
                INNER JOIN solicitempcmater ON pc16_solicitem = pc11_codigo
                INNER JOIN solicitemunid ON pc17_codigo = pc11_codigo
                INNER JOIN matunid ON m61_codmatunid = pc17_unid
                INNER JOIN pcmater ON pc01_codmater = pc16_codmater
                WHERE pc81_codproc = $pc80_codproc
      ";
  }

  public function getlicitacao($pc80_codproc)
  {
      return "
            SELECT DISTINCT l21_codliclicita
            FROM liclicitem
            INNER JOIN pcprocitem ON l21_codpcprocitem = pc81_codprocitem
            WHERE pc81_codproc= $pc80_codproc
      ";
  }
}
