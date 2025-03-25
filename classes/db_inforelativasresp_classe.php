<?

class cl_inforelativasresp
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
    var $rh234_sequencial = 0;
    var $rh234_regist = 0;
    var $rh234_cpf = null;
    var $rh234_orgao = null;
    var $rh234_descrorgao = null;
    var $rh234_numinscricao = null;
    var $rh234_uf = null;
    // cria propriedade com as variaveis do arquivo 
    var $campos = "
                 rh234_sequencial = int4 = Sequencial da Tabela
                 rh234_regist = int4 = Matricula do Servidor
                 rh234_cpf = varchar(11) = CPF do Responsável
                 rh234_orgao = int4 = Órgão de Classe
                 rh234_descrorgao = text = Descrição (sigla) do órgão
                 rh234_numinscricao = int8 = Número de Inscrição no Órgão de Classe
                 rh234_uf = varchar(2) = Sigla da Unidade da Federação - UF do Órgão de Classe
                 ";
    //funcao construtor da classe 
    function cl_inforelativasresp()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("inforelativasresp");
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
            $this->rh234_sequencial = ($this->rh234_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh234_sequencial"] : $this->rh234_sequencial);
            $this->rh234_regist = ($this->rh234_regist == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh234_regist"] : $this->rh234_regist);
            $this->rh234_cpf = ($this->rh234_cpf == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh234_cpf"] : $this->rh234_cpf);
            $this->rh234_orgao = ($this->rh234_orgao == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh234_orgao"] : $this->rh234_orgao);
            $this->rh234_descrorgao = ($this->rh234_descrorgao == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh234_descrorgao"] : $this->rh234_descrorgao);
            $this->rh234_numinscricao = ($this->rh234_numinscricao == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh234_numinscricao"] : $this->rh234_numinscricao);
            $this->rh234_uf = ($this->rh234_uf == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh234_uf"] : $this->rh234_uf);
        } else {
            $this->rh234_sequencial = ($this->rh234_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh234_sequencial"] : $this->rh234_sequencial);
        }
    }
    // funcao para inclusao
    function incluir($rh234_sequencial)
    {
        $this->atualizacampos();
        if ($this->rh234_regist == null) {
            $this->erro_sql = " Campo Servidor nao Informado.";
            $this->erro_campo = "rh234_regist";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh234_cpf == null) {
            $this->erro_sql = " Campo CPF nao Informado.";
            $this->erro_campo = "rh234_cpf";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh234_cpf == '00000000000' || strlen($this->rh234_cpf) < 11) {
            $this->erro_sql = " Campo CPF inválido.";
            $this->erro_campo = "rh234_cpf";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh234_orgao == 9 && $this->rh234_descrorgao == null) {
            $this->erro_sql = " Campo CPF nao Informado.";
            $this->erro_campo = "rh234_cpf";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh234_numinscricao == '' || $this->rh234_numinscricao == null) {
            $this->rh234_numinscricao = 'null';
        }
        if ($rh234_sequencial == "" || $rh234_sequencial == null) {
            $result = db_query("select nextval('inforelativasresp_rh234_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: inforelativasresp_rh234_sequencial_seq do campo: rh234_sequencial";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->rh234_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from inforelativasresp_rh234_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $rh234_sequencial)) {
                $this->erro_sql = " Campo rh234_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->rh234_sequencial = $rh234_sequencial;
            }
        }
        if (($this->rh234_sequencial == null) || ($this->rh234_sequencial == "")) {
            $this->erro_sql = " Campo rh234_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into inforelativasresp(
                                      rh234_sequencial 
                                     ,rh234_regist
                                     ,rh234_cpf
                                     ,rh234_orgao
                                     ,rh234_descrorgao
                                     ,rh234_numinscricao
                                     ,rh234_uf
                      )
              values (
                               $this->rh234_sequencial
                              ,$this->rh234_regist
                              ,'$this->rh234_cpf'
                              ,$this->rh234_orgao
                              ,'$this->rh234_descrorgao'
                              ,$this->rh234_numinscricao
                              ,'$this->rh234_uf'
                     )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "inforelativasresp ($this->rh234_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "inforelativasresp já Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "inforelativasresp ($this->rh234_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->rh234_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        return true;
    }
    // funcao para alteracao
    function alterar($rh234_sequencial = "")
    {
        $this->atualizacampos();
        $sql = " update inforelativasresp set ";
        $virgula = "";
        if (trim($this->rh234_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh234_sequencial"])) {
            $sql  .= $virgula . " rh234_sequencial = $this->rh234_sequencial ";
            $virgula = ",";
            if (trim($this->rh234_sequencial) == null) {
                $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
                $this->erro_campo = "rh234_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh234_regist) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh234_regist"])) {
            $sql  .= $virgula . " rh234_regist = $this->rh234_regist ";
            $virgula = ",";
            if (trim($this->rh234_regist) == null) {
                $this->erro_sql = " Campo Matricula do Servidor nao Informado.";
                $this->erro_campo = "rh234_regist";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh234_cpf) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh234_cpf"])) {
            $sql  .= $virgula . " rh234_cpf = $this->rh234_cpf ";
            $virgula = ",";
            if (trim($this->rh234_cpf) == null) {
                $this->erro_sql = " Campo Departamento nao Informado.";
                $this->erro_campo = "rh234_cpf";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh234_orgao) != '' || isset($GLOBALS['HTTP_POST_VARS']['rh234_orgao'])) {
            $sql .= $virgula . " rh234_orgao = '$this->rh234_orgao' ";
            $virgula = ',';
        }
        if (trim($this->rh234_descrorgao) != '' || isset($GLOBALS['HTTP_POST_VARS']['rh234_descrorgao'])) {
            $sql .= $virgula . " rh234_descrorgao = '$this->rh234_descrorgao' ";
            $virgula = ',';
        }
        if (trim($this->rh234_numinscricao) != '' || isset($GLOBALS['HTTP_POST_VARS']['rh234_numinscricao'])) {
            $sql .= $virgula . " rh234_numinscricao = '$this->rh234_numinscricao' ";
            $virgula = ',';
        }
        if (trim($this->rh234_uf) != '' || isset($GLOBALS['HTTP_POST_VARS']['rh234_uf'])) {
            $sql .= $virgula . " rh234_uf = '$this->rh234_uf' ";
            $virgula = ',';
        }
        $sql .= " where ";
        if ($rh234_sequencial != null) {
            $sql .= " rh234_sequencial = $this->rh234_sequencial";
        }
        $resaco = $this->sql_record($this->sql_query_file($this->rh234_sequencial));
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "inforelativasresp nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->rh234_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "inforelativasresp nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->rh234_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->rh234_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao 
    function excluir($rh234_sequencial = null, $dbwhere = null)
    {
        if ($dbwhere == null || $dbwhere == "") {
            $resaco = $this->sql_record($this->sql_query_file($rh234_sequencial));
        } else {
            $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
        }
        $sql = " delete from inforelativasresp
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($rh234_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " rh234_sequencial = $rh234_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "inforelativasresp nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $rh234_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "inforelativasresp nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $rh234_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $rh234_sequencial;
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
            $this->erro_sql   = "Record Vazio na Tabela:inforelativasresp";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql 
    function sql_query($rh234_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "select ";
        if ($campos != "*") {
            $campos_sql = preg_split("#", $campos);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }
        $sql .= " from inforelativasresp ";
        $sql .= " inner join infoambiente on infoambiente.rh230_regist = inforelativasresp.rh234_regist";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($rh234_sequencial != null) {
                $sql2 .= " where inforelativasresp.rh234_sequencial = $rh234_sequencial ";
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
    // funcao do sql 
    function sql_query_file($rh234_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from inforelativasresp ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($rh234_sequencial != null) {
                $sql2 .= " where inforelativasresp.rh234_sequencial = $rh234_sequencial ";
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
}
