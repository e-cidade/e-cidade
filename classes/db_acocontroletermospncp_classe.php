<?php
//MODULO: licitacao
//CLASSE DA ENTIDADE acocontroletermospncp
class cl_acocontroletermospncp
{
    // cria variaveis de erro
    public $rotulo     = null;
    public $query_sql  = null;
    public $numrows    = 0;
    public $numrows_incluir = 0;
    public $numrows_alterar = 0;
    public $numrows_excluir = 0;
    public $erro_status = null;
    public $erro_sql   = null;
    public $erro_banco = null;
    public $erro_msg   = null;
    public $erro_campo = null;
    public $pagina_retorno = null;
    // cria variaveis do arquivo
    public $l214_sequencial = 0;
    public $l214_numerotermo = 0;
    public $l214_numcontratopncp = null;
    public $l213_usuario = null;
    public $l213_dtlancamento = null;
    public $l214_anousu = null;
    public $l214_acordo = 0;
    public $l214_numeroaditamento = 0;
    public $l214_acordoposicao = null;
    public $l214_instit = null;

    public $l214_tipotermocontratoid = null;

    // cria propriedade com as variaveis do arquivo
    public $campos = "
                 l214_sequencial = int8 = l214_sequencial
                 l214_numerotermo = int8 = l214_numerotermo
                 l214_numcontratopncp = int8 = codigo contrato pncp
                 l213_usuario = int8 = usuario
                 l213_dtlancamento = date = data de envio
                 l214_anousu = int8 = ano da compra
                 l214_acordo = int8 = l214_acordo
                 l214_numeroaditamento = int8 = numero do aditamento no ecidade
                 l214_acordoposicao = int8 = acordoposicao
                 l214_instit = int8 = l214_instit
                 l214_tipotermocontratoid = int4 = tipo de termo
                 ";

