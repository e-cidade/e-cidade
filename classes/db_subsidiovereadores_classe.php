<?
/*
*     E-cidade Software Publico para Gestao Municipal                
*  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: sicom
class cl_subsidiovereadores
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
    var $si179_sequencial = 0;
    var $si179_valor = 0;
    var $si179_percentual = 0;
    var $si179_dataini = null;
    var $si179_lei = 0;
    var $si179_publicacao = null;
    var $si179_instit = null;
    // cria propriedade com as variaveis do arquivo 
    var $campos = "
                si179_sequencial = int4 = Código Sequencial 
                si179_valor = float = Valor do Subsidio
                si179_percentual = float = Percentual Reajuste
                si179_dataini = date = Data Inicio Vigência
                si179_lei = integer = Lei Autorizativa
                si179_publicacao = date = Data da Publicacao
                si179_instit = int4 = Instituição 
                ";
    //funcao construtor da classe 
    function cl_subsidiovereadores()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("subsidiovereadores");
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
            $this->si179_sequencial = ($this->si179_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si179_sequencial"] : $this->si179_sequencial);
            $this->si179_valor = ($this->si179_valor == "" ? @$GLOBALS["HTTP_POST_VARS"]["si179_valor"] : $this->si179_valor);
            $this->si179_percentual = ($this->si179_percentual == "" ? @$GLOBALS["HTTP_POST_VARS"]["si179_percentual"] : $this->si179_percentual);
            $this->si179_dataini = ($this->si179_dataini == "" ? @$GLOBALS["HTTP_POST_VARS"]["si179_dataini"] : $this->si179_dataini);
            $this->si179_lei = ($this->si179_lei == "" ? @$GLOBALS["HTTP_POST_VARS"]["si179_lei"] : $this->si179_lei);
            $this->si179_publicacao = ($this->si179_publicacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si179_publicacao"] : $this->si179_publicacao);
            $this->si179_instit = ($this->si179_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si179_instit"] : $this->si179_instit);
        } else {
            $this->si179_sequencial = ($this->si179_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si179_sequencial"] : $this->si179_sequencial);
        }
    }
    // funcao para inclusao
    function incluir($si179_sequencial)
    {
        $this->atualizacampos();
        if ($this->si179_valor == null) {
            $this->erro_sql = " Campo Valor Subsidio nao Informado.";
            $this->erro_campo = "si179_valor";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si179_percentual == null) {
            $this->erro_sql = " Campo Percentual Reajuste nao Informado.";
            $this->erro_campo = "si179_percentual";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si179_dataini == null) {
            $this->erro_sql = " Campo Data Inicio Vigência nao Informado.";
            $this->erro_campo = "si179_dataini";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si179_lei == null) {
            $this->erro_sql = " Campo Lei Autorizativa nao Informado.";
            $this->erro_campo = "si179_lei";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si179_publicacao == null) {
            $this->erro_sql = " Campo Data da Publicacao nao Informado.";
            $this->erro_campo = "si179_publicacao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->si179_instit == null) {
            $this->erro_sql = " Campo Instituição nao Informado.";
            $this->erro_campo = "si179_instit";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($si179_sequencial == "" || $si179_sequencial == null) {
            $result = db_query("select nextval('subsidiovereadores_si179_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: subsidiovereadores_si179_sequencial_seq do campo: si179_sequencial";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->si179_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from subsidiovereadores_si179_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $si179_sequencial)) {
                $this->erro_sql = " Campo si179_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->si179_sequencial = $si179_sequencial;
            }
        }
        if (($this->si179_sequencial == null) || ($this->si179_sequencial == "")) {
            $this->erro_sql = " Campo si179_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into subsidiovereadores(
                                    si179_sequencial 
                                    ,si179_valor 
                                    ,si179_percentual 
                                    ,si179_dataini 
                                    ,si179_lei 
                                    ,si179_publicacao 
                                    ,si179_instit 
                    )
            values (
                            $this->si179_sequencial 
                            ,$this->si179_valor 
                            ,$this->si179_percentual 
                            ,'$this->si179_dataini'
                            ,$this->si179_lei 
                            ,'$this->si179_publicacao'
                            ,$this->si179_instit 
                    )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "Subsidio dos Vereadores ($this->si179_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Subsidio dos Vereadores já Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "Subsidio dos Vereadores ($this->si179_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->si179_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        return true;
    }
    // funcao para alteracao
    function alterar($si179_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update subsidiovereadores set ";
        $virgula = "";
        if (trim($this->si179_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si179_sequencial"])) {
            $sql  .= $virgula . " si179_sequencial = $this->si179_sequencial ";
            $virgula = ",";
            if (trim($this->si179_sequencial) == null) {
                $this->erro_sql = " Campo Código Sequencial nao Informado.";
                $this->erro_campo = "si179_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si179_valor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si179_valor"])) {
            $sql  .= $virgula . " si179_valor = $this->si179_valor ";
            $virgula = ",";
            if (trim($this->si179_valor) == null) {
                $this->erro_sql = " Campo Valor Subsidio nao Informado.";
                $this->erro_campo = "si179_valor";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si179_percentual) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si179_percentual"])) {
            $sql  .= $virgula . " si179_percentual = $this->si179_percentual ";
            $virgula = ",";
            if (trim($this->si179_percentual) == null) {
                $this->erro_sql = " Campo Percentual Reajuste nao Informado.";
                $this->erro_campo = "si179_percentual";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si179_dataini) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si179_dataini"])) {
            $sql  .= $virgula . " si179_dataini = '$this->si179_dataini' ";
            $virgula = ",";
            if (trim($this->si179_dataini) == null) {
                $this->erro_sql = " Campo Data Inicio Vigência nao Informado.";
                $this->erro_campo = "si179_dataini";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si179_lei) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si179_lei"])) {
            $sql  .= $virgula . " si179_lei = $this->si179_lei ";
            $virgula = ",";
            if (trim($this->si179_lei) == null) {
                $this->erro_sql = " Campo Lei Autorizativa nao Informado.";
                $this->erro_campo = "si179_lei";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si179_publicacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si179_publicacao"])) {
            $sql  .= $virgula . " si179_publicacao = '$this->si179_publicacao' ";
            $virgula = ",";
            if (trim($this->si179_publicacao) == null) {
                $this->erro_sql = " Campo Data da Publicação nao Informado.";
                $this->erro_campo = "si179_publicacao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->si179_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si179_instit"])) {
            $sql  .= $virgula . " si179_instit = $this->si179_instit ";
            $virgula = ",";
            if (trim($this->si179_instit) == null) {
                $this->erro_sql = " Campo Instituição nao Informado.";
                $this->erro_campo = "si179_instit";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($si179_sequencial != null) {
            $sql .= " si179_sequencial = $this->si179_sequencial";
        }
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Subsidio dos Vereadores nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->si179_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Subsidio dos Vereadores nao Encontrado. Alteracao não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $this->si179_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->si179_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao 
    function excluir($si179_sequencial = null, $dbwhere = null)
    {
        $sql = " delete from subsidiovereadores
                where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($si179_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " si179_sequencial = $si179_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Subsidio dos Vereadores nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $si179_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Subsidio dos Vereadores nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $si179_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $si179_sequencial;
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
            $this->erro_sql   = "Record Vazio na Tabela:subsidiovereadores";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql 
    function sql_query($si179_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from subsidiovereadores ";
        $sql .= "      inner join db_config  on  db_config.codigo = subsidiovereadores.si179_instit";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($si179_sequencial != null) {
                $sql2 .= " where subsidiovereadores.si179_sequencial = $si179_sequencial ";
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
    function sql_query_file($si179_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from subsidiovereadores ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($si179_sequencial != null) {
                $sql2 .= " where subsidiovereadores.si179_sequencial = $si179_sequencial ";
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
