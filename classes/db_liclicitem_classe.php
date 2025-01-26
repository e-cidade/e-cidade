<?php
//MODULO: licita��o
//CLASSE DA ENTIDADE liclicitem
class cl_liclicitem
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
    var $l21_codigo = 0;
    var $l21_codliclicita = 0;
    var $l21_codpcprocitem = 0;
    var $l21_situacao = 0;
    var $l21_ordem = 0;
    var $l21_reservado = null;
    var $l21_sigilo = null;

    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 l21_codigo = int8 = Cod. Sequencial
                 l21_codliclicita = int8 = Cod. Sequencial
                 l21_codpcprocitem = int8 = C�digo sequencial do item no processo
                 l21_situacao = int4 = Situa��o
                 l21_ordem = int4 = Seq��ncia
                 l21_reservado = boolean = Reservado
                 l21_sigilo = boolean = orc sigiloso
                 ";
    //funcao construtor da classe
    function cl_liclicitem()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("liclicitem");
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
            $this->l21_codigo = ($this->l21_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["l21_codigo"] : $this->l21_codigo);
            $this->l21_codliclicita = ($this->l21_codliclicita == "" ? @$GLOBALS["HTTP_POST_VARS"]["l21_codliclicita"] : $this->l21_codliclicita);
            $this->l21_codpcprocitem = ($this->l21_codpcprocitem == "" ? @$GLOBALS["HTTP_POST_VARS"]["l21_codpcprocitem"] : $this->l21_codpcprocitem);
            $this->l21_situacao = ($this->l21_situacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l21_situacao"] : $this->l21_situacao);
            $this->l21_ordem = ($this->l21_ordem == "" ? @$GLOBALS["HTTP_POST_VARS"]["l21_ordem"] : $this->l21_ordem);
            $this->l21_reservado = ($this->l21_reservado == "" ? @$GLOBALS["HTTP_POST_VARS"]["l21_reservado"] : $this->l21_reservado);
            $this->l21_sigilo = ($this->l21_sigilo == "" ? @$GLOBALS["HTTP_POST_VARS"]["l21_sigilo"] : $this->l21_sigilo);
        } else {
            $this->l21_codigo = ($this->l21_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["l21_codigo"] : $this->l21_codigo);
        }
    }
    // funcao para inclusao
    function incluir($l21_codigo)
    {
        $this->atualizacampos();
        if ($this->l21_codliclicita == null) {
            $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
            $this->erro_campo = "l21_codliclicita";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l21_codpcprocitem == null) {
            $this->erro_sql = " Campo C�digo sequencial do item no processo nao Informado.";
            $this->erro_campo = "l21_codpcprocitem";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l21_situacao == null) {
            $this->erro_sql = " Campo Situa��o nao Informado.";
            $this->erro_campo = "l21_situacao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l21_ordem == null) {
            $this->l21_ordem = "1";
        }
        if ($l21_codigo == "" || $l21_codigo == null) {
            $result = db_query("select nextval('liclicitem_l21_codigo_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: liclicitem_l21_codigo_seq do campo: l21_codigo";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->l21_codigo = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from liclicitem_l21_codigo_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $l21_codigo)) {
                $this->erro_sql = " Campo l21_codigo maior que �ltimo n�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este n�mero.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->l21_codigo = $l21_codigo;
            }
        }
        if (($this->l21_codigo == null) || ($this->l21_codigo == "")) {
            $this->erro_sql = " Campo l21_codigo nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l21_reservado == null || !$this->l21_reservado) {
            $this->l21_reservado = 'false';
        }

        if ($this->l21_sigilo == null || !$this->l21_sigilo) {
            $this->l21_sigilo = 'false';
        }

        $sql = "insert into liclicitem(
                                       l21_codigo
                                      ,l21_codliclicita
                                      ,l21_codpcprocitem
                                      ,l21_situacao
                                      ,l21_ordem
                                      ,l21_reservado
                                      ,l21_sigilo
                       )
                values (
                                $this->l21_codigo
                               ,$this->l21_codliclicita
                               ,$this->l21_codpcprocitem
                               ,$this->l21_situacao
                               ,$this->l21_ordem
                               ,'$this->l21_reservado'
                               ,'$this->l21_sigilo'
                      )";
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "liclicitem ($this->l21_codigo) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "liclicitem j� Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "liclicitem ($this->l21_codigo) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->l21_codigo;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $resaco = $this->sql_record($this->sql_query_file($this->l21_codigo));
        if (($resaco != false) || ($this->numrows != 0)) {
            $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
            $acount = pg_result($resac, 0, 0);
            $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
            $resac = db_query("insert into db_acountkey values($acount,7600,'$this->l21_codigo','I')");
            $resac = db_query("insert into db_acount values($acount,1261,7600,'','" . AddSlashes(pg_result($resaco, 0, 'l21_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,1261,7601,'','" . AddSlashes(pg_result($resaco, 0, 'l21_codliclicita')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,1261,7602,'','" . AddSlashes(pg_result($resaco, 0, 'l21_codpcprocitem')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,1261,10011,'','" . AddSlashes(pg_result($resaco, 0, 'l21_situacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            $resac = db_query("insert into db_acount values($acount,1261,10973,'','" . AddSlashes(pg_result($resaco, 0, 'l21_ordem')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
        }
        return true;
    }
    // funcao para alteracao
    function alterar($l21_codigo = null)
    {
        $this->atualizacampos();
        $sql = " update liclicitem set ";
        $virgula = "";
        if (trim($this->l21_codigo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l21_codigo"])) {
            $sql  .= $virgula . " l21_codigo = $this->l21_codigo ";
            $virgula = ",";
            if (trim($this->l21_codigo) == null) {
                $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
                $this->erro_campo = "l21_codigo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l21_codliclicita) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l21_codliclicita"])) {
            $sql  .= $virgula . " l21_codliclicita = $this->l21_codliclicita ";
            $virgula = ",";
            if (trim($this->l21_codliclicita) == null) {
                $this->erro_sql = " Campo Cod. Sequencial nao Informado.";
                $this->erro_campo = "l21_codliclicita";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l21_codpcprocitem) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l21_codpcprocitem"])) {
            $sql  .= $virgula . " l21_codpcprocitem = $this->l21_codpcprocitem ";
            $virgula = ",";
            if (trim($this->l21_codpcprocitem) == null) {
                $this->erro_sql = " Campo C�digo sequencial do item no processo nao Informado.";
                $this->erro_campo = "l21_codpcprocitem";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l21_situacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l21_situacao"])) {
            $sql  .= $virgula . " l21_situacao = $this->l21_situacao ";
            $virgula = ",";
            if (trim($this->l21_situacao) == null) {
                $this->erro_sql = " Campo Situa��o nao Informado.";
                $this->erro_campo = "l21_situacao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l21_ordem) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l21_ordem"])) {
            if (trim($this->l21_ordem) == "" && isset($GLOBALS["HTTP_POST_VARS"]["l21_ordem"])) {
                $this->l21_ordem = "0";
            }
            $sql  .= $virgula . " l21_ordem = $this->l21_ordem ";
            $virgula = ",";
        }
        $sql .= " where ";
        if ($l21_codigo != null) {
            $sql .= " l21_codigo = $this->l21_codigo";
        }
        $resaco = $this->sql_record($this->sql_query_file($this->l21_codigo));
        if ($this->numrows > 0) {
            for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,7600,'$this->l21_codigo','A')");
                if (isset($GLOBALS["HTTP_POST_VARS"]["l21_codigo"]))
                    $resac = db_query("insert into db_acount values($acount,1261,7600,'" . AddSlashes(pg_result($resaco, $conresaco, 'l21_codigo')) . "','$this->l21_codigo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["l21_codliclicita"]))
                    $resac = db_query("insert into db_acount values($acount,1261,7601,'" . AddSlashes(pg_result($resaco, $conresaco, 'l21_codliclicita')) . "','$this->l21_codliclicita'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["l21_codpcprocitem"]))
                    $resac = db_query("insert into db_acount values($acount,1261,7602,'" . AddSlashes(pg_result($resaco, $conresaco, 'l21_codpcprocitem')) . "','$this->l21_codpcprocitem'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["l21_situacao"]))
                    $resac = db_query("insert into db_acount values($acount,1261,10011,'" . AddSlashes(pg_result($resaco, $conresaco, 'l21_situacao')) . "','$this->l21_situacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                if (isset($GLOBALS["HTTP_POST_VARS"]["l21_ordem"]))
                    $resac = db_query("insert into db_acount values($acount,1261,10973,'" . AddSlashes(pg_result($resaco, $conresaco, 'l21_ordem')) . "','$this->l21_ordem'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "liclicitem nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->l21_codigo;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "liclicitem nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->l21_codigo;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->l21_codigo;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    function excluir($l21_codigo = null, $dbwhere = null)
    {
        if ($dbwhere == null || $dbwhere == "") {
            $resaco = $this->sql_record($this->sql_query_file($l21_codigo));
        } else {
            $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
        }
        if (($resaco != false) || ($this->numrows != 0)) {
            for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,7600,'$l21_codigo','E')");
                $resac = db_query("insert into db_acount values($acount,1261,7600,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l21_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1261,7601,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l21_codliclicita')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1261,7602,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l21_codpcprocitem')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1261,10011,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l21_situacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1261,10973,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l21_ordem')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        $sql = " delete from liclicitem
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($l21_codigo != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " l21_codigo = $l21_codigo ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "liclicitem nao Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_sql .= "Valores : " . $l21_codigo;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "liclicitem nao Encontrado. Exclus�o n�o Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $l21_codigo;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $l21_codigo;
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
        $this->numrows = pg_numrows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql   = "Record Vazio na Tabela:liclicitem";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    function sql_query($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicitem ";
        $sql .= "      inner join pcprocitem   on  pcprocitem.pc81_codprocitem = liclicitem.l21_codpcprocitem";
        $sql .= "      inner join liclicita    on  liclicita.l20_codigo     = liclicitem.l21_codliclicita";
        $sql .= "      inner join solicitem    on  solicitem.pc11_codigo    = pcprocitem.pc81_solicitem";
        $sql .= "      inner join pcproc       on  pcproc.pc80_codproc      = pcprocitem.pc81_codproc";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario   = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita     on  cflicita.l03_codigo      = liclicita.l20_codtipocom";
        $sql .= "      inner join liclocal     on  liclocal.l26_codigo      = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo   = liclicita.l20_liccomissao";
        $sql .= "       left join solicitatipo on  solicitatipo.pc12_numero = solicitem.pc11_numero";
        $sql .= "       left join pctipocompra on  pctipocompra.pc50_codcom = solicitatipo.pc12_tipo";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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
    function sql_query_anulados($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicitem ";
        $sql .= "      inner join liclicita        on liclicita.l20_codigo            = liclicitem.l21_codliclicita";
        $sql .= "      inner join cflicita         on cflicita.l03_codigo             = liclicita.l20_codtipocom";
        $sql .= "      inner join pctipocompra     on pctipocompra.pc50_codcom        = cflicita.l03_codcom";
        $sql .= "      inner join pcprocitem       on liclicitem.l21_codpcprocitem    = pcprocitem.pc81_codprocitem";
        $sql .= "      inner join pcproc           on pcproc.pc80_codproc             = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem        on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita         on solicita.pc10_numero            = solicitem.pc11_numero";
        $sql .= "      inner join db_depart        on db_depart.coddepto              = solicita.pc10_depto";
        $sql .= "      inner join db_usuarios      on solicita.pc10_login             = db_usuarios.id_usuario";
        $sql .= "      left  join solicitemunid    on solicitemunid.pc17_codigo       = solicitem.pc11_codigo";
        $sql .= "      left  join matunid          on matunid.m61_codmatunid          = solicitemunid.pc17_unid";
        $sql .= "      left  join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
        $sql .= "      left  join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater";
        $sql .= "      left  join solicitemele     on solicitemele.pc18_solicitem     = solicitem.pc11_codigo";
        $sql .= "      left  join liclicitemanu    on liclicitemanu.l07_liclicitem    = liclicitem.l21_codigo";
        $sql .= "      left  join licsituacao      on licsituacao.l08_sequencial    = liclicita.l20_licsituacao";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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
    function sql_query_file($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicitem ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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
    function sql_query_inf($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $lTrazerApenasVencedor = false)
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
        $sql .= " from liclicitem ";
        $sql .= "      inner join pcprocitem           on liclicitem.l21_codpcprocitem        = pcprocitem.pc81_codprocitem";
        $sql .= "      left join pcorcamitemproc      on pc31_pcprocitem                     = pc81_codprocitem";
        $sql .= "      inner join pcproc               on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc";
        $sql .= "      left  join itemprecoreferencia  on si02_itemproccompra                 = pcorcamitemproc.pc31_orcamitem";
        $sql .= "      inner join solicitem            on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita             on solicita.pc10_numero                = solicitem.pc11_numero";
        $sql .= "      inner join db_depart            on db_depart.coddepto                  = solicita.pc10_depto";
        $sql .= "      left  join liclicita            on liclicita.l20_codigo                = liclicitem.l21_codliclicita";
        $sql .= "      left  join licsituacao 			on l08_sequencial 					   = l20_licsituacao";
        $sql .= "      left  join cflicita             on cflicita.l03_codigo                 = liclicita.l20_codtipocom";
        $sql .= "      left  join pctipocompra         on pctipocompra.pc50_codcom            = cflicita.l03_codcom";
        $sql .= "      left  join solicitemunid        on solicitemunid.pc17_codigo           = solicitem.pc11_codigo";
        $sql .= "      left  join matunid              on matunid.m61_codmatunid              = solicitemunid.pc17_unid";
        $sql .= "      left  join pcorcamitemlic       on l21_codigo                          = pc26_liclicitem ";
        $sql .= "      left  join pcorcamval           on pc26_orcamitem                      = pc23_orcamitem ";
        $sql .= "      left join pcorcamjulg    on pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem";
        $sql .= "                              and pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne";
        if ($lTrazerApenasVencedor) {
            $sql .= "  and pcorcamjulg.pc24_pontuacao  = 1";
        }
        $sql .= "      left  join pcorcamforne         on pc21_orcamforne                     = pc23_orcamforne ";
        $sql .= "      left  join cgm                  on pc21_numcgm                         = z01_numcgm ";
        $sql .= "      left  join db_usuarios          on pcproc.pc80_usuario                 = db_usuarios.id_usuario";
        $sql .= "      left  join solicitempcmater     on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo";
        $sql .= "      left  join pcmater              on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater";
        $sql .= "      left  join pcsubgrupo           on pcsubgrupo.pc04_codsubgrupo         = pcmater.pc01_codsubgrupo";
        $sql .= "      left  join pctipo               on pctipo.pc05_codtipo                 = pcsubgrupo.pc04_codtipo";
        $sql .= "      left  join solicitemele         on solicitemele.pc18_solicitem         = solicitem.pc11_codigo";
        $sql .= "      left  join orcelemento          on orcelemento.o56_codele              = solicitemele.pc18_codele";
        $sql .= "                                     and orcelemento.o56_anousu              = " . db_getsession("DB_anousu");
        $sql .= "      left  join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "      left  join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori";
        $sql .= "                                     and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen";
        $sql .= "      left  join empautoriza          on empautoriza.e54_autori              = empautitem.e55_autori ";
        $sql .= "      left  join empempaut            on empempaut.e61_autori                = empautitem.e55_autori ";
        $sql .= "      left  join empempenho           on empempenho.e60_numemp               = empempaut.e61_numemp ";
        $sql .= "      left  join pcdotac              on solicitem.pc11_codigo               = pcdotac.pc13_codigo ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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
    function sql_query_orc($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicitem ";
        $sql .= "      inner join pcprocitem  on  liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
        $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto";
        $sql .= "      inner join db_usuarios  on  pcproc.pc80_usuario = db_usuarios.id_usuario";
        $sql .= "      inner join pcdotac on  solicitem.pc11_codigo = pcdotac.pc13_codigo ";
        $sql .= "      inner join orcdotacao on  o58_coddot = pc13_coddot and pc13_anousu = o58_anousu  ";
        $sql .= "      inner join orcorgao on o40_orgao = o58_orgao and o40_anousu=o58_anousu ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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
    function sql_query_proc($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicitem ";
        $sql .= "      inner join pcprocitem  on  liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "      left join  processocompraloteitem on pcprocitem.pc81_codprocitem = pc69_pcprocitem";
        $sql .= "      left join  processocompralote     on pc69_processocompralote = pc68_sequencial";
        $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
        $sql .= "      inner join db_depart  on  db_depart.coddepto = pcproc.pc80_depto";
        $sql .= "      inner join db_usuarios  on  pcproc.pc80_usuario = db_usuarios.id_usuario";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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
    function sql_query_sol($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicitem ";
        $sql .= "      inner join pcprocitem           on  liclicitem.l21_codpcprocitem    = pcprocitem.pc81_codprocitem";
        $sql .= "      inner join pcproc               on  pcproc.pc80_codproc             = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem            on  solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita             on  solicita.pc10_numero            = solicitem.pc11_numero";
        $sql .= "      inner join db_depart            on  db_depart.coddepto              = solicita.pc10_depto";
        $sql .= "      inner join db_usuarios          on  solicita.pc10_login             = db_usuarios.id_usuario";
        $sql .= "      left  join solicitemunid        on  solicitemunid.pc17_codigo       = solicitem.pc11_codigo";
        $sql .= "      left  join matunid              on  matunid.m61_codmatunid          = solicitemunid.pc17_unid";
        $sql .= "      left  join solicitempcmater     on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
        $sql .= "      left  join pcmater  				    on  pcmater.pc01_codmater           = solicitempcmater.pc16_codmater";
        $sql .= "      left  join solicitemele         on  solicitemele.pc18_solicitem     = solicitem.pc11_codigo";
        $sql .= "      left  join solicitaprotprocesso on solicitaprotprocesso.pc90_solicita  = solicita.pc10_numero ";
        $sql .= "      left  join processocompraloteitem on processocompraloteitem.pc69_pcprocitem = pcprocitem.pc81_codprocitem ";
        $sql .= "      left  join processocompralote on processocompralote.pc68_sequencial = processocompraloteitem.pc69_processocompralote ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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

    function sql_query_soljulg($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $filtros = '')
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
        $sql .= " from liclicitem ";
        $sql .= "      inner join liclicita on l21_codliclicita = l20_codigo";
        $sql .= "      inner join pcprocitem  on  liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
        $sql .= "      inner join db_depart  on  db_depart.coddepto = solicita.pc10_depto";
        $sql .= "      inner join db_usuarios  on  solicita.pc10_login = db_usuarios.id_usuario";
        $sql .= "      inner join pcorcamitemlic on l21_codigo = pc26_liclicitem ";
        $sql .= "      inner join pcorcamval     on pc26_orcamitem = pc23_orcamitem ";
        $sql .= "      inner join pcorcamjulg    on pc23_orcamitem = pc24_orcamitem ";
        $sql .= "                               and pc23_orcamforne = pc24_orcamforne";
        $sql .= "                               and pc24_pontuacao  = 1";
        $sql .= "      inner join pcorcamforne   on pc23_orcamforne = pc21_orcamforne ";
        $sql .= "      left  join solicitemunid  on  solicitemunid.pc17_codigo = solicitem.pc11_codigo";
        $sql .= "      left  join matunid  on  matunid.m61_codmatunid = solicitemunid.pc17_unid";
        $sql .= "      left  join solicitempcmater  on  solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
        $sql .= "      left  join pcmater  on  pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
        $sql .= "      left  join solicitemele  on  solicitemele.pc18_solicitem = solicitem.pc11_codigo";
        $sql .= "      left  join acordoliclicitem  on  l21_codigo = ac24_liclicitem";
        $sql .= "      left join itenshomologacao on l203_fornecedor = pc21_numcgm and l203_item = pc81_codprocitem";

        if ($filtros) {
            $sql .= "      left join cflicita on l03_codigo = l20_codtipocom";
        }
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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
    /*
     * Fun��o criada para retornar itens da licita��es de credenciamento
     * OC OC8339
     */
    function sql_query_soljulgCredenciamento($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " FROM credenciamento ";
        $sql .= " INNER JOIN liclicita ON l20_codigo = l205_licitacao";
        $sql .= " INNER JOIN liclicitem ON (l21_codliclicita,l21_codpcprocitem) = (l20_codigo,l205_item)";
        $sql .= " INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= " INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= " INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc";
        $sql .= " INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
        $sql .= " INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo";
        $sql .= " INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo";
        $sql .= " INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater";
        $sql .= " INNER JOIN pcorcamitemproc ON pcorcamitemproc.pc31_pcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= " INNER JOIN pcorcamitem ON pcorcamitem.pc22_orcamitem = pcorcamitemproc.pc31_orcamitem";
        $sql .= " INNER JOIN pcorcam on pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
        $sql .= " INNER JOIN pcorcamforne ON pcorcamforne.pc21_codorc = pcorcam.pc20_codorc";
        $sql .= " INNER JOIN pcorcamval ON pcorcamval.pc23_orcamitem = pcorcamitem.pc22_orcamitem AND pcorcamval.pc23_orcamforne = pcorcamforne.pc21_orcamforne";
        $sql .= " INNER JOIN pcorcamjulg ON pcorcamjulg.pc24_orcamitem = pcorcamitem.pc22_orcamitem AND pcorcamjulg.pc24_orcamforne = pcorcamforne.pc21_orcamforne";
        $sql .= " LEFT JOIN solicitemele ON solicitemele.pc18_solicitem = solicitem.pc11_codigo";
        $sql .= " LEFT JOIN credenciamentosaldo on credenciamentosaldo.l213_licitacao = liclicita.l20_codigo AND l21_codigo = l213_itemlicitacao";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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

    function sql_query_dotacao_reserva($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicitem ";
        $sql .= "      inner join pcprocitem  on  liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "      inner join pcproc  on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
        $sql .= "      inner join pcdotac on  solicitem.pc11_codigo = pcdotac.pc13_codigo ";
        $sql .= "      left join  orcreservasol  on pc13_sequencial = o82_pcdotac";
        $sql .= "      left join  orcreserva     on o82_codres      = o80_codres";
        $sql2 = "";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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

    function sql_query_portal_transparencia($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $lTrazerApenasVencedor = false)
    {
        $sql = "select distinct ";
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
        $sql .= " from liclicitem ";
        $sql .= "      inner join pcprocitem           on liclicitem.l21_codpcprocitem        = pcprocitem.pc81_codprocitem";
        $sql .= "      inner join pcproc               on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc";
        $sql .= "      inner join solicitem            on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem";
        $sql .= "      inner join solicita             on solicita.pc10_numero                = solicitem.pc11_numero";
        $sql .= "      inner join db_depart            on db_depart.coddepto                  = solicita.pc10_depto";
        $sql .= "      left  join liclicita            on liclicita.l20_codigo                = liclicitem.l21_codliclicita";
        $sql .= "      left  join cflicita             on cflicita.l03_codigo                 = liclicita.l20_codtipocom";
        $sql .= "      left  join pctipocompra         on pctipocompra.pc50_codcom            = cflicita.l03_codcom";
        $sql .= "      left  join solicitemunid        on solicitemunid.pc17_codigo           = solicitem.pc11_codigo";
        $sql .= "      left  join matunid              on matunid.m61_codmatunid              = solicitemunid.pc17_unid";
        $sql .= "      left  join pcorcamitemlic       on l21_codigo                          = pc26_liclicitem ";
        $sql .= "      left join pcorcamjulg           on pc26_orcamitem = pcorcamjulg.pc24_orcamitem";
        $sql .= "                                     and pcorcamjulg.pc24_pontuacao  = 1";
        $sql .= "      left  join pcorcamval           on pc24_orcamitem                      = pc23_orcamitem ";
        $sql .= "                                     and pc24_orcamforne                     = pc23_orcamforne ";
        $sql .= "      left  join pcorcamforne         on pc21_orcamforne                     = pc23_orcamforne ";
        $sql .= "      left  join cgm                  on pc21_numcgm                         = z01_numcgm ";
        $sql .= "      left  join db_usuarios          on pcproc.pc80_usuario                 = db_usuarios.id_usuario";
        $sql .= "      left  join solicitempcmater     on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo";
        $sql .= "      left  join pcmater              on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater";
        $sql .= "      left  join pcsubgrupo           on pcsubgrupo.pc04_codsubgrupo         = pcmater.pc01_codsubgrupo";
        $sql .= "      left  join pctipo               on pctipo.pc05_codtipo                 = pcsubgrupo.pc04_codtipo";
        $sql .= "      left  join solicitemele         on solicitemele.pc18_solicitem         = solicitem.pc11_codigo";
        $sql .= "      left  join orcelemento          on orcelemento.o56_codele              = solicitemele.pc18_codele";
        $sql .= "                                     and orcelemento.o56_anousu              = extract(year from pc10_data)";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l21_codigo != null) {
                $sql2 .= " where liclicitem.l21_codigo = $l21_codigo ";
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

    /**
     * @param null $l21_codigo
     * @param string $campos
     * @param null $ordem
     * @param string $dbwhere
     * @return string
     */
    public function sql_query_licitacao_compilacao($l21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = " select {$campos} ";
        $sql .= "  from liclicitem ";
        $sql .= "       inner join pcprocitem  on  liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem";
        $sql .= "       inner join pcproc      on  pcproc.pc80_codproc = pcprocitem.pc81_codproc";
        $sql .= "       inner join solicitem   on  solicitem.pc11_codigo = pcprocitem.pc81_solicitem";
        $sql .= "       inner join solicita    on  solicita.pc10_numero = solicitem.pc11_numero";
        $sql .= "       inner join liclicita   on  liclicita.l20_codigo = liclicitem.l21_codliclicita";

        if (!empty($dbwhere)) {
            $sql .= " where {$dbwhere} ";
        } else if (!empty($l21_codigo)) {
            $sql .= " where liclicitem.l21_codigo = $l21_codigo ";
        }

        if (!empty($ordem)) {
            $sql .= " order by {$ordem} ";
        }
        return $sql;
    }

    public function sql_tipocompratribunal($l21_codigo = null)
    {

        $sql  = " SELECT l03_pctipocompratribunal ";
        $sql .= " FROM liclicita ";
        $sql .= " INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= " where liclicita.l20_codigo = $l21_codigo ";

        return $sql;
    }

    public function sql_queryverificajulgamento($l21_codliclicita)
    {
        $sql = "
        SELECT DISTINCT
            pcorcam.*
        FROM liclicitem
        LEFT JOIN pcorcamitemlic ON l21_codigo = pc26_liclicitem
        LEFT JOIN pcorcamval ON pc26_orcamitem = pc23_orcamitem
        LEFT JOIN pcorcamjulg ON pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
        AND pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
        LEFT JOIN pcorcamforne ON pc21_orcamforne = pc23_orcamforne
        left join pcorcamitem on pc22_orcamitem=pc26_orcamitem
        LEFT JOIN pcorcam on pc20_codorc=pc22_codorc and pc20_codorc=pc21_codorc
        WHERE l21_codliclicita =  $l21_codliclicita";

        return $sql;
    }

    public function queryOrdemItens($l20_codigo){
        return "SELECT COALESCE(MAX(l21_ordem) + 1, 1) AS l21_ordem
                    FROM liclicitem
                    WHERE l21_codliclicita = {$l20_codigo}";
    }

    public function getItensLicitacao($l20_codigo,$l224_forne,$lote)
    {

        return "
    SELECT DISTINCT
       l21_ordem,
       pc01_codmater,
       pc01_descrmater,
       m61_descr AS unidade,
       pc11_quant AS quantidade,
       l224_porcent AS percentual,
       l224_vlrun AS vlr_unitario,
       l224_valor AS vlr_total,
       l224_marca AS marca,
       l224_codigo,
       pc81_codprocitem,
       l04_descricao,
       l20_objeto,
       l20_anousu,
       l20_numero,
       pc01_complmater,
       pc01_taxa,
       pc01_tabela,
       l20_criterioadjudicacao
    FROM liclicitem
    INNER JOIN liclicita ON l20_codigo = l21_codliclicita
    INNER JOIN pcprocitem ON pc81_codprocitem = l21_codpcprocitem
    INNER JOIN solicitem ON pc11_codigo = pc81_solicitem
    INNER JOIN solicitempcmater ON pc16_solicitem = pc11_codigo
    INNER JOIN pcmater ON pc01_codmater = pc16_codmater
    INNER JOIN solicitemunid ON pc17_codigo = pc11_codigo
    INNER JOIN matunid ON m61_codmatunid = pc17_unid
    INNER JOIN licpropostavinc ON l223_liclicita = l21_codliclicita
    INNER JOIN licproposta ON l224_codigo = l223_codigo AND l224_propitem = l21_codpcprocitem
    LEFT JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
    WHERE l21_codliclicita = {$l20_codigo}
    AND l223_fornecedor = {$l224_forne}
    AND ('{$lote}' = 'Todos' OR l04_descricao = '{$lote}')

    UNION

    SELECT DISTINCT
       l21_ordem,
       pc01_codmater,
       pc01_descrmater,
       m61_descr AS unidade,
       pc11_quant AS quantidade,
       0 AS percentual,
       0 AS vlr_unitario,
       0 AS vlr_total,
       '' AS marca,
       0 AS l224_codigo,
       pc81_codprocitem,
       l04_descricao,
       l20_objeto,
       l20_anousu,
       l20_numero,
       pc01_complmater,
       pc01_taxa,
       pc01_tabela,
       l20_criterioadjudicacao
    FROM liclicitem
    INNER JOIN liclicita ON l20_codigo = l21_codliclicita
    INNER JOIN pcprocitem ON pc81_codprocitem = l21_codpcprocitem
    INNER JOIN solicitem ON pc11_codigo = pc81_solicitem
    INNER JOIN solicitempcmater ON pc16_solicitem = pc11_codigo
    INNER JOIN pcmater ON pc01_codmater = pc16_codmater
    INNER JOIN solicitemunid ON pc17_codigo = pc11_codigo
    INNER JOIN matunid ON m61_codmatunid = pc17_unid
    LEFT JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
    WHERE l21_codliclicita = {$l20_codigo}
    AND ('{$lote}' = 'Todos' OR l04_descricao = '{$lote}')
    AND l21_codigo NOT IN (
        SELECT l21_codigo
        FROM liclicitem
        INNER JOIN licpropostavinc ON l223_liclicita = l21_codliclicita
        INNER JOIN licproposta ON l224_codigo = l223_codigo AND l224_propitem = l21_codpcprocitem
        WHERE l21_codliclicita = {$l20_codigo}
        AND l223_fornecedor = {$l224_forne}
    )
    ORDER BY l21_ordem;
";

    }

    public function getPrecoReferencia($l20_codigo){

        return"
        SELECT DISTINCT
        l21_ordem,
        case
           WHEN l20_criterioadjudicacao = 1
           AND pc01_tabela = 't'
           then si02_vlprecoreferencia
            else null end as valortabela
        FROM liclicitem
        INNER JOIN liclicitemlote on l04_liclicitem=l21_codigo
        INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
        INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
        INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
        INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
        INNER JOIN db_depart ON db_depart.coddepto = solicita.pc10_depto
        join pcorcamitemproc on pc31_pcprocitem=pc81_codprocitem
        join pcorcamitem ocitemprc on ocitemprc.pc22_orcamitem=pc31_orcamitem
        join itemprecoreferencia on si02_itemproccompra=ocitemprc.pc22_orcamitem
        LEFT JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
        LEFT JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
        LEFT JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
        LEFT JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
        LEFT JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
        LEFT JOIN pcorcamitemlic ON l21_codigo = pc26_liclicitem
        LEFT JOIN pcorcamval ON pc26_orcamitem = pc23_orcamitem
        LEFT JOIN pcorcamjulg ON pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
        AND pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
        LEFT JOIN pcorcamforne ON pc21_orcamforne = pc23_orcamforne
        LEFT JOIN cgm ON pc21_numcgm = z01_numcgm
        LEFT JOIN db_usuarios ON pcproc.pc80_usuario = db_usuarios.id_usuario
        LEFT JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
        LEFT JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
        left join pcorcamitem on pcorcamitem.pc22_orcamitem=pc26_orcamitem
        left join situacaoitemcompra on l218_pcorcamitemlic=pcorcamitem.pc22_orcamitem
        left join situacaoitemlic on l219_codigo=l218_codigo
        left join situacaoitem on l217_sequencial=l219_situacao
        WHERE l20_codigo=$l20_codigo;";
    }

}
