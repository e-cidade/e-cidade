<?

//MODULO: pessoal
//CLASSE DA ENTIDADE planosaude
class cl_planosaude
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
    var $r75_sequencial = 0;
    var $r75_anousu = 0;
    var $r75_mesusu = 0;
    var $r75_regist = 0;
    var $r75_cnpj   = 0;
    var $r75_ans    = 0;
    var $r75_dependente = null;
    var $r75_numcgm = null;
    var $r75_nomedependente = null;
    var $r75_valor  = 0;
    var $r75_instit = 0;
    // cria propriedade com as variaveis do arquivo 
    var $campos = "
                 r75_sequencial = int4 = Sequencial
                 r75_anousu = int4 = Ano 
                 r75_mesusu = int4 = Mês 
                 r75_regist = int4 = Código do Servidor 
                 r75_cnpj   = varchar(14) = CNPJ da Operadora
                 r75_ans    = int4 = Registro ANS
                 r75_dependente = bool = Possui Dependente
                 r75_numcgm = int4 = Numero CGM
                 r75_nomedependente = character varying(100) = Nome do Dependente
                 r75_valor  = float8 = Valor
                 r75_instit = int4 = Instituição
                 ";
    //funcao construtor da classe 
    function cl_planosaude()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("planosaude");
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
            $this->r75_sequencial = ($this->r75_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_sequencial"] : $this->r75_sequencial);
            $this->r75_anousu = ($this->r75_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_anousu"] : $this->r75_anousu);
            $this->r75_mesusu = ($this->r75_mesusu == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_mesusu"] : $this->r75_mesusu);
            $this->r75_regist = ($this->r75_regist == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_regist"] : $this->r75_regist);
            $this->r75_cnpj = ($this->r75_cnpj == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_cnpj"] : $this->r75_cnpj);
            $this->r75_ans = ($this->r75_ans == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_ans"] : $this->r75_ans);
            $this->r75_dependente = ($this->r75_dependente == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_dependente"] : $this->r75_dependente);
            $this->r75_numcgm = ($this->r75_numcgm == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_numcgm"] : $this->r75_numcgm);
            $this->r75_nomedependente = ($this->r75_nomedependente == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_nomedependente"] : $this->r75_nomedependente);
            $this->r75_valor = ($this->r75_valor == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_valor"] : $this->r75_valor);
            $this->r75_instit = ($this->r75_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_instit"] : $this->r75_instit);
        } else {
            $this->r75_sequencial = ($this->r75_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_sequencial"] : $this->r75_sequencial);
            $this->r75_anousu = ($this->r75_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_anousu"] : $this->r75_anousu);
            $this->r75_mesusu = ($this->r75_mesusu == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_mesusu"] : $this->r75_mesusu);
            $this->r75_regist = ($this->r75_regist == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_regist"] : $this->r75_regist);
            $this->r75_cnpj = ($this->r75_cnpj == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_cnpj"] : $this->r75_cnpj);
            $this->r75_ans = ($this->r75_ans == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_ans"] : $this->r75_ans);
            $this->r75_dependente = ($this->r75_dependente == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_dependente"] : $this->r75_dependente);
            $this->r75_numcgm = ($this->r75_numcgm == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_numcgm"] : $this->r75_numcgm);
            $this->r75_nomedependente = ($this->r75_nomedependente == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_nomedependente"] : $this->r75_nomedependente);
            $this->r75_valor = ($this->r75_valor == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_valor"] : $this->r75_valor);
            $this->r75_instit = ($this->r75_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["r75_instit"] : $this->r75_instit);
        }
    }
    // funcao para inclusao
    function incluir($r75_sequencial)
    {

        $this->atualizacampos();

        if ($this->r75_anousu == null) {
            $this->erro_sql = " Campo Ano não informado.";
            $this->erro_campo = "r75_anousu";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->r75_mesusu == null) {
            $this->erro_sql = " Campo Mês não informado.";
            $this->erro_campo = "r75_mesusu";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->r75_regist == null) {
            $this->erro_sql = " Campo Código do Servidor não informado.";
            $this->erro_campo = "r75_regist";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->r75_cnpj == null) {
            $this->erro_sql = " Campo CNPJ não informado.";
            $this->erro_campo = "r75_cnpj";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->r75_ans == null) {
            $this->erro_sql = " Campo Registro ANS não informado.";
            $this->erro_campo = "r75_ans";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->r75_dependente == null) {
            $this->erro_sql = " Campo Dependente não informado.";
            $this->erro_campo = "r75_dependente";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->r75_dependente == "t" && ($this->r75_numcgm == null || $this->r75_numcgm == "")) {
            $this->erro_sql = " Campo CGM não informado.";
            $this->erro_campo = "r75_numcgm";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->r75_dependente == 't' && $this->r75_nomedependente == null) {
            $this->erro_sql = " Campo Nome do Dependente não Informado.";
            $this->erro_campo = "r75_nomedependente";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->r75_dependente == "f" && ($this->r75_numcgm == null || $this->r75_numcgm == "")) {
            $this->r75_numcgm == "null";
        }
        if ($this->r75_dependente == "f" && ($this->r75_nomedependente == null || $this->r75_nomedependente == "")) {
            $this->r75_nomedependente == "null";
        }
        if ($this->r75_valor == null) {
            $this->erro_sql = " Campo Valor não informado.";
            $this->erro_campo = "r75_valor";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->r75_instit == null) {
            $this->erro_sql = " Campo codigo da instituicao não Informado.";
            $this->erro_campo = "r75_instit";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($r75_sequencial == "" || $r75_sequencial == null) {
            $result = db_query("select nextval('planosaude_r75_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: planosaude_r75_sequencial_seq do campo: r75_sequencial";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->r75_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from planosaude_r75_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $r75_sequencial)) {
                $this->erro_sql = " Campo r75_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->r75_sequencial = $r75_sequencial;
            }
        }
        if (($this->r75_sequencial == null) || ($this->r75_sequencial == "")) {
            $this->erro_sql = " Campo r75_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $sql = "insert into planosaude(
                                       r75_sequencial
                                      ,r75_anousu 
                                      ,r75_mesusu 
                                      ,r75_regist
                                      ,r75_cnpj
                                      ,r75_ans
                                      ,r75_dependente
                                      ,r75_numcgm
                                      ,r75_nomedependente 
                                      ,r75_valor
                                      ,r75_instit 
                       )
                values (
                                $this->r75_sequencial
                               ,$this->r75_anousu
                               ,$this->r75_mesusu
                               ,$this->r75_regist
                               ,$this->r75_cnpj
                               ,'$this->r75_ans'
                               ,'$this->r75_dependente'
                               ,".($this->r75_numcgm == "null" || $this->r75_numcgm == ""?"null":$this->r75_numcgm)."
                               ,'$this->r75_nomedependente'
                               ,$this->r75_valor
                               ,$this->r75_instit
                      )";
                      
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "Plano de Saude ($this->r75_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Plano de Saude já Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "Plano de Saude ($this->r75_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->r75_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);

        return true;
    }
    // funcao para alteracao
    function alterar($r75_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update planosaude set ";
        $virgula = "";
        if (trim($this->r75_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_sequencial"])) {
            $sql  .= $virgula . " r75_sequencial = $this->r75_sequencial ";
            $virgula = ",";
            if (trim($this->r75_sequencial) == null || trim($this->r75_sequencial) == 0) {
                $this->erro_sql = " Campo Sequencial nao Informado.";
                $this->erro_campo = "r75_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->r75_anousu) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_anousu"])) {
            $sql  .= $virgula . " r75_anousu = $this->r75_anousu ";
            $virgula = ",";
            if (trim($this->r75_anousu) == null) {
                $this->erro_sql = " Campo Ano não informado.";
                $this->erro_campo = "r75_anousu";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->r75_mesusu) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_mesusu"])) {
            $sql  .= $virgula . " r75_mesusu = $this->r75_mesusu ";
            $virgula = ",";
            if (trim($this->r75_mesusu) == null) {
                $this->erro_sql = " Campo Mês não informado.";
                $this->erro_campo = "r75_mesusu";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->r75_regist) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_regist"])) {
            $sql  .= $virgula . " r75_regist = $this->r75_regist ";
            $virgula = ",";
            if (trim($this->r75_regist) == null) {
                $this->erro_sql = " Campo Código do Servidor não informado.";
                $this->erro_campo = "r75_regist";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->r75_cnpj) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_cnpj"])) {
            $sql  .= $virgula . " r75_cnpj = $this->r75_cnpj ";
            $virgula = ",";
            if (trim($this->r75_cnpj) == null) {
                $this->erro_sql = " Campo CNPJ não informado.";
                $this->erro_campo = "r75_cnpj";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->r75_ans) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_ans"])) {
            $sql  .= $virgula . " r75_ans = '$this->r75_ans' ";
            $virgula = ",";
            if (trim($this->r75_ans) == null) {
                $this->erro_sql = " Campo Registro ANS não informado.";
                $this->erro_campo = "r75_ans";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->r75_dependente) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_dependente"])) {
            $sql  .= $virgula . " r75_dependente = '$this->r75_dependente' ";
            $virgula = ",";
            if (trim($this->r75_dependente) == null) {
                $this->erro_sql = " Campo dependente não informado.";
                $this->erro_campo = "r75_dependente";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->r75_numcgm) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_numcgm"])) {
            $sql  .= $virgula . " r75_numcgm = $this->r75_numcgm ";
            $virgula = ",";
            if (trim($this->r75_numcgm) == null) {
                $this->erro_sql = " Campo CGM não informado.";
                $this->erro_campo = "r75_numcgm";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->r75_nomedependente) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_nomedependente"])) {
            $sql  .= $virgula . " r75_nomedependente = '$this->r75_nomedependente' ";
            $virgula = ",";
            if (trim($this->r75_nomedependente) == null) {
                $this->erro_sql = " Campo Nome do dependente nao Informado.";
                $this->erro_campo = "r75_nomedependente";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->r75_valor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_valor"])) {
            $sql  .= $virgula . " r75_valor = $this->r75_valor ";
            $virgula = ",";
            if (trim($this->r75_valor) == null) {
                $this->erro_sql = " Campo Valor nao Informado.";
                $this->erro_campo = "r75_valor";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->r75_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["r75_instit"])) {
            $sql  .= $virgula . " r75_instit = $this->r75_instit ";
            $virgula = ",";
            if (trim($this->r75_instit) == null) {
                $this->erro_sql = " Campo codigo da instituicao não Informado.";
                $this->erro_campo = "r75_instit";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        $sql .= " where ";
        if ($r75_sequencial != null) {
            $sql .= " r75_sequencial = $this->r75_sequencial";
        }
        $result = db_query($sql);
        if (!$result) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Plano de Saude nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->r75_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Plano de Saude nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->r75_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->r75_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao 
    function excluir($r75_sequencial = null, $dbwhere = null)
    {
        $sql = " delete from planosaude
                       where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($r75_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " r75_sequencial = $r75_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Cadastro do Plano de Saude nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $r75_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Cadastro do Plano de Saude nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $r75_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $r75_sequencial;
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
            $this->numrows = 0;
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Erro ao selecionar os registros.";
            $this->erro_msg = "Usurio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_num_rows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Record Vazio na Tabela:planosaude";
            $this->erro_msg = "Usurio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    function sql_query($r75_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from planosaude ";
        $sql .= "      inner join rhpessoal  on  rh01_regist    = r75_regist" ;
        $sql .= "      inner join cgm        on  z01_numcgm     = rh01_numcgm";
        $sql .= "      inner join db_config  on  codigo         = rh01_instit";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($r75_sequencial != null) {
                $sql2 .= " where planosaude.r75_sequencial = $r75_sequencial ";
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
    function sql_query_file($r75_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from planosaude ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($r75_sequencial != null) {
                $sql2 .= " where planosaude.r75_sequencial = $r75_sequencial ";
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
    function sql_query_dados($r75_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from planosaude ";
        $sql .= "      inner join rhpessoal  on  rh01_regist    = r75_regist" ;
        $sql .= "      inner join cgm        on  z01_numcgm     = rh01_numcgm";
        $sql .= "      inner join db_config  on  codigo         = r75_instit" ;
        $sql2 = "";
        if ($dbwhere == "") {
            if ($r75_sequencial != null) {
                $sql2 .= " where planosaude.r75_sequencial = $r75_sequencial ";
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