    //funcao construtor da classe
    function __construct()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("acocontroletermospncp");
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
            $this->l214_sequencial = ($this->l214_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["l214_sequencial"] : $this->l214_sequencial);
            $this->l214_numerotermo = ($this->l214_numerotermo == "" ? @$GLOBALS["HTTP_POST_VARS"]["l214_numerotermo"] : $this->l214_numerotermo);
            $this->l214_numcontratopncp = ($this->l214_numcontratopncp == "" ? @$GLOBALS["HTTP_POST_VARS"]["l214_numcontratopncp"] : $this->l214_numcontratopncp);
            $this->l213_usuario = ($this->l213_usuario == "" ? @$GLOBALS["HTTP_POST_VARS"]["l213_usuario"] : $this->l213_usuario);
            $this->l213_dtlancamento = ($this->l213_dtlancamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["l213_dtlancamento"] : $this->l213_dtlancamento);
            $this->l214_anousu = ($this->l214_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["l214_anousu"] : $this->l214_anousu);
            $this->l214_acordo = ($this->l214_acordo == "" ? @$GLOBALS["HTTP_POST_VARS"]["l214_acordo"] : $this->l214_acordo);
            $this->l214_numeroaditamento = ($this->l214_numeroaditamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["l214_numeroaditamento"] : $this->l214_numeroaditamento);
            $this->l214_acordoposicao = ($this->l214_acordoposicao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l214_acordoposicao"] : $this->l214_acordoposicao);
            $this->l214_instit = ($this->l214_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["l214_instit"] : $this->l214_instit);
            $this->l214_tipotermocontratoid = ($this->l214_tipotermocontratoid == "" ? @$GLOBALS["HTTP_POST_VARS"]["l214_tipotermocontratoid"] : $this->l214_tipotermocontratoid);
        }
    }

    // funcao para inclusao
    function incluir()
    {

        $this->atualizacampos();

        if ($this->l214_numerotermo == null || $this->l214_numerotermo == "") {
            $this->l214_numerotermo = "NULL";
        }

        if ($this->l214_acordo == null) {
            $this->erro_sql = " Campo l214_acordo n�o informado.";
            $this->erro_campo = "l214_acordo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l214_numcontratopncp == null) {
            $this->erro_sql = " Campo l214_numcontratopncp n�o informado.";
            $this->erro_campo = "l214_numcontratopncp";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l213_usuario == null) {
            $this->erro_sql = " Campo l213_usuario n�o informado.";
            $this->erro_campo = "l213_usuario";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l214_anousu == null) {
            $this->erro_sql = " Campo l214_anousu n�o informado.";
            $this->erro_campo = "l214_anousu";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l214_sequencial == "" || $this->l214_sequencial == null) {
            $result = db_query("select nextval('acocontroletermospncp_l214_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: acocontroletermospncp_l214_sequencial_seq do campo: l214_sequencial";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->l214_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from acocontroletermospncp_l214_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $this->l214_sequencial)) {
                $this->erro_sql = " Campo l214_sequencial maior que �ltimo n�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este n�mero.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->l214_sequencial = $this->l214_sequencial;
            }
        }
        if (($this->l214_sequencial == null) || ($this->l214_sequencial == "")) {
            $this->erro_sql = " Campo l214_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into acocontroletermospncp(
                                       l214_sequencial
                                      ,l214_numerotermo
                                      ,l214_numcontratopncp
                                      ,l213_usuario
                                      ,l213_dtlancamento
                                      ,l214_anousu
                                      ,l214_acordo
                                      ,l214_numeroaditamento
                                      ,l214_acordoposicao
                                      ,l214_instit
                                      ,l214_tipotermocontratoid
                       )
                values (
                                $this->l214_sequencial
                               ,$this->l214_numerotermo
                               ,$this->l214_numcontratopncp
                               ,$this->l213_usuario
                               ,'$this->l213_dtlancamento'
                               ,$this->l214_anousu
                               ,$this->l214_acordo
                               ,$this->l214_numeroaditamento
                               ,$this->l214_acordoposicao
                               ,$this->l214_instit
                               ,$this->l214_tipotermocontratoid
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "acocontroletermospncp () nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "acocontroletermospncp j� Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "acocontroletermospncp () nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {
        }
        return true;
    }

    // funcao para alteracao
    function alterar($l214_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update acocontroletermospncp set ";
        $virgula = "";
        if (trim($this->l214_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l214_sequencial"])) {
            $sql  .= $virgula . " l214_sequencial = $this->l214_sequencial ";
            $virgula = ",";
            if (trim($this->l214_sequencial) == null) {
                $this->erro_sql = " Campo l214_sequencial n�o informado.";
                $this->erro_campo = "l214_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l214_numerotermo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l214_numerotermo"])) {
            $sql  .= $virgula . " l214_numerotermo = $this->l214_numerotermo ";
            $virgula = ",";
            if (trim($this->l214_numerotermo) == null) {
                $this->erro_sql = " Campo l214_numerotermo n�o informado.";
                $this->erro_campo = "l214_numerotermo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l214_acordo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l214_acordo"])) {
            $sql  .= $virgula . " l214_acordo = $this->l214_acordo ";
            $virgula = ",";
            if (trim($this->l214_acordo) == null) {
                $this->erro_sql = " Campo l214_acordo n�o informado.";
                $this->erro_campo = "l214_acordo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        $sql .= "l214_sequencial = '$l214_sequencial'";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "acocontroletermospncp nao Alterado. Alteracao Abortada.\\n";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "acocontroletermospncp nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    // funcao para exclusao
    function excluir($l214_sequencial = null, $dbwhere = null)
    {

        $sql = " delete from acocontroletermospncp
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            $sql2 = "l214_sequencial = $l214_sequencial";
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "acocontroletermospncp nao Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "acocontroletermospncp nao Encontrado. Exclus�o n�o Efetuada.\\n";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
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
            $this->erro_sql   = "Erro ao selecionar os registros.";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:acocontroletermospncp";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    // funcao do sql
    function sql_query($l214_sequencial = null, $campos = "acocontroletermospncp.l214_sequencial,*", $ordem = null, $dbwhere = "")
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
        $sql .= " from acocontroletermospncp ";
        $sql .= " join acordo on ac16_sequencial = l214_acordo ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l214_sequencial != "" && $l214_sequencial != null) {
                $sql2 = " where acocontroletermospncp.l214_sequencial = '$l214_sequencial'";
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

    // funcao do sql
    function sql_query_file($l214_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from acocontroletermospncp ";
        $sql2 = "";
        if ($dbwhere == "") {
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
}