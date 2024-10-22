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

class cl_cgmcnae
{
    // cria variaveis de erro 
    var $rotulo = null;
    var $query_sql = null;
    var $numrows = 0;
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
    var $z16_sequencial = 0;
    var $z16_numcgm = 0;
    var $z16_cnae = 0;
    var $z16_tipo = 0;

    // cria propriedade com as variaveis do arquivo 
    var $campos = "
        z16_sequencial = int4 = Código sequencial 
        z16_numcgm = int4 = Código sequencial cgm 
        z16_cnae = int4 = Código sequencial cnae 
        z16_tipo = varchar(1) = p - primario e s - secundário";

    // funcao construtor da classe 
    function cl_cgmcnae()
    {
        // classes dos rotulos dos campos
        $this->rotulo = new rotulo("cgmanalitico");
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
            $this->z16_sequencial = ($this->z16_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["z16_sequencial"] : $this->z16_sequencial);
            $this->z16_numcgm = ($this->z16_numcgm == "" ? @$GLOBALS["HTTP_POST_VARS"]["z16_numcgm"] : $this->z16_numcgm);
            $this->z16_cnae = ($this->z16_cnae == "" ? @$GLOBALS["HTTP_POST_VARS"]["z16_cnae"] : $this->z16_cnae);
            $this->z16_tipo = ($this->z16_tipo == "" ? @$GLOBALS["HTTP_POST_VARS"]["z16_tipo"] : $this->z16_tipo);
        } else {
            $this->z16_sequencial = ($this->z16_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["z16_sequencial"] : $this->z16_sequencial);
        }
    }

    // funcao para inclusao
    function incluir($z16_sequencial = null)
    {
        $this->atualizacampos();

        if($z16_sequencial == "" || $z16_sequencial == null ){
            $result = db_query("select nextval('cgmcnae_z16_sequencial_seq')"); 
            if($result==false){
              $this->erro_banco = str_replace("\n","",@pg_last_error());
              $this->erro_sql   = "Verifique o cadastro da sequencia: cgmcnae_z16_sequencial_seq do campo: z16_sequencial"; 
              $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
              $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
              $this->erro_status = "0";
              return false; 
            }
            $this->z16_sequencial = pg_result($result,0,0); 
          }else{
            $result = db_query("select last_value from cgmcnae_z16_sequencial_seq");
            if(($result != false) && (pg_result($result,0,0) < $z16_sequencial)){
              $this->erro_sql = " Campo z16_sequencial maior que último número da sequencia.";
              $this->erro_banco = "Sequencia menor que este número.";
              $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
              $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
              $this->erro_status = "0";
              return false;
            }else{
              $this->z16_sequencial = $z16_sequencial; 
            }
          }

          if(($this->z16_sequencial == null) || ($this->z16_sequencial == "") ){ 
            $this->erro_sql = " Campo z16_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
            $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            $this->erro_status = "0";
            return false;
          }

        if ($this->z16_numcgm == null) {
            $this->erro_sql = " Campo Código sequencial cgm nao Informado.";
            $this->erro_campo = "z16_numcgm";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->z16_cnae == null) {
            $this->erro_sql = " Campo Código sequencial cnae nao Informado.";
            $this->erro_campo = "z16_cnae";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        
        if ($this->z16_tipo == null) {
            $this->erro_sql = " Campo Tipo cnae nao Informado.";
            $this->erro_campo = "z16_tipo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        $sql = " INSERT INTO cgmcnae (z16_sequencial, z16_numcgm, z16_cnae, z16_tipo)
                VALUES ($this->z16_sequencial, $this->z16_numcgm, $this->z16_cnae, '{$this->z16_tipo}')";
        // throw new Exception($sql);
        $result = db_query($sql);

        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "cgmcnae ($this->z16_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "cgmcnae já Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "cgmcnae ($this->z16_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->z16_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);

        return true;
    }

    // funcao para alteracao
    function alterar($z16_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " UPDATE cgmcnae SET ";
        $virgula = "";
        if (trim($this->z16_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["z16_sequencial"])) {
            $sql  .= $virgula . " z16_sequencial = $this->z16_sequencial ";
            $virgula = ",";
            if (trim($this->z16_sequencial) == null) {
                $this->erro_sql = " Campo Código sequencial nao Informado.";
                $this->erro_campo = "z16_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->z16_numcgm) != "" || isset($GLOBALS["HTTP_POST_VARS"]["z16_numcgm"])) {
            $sql  .= $virgula . " z16_numcgm = $this->z16_numcgm ";
            $virgula = ",";
            if (trim($this->z16_numcgm) == null) {
                $this->erro_sql = " Campo Código sequencial cgm nao Informado.";
                $this->erro_campo = "z16_numcgm";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->z16_cnae) != "" || isset($GLOBALS["HTTP_POST_VARS"]["z16_cnae"])) {
            $sql  .= $virgula . " z16_cnae = $this->z16_cnae ";
            $virgula = ",";
            if (trim($this->z16_cnae) == null) {
                $this->erro_sql = " Campo Código sequencial cnae nao Informado.";
                $this->erro_campo = "z16_cnae";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->z16_tipo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["z16_tipo"])) {
            $sql  .= $virgula . " z16_tipo = $this->z16_tipo ";
            $virgula = ",";
            if (trim($this->z16_tipo) == null) {
                $this->erro_sql = " Campo Tipo cnae nao Informado.";
                $this->erro_campo = "z16_tipo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        $sql .= " where ";
        if ($z16_sequencial != null) {
            $sql .= " z16_sequencial = $this->z16_sequencial";
        }
        $resaco = $this->sql_record($this->sql_query_file($this->z16_sequencial));
        if ($this->numrows > 0) {
            for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,10189,'$this->z16_sequencial','A')");
                if (isset($GLOBALS["HTTP_POST_VARS"]["z16_sequencial"]))
                    $resac = db_query("insert into db_acount values($acount,1753,10189,'" . AddSlashes(pg_result($resaco, $conresaco, 'z16_sequencial')) . "','$this->z16_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["z16_cnae"]))
                    $resac = db_query("insert into db_acount values($acount,1753,10190,'" . AddSlashes(pg_result($resaco, $conresaco, 'z16_cnae')) . "','$this->z16_cnae'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "cgmcnae nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->z16_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "cgmcnae nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->z16_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->z16_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    // funcao para exclusao 
    function excluir($z16_sequencial = null, $dbwhere = null)
    {
        if ($dbwhere == null || $dbwhere == "") {
            $resaco = $this->sql_record($this->sql_query_file($z16_sequencial));
        } else {
            $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
        }
        if (($resaco != false) || ($this->numrows != 0)) {
            for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,10189,'$z16_sequencial','E')");
                $resac = db_query("insert into db_acount values($acount,1753,10189,'','" . AddSlashes(pg_result($resaco, $iresaco, 'z16_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1753,10190,'','" . AddSlashes(pg_result($resaco, $iresaco, 'z16_cnae')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        $sql = " delete from cgmcnae
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($z16_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " z16_sequencial = $z16_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
  
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "cgmcnae nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $z16_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "cgmcnae nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $z16_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $z16_sequencial;
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
        $this->numrows = pg_numrows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:cgmcnae";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    function sql_query($z16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = " SELECT ";
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
        $sql .= " FROM cgmcnae ";
        $sql .= " INNER JOIN cnae ON q71_sequencial = z16_cnae";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($z16_sequencial != null) {
                $sql2 .= " WHERE cgmcnae.z16_sequencial = $z16_sequencial ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " WHERE $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " ORDER BY ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }

    function sql_query_file($z16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql = "SELECT ";
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
        $sql .= " FROM cgmcnae ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($z16_sequencial != null) {
                $sql2 .= " WHERE cgmcnae.z16_sequencial = $z16_sequencial ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " WHERE $dbwhere";
        }
        $sql .= $sql2;
        if ($ordem != null) {
            $sql .= " ORDER BY ";
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
