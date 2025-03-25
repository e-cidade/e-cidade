<?

class cl_agentesnocivos
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
    var $rh232_sequencial = 0;
    var $rh232_regist = 0;
    var $rh232_agente = null;
    var $rh232_tipoavaliacao = null;
    var $rh232_icdexposicao = null;
    var $rh232_ltolerancia = null;
    var $rh232_unidade = null;
    var $rh232_tecnicamed = null;
    var $rh232_epc = 0;
    var $rh232_epceficaz = null;
    var $rh232_epi = 0;
    var $rh232_epieficaz = null;
    var $rh232_epicertificado = null;
    var $rh232_epidescricao = null;
    var $rh232_epiporinviabilidade = null;
    var $rh232_epiobscondicoes = null;
    var $rh232_epiobsuso = null;
    var $rh232_epiobsprazo = null;
    var $rh232_obsperiodicidade = null;
    var $rh232_obshigienizacao = null;
    // cria propriedade com as variaveis do arquivo 
    var $campos = "
                 rh232_sequencial = int4 = Sequencial da Tabela
                 rh232_regist = int4 = Matricula do Servidor
                 rh232_agente = varchar(16) = Agente Nocivo
                 rh232_tipoavaliacao = char = Tipo de Avaliação
                 rh232_icdexposicao = numeric = Intensidade, Concentração ou Dose da Exposição
                 rh232_ltolerancia = numeric = Limite de Tolerância
                 rh232_unidade = int4 = Unidade de Medida
                 rh232_tecnicamed = text = Técnica de Medição
                 rh232_epc = int4 = Utiliza Equipamentos de Proteção Coletiva
                 rh232_epceficaz = bool = Os EPCs são eficazes
                 rh232_epi = int4 = Utiliza Equipamentos de Proteção Individual
                 rh232_epieficaz = bool = Os EPIs são Eficazes
                 rh232_epicertificado = text = Certificado de Aprovação do EPI
                 rh232_epidescricao = text = Descrição do EPI
                 rh232_epiporinviabilidade = bool = Foi tentada a implementação de medidas de proteção coletiva, optando-se pelo EPI por inviabilidade
                 rh232_epiobscondicoes = bool = Foram observadas as condições de funcionamento do EPI ao longo do tempo, conforme especificação
                 rh232_epiobsuso = bool = Foi observado o uso ininterrupto do EPI ao longo do tempo, conforme especificação técnica
                 rh232_epiobsprazo = bool = Foi observado o prazo de validade do CA no momento da compra do EPI
                 rh232_obsperiodicidade = bool = É observada a periodicidade de troca definida pelo fabricante nacional ou importador e/ou programas ambientais, comprovada mediante recibo assinado pelo usuário em época própria
                 rh232_obshigienizacao = bool = É observada a higienização conforme orientação do fabricante nacional ou importador
                 ";
    //funcao construtor da classe 
    function cl_agentesnocivos()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("agentesnocivos");
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
            $this->rh232_sequencial = ($this->rh232_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_sequencial"] : $this->rh232_sequencial);
            $this->rh232_regist = ($this->rh232_regist == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_regist"] : $this->rh232_regist);
            $this->rh232_agente = ($this->rh232_agente == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_agente"] : $this->rh232_agente);
            $this->rh232_tipoavaliacao = ($this->rh232_tipoavaliacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_tipoavaliacao"] : $this->rh232_tipoavaliacao);
            $this->rh232_icdexposicao = ($this->rh232_icdexposicao == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_icdexposicao"] : $this->rh232_icdexposicao);
            $this->rh232_ltolerancia = ($this->rh232_ltolerancia == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_ltolerancia"] : $this->rh232_ltolerancia);
            $this->rh232_unidade = ($this->rh232_unidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_unidade"] : $this->rh232_unidade);
            $this->rh232_tecnicamed = ($this->rh232_tecnicamed == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_tecnicamed"] : $this->rh232_tecnicamed);
            $this->rh232_epc = ($this->rh232_epc == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_epc"] : $this->rh232_epc);
            $this->rh232_epceficaz = ($this->rh232_epceficaz == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_epceficaz"] : $this->rh232_epceficaz);
            $this->rh232_epi = ($this->rh232_epi == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_epi"] : $this->rh232_epi);
            $this->rh232_epieficaz = ($this->rh232_epieficaz == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_epieficaz"] : $this->rh232_epieficaz);
            $this->rh232_epicertificado = ($this->rh232_epicertificado == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_epicertificado"] : $this->rh232_epicertificado);
            $this->rh232_epidescricao = ($this->rh232_epidescricao == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_epidescricao"] : $this->rh232_epidescricao);
            $this->rh232_epiporinviabilidade = ($this->rh232_epiporinviabilidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_epiporinviabilidade"] : $this->rh232_epiporinviabilidade);
            $this->rh232_epiobscondicoes = ($this->rh232_epiobscondicoes == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_epiobscondicoes"] : $this->rh232_epiobscondicoes);
            $this->rh232_epiobsuso = ($this->rh232_epiobsuso == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_epiobsuso"] : $this->rh232_epiobsuso);
            $this->rh232_epiobsprazo = ($this->rh232_epiobsprazo == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_epiobsprazo"] : $this->rh232_epiobsprazo);
            $this->rh232_obsperiodicidade = ($this->rh232_obsperiodicidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_obsperiodicidade"] : $this->rh232_obsperiodicidade);
            $this->rh232_obshigienizacao = ($this->rh232_obshigienizacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_obshigienizacao"] : $this->rh232_obshigienizacao);
        } else {
            $this->rh232_sequencial = ($this->rh232_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["rh232_sequencial"] : $this->rh232_sequencial);
        }
    }
    // funcao para inclusao
    function incluir($rh232_sequencial)
    {
        $this->atualizacampos();
        if ($this->rh232_regist == null) {
            $this->erro_sql = " Campo Tipo de Certificado nao Informado.";
            $this->erro_campo = "rh232_regist";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh232_agente == null || $this->rh232_agente == 0) {
            $this->erro_sql = " Campo Agente Nocivo nao Informado.";
            $this->erro_campo = "rh232_agente";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh232_agente != 92 && $this->rh232_tipoavaliacao == 0) {
            $this->erro_sql = " Campo Tipo Avaliacao nao Informado.";
            $this->erro_campo = "rh232_tipoavaliacao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh232_tipoavaliacao == 1 && $this->rh232_icdexposicao == null) {
            $this->erro_sql = " Campo Intensidade, Concentração ou Dose da Exposição nao Informado.";
            $this->erro_campo = "rh232_icdexposicao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh232_tipoavaliacao == 1 && ($this->rh232_agente != 24 || $this->rh232_agente != 77) && $this->rh232_ltolerancia == null) {
            $this->erro_sql = " Campo Limite de Tolerância nao Informado.";
            $this->erro_campo = "rh232_ltolerancia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh232_tipoavaliacao == 1 && $this->rh232_unidade == null) {
            $this->erro_sql = " Campo Unidade de Medida nao Informado.";
            $this->erro_campo = "rh232_unidade";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->rh232_tipoavaliacao == 1 && $this->rh232_tecnicamed == null) {
            $this->erro_sql = " Campo Técnica de Medida nao Informado.";
            $this->erro_campo = "rh232_tecnicamed";
            $this->erro_banco = "";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if (($this->rh232_icdexposicao == "") || ($this->rh232_icdexposicao == null)) {
            $this->rh232_icdexposicao = "NULL";
        }
        if (($this->rh232_ltolerancia == "") || ($this->rh232_ltolerancia == null)) {
            $this->rh232_ltolerancia = "NULL";
        }
        if (($this->rh232_epicertificado == "") || ($this->rh232_epicertificado == null)) {
            $this->rh232_epicertificado = "NULL";
        }
        if ($rh232_sequencial == "" || $rh232_sequencial == null) {
            $result = db_query("select nextval('agentesnocivos_rh232_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: agentesnocivos_rh232_sequencial_seq do campo: rh232_sequencial";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->rh232_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from agentesnocivos_rh232_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $rh232_sequencial)) {
                $this->erro_sql = " Campo rh232_sequencial maior que último número da sequencia.";
                $this->erro_banco = "Sequencia menor que este número.";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->rh232_sequencial = $rh232_sequencial;
            }
        }
        if (($this->rh232_sequencial == null) || ($this->rh232_sequencial == "")) {
            $this->erro_sql = " Campo rh232_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        $sql = "insert into agentesnocivos(
                                       rh232_sequencial 
                                      ,rh232_regist
                                      ,rh232_agente
                                      ,rh232_tipoavaliacao
                                      ,rh232_icdexposicao
                                      ,rh232_ltolerancia
                                      ,rh232_unidade
                                      ,rh232_tecnicamed
                                      ,rh232_epc
                                      ,rh232_epceficaz
                                      ,rh232_epi
                                      ,rh232_epieficaz
                                      ,rh232_epicertificado
                                      ,rh232_epidescricao
                                      ,rh232_epiporinviabilidade
                                      ,rh232_epiobscondicoes
                                      ,rh232_epiobsuso
                                      ,rh232_epiobsprazo
                                      ,rh232_obsperiodicidade
                                      ,rh232_obshigienizacao
                       )
                values (
                                $this->rh232_sequencial
                               ,$this->rh232_regist
                               ,$this->rh232_agente
                               ,$this->rh232_tipoavaliacao
                               ,$this->rh232_icdexposicao
                               ,$this->rh232_ltolerancia
                               ,$this->rh232_unidade
                               ,'$this->rh232_tecnicamed'
                               ,$this->rh232_epc
                               ,$this->rh232_epceficaz
                               ,$this->rh232_epi
                               ,$this->rh232_epieficaz
                               ,'$this->rh232_epicertificado'
                               ,'$this->rh232_epidescricao'
                               ,$this->rh232_epiporinviabilidade
                               ,$this->rh232_epiobscondicoes
                               ,$this->rh232_epiobsuso
                               ,$this->rh232_epiobsprazo
                               ,$this->rh232_obsperiodicidade
                               ,$this->rh232_obshigienizacao
                      )";
        $result = db_query($sql);

        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "agentesnocivos ($this->rh232_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $sql . " \\n\\n";
                $this->erro_banco = "agentesnocivos já Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "agentesnocivos ($this->rh232_sequencial) nao Incluído. Inclusao Abortada.";
                $this->erro_msg   = "Usuário: \\n\\n " . $sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->rh232_sequencial;
        $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        return true;
    }
    // funcao para alteracao
    function alterar($rh232_sequencial = "")
    {
        $this->atualizacampos();
        $sql = " update agentesnocivos set ";
        $virgula = "";
        if (trim($this->rh232_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_sequencial"])) {
            $sql  .= $virgula . " rh232_sequencial = $this->rh232_sequencial ";
            $virgula = ",";
            if (trim($this->rh232_sequencial) == null) {
                $this->erro_sql = " Campo Codigo Sequencial nao Informado.";
                $this->erro_campo = "rh232_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh232_regist) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_regist"])) {
            $sql  .= $virgula . " rh232_regist = $this->rh232_regist ";
            $virgula = ",";
            if (trim($this->rh232_regist) == null) {
                $this->erro_sql = " Campo Matricula do Servidor nao Informado.";
                $this->erro_campo = "rh232_regist";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh232_agente) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_agente"])) {
            $sql  .= $virgula . " rh232_agente = '$this->rh232_agente' ";
            $virgula = ",";
            if (trim($this->rh232_agente) == null) {
                $this->erro_sql = " Campo Agente Nocivo nao Informado.";
                $this->erro_campo = "rh232_agente";
                $this->erro_banco = "";
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->rh232_tipoavaliacao) != "" || isset($GLOBALS['HTTP_POST_VARS']['rh232_tipoavaliacao'])) {
            $sql .= $virgula . " rh232_tipoavaliacao = '$this->rh232_tipoavaliacao' ";
            $virgula = ',';
        }
        if (trim($this->rh232_icdexposicao) != "" || isset($GLOBALS['HTTP_POST_VARS']['rh232_icdexposicao']) &&  ($GLOBALS["HTTP_POST_VARS"]["rh232_icdexposicao"] != "")) {
            $sql .= $virgula . " rh232_icdexposicao = $this->rh232_icdexposicao ";
            $virgula = ',';
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["rh232_icdexposicao"])) {
                $sql  .= $virgula . " rh232_icdexposicao = null ";
                $virgula = ",";
            }
        }
        if (trim($this->rh232_ltolerancia) != "" || isset($GLOBALS['HTTP_POST_VARS']['rh232_ltolerancia'])  &&  ($GLOBALS["HTTP_POST_VARS"]["rh232_ltolerancia"] != "")) {
            $sql .= $virgula . " rh232_ltolerancia = $this->rh232_ltolerancia ";
            $virgula = ',';
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["rh232_ltolerancia"])) {
                $sql  .= $virgula . " rh232_ltolerancia = null ";
                $virgula = ",";
            }
        }
        if (trim($this->rh232_unidade) != "" || isset($GLOBALS['HTTP_POST_VARS']['rh232_unidade'])) {
            $sql .= $virgula . " rh232_unidade = '$this->rh232_unidade' ";
            $virgula = ',';
        }
        if (trim($this->rh232_tecnicamed) != "" || isset($GLOBALS['HTTP_POST_VARS']['rh232_tecnicamed'])) {
            $sql .= $virgula . " rh232_tecnicamed = '$this->rh232_tecnicamed' ";
            $virgula = ',';
        }
        if (trim($this->rh232_epc) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_epc"])) {
            $sql  .= $virgula . " rh232_epc = '$this->rh232_epc' ";
            $virgula = ",";
        }
        if (trim($this->rh232_epceficaz) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_epceficaz"])) {
            $sql  .= $virgula . " rh232_epceficaz = '$this->rh232_epceficaz' ";
            $virgula = ",";
        }
        if (trim($this->rh232_epi) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_epi"])) {
            $sql  .= $virgula . " rh232_epi = '$this->rh232_epi' ";
            $virgula = ",";
        }
        if (trim($this->rh232_epieficaz) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_epieficaz"])) {
            $sql  .= $virgula . " rh232_epieficaz = '$this->rh232_epieficaz' ";
            $virgula = ",";
        }
        if (trim($this->rh232_epicertificado) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_epicertificado"])) {
            $sql  .= $virgula . " rh232_epicertificado = '$this->rh232_epicertificado'";
            $virgula = ",";
        }
        if (trim($this->rh232_epidescricao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_epidescricao"])) {
            $sql  .= $virgula . " rh232_epidescricao = '$this->rh232_epidescricao' ";
            $virgula = ",";
        }
        if (trim($this->rh232_epiporinviabilidade) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_epiporinviabilidade"])) {
            $sql  .= $virgula . " rh232_epiporinviabilidade = '$this->rh232_epiporinviabilidade' ";
            $virgula = ",";
        }
        if (trim($this->rh232_epiobscondicoes) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_epiobscondicoes"])) {
            $sql  .= $virgula . " rh232_epiobscondicoes = '$this->rh232_epiobscondicoes' ";
            $virgula = ",";
        }
        if (trim($this->rh232_epiobsuso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_epiobsuso"])) {
            $sql  .= $virgula . " rh232_epiobsuso = '$this->rh232_epiobsuso' ";
            $virgula = ",";
        }
        if (trim($this->rh232_epiobsprazo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_epiobsprazo"])) {
            $sql  .= $virgula . " rh232_epiobsprazo = '$this->rh232_epiobsprazo' ";
            $virgula = ",";
        }
        if (trim($this->rh232_obsperiodicidade) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_obsperiodicidade"])) {
            $sql  .= $virgula . " rh232_obsperiodicidade = '$this->rh232_obsperiodicidade' ";
            $virgula = ",";
        }
        if (trim($this->rh232_obshigienizacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["rh232_obshigienizacao"])) {
            $sql  .= $virgula . " rh232_obshigienizacao = '$this->rh232_obshigienizacao' ";
        }
        $sql .= " where ";
        if ($rh232_sequencial != null) {
            $sql .= " rh232_sequencial = $this->rh232_sequencial";
        }
        $resaco = $this->sql_record($this->sql_query_file($this->rh232_sequencial));
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "agentesnocivos não Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->rh232_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "agentesnocivos não foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->rh232_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Alteração efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->rh232_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao 
    function excluir($rh232_sequencial = null, $dbwhere = null)
    {
        if ($dbwhere == null || $dbwhere == "") {
            $resaco = $this->sql_record($this->sql_query_file($rh232_sequencial));
        } else {
            $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
        }
        $sql = " delete from agentesnocivos
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($rh232_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " rh232_sequencial = $rh232_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "agentesnocivos nao Excluído. Exclusão Abortada.\\n";
            $this->erro_sql .= "Valores : " . $rh232_sequencial;
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "agentesnocivos nao Encontrado. Exclusão não Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $rh232_sequencial;
                $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $rh232_sequencial;
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
            $this->erro_sql   = "Record Vazio na Tabela:agentesnocivos";
            $this->erro_msg   = "Usuário: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql 
    function sql_query($rh232_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from agentesnocivos ";
        $sql .= " inner join infoambiente on infoambiente.rh230_regist = agentesnocivos.rh232_regist";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($rh232_sequencial != null) {
                $sql2 .= " where agentesnocivos.rh232_sequencial = $rh232_sequencial ";
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
    function sql_query_file($rh232_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from agentesnocivos ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($rh232_sequencial != null) {
                $sql2 .= " where agentesnocivos.rh232_sequencial = $rh232_sequencial ";
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
    function sql_query_agente($rh232_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from agentesnocivos ";
        $sql .= " inner join infoambiente on infoambiente.rh230_regist = agentesnocivos.rh232_regist";
        $sql .= " inner join rhagente     on rhagente.rh233_sequencial = agentesnocivos.rh232_agente";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($rh232_sequencial != null) {
                $sql2 .= " where agentesnocivos.rh232_sequencial = $rh232_sequencial ";
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
