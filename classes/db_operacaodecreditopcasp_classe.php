<?
class cl_db_operacaodecreditopcasp
{
    var $erro_sql    = null;
    var $erro_banco  = null;
    var $erro_msg    = null;
    var $erro_status = null;
    var $erro_campo  = null;
    var $numrows     = 0;
    var $pagina_retorno = null;

    var $dv01_sequencial = 0;
    var $dv01_codoperacaocredito = 'NULL';
    var $dv01_principaldividalongoprazop = 'NULL';
    var $dv01_principaldividacurtoprazop = 'NULL';
    var $dv01_principaldividaf = 'NULL';
    var $dv01_jurosdividalongoprazop = 'NULL';
    var $dv01_jurosdividacurtoprazop = 'NULL';
    var $dv01_jurosdividaf = 'NULL';
    var $dv01_reduzido = 'NULL';
    var $dv01_anousu = 'NULL';
    var $dv01_controlelrf = 'NULL';

    var $campos = "
                    dv01_codoperacaocredito = int8 =  
                    dv01_principaldividalongoprazop = int8 =  
                    dv01_principaldividacurtoprazop = int8 =
                    dv01_principaldividaf = int8 = 
                    dv01_jurosdividalongoprazop = int8 = 
                    dv01_jurosdividaf = int8 = 
                    dv01_reduzido = int8 = 
                    dv01_anousu = int8 =
                    dv01_controlelrf = int8 =
                ";

    function codeAlreadyExists($dv01_codoperacaocredito) {

      $this->dv01_codoperacaocredito =  $dv01_codoperacaocredito;

      $sqlString = "SELECT dv01_sequencial FROM contabilidade.dv_dividaconsolidadapcasp WHERE dv01_codoperacaocredito = $dv01_codoperacaocredito";
      $result    = db_query($sqlString);
      $rows      = pg_num_rows($result);

      if($rows > 0) {
        return $rows;
      }

      return null;
    }

    function incluir($dv01_codoperacaocredito)
    {
        $this->atualizacampos();

        $sqlString = "INSERT INTO contabilidade.dv_dividaconsolidadapcasp (
          dv01_codoperacaocredito, 
          dv01_principaldividalongoprazop, 
          dv01_principaldividacurtoprazop,
          dv01_principaldividaf, 
          dv01_jurosdividalongoprazop,
          dv01_jurosdividacurtoprazop,
          dv01_jurosdividaf,
          dv01_reduzido, 
          dv01_anousu, 
          dv01_controlelrf
        ) VALUES (
          $this->dv01_codoperacaocredito, 
          $this->dv01_principaldividalongoprazop, 
          $this->dv01_principaldividacurtoprazop,
          $this->dv01_principaldividaf, 
          $this->dv01_jurosdividalongoprazop,
          $this->dv01_jurosdividacurtoprazop,
          $this->dv01_jurosdividaf,
          $this->dv01_reduzido,
          $this->dv01_anousu, 
          $this->dv01_controlelrf
        )";

        if(empty($this->dv01_codoperacaocredito)) {

          $this->erro_banco = "";
          $this->erro_sql = "Para vincular as contas Pcasp é necessário salvar a Dívida Consolidada.\\n";
          // $this->erro_sql .= "Valores : " . $this->dv01_sequencial;
          $this->erro_msg   = $this->erro_sql . " \\n\\n";
          // $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "0";
          return false;
        }

        $result = db_query($sqlString);

