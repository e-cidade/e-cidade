<?
//MODULO: sicom
//CLASSE DA ENTIDADE ralic102025
class cl_ralic102025
{
  // cria variaveis de erro
  var $rotulo = null;
  var $query_sql = null;
  var $numrows = 0;
  var $numrows_incluir = 0;
  var $numrows_alterar = 0;
  var $numrows_excluir = 0;
  var $erro_status = null;
  var $erro_sql = null;
  var $erro_banco = null;
  var $erro_msg = null;
  var $erro_campo = null;
  var $pagina_retorno = null;
  // cria variaveis do arquivo
  var $si180_sequencial = 0;
  var $si180_tiporegistro = 0;
  var $si180_codorgaoresp = null;
  var $si180_codunidadesubresp = null;
  var $si180_codunidadesubrespestadual = null;
  var $si180_exerciciolicitacao = 0;
  var $si180_nroprocessolicitatorio = null;
  var $si180_tipocadastradolicitacao = null;
  var $si180_dsccadastrolicitatorio = '';
  var $si180_codmodalidadelicitacao = 0;
  var $si180_naturezaprocedimento = 0;
  var $si180_nroedital = 0;
  var $si180_exercicioedital = 0;
  var $si180_dtpublicacaoeditaldo_dia = null;
  var $si180_dtpublicacaoeditaldo_mes = null;
  var $si180_dtpublicacaoeditaldo_ano = null;
  var $si180_dtpublicacaoeditaldo = null;
  var $si180_dtaberturaenvelopes_dia = null;
  var $si180_dtaberturaenvelopes_mes = null;
  var $si180_dtaberturaenvelopes_ano = null;
  var $si180_dtaberturaenvelopes = null;
  var $si180_link = '';
  var $si180_tipolicitacao = 0;
  var $si180_naturezaobjeto = 0;
  var $si180_objeto = null;
  var $si180_regimeexecucaoobras = 0;
  var $si180_tipoorcamento = 0;
  var $si180_vlcontratacao = 0;
  var $si180_bdi = 0;
  var $si180_mesexercicioreforc = 0;
  var $si180_origemrecurso = 0;
  var $si180_dscorigemrecurso = '';
  var $si180_qtdlotes = null;
  var $si180_mes = 0;
  var $si180_instit = 0;
  var $si180_leidalicitacao = 0;
  var $si180_mododisputa = 0;
  var $si180_emailcontato = 0;

  var $campos = "
                 si180_sequencial = int8 = sequencial
                 si180_tiporegistro = int8 = Tipo do registro
                 si180_codorgaoresp = varchar(2) = C�digo do �rg�o
                 si180_codunidadesubresp = varchar(8) = C�digo da unidade
                 si180_codunidadesubrespestadual = varchar(4) = C�digo da unidade respons�vel
                 si180_exerciciolicitacao = int8 = Exerc�cio da licita��o
                 si180_nroprocessolicitatorio = varchar(12) = N�mero sequencial do processo
                 si180_tipocadastradolicitacao = int(8) = Tipo de cadastro da licita��o
                 si180_dsccadastrolicitatorio = varchar(150) = Motivo da anula��o ou revoga��o
                 si180_codmodalidadelicitacao = int8 = Modalidade da Licita��o
                 si180_naturezaprocedimento = int8 = Natureza do Procedimento
                 si180_nroedital = int8 = N�mero do edital
                 si180_exercicioedital = int8 = Exerc�cio do edital
                 si180_dtpublicacaoeditaldo = date = Data de publica��o do edital
                 si180_dtaberturaenvelopes = date = Data de publica��o do edital
                 si180_link = varchar(200) = Link da publica��o de documentos
                 si180_tipolicitacao = int8 = Tipo de licita��o
                 si180_naturezaobjeto = int8 = Natureza do objeto
                 si180_objeto = varchar(500) = Objeto da licita��o
                 si180_regimeexecucaoobras = int8 = Regime de execu��o para obras
                 si180_tipoorcamento = int8 = Regime de execu��o para obras
                 si180_vlcontratacao = real = Valor Estimado da contrata��o
                 si180_bdi = real = Percentual da Bonifica��o e Despesas
                 si180_mesexercicioreforc = int(4) = Percentual da Bonifica��o e Despesas
                 si180_origemrecurso = int(4) = Origem do recurso
                 si180_dscorigemrecurso = varchar(150) = Descri��o da origem do recurso
                 si180_qtdlotes = integer = Quantidade de lotes
                 si180_mes = int8 = M�s
                 si180_instit = int8 = Institui��o
                 si180_leidalicitacao = int8 = Lei da licita��o
                 si180_mododisputa = int8 = Modo disputa
                 si180_emailcontato = varchar(200) = email
                 ";
  // cria propriedade com as variaveis do arquivo

