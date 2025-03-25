<?
//MODULO: sicom
//CLASSE DA ENTIDADE \d subve102025

class cl_subve102025
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
    var $si180_sequencial = 0;
    var $si180_tiporegistro = 0;
    var $si180_codsubsidio = 0;
    var $si180_vlrtotalsubsidio = null;
    var $si180_porcentagemreajuste = null;
    var $si180_dtinicialsubsidio = null;
    var $si180_nrleisubsidio = null;
    var $si180_dtpublicacaoleisubsidio = null;
    var $si180_instit = 0;
    var $si180_mes = 0;
    var $campos = "
                    si180_sequencial = int4 = Código Sequencial
                    si180_tiporegistro = int8 = Tipo registro
                    si180_codsubsidio = int4 = Código Subsidio
                    si180_vlrtotalsubsidio = float = Valor do Subsidio
                    si180_porcentagemreajuste = float = Percentual Reajuste
                    si180_dtinicialsubsidio = date = Data Inicio Vigência
                    si180_nrleisubsidio = integer = Lei Autorizativa
                    si180_dtpublicacaoleisubsidio = date = Data da Publicacao
                    si180_instit = int4 = Instituição
                    si180_mes = int4 = Mês 
                ";
    //funcao construtor da classe 
    function __construct()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("subve102025");
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
            $this->si180_sequencial = ($this->si180_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_sequencial"] : $this->si180_sequencial);
            $this->si180_tiporegistro = ($this->si180_tiporegistro == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_tiporegistro"] : $this->si180_tiporegistro);
            $this->si180_codsubsidio = ($this->si180_codsubsidio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_codsubsidio"] : $this->si180_codsubsidio);
            $this->si180_vlrtotalsubsidio = ($this->si180_vlrtotalsubsidio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_vlrtotalsubsidio"] : $this->si180_vlrtotalsubsidio);
            $this->si180_porcentagemreajuste = ($this->si180_porcentagemreajuste == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_porcentagemreajuste"] : $this->si180_porcentagemreajuste);
            $this->si180_dtinicialsubsidio = ($this->si180_dtinicialsubsidio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_dtinicialsubsidio"] : $this->si180_dtinicialsubsidio);
            $this->si180_nrleisubsidio = ($this->si180_nrleisubsidio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_nrleisubsidio"] : $this->si180_nrleisubsidio);
            $this->si180_dtpublicacaoleisubsidio = ($this->si180_dtpublicacaoleisubsidio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_dtpublicacaoleisubsidio"] : $this->si180_dtpublicacaoleisubsidio);
            $this->si180_instit = ($this->si180_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_instit"] : $this->si180_instit);
            $this->si180_mes = ($this->si180_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_mes"] : $this->si180_mes);
        } else {
            $this->si180_sequencial = ($this->si180_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_sequencial"] : $this->si180_sequencial);
        }
    }
    // funcao para inclusao
    function incluir($si180_sequencial)
    {
        $this->atualizacampos();
        if ($this->si180_tiporegistro == null) {
            $this->erro_sql = " Campo Tipo registro nao informado.";
            $this->erro_campo = "si180_tiporegistro";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuario: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si180_codsubsidio == null) {
            $this->erro_sql = " Codigo de matricula nao informado.";
            $this->erro_campo = "si180_codsubsidio";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuario: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si180_vlrtotalsubsidio == null) {
            $this->erro_sql = " Campo Valor Subsidio nao Informado.";
            $this->erro_campo = "si180_vlrtotalsubsidio";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si180_porcentagemreajuste == null) {
            $this->erro_sql = " Campo Percentual Reajuste nao Informado.";
            $this->erro_campo = "si180_porcentagemreajuste";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si180_dtinicialsubsidio == null) {
            $this->erro_sql = " Campo Data Inicio Vigência nao Informado.";
            $this->erro_campo = "si180_dtinicialsubsidio";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si180_nrleisubsidio == null) {
            $this->erro_sql = " Campo Lei Autorizativa nao Informado.";
            $this->erro_campo = "si180_nrleisubsidio";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si180_dtpublicacaoleisubsidio == null) {
            $this->erro_sql = " Campo Data da Publicacao nao Informado.";
            $this->erro_campo = "si180_dtpublicacaoleisubsidio";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si180_instit == null) {
            $this->erro_sql = " Campo Instituição nao Informado.";
            $this->erro_campo = "si180_instit";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si180_mes == null) {
            $this->erro_sql = " Campo si180_mes nao informado.";
            $this->erro_campo = "si180_mes";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuario: \n\n " . $this->erro_sql . " \n\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \n\n " . $this->erro_banco . " \n"));
            $this->erro_status = "0";
            return false;
        }
        if ($si180_sequencial == "" || $si180_sequencial == null) {
            $result = db_query("select nextval('subve102025_si180_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: subve102025_si180_sequencial_seq do campo: si180_sequencial";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->si180_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from subve102025_si180_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $si180_sequencial)) {
                $this->erro_sql = " Campo si180_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->si180_sequencial = $si180_sequencial;
            }
        }
        if (($this->si180_sequencial == null) || ($this->si180_sequencial == "")) {
            $this->erro_sql = " Campo si180_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into subve102025(
                                    si180_sequencial
                                    ,si180_tiporegistro
                                    ,si180_codsubsidio
                                    ,si180_vlrtotalsubsidio 
                                    ,si180_porcentagemreajuste 
                                    ,si180_dtinicialsubsidio 
                                    ,si180_nrleisubsidio 
                                    ,si180_dtpublicacaoleisubsidio 
                                    ,si180_instit 
                                    ,si180_mes
                    )
            values (
                            $this->si180_sequencial 
                            ,$this->si180_tiporegistro
                            ,$this->si180_codsubsidio
                            ,$this->si180_vlrtotalsubsidio 
                            ,$this->si180_porcentagemreajuste 
                            ,'$this->si180_dtinicialsubsidio'
                            ,$this->si180_nrleisubsidio 
                            ,'$this->si180_dtpublicacaoleisubsidio'
                            ,$this->si180_instit
                            ,$this->si180_mes
                    )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "Subve102025 ($this->si180_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Subve102025 já Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "Subve102025 ($this->si180_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->si180_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        return true;
    }
    // funcao para alteracao
    function alterar($si180_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update subve102025 set ";
        $virgula = "";
        if (trim($this->si180_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_sequencial"])) {
            $sql  .= $virgula . " si180_sequencial = $this->si180_sequencial ";
            $virgula = ",";
            if (trim($this->si180_sequencial) == null) {
                $this->erro_sql = " Campo Código Sequencial nao Informado.";
                $this->erro_campo = "si180_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si180_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_tiporegistro"])) {
            $sql  .= $virgula . " si180_tiporegistro = $this->si180_tiporegistro ";
            $virgula = ",";
            if (trim($this->si180_tiporegistro) == null) {
                $this->erro_sql = " Campo Tipo registro nao informado.";
                $this->erro_campo = "si180_tiporegistro";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuario: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \n\n " . $this->erro_banco . " \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si180_codsubsidio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_codsubsidio"])) {
            $sql  .= $virgula . " si180_codsubsidio = $this->si180_codsubsidio ";
            $virgula = ",";
            if (trim($this->si180_codsubsidio) == null) {
                $this->erro_sql = " Codigo do subsidio nao informado.";
                $this->erro_campo = "si180_codsubsidio";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuario: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \n\n " . $this->erro_banco . " \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si180_vlrtotalsubsidio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_vlrtotalsubsidio"])) {
            $sql  .= $virgula . " si180_vlrtotalsubsidio = $this->si180_vlrtotalsubsidio ";
            $virgula = ",";
            if (trim($this->si180_vlrtotalsubsidio) == null) {
                $this->erro_sql = " Campo Valor Subsidio nao Informado.";
                $this->erro_campo = "si180_vlrtotalsubsidio";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si180_porcentagemreajuste) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_porcentagemreajuste"])) {
            $sql  .= $virgula . " si180_porcentagemreajuste = $this->si180_porcentagemreajuste ";
            $virgula = ",";
            if (trim($this->si180_porcentagemreajuste) == null) {
                $this->erro_sql = " Campo Percentual Reajuste nao Informado.";
                $this->erro_campo = "si180_porcentagemreajuste";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si180_dtinicialsubsidio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_dtinicialsubsidio"])) {
            $sql  .= $virgula . " si180_dtinicialsubsidio = '$this->si180_dtinicialsubsidio' ";
            $virgula = ",";
            if (trim($this->si180_dtinicialsubsidio) == null) {
                $this->erro_sql = " Campo Data Inicio Vigência nao Informado.";
                $this->erro_campo = "si180_dtinicialsubsidio";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si180_nrleisubsidio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_nrleisubsidio"])) {
            $sql  .= $virgula . " si180_nrleisubsidio = $this->si180_nrleisubsidio ";
            $virgula = ",";
            if (trim($this->si180_nrleisubsidio) == null) {
                $this->erro_sql = " Campo Lei Autorizativa nao Informado.";
                $this->erro_campo = "si180_nrleisubsidio";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si180_dtpublicacaoleisubsidio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_dtpublicacaoleisubsidio"])) {
            $sql  .= $virgula . " si180_dtpublicacaoleisubsidio = '$this->si180_dtpublicacaoleisubsidio' ";
            $virgula = ",";
            if (trim($this->si180_dtpublicacaoleisubsidio) == null) {
                $this->erro_sql = " Campo Data da Publicação nao Informado.";
                $this->erro_campo = "si180_dtpublicacaoleisubsidio";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si180_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_instit"])) {
            $sql  .= $virgula . " si180_instit = $this->si180_instit ";
            $virgula = ",";
            if (trim($this->si180_instit) == null) {
                $this->erro_sql = " Campo Instituição nao Informado.";
                $this->erro_campo = "si180_instit";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si180_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_mes"])) {
            $sql  .= $virgula . " si180_mes = $this->si180_mes ";
            $virgula = ",";
            if (trim($this->si180_mes) == null) {
                $this->erro_sql = " Campo si180_mes nao informado.";
                $this->erro_campo = "si180_mes";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuario: \n\n " . $this->erro_sql . " \n\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \n\n " . $this->erro_banco . " \n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($si180_sequencial != null) {
            $sql .= " si180_sequencial = $this->si180_sequencial";
        }
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Subve102025 nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->si180_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Subve102025 nao Encontrado. Alteracao não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $this->si180_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->si180_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao 
    function excluir($si180_sequencial = null, $dbwhere = null)
    {
        $sql = " delete from subve102025
                where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($si180_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " si180_sequencial = $si180_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Subve102025 nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $si180_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Subve102025 nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $si180_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $si180_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
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
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:subve102025";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql 
    function sql_query($si180_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from subve102025 ";
        $sql .= "      inner join db_config  on  db_config.codigo = subve102025.si180_instit";
        $sql .= "      inner join infocomplementaresinstit on infocomplementaresinstit.si09_instit = db_config.codigo";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($si180_sequencial != null) {
                $sql2 .= " where subve102025.si180_sequencial = $si180_sequencial ";
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
    function sql_query_file($si180_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from subve102025 ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($si180_sequencial != null) {
                $sql2 .= " where subve102025.si180_sequencial = $si180_sequencial ";
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
}