        if ($result == false) {
          $this->erro_banco = str_replace("\n", "", @pg_last_error());
          if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
            $this->erro_sql   = " ($this->dv01_sequencial) nao Incluído. Inclusao Abortada.";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_banco = " já Cadastrado";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          } else {
            $this->erro_sql   = " ($this->dv01_sequencial) nao Incluído. Inclusao Abortada.";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          }
          $this->erro_status = "0";
          return false;
        }

        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->dv01_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        
        return true;
    }

    function alterar($dv01_codoperacaocredito = null)
    {
        $this->atualizacampos();

        $sql = " UPDATE contabilidade.dv_dividaconsolidadapcasp SET ";
        $virgula = "";

        $sql  .= $virgula . " dv01_principaldividalongoprazop = $this->dv01_principaldividalongoprazop ";
        $virgula = ",";

        $sql  .= $virgula . " dv01_principaldividacurtoprazop = $this->dv01_principaldividacurtoprazop ";
        $virgula = ",";

        $sql  .= $virgula . " dv01_principaldividaf = $this->dv01_principaldividaf ";
        $virgula = ",";

        $sql  .= $virgula . " dv01_jurosdividalongoprazop = $this->dv01_jurosdividalongoprazop ";
        $virgula = ",";

        $sql  .= $virgula . " dv01_jurosdividacurtoprazop = $this->dv01_jurosdividacurtoprazop ";
        $virgula = ",";

        $sql  .= $virgula . " dv01_jurosdividaf = $this->dv01_jurosdividaf ";
        $virgula = ",";

        $sql  .= $virgula . " dv01_reduzido = $this->dv01_reduzido ";
        $virgula = ",";

        $sql  .= $virgula . " dv01_controlelrf = $this->dv01_controlelrf ";
        $virgula = ",";

        $sql .= " where  dv01_codoperacaocredito = $this->dv01_codoperacaocredito ;";

        $result = db_query($sql);

        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->dv01_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->dv01_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->dv01_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                return true;
            }
        }
    }

    function excluir() {

      $this->atualizacampos();

      $sql = " DELETE FROM contabilidade.dv_dividaconsolidadapcasp WHERE dv01_codoperacaocredito = {$this->dv01_codoperacaocredito}";
      $result = db_query($sql);

      if ($result == false) {
        $this->erro_banco = str_replace("\n", "", @pg_last_error());
        $this->erro_sql   = " Nao Excluído. Exclusão Abortada.\\n";
        $this->erro_sql .= "Valores : " . $this->dv01_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      } else {

        if (pg_affected_rows($result) == 0) {
          $this->erro_banco = "";
          $this->erro_sql = " Nenhum registro encontrado. Exclusão não Efetuada.\\n";
          $this->erro_sql .= "Valores : " . $this->dv01_sequencial;
          $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "1";
          return true;
        } else {
          $this->erro_banco = "";
          $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
          $this->erro_sql .= "Valores : " . $this->dv01_sequencial;
          $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
          $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
          $this->erro_status = "1";
          return true;
        }
      }
    }

    function atualizacampos($exclusao = false)
    {

      if ($exclusao == false) {

        $this->dv01_sequencial = ($this->dv01_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["dv01_sequencial"] : $this->dv01_sequencial);
        $this->dv01_codoperacaocredito = (!empty($GLOBALS["HTTP_POST_VARS"]["dv01_codoperacaocredito"]) ? $GLOBALS["HTTP_POST_VARS"]["dv01_codoperacaocredito"] : $this->dv01_codoperacaocredito);
        $this->dv01_principaldividalongoprazop = (!empty($GLOBALS["HTTP_POST_VARS"]["dv01_principaldividalongoprazop"]) ? $GLOBALS["HTTP_POST_VARS"]["dv01_principaldividalongoprazop"] : $this->dv01_principaldividalongoprazop);
        $this->dv01_principaldividacurtoprazop = (!empty($GLOBALS["HTTP_POST_VARS"]["dv01_principaldividacurtoprazop"]) ? $GLOBALS["HTTP_POST_VARS"]["dv01_principaldividacurtoprazop"] : $this->dv01_principaldividacurtoprazop);
        $this->dv01_principaldividaf = (!empty($GLOBALS["HTTP_POST_VARS"]["dv01_principaldividaf"]) ? $GLOBALS["HTTP_POST_VARS"]["dv01_principaldividaf"] : $this->dv01_principaldividaf);
        $this->dv01_jurosdividacurtoprazop = (!empty($GLOBALS["HTTP_POST_VARS"]["dv01_jurosdividacurtoprazop"]) ? $GLOBALS["HTTP_POST_VARS"]["dv01_jurosdividacurtoprazop"] : $this->dv01_jurosdividacurtoprazop);
        $this->dv01_jurosdividalongoprazop = (!empty($GLOBALS["HTTP_POST_VARS"]["dv01_jurosdividalongoprazop"]) ? $GLOBALS["HTTP_POST_VARS"]["dv01_jurosdividalongoprazop"] : $this->dv01_jurosdividalongoprazop);
        $this->dv01_jurosdividaf = (!empty($GLOBALS["HTTP_POST_VARS"]["dv01_jurosdividaf"]) ? $GLOBALS["HTTP_POST_VARS"]["dv01_jurosdividaf"] : $this->dv01_jurosdividaf);
        $this->dv01_reduzido = (!empty($GLOBALS["HTTP_POST_VARS"]["dv01_reduzido"]) ? $GLOBALS["HTTP_POST_VARS"]["dv01_reduzido"] : $this->dv01_reduzido);
        $this->dv01_anousu = (!empty($GLOBALS["HTTP_POST_VARS"]["dv01_anousu"]) ? $GLOBALS["HTTP_POST_VARS"]["dv01_anousu"] : $this->dv01_anousu);
        $this->dv01_controlelrf = (!empty($GLOBALS["HTTP_POST_VARS"]["dv01_controlelrf"]) ? $GLOBALS["HTTP_POST_VARS"]["dv01_controlelrf"] : $this->dv01_controlelrf);
  
      } else {
        $this->dv01_sequencial = ($this->dv01_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["dv01_sequencial"] : $this->dv01_sequencial);
      }
    }

    function erro($mostra, $retorna)
    {
      if (($this->erro_status == "0") || ($mostra == true && $this->erro_status != null)) {
        echo "<script>alert(\"" . $this->erro_msg . "\");</script>";
        if ($retorna == true) {
          echo "<script>location.href='" . $this->pagina_retorno . "'</script>";
        }
      }
    }

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
        $this->erro_sql   = "Dados do Grupo nao Encontrado";
        $this->erro_msg   = "Usuário: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "0";
        return false;
      }
      return $result;
    }

    function sql_query($dv01_codoperacaocredito = null, $campos = "*", $ordem = null, $dbwhere = "")
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
      $sql  .= " from contabilidade.dv_dividaconsolidadapcasp ";
      $sql  .= " left join contabilidade.conplanoreduz cpr on (dv01_principaldividalongoprazop,dv01_anousu, ".db_getsession("DB_instit").") = (cpr.c61_reduz,cpr.c61_anousu, cpr.c61_instit) ";
      $sql  .= " left join contabilidade.conplano cp on (cp.c60_codcon,cp.c60_anousu) = (cpr.c61_codcon,cpr.c61_anousu) ";

      $sql  .= " left join contabilidade.conplanoreduz cpr2 on (dv01_principaldividacurtoprazop,dv01_anousu, ".db_getsession("DB_instit").") = (cpr2.c61_reduz,cpr2.c61_anousu, cpr2.c61_instit) ";
      $sql  .= " left join contabilidade.conplano cp2 on (cp2.c60_codcon,cp2.c60_anousu) = (cpr2.c61_codcon,cpr2.c61_anousu) ";

      $sql  .= " left join contabilidade.conplanoreduz cpr3 on (dv01_principaldividaf,dv01_anousu, ".db_getsession("DB_instit").") = (cpr3.c61_reduz,cpr3.c61_anousu, cpr3.c61_instit) ";
      $sql  .= " left join contabilidade.conplano cp3 on (cp3.c60_codcon,cp3.c60_anousu) = (cpr3.c61_codcon,cpr3.c61_anousu) ";

      $sql  .= " left join contabilidade.conplanoreduz cpr4 on (dv01_jurosdividalongoprazop,dv01_anousu, ".db_getsession("DB_instit").") = (cpr4.c61_reduz,cpr4.c61_anousu, cpr4.c61_instit) ";
      $sql  .= " left join contabilidade.conplano cp4 on (cp4.c60_codcon,cp4.c60_anousu) = (cpr4.c61_codcon,cpr4.c61_anousu) ";

      $sql  .= " left join contabilidade.conplanoreduz cpr5 on (dv01_jurosdividaf,dv01_anousu, ".db_getsession("DB_instit").") = (cpr5.c61_reduz,cpr5.c61_anousu, cpr5.c61_instit) ";
      $sql  .= " left join contabilidade.conplano cp5 on (cp5.c60_codcon,cp5.c60_anousu) = (cpr5.c61_codcon,cpr5.c61_anousu) ";

      $sql  .= " left join contabilidade.conplanoreduz cpr6 on (dv01_jurosdividacurtoprazop,dv01_anousu, ".db_getsession("DB_instit").") = (cpr6.c61_reduz,cpr6.c61_anousu, cpr6.c61_instit) ";
      $sql  .= " left join contabilidade.conplano cp6 on (cp6.c60_codcon,cp6.c60_anousu) = (cpr6.c61_codcon,cpr6.c61_anousu) ";

      $sql  .= " left join contabilidade.conplanoreduz cpr7 on (dv01_controlelrf,dv01_anousu, ".db_getsession("DB_instit").") = (cpr7.c61_reduz,cpr7.c61_anousu, cpr7.c61_instit) ";
      $sql  .= " left join contabilidade.conplano cp7 on (cp7.c60_codcon,cp7.c60_anousu) = (cpr7.c61_codcon,cpr7.c61_anousu) ";
      

      if ($dbwhere == "") {
        if ($dv01_codoperacaocredito != null) {
          $sql2 .= " where dv_dividaconsolidadapcasp.dv01_codoperacaocredito = $dv01_codoperacaocredito ";
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