  // funcao construtor da classe
  function cl_ralic102025()
  {
    //classes dos rotulos dos campos
    $this->rotulo = new rotulo("ralic102025");
    $this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
  }

  // funcao erro
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
      $this->si180_codorgaoresp = ($this->si180_codorgaoresp == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_codorgaoresp"] : $this->si180_codorgaoresp);
      $this->si180_codunidadesubresp = ($this->si180_codunidadesubresp == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_codunidadesubresp"] : $this->si180_codunidadesubresp);
      $this->si180_exerciciolicitacao = ($this->si180_exerciciolicitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_exerciciolicitacao"] : $this->si180_exerciciolicitacao);
      $this->si180_nroprocessolicitatorio = ($this->si180_nroprocessolicitatorio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_nroprocessolicitatorio"] : $this->si180_nroprocessolicitatorio);
      $this->si180_tipocadastradolicitacao = ($this->si180_tipocadastradolicitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_tipocadastradolicitacao"] : $this->si180_tipocadastradolicitacao);
      $this->si180_dsccadastrolicitatorio = ($this->si180_dsccadastrolicitatorio == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_dsccadastrolicitatorio"] : $this->si180_dsccadastrolicitatorio);
      $this->si180_codmodalidadelicitacao = ($this->si180_codmodalidadelicitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_codmodalidadelicitacao"] : $this->si180_codmodalidadelicitacao);
      $this->si180_naturezaprocedimento = ($this->si180_naturezaprocedimento == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_naturezaprocedimento"] : $this->si180_naturezaprocedimento);
      $this->si180_nroedital = ($this->si180_nroedital == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_nroedital"] : $this->si180_nroedital);
      $this->si180_exercicioedital = ($this->si180_exercicioedital == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_exercicioedital"] : $this->si180_exercicioedital);

      if ($this->si180_dtpublicacaoeditaldo == "") {
        $this->si180_dtpublicacaoeditaldo_dia = ($this->si180_dtpublicacaoeditaldo_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_dtpublicacaoeditaldo_dia"] : $this->si180_dtpublicacaoeditaldo_dia);
        $this->si180_dtpublicacaoeditaldo_mes = ($this->si180_dtpublicacaoeditaldo_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_dtpublicacaoeditaldo_mes"] : $this->si180_dtpublicacaoeditaldo_mes);
        $this->si180_dtpublicacaoeditaldo_ano = ($this->si180_dtpublicacaoeditaldo_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_dtpublicacaoeditaldo_ano"] : $this->si180_dtpublicacaoeditaldo_ano);
        if ($this->si180_dtpublicacaoeditaldo_dia != "") {
          $this->si180_dtpublicacaoeditaldo = $this->si180_dtpublicacaoeditaldo_ano . "-" . $this->si180_dtpublicacaoeditaldo_mes . "-" . $this->si180_dtpublicacaoeditaldo_dia;
        }
      }
      if ($this->si180_dtaberturaenvelopes == "") {
        $this->si180_dtaberturaenvelopes_dia = ($this->si180_dtaberturaenvelopes_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_dtaberturaenvelopes_dia"] : $this->si180_dtaberturaenvelopes_dia);
        $this->si180_dtaberturaenvelopes_mes = ($this->si180_dtaberturaenvelopes_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_dtaberturaenvelopes_mes"] : $this->si180_dtaberturaenvelopes_mes);
        $this->si180_dtaberturaenvelopes_ano = ($this->si180_dtaberturaenvelopes_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_dtaberturaenvelopes_ano"] : $this->si180_dtaberturaenvelopes_ano);
        if ($this->si180_dtaberturaenvelopes_dia != "") {
          $this->si180_dtaberturaenvelopes = $this->si180_dtaberturaenvelopes_ano . "-" . $this->si180_dtaberturaenvelopes_mes . "-" . $this->si180_dtaberturaenvelopes_dia;
        }
      }

      $this->si180_link = ($this->si180_link == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_link"] : $this->si180_link);
      $this->si180_tipolicitacao = ($this->si180_tipolicitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_tipolicitacao"] : $this->si180_tipolicitacao);
      $this->si180_naturezaobjeto = ($this->si180_naturezaobjeto == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_naturezaobjeto"] : $this->si180_naturezaobjeto);
      $this->si180_objeto = ($this->si180_objeto == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_objeto"] : $this->si180_objeto);
      $this->si180_regimeexecucaoobras = ($this->si180_regimeexecucaoobras == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_regimeexecucaoobras"] : $this->si180_regimeexecucaoobras);
      $this->si180_tipoorcamento = ($this->si180_tipoorcamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_tipoorcamento"] : $this->si180_tipoorcamento);
      $this->si180_vlcontratacao = ($this->si180_vlcontratacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_vlcontratacao"] : $this->si180_vlcontratacao);
      $this->si180_bdi = ($this->si180_bdi == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_bdi"] : $this->si180_bdi);
      $this->si180_mesexercicioreforc = ($this->si180_mesexercicioreforc == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_mesexercicioreforc"] : $this->si180_mesexercicioreforc);
      $this->si180_origemrecurso = ($this->si180_origemrecurso == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_origemrecurso"] : $this->si180_origemrecurso);
      $this->si180_dscorigemrecurso = ($this->si180_dscorigemrecurso == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_dscorigemrecurso"] : $this->si180_dscorigemrecurso);
      $this->si180_mes = ($this->si180_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_mes"] : $this->si180_mes);
      $this->si180_instit = ($this->si180_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_instit"] : $this->si180_instit);
      $this->si180_leidalicitacao = ($this->si180_leidalicitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_leidalicitacao"] : $this->si180_leidalicitacao);
      $this->si180_mododisputa = ($this->si180_mododisputa == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_mododisputa"] : $this->si180_mododisputa);
      $this->si180_emailcontato = ($this->si180_emailcontato == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_emailcontato"] : $this->si180_emailcontato);

      $this->si180_qtdlotes = ($this->si180_qtdlotes == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_qtdlotes"] : $this->si180_qtdlotes);
    } else {
      $this->si180_sequencial = ($this->si180_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["si180_sequencial"] : $this->si180_sequencial);
    }
  }

  //funcao para inclusao
  function incluir($si180_sequencial)
  {
    $this->atualizacampos();
    if ($this->si180_tiporegistro == null) {
      $this->erro_sql = " Campo Tipo do registro nao Informado.";
      $this->erro_campo = "si180_tiporegistro";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si180_exerciciolicitacao == null) {
      $this->erro_sql = " Campo Exerc�cio da Licita��o n�o Informado.";
      $this->erro_campo = "si180_exerciciolicitacao";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si180_nroprocessolicitatorio == null) {
      $this->erro_sql = " Campo N�mero do Processo Licitat�rio n�o Informado.";
      $this->erro_campo = "si180_nroprocessolicitatorio";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si180_codmodalidadelicitacao == null) {
      $this->si180_codmodalidadelicitacao = "0";
    }
    if ($this->si180_naturezaprocedimento == null) {
      $this->si180_naturezaprocedimento = "0";
    }
    if ($this->si180_dtpublicacaoeditaldo == null) {
      $this->si180_dtpublicacaoeditaldo = "null";
    }
    if ($this->si180_dtaberturaenvelopes == null) {
      $this->si180_dtaberturaenvelopes = "null";
    }
    if ($this->si180_tipolicitacao == null) {
      $this->si180_tipolicitacao = "0";
    }
    if ($this->si180_naturezaobjeto == null) {
      $this->si180_naturezaobjeto = "0";
    }
    if ($this->si180_regimeexecucaoobras == null) {
      $this->si180_regimeexecucaoobras = "0";
    }
    if ($this->si180_tipoorcamento == null) {
      $this->si180_tipoorcamento = "1";
    }
    if ($this->si180_mes == null) {
      $this->erro_sql = " Campo M�s nao Informado.";
      $this->erro_campo = "si180_mes";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si180_instit == null) {
      $this->erro_sql = " Campo Institui��o nao Informado.";
      $this->erro_campo = "si180_instit";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si180_mododisputa == null) {
      $this->erro_sql = " Campo modo de disputa nao Informado.";
      $this->erro_campo = "si180_mododisputa";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    if ($this->si180_leidalicitacao == null) {
      $this->erro_sql = " Campo Lei da licita��o nao Informado.";
      $this->erro_campo = "si180_leidalicitacao";
      $this->erro_banco = "";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($si180_sequencial == "" || $si180_sequencial == null) {
      $result = db_query("select nextval('ralic102025_si180_sequencial_seq')");
      if ($result == false) {
        $this->erro_banco = str_replace("
", "", @pg_last_error());
        $this->erro_sql = "Verifique o cadastro da sequencia: ralic102025_si180_sequencial_seq do campo: si180_sequencial";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
      $this->si180_sequencial = pg_result($result, 0, 0);
    } else {
      $result = db_query("select last_value from ralic102025_si180_sequencial_seq");
      if (($result != false) && (pg_result($result, 0, 0) < $si180_sequencial)) {
        $this->erro_sql = " Campo si180_sequencial maior que �ltimo n�mero da sequencia.";
        $this->erro_banco = "Sequencia menor que este n�mero.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      } else {
        $this->si180_sequencial = $si180_sequencial;
      }
    }
    if (($this->si180_sequencial == null) || ($this->si180_sequencial == "")) {
      $this->erro_sql = " Campo si180_sequencial nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    if ($this->si180_exercicioedital == null) {
      $this->erro_sql = " Campo si180_exercicioedital nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }

    if ($this->si180_bdi == null) {
      $this->si180_bdi = 0;
    }

    if ($this->si180_vlcontratacao == null) {
      $this->si180_vlcontratacao = 0;
    }

    if ($this->si180_qtdlotes == null) {
      $this->si180_qtdlotes = 'null';
    }

    $sql = "insert into ralic102025(
                                       si180_sequencial
                                      ,si180_tiporegistro
                                      ,si180_codorgaoresp
                                      ,si180_codunidadesubresp
                                      ,si180_codunidadesubrespestadual
                                      ,si180_exerciciolicitacao
                                      ,si180_nroprocessolicitatorio
                                      ,si180_tipocadastradolicitacao
                                      ,si180_dsccadastrolicitatorio
                                      ,si180_codmodalidadelicitacao
                                      ,si180_naturezaprocedimento
                                      ,si180_nroedital
                                      ,si180_exercicioedital
                                      ,si180_dtpublicacaoeditaldo
                                      ,si180_link
                                      ,si180_tipolicitacao
                                      ,si180_naturezaobjeto
                                      ,si180_objeto
                                      ,si180_regimeexecucaoobras
                                      ,si180_vlcontratacao
                                      ,si180_bdi
                                      ,si180_mesexercicioreforc
                                      ,si180_origemrecurso
                                      ,si180_dscorigemrecurso
                                      ,si180_qtdlotes
                                      ,si180_mes
                                      ,si180_instit
                                      ,si180_leidalicitacao
                                      ,si180_mododisputa
                                      ,si180_dtaberturaenvelopes
                                      ,si180_tipoorcamento
                                      ,si180_emailcontato
                       )
                values (
                                $this->si180_sequencial
                               ,$this->si180_tiporegistro
                               ,$this->si180_codorgaoresp
                               ,'$this->si180_codunidadesubresp'
                               ,'$this->si180_codunidadesubrespestadual'
                               ,$this->si180_exerciciolicitacao
                               ,'$this->si180_nroprocessolicitatorio'
                               ,$this->si180_tipocadastradolicitacao
                               ,'$this->si180_dsccadastrolicitatorio'
                               ,$this->si180_codmodalidadelicitacao
                               ,$this->si180_naturezaprocedimento
                               ,$this->si180_nroedital
                               ,$this->si180_exercicioedital
                               ," . ($this->si180_dtpublicacaoeditaldo == "null" || $this->si180_dtpublicacaoeditaldo == "" ? "null" : "'" . $this->si180_dtpublicacaoeditaldo . "'") . "
                               ,'$this->si180_link'
                               ,$this->si180_tipolicitacao
                               ,$this->si180_naturezaobjeto
                               ,'$this->si180_objeto'
                               ,$this->si180_regimeexecucaoobras
                               ,$this->si180_vlcontratacao
                               ,$this->si180_bdi
                               ,$this->si180_mesexercicioreforc
                               ,$this->si180_origemrecurso
                               ,'$this->si180_dscorigemrecurso'
                               ,$this->si180_qtdlotes
                               ,$this->si180_mes
                               ,$this->si180_instit
                               ,$this->si180_leidalicitacao
                               ,$this->si180_mododisputa
                               ," . ($this->si180_dtaberturaenvelopes == "null" || $this->si180_dtaberturaenvelopes == "" ? "null" : "'" . $this->si180_dtaberturaenvelopes . "'") . "
                               ,$this->si180_tipoorcamento
                               ,'$this->si180_emailcontato'
                      )";

    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
        $this->erro_sql = "ralic102025 ($this->si180_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_banco = "ralic102025 j� Cadastrado";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      } else {
        $this->erro_sql = "ralic102025 ($this->si180_sequencial) nao Inclu�do. Inclusao Abortada.";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir = 0;

      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\n";
    $this->erro_sql .= "Valores : " . $this->si180_sequencial;
    $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
    $this->erro_status = "1";
    $this->numrows_incluir = pg_affected_rows($result);
    $resaco = $this->sql_record($this->sql_query_file($this->si180_sequencial));

    return true;
  }

  // funcao para alteracao
  function alterar($si180_sequencial = null)
  {
    $this->atualizacampos();
    $sql = " update ralic102025 set ";
    $virgula = "";
    if (trim($this->si180_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_sequencial"])) {
      if (trim($this->si180_sequencial) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si180_sequencial"])) {
        $this->si180_sequencial = "0";
      }
      $sql .= $virgula . " si180_sequencial = $this->si180_sequencial ";
      $virgula = ",";
    }
    if (trim($this->si180_tiporegistro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_tiporegistro"])) {
      $sql .= $virgula . " si180_tiporegistro = $this->si180_tiporegistro ";
      $virgula = ",";
      if (trim($this->si180_tiporegistro) == null) {
        $this->erro_sql = " Campo Tipo do registro nao Informado.";
        $this->erro_campo = "si180_tiporegistro";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si180_codorgaoresp) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_codorgaoresp"])) {
      $sql .= $virgula . " si180_codorgaoresp = $this->si180_codorgaoresp ";
      $virgula = ",";
    }
    if (trim($this->si180_codunidadesubresp) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_codunidadesubresp"])) {
      $sql .= $virgula . " si180_codunidadesubresp = '$this->si180_codunidadesubresp' ";
      $virgula = ",";
    }
    if (trim($this->si180_exerciciolicitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_exerciciolicitacao"])) {
      if (trim($this->si180_exerciciolicitacao) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si180_exerciciolicitacao"])) {
        $this->si180_exerciciolicitacao = "0";
      }
      $sql .= $virgula . " si180_exerciciolicitacao = $this->si180_exerciciolicitacao ";
      $virgula = ",";
    }
    if (trim($this->si180_nroprocessolicitatorio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_nroprocessolicitatorio"])) {
      $sql .= $virgula . " si180_nroprocessolicitatorio = '$this->si180_nroprocessolicitatorio' ";
      $virgula = ",";
    }
    if (trim($this->si180_codmodalidadelicitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_codmodalidadelicitacao"])) {
      if (trim($this->si180_codmodalidadelicitacao) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si180_codmodalidadelicitacao"])) {
        $this->si180_codmodalidadelicitacao = "0";
      }
      $sql .= $virgula . " si180_codmodalidadelicitacao = $this->si180_codmodalidadelicitacao ";
      $virgula = ",";
    }

    if (trim($this->si180_naturezaprocedimento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_naturezaprocedimento"])) {
      if (trim($this->si180_naturezaprocedimento) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si180_naturezaprocedimento"])) {
        $this->si180_naturezaprocedimento = "0";
      }
      $sql .= $virgula . " si180_naturezaprocedimento = $this->si180_naturezaprocedimento ";
      $virgula = ",";
    }

    if (trim($this->si180_dtpublicacaoeditaldo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_dtpublicacaoeditaldo_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["si180_dtpublicacaoeditaldo_dia"] != "")) {
      $sql .= $virgula . " si180_dtpublicacaoeditaldo = '$this->si180_dtpublicacaoeditaldo' ";
      $virgula = ",";
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["si180_dtpublicacaoeditaldo_dia"])) {
        $sql .= $virgula . " si180_dtpublicacaoeditaldo = null ";
        $virgula = ",";
      }
    }
    if (trim($this->si180_dtaberturaenvelopes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_dtaberturaenvelopes_dia"]) && ($GLOBALS["HTTP_POST_VARS"]["si180_dtaberturaenvelopes_dia"] != "")) {
      $sql .= $virgula . " si180_dtaberturaenvelopes = '$this->si180_dtaberturaenvelopes' ";
      $virgula = ",";
    } else {
      if (isset($GLOBALS["HTTP_POST_VARS"]["si180_dtaberturaenvelopes_dia"])) {
        $sql .= $virgula . " si180_dtaberturaenvelopes = null ";
        $virgula = ",";
      }
    }
    if (trim($this->si180_tipolicitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_tipolicitacao"])) {
      if (trim($this->si180_tipolicitacao) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si180_tipolicitacao"])) {
        $this->si180_tipolicitacao = "0";
      }
      $sql .= $virgula . " si180_tipolicitacao = $this->si180_tipolicitacao ";
      $virgula = ",";
    }
    if (trim($this->si180_naturezaobjeto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_naturezaobjeto"])) {
      if (trim($this->si180_naturezaobjeto) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si180_naturezaobjeto"])) {
        $this->si180_naturezaobjeto = "0";
      }
      $sql .= $virgula . " si180_naturezaobjeto = $this->si180_naturezaobjeto ";
      $virgula = ",";
    }
    // echo 'Valor da contratacao: '.$this->si180_objeto;
    if (trim($this->si180_objeto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_objeto"])) {
      $sql .= $virgula . " si180_objeto = '$this->si180_objeto' ";
      $virgula = ",";
    }
    if (trim($this->si180_regimeexecucaoobras) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_regimeexecucaoobras"])) {
      if (trim($this->si180_regimeexecucaoobras) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si180_regimeexecucaoobras"])) {
        $this->si180_regimeexecucaoobras = "0";
      }
      $sql .= $virgula . " si180_regimeexecucaoobras = $this->si180_regimeexecucaoobras ";
      $virgula = ",";
    }
    if (trim($this->si180_tipoorcamento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_tipoorcamento"])) {
      if (trim($this->si180_tipoorcamento) == "" && isset($GLOBALS["HTTP_POST_VARS"]["si180_tipoorcamento"])) {
        $this->si180_tipoorcamento = "0";
      }
      $sql .= $virgula . " si180_tipoorcamento = $this->si180_tipoorcamento ";
      $virgula = ",";
    }

    if (trim($this->si180_mes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_mes"])) {
      $sql .= $virgula . " si180_mes = $this->si180_mes ";
      $virgula = ",";
      if (trim($this->si180_mes) == null) {
        $this->erro_sql = " Campo M�s nao Informado.";
        $this->erro_campo = "si180_mes";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }
    if (trim($this->si180_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_instit"])) {
      $sql .= $virgula . " si180_instit = $this->si180_instit ";
      $virgula = ",";
      if (trim($this->si180_instit) == null) {
        $this->erro_sql = " Campo Institui��o nao Informado.";
        $this->erro_campo = "si180_instit";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }

    if (trim($this->si180_mododisputa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_mododisputa"])) {
      $sql .= $virgula . " si180_mododisputa = $this->si180_mododisputa ";
      $virgula = ",";
      if (trim($this->si180_mododisputa) == null) {
        $this->erro_sql = " Campo modo disputa nao Informado.";
        $this->erro_campo = "si180_mododisputa";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }

    if (trim($this->si180_leidalicitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_leidalicitacao"])) {
      $sql .= $virgula . " si180_leidalicitacao = $this->si180_leidalicitacao ";
      $virgula = ",";
      if (trim($this->si180_leidalicitacao) == null) {
        $this->erro_sql = " Campo Lei da licita��o nao Informado.";
        $this->erro_campo = "si180_leidalicitacao";
        $this->erro_banco = "";
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "0";

        return false;
      }
    }

    if (trim($this->si180_qtdlotes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["si180_qtdlotes"])) {
      $sql .= $virgula . " si180_qtdlotes = '$this->si180_qtdlotes' ";
    }

    $sql .= " where ";
    if ($si180_sequencial != null) {
      $sql .= " si180_sequencial = $this->si180_sequencial";
    }

    $result = db_query($sql);
    if ($result == false) {
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "ralic102025 nao Alterado. Alteracao Abortada.\n";
      $this->erro_sql .= "Valores : " . $this->si180_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_alterar = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "ralic102025 nao foi Alterado. Alteracao Executada.\n";
        $this->erro_sql .= "Valores : " . $this->si180_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Altera��o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $this->si180_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_alterar = pg_affected_rows($result);

        return true;
      }
    }
  }

  // funcao para exclusao
  function excluir($si180_sequencial = null, $dbwhere = null)
  {
    if ($dbwhere == null || $dbwhere == "") {
      $resaco = $this->sql_record($this->sql_query_file($si180_sequencial));
    } else {
      $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
    }
    $sql = " delete from ralic102025
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
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "ralic102025 nao Exclu�do. Exclus�o Abortada.\n";
      $this->erro_sql .= "Valores : " . $si180_sequencial;
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";
      $this->numrows_excluir = 0;

      return false;
    } else {
      if (pg_affected_rows($result) == 0) {
        $this->erro_banco = "";
        $this->erro_sql = "ralic102025 nao Encontrado. Exclus�o n�o Efetuada.\n";
        $this->erro_sql .= "Valores : " . $si180_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
        $this->erro_status = "1";
        $this->numrows_excluir = 0;

        return true;
      } else {
        $this->erro_banco = "";
        $this->erro_sql = "Exclus�o efetuada com Sucesso\n";
        $this->erro_sql .= "Valores : " . $si180_sequencial;
        $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
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
      $this->numrows = 0;
      $this->erro_banco = str_replace("
", "", @pg_last_error());
      $this->erro_sql = "Erro ao selecionar os registros.";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
      $this->erro_status = "0";

      return false;
    }
    $this->numrows = pg_numrows($result);
    if ($this->numrows == 0) {
      $this->erro_banco = "";
      $this->erro_sql = "Record Vazio na Tabela:ralic102025";
      $this->erro_msg = "Usu�rio: \n\n " . $this->erro_sql . " \n\n";
      $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \n\n " . $this->erro_banco . " \n"));
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
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        $sql .= $virgula . $campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from ralic102025 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si180_sequencial != null) {
        $sql2 .= " where ralic102025.si180_sequencial = $si180_sequencial ";
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
  function sql_query_file($si180_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
    $sql .= " from ralic102025 ";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($si180_sequencial != null) {
        $sql2 .= " where ralic102025.si180_sequencial = $si180_sequencial ";
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
