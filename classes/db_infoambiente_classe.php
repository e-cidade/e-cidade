<?

//MODULO: esocial
//CLASSE DA ENTIDADE infoambiente
class cl_infoambiente
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
    var $rh230_regist = 0;
    var $rh230_data_dia = null;
    var $rh230_data_mes = null;
    var $rh230_data_ano = null;
    var $rh230_data = null;
    var $rh230_descricao = null;
    var $rh230_instit = null;

    // cria propriedade com as variaveis do arquivo 
    var $campos = "
                 rh230_regist = int4 = Matricula do servidor
                 rh230_data = date = Data de Inicio
                 rh230_descricao = text = Descrição do Ambiente
                 rh230_instit = int4 = Instituição
                 ";

    //funcao construtor da classe 
    function cl_infoambiente()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("infoambiente");
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
            $this->rh230_regist = ($this->rh230_regist == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh230_regist"] : $this->rh230_regist);
            if ($this->rh230_data == "") {
                $this->rh230_data_dia = ($this->rh230_data_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh230_data_dia"] : $this->rh230_data_dia);
                $this->rh230_data_mes = ($this->rh230_data_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh230_data_mes"] : $this->rh230_data_mes);
                $this->rh230_data_ano = ($this->rh230_data_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh230_data_ano"] : $this->rh230_data_ano);
                if ($this->rh230_data_dia != "") {
                    $this->rh230_data = $this->rh230_data_ano . "-" . $this->rh230_data_mes . "-" . $this->rh230_data_dia;
                }
            }
            $this->rh230_descricao = ($this->rh230_descricao == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh230_descricao"] : $this->rh230_descricao);
            $this->rh230_instit = ($this->rh230_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh230_instit"] : $this->rh230_instit);
        } else {
            $this->rh230_regist = ($this->rh230_regist == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh230_regist"] : $this->rh230_regist);
        }
    }
    // funcao para inclusao
    function incluir($rh230_regist)
    {
        $this->atualizacampos();
        if ($this->rh230_data == null) {
            $this->erro_sql = " Campo Data de Inicio não informado.";
            $this->erro_campo = "rh230_data_dia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh230_descricao == null) {
            $this->erro_sql = " Campo Descrição do Ambiente não informado.";
            $this->erro_campo = "rh230_descricao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->rh230_regist = $rh230_regist;
        if (($this->rh230_regist == null) || ($this->rh230_regist == "")) {
            $this->erro_sql = " Campo Matricula nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into infoambiente(
                                      rh230_regist 
                                     ,rh230_data 
                                     ,rh230_descricao
                                     ,rh230_instit
                      )
               values (
                              $this->rh230_regist
                              ," . ($this->rh230_data == "null" || $this->rh230_data == "" ? "null" : "'" . $this->rh230_data . "'") . " 
                              ,'$this->rh230_descricao' 
                              ,$this->rh230_instit
                     )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "Informação do Ambiente para Servidor ($this->rh230_regist) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Servidor já possui cadastro.";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "Informacao de Ambiente para Servidor ($this->rh230_regist) nao Incluida. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql  = "Inclusao efetuada com Sucesso\\n\\n";
        $this->erro_sql .= "Matricula : " . $this->rh230_regist;
        $this->erro_msg  = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $resaco = $this->sql_record($this->sql_query_file($this->rh230_regist));
        return true;
    }

    // funcao para alteracao
    public function alterar($rh230_regist = null)
    {
        $this->atualizacampos();
        $sql = " update infoambiente set ";
        $virgula = "";
        if (trim($this->rh230_regist) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh230_regist"])) {
            $sql  .= $virgula . " rh230_regist = $this->rh230_regist ";
            $virgula = ",";
            if (trim($this->rh230_regist) == null) {
                $this->erro_sql = " Campo Matricula não informado.";
                $this->erro_campo = "rh230_regist";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh230_data) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh230_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh230_data_dia"] != "")) {
            $sql  .= $virgula . " rh230_data = '$this->rh230_data' ";
            $virgula = ",";
            if (trim($this->rh230_data) == null) {
                $this->erro_sql = " Campo Data não informado.";
                $this->erro_campo = "rh230_data_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["rh230_data_dia"])) {
                $sql  .= $virgula . " rh230_data = null ";
                $virgula = ",";
                if (trim($this->rh230_data) == null) {
                    $this->erro_sql = " Campo Data não informado.";
                    $this->erro_campo = "rh230_data_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        if (trim($this->rh230_descricao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh230_descricao"])) {
            $sql  .= $virgula . " rh230_descricao = '$this->rh230_descricao' ";
            $virgula = ",";
        }
        $sql .= " where ";
        if ($rh230_regist != null) {
            $sql .= " rh230_regist = $this->rh230_regist";
        }

        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Informacao de Ambiente para Servidor nao Alterada. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->rh230_regist;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Informacao de Ambiente para Servidor nao foi Alterada. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->rh230_regist;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->rh230_regist;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao 
    public function excluir($rh230_regist = null, $dbwhere = null)
    {

        $sql = " delete from infoambiente
                 where ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh230_regist)) {
                if (!empty($sql2)) {
                    $sql2 .= " and ";
                }
                $sql2 .= " rh230_regist = $rh230_regist ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Informacao de Ambiente para Servidor nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $rh230_regist;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Informacao de Ambiente para Servidor nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $rh230_regist;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $rh230_regist;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
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
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:infoambiente";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql 
    public function sql_query($rh230_regist = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = "select {$campos}";
        $sql .= "  from infoambiente ";
        $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = infoambiente.rh230_regist";
        $sql .= "      inner join cgm        on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
        $sql .= "      inner join db_config  on  db_config.codigo = infoambiente.rh230_instit";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh230_regist)) {
                $sql2 .= " where infoambiente.rh230_regist = $rh230_regist ";
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
    public function sql_query_file($rh230_regist = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = "select {$campos} ";
        $sql .= "  from infoambiente ";
        $sql2 = "";
        if (empty($dbwhere)) {
            if (!empty($rh230_regist)) {
                $sql2 .= " where infoambiente.rh230_regist = $rh230_regist ";
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
}
