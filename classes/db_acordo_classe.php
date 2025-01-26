<?php
//MODULO: acordos
//CLASSE DA ENTIDADE acordo
class cl_acordo
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
    var $ac16_sequencial = 0;
    var $ac16_acordosituacao = 0;
    var $ac16_coddepto = 0;
    var $ac16_numero = null;
    var $ac16_anousu = 0;
    var $ac16_dataassinatura_dia = null;
    var $ac16_dataassinatura_mes = null;
    var $ac16_dataassinatura_ano = null;
    var $ac16_dataassinatura = null;
    var $ac16_datapublicacao_dia = null;
    var $ac16_datapublicacao_mes = null;
    var $ac16_datapublicacao_ano = null;
    var $ac16_datapublicacao = null;
    var $ac16_contratado = 0;
    var $ac16_datainicio_dia = null;
    var $ac16_datainicio_mes = null;
    var $ac16_datainicio_ano = null;
    var $ac16_datainicio = null;
    var $ac16_datafim_dia = null;
    var $ac16_datafim_mes = null;
    var $ac16_datafim_ano = null;
    var $ac16_datafim = null;
    var $ac16_resumoobjeto = null;
    var $ac16_objeto = null;
    var $ac16_instit = 0;
    var $ac16_acordocomissao = 0;
    var $ac16_lei = null;
    var $ac16_acordogrupo = 0;
    var $ac16_origem = 0;
    var $ac16_qtdrenovacao = 0;
    var $ac16_tipounidtempo = 0;
    var $ac16_deptoresponsavel = 0;
    var $ac16_numeroprocesso = null;
    var $ac16_periodocomercial = 'f';
    var $ac16_qtdperiodo = 0;
    var $ac16_tipounidtempoperiodo = 0;
    var $ac16_acordocategoria = 0;
    var $ac16_acordoclassificacao = 0;
    var $ac16_numeroacordo = 0;
    var $ac16_valor = 0;
    var $ac16_tipoorigem = 0;
    var $ac16_formafornecimento = null;
    var $ac16_formapagamento = null;
    var $ac16_licitacao = null;
    var $ac16_veiculodivulgacao = null;
    var $ac16_datarescisao_dia = null;
    var $ac16_datarescisao_mes = null;
    var $ac16_datarescisao_ano = null;
    var $ac16_datarescisao = null;
    var $ac16_valorrescisao = null;
    var $ac16_semvigencia = null;
    var $ac16_licoutroorgao = null;
    var $ac16_adesaoregpreco = null;
    var $ac16_tipocadastro = null;
    var $ac16_datareferencia_dia = null;
    var $ac16_datareferencia_mes = null;
    var $ac16_datareferencia_ano = null;
    var $ac16_datareferencia = null;
    var $ac16_providencia = null;
    var $ac16_reajuste = null;
    var $ac16_criterioreajuste = null;
    var $ac16_datareajuste = null;
    var $ac16_periodoreajuste = null;
    var $ac16_datareajuste_dia = null;
    var $ac16_datareajuste_mes = null;
    var $ac16_datareajuste_ano = null;
    var $ac16_indicereajuste = null;
    var $ac16_descricaoreajuste = null;
    var $ac16_descricaoindice = null;
    var $ac16_vigenciaindeterminada = null;
    var $ac16_tipopagamento = 0;
    var $ac16_numparcela = null;
    var $ac16_vlrparcela = null;
    var $ac16_identificadorcipi = null;
    var $ac16_urlcipi = null;
    var $ac16_infcomplementares = null;
    /**
     * A descri��o do status do campo ac16_providencia podem ser checados na tabela providencia
     */

    // cria propriedade com as variaveis do arquivo
    var $campos = "
    ac16_sequencial = int4 = Acordo
    ac16_acordosituacao = int4 = Acordo Situa��o
    ac16_coddepto = int4 = C�digo Departamento
    ac16_numero = varchar(60) = N�mero
    ac16_anousu = int4 = Ano Exerc�cio
    ac16_dataassinatura = date = Data da Assinatura
    ac16_datapublicacao = date = Data Publica��o
    ac16_contratado = int4 = Contratado
    ac16_datainicio = date = Data de In�cio
    ac16_datafim = date = Data de Fim
    ac16_resumoobjeto = varchar(50) = Resumo Objeto
    ac16_objeto = text = Objeto do Contrato
    ac16_instit = int4 = Institui��o
    ac16_acordocomissao = int4 = Acordo Comiss�o
    ac16_lei = varchar(60) = Lei
    ac16_acordogrupo = int4 = Acordo Grupo
    ac16_origem = int4 = Origem
    ac16_qtdrenovacao = float8 = Quantidade de Renova��o
    ac16_tipounidtempo = int4 = Unidade do Tempo
    ac16_deptoresponsavel = int4 = Departamento Respons�vel
    ac16_numeroprocesso = varchar(60) = Numero do Processo
    ac16_periodocomercial = bool = Per�odo Comercial
    ac16_qtdperiodo = float8 = Quantidade do Per�odo de Vig�ncia
    ac16_tipounidtempoperiodo = int4 = Tipo de Per�odo de Vig�ncia
    ac16_acordocategoria = int4 = Acordo Categoria
    ac16_acordoclassificacao = int4 = Sequencial da Classifica��o do Contrato
    ac16_numeroacordo = int4 = N�mero do acordo
    ac16_valor = float8 = Valor do acordo
    ac16_tipoorigem = int8 = Tipo de Origem acordo
    ac16_formafornecimento = Forma de fornecimento acordo
    ac16_formapagamento = Forma de pagamento acordo
    ac16_veiculodivulgacao = varchar(50) = Ve�culo de divulga��o
    ac16_datarescisao = date = Data Rescis�o
    ac16_valorrescisao = float8 = Valor da rescis�o do acordo
    ac16_semvigencia = bool = Contrato criado sem vig�ncia
    ac16_licoutroorgao = int8 = licitacao de outros orgaos
    ac16_adesaoregpreco = int8 = adesao de registro de precos
    ac16_tipocadastro   = int8 = tipo de cadastro
    ac16_providencia = int4 = Status da Provid�ncia do Acordo
    ac16_datareferencia = date = Data de refer�ncia para gera��o do SICOM
    ac16_reajuste = boll = possui reajuste
    ac16_criterioreajuste =
    ac16_datareajuste = ;
    ac16_periodoreajuste = ;
    ac16_datareajuste_dia = ;
    ac16_datareajuste_mes = ;
    ac16_datareajuste_ano = ;
    ac16_indicereajuste = ;
    ac16_descricaoreajuste = ;
    ac16_descricaoindice = ;
    ac16_vigenciaindeterminada = bool = Vig�ncia Indeterminada;
    ac16_tipopagamento = int11 = Tipo de Per�odo de Vig�ncia
    ac16_numparcela = int11 = Tipo de Per�odo de Vig�ncia
    ac16_vlrparcela = float8 = Tipo de Per�odo de Vig�ncia
    ac16_identificadorcipi = varchar(512) = Identificar CIPI
    ac16_urlcipi = varchar(14) = Url CIPI
    ac16_infcomplementares = text = Informa��es Complementares
    ";
    //funcao construtor da classe
    function cl_acordo()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("acordo");
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
            $this->ac16_sequencial = ($this->ac16_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_sequencial"] : $this->ac16_sequencial);
            $this->ac16_acordosituacao = ($this->ac16_acordosituacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_acordosituacao"] : $this->ac16_acordosituacao);

            $this->ac16_semvigencia = ($this->ac16_semvigencia == null ? @$GLOBALS["HTTP_POST_VARS"]["ac16_semvigencia"] : $this->ac16_semvigencia);

            $this->ac16_coddepto = ($this->ac16_coddepto == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_coddepto"] : $this->ac16_coddepto);
            $this->ac16_numero = ($this->ac16_numero == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_numero"] : $this->ac16_numero);
            $this->ac16_anousu = ($this->ac16_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_anousu"] : $this->ac16_anousu);
            if ($this->ac16_dataassinatura == "") {
                $this->ac16_dataassinatura_dia = ($this->ac16_dataassinatura_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_dia"] : $this->ac16_dataassinatura_dia);
                $this->ac16_dataassinatura_mes = ($this->ac16_dataassinatura_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_mes"] : $this->ac16_dataassinatura_mes);
                $this->ac16_dataassinatura_ano = ($this->ac16_dataassinatura_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_ano"] : $this->ac16_dataassinatura_ano);
                if ($this->ac16_dataassinatura_dia != "") {
                    $this->ac16_dataassinatura = $this->ac16_dataassinatura_ano . "-" . $this->ac16_dataassinatura_mes . "-" . $this->ac16_dataassinatura_dia;
                }
            }
            if ($this->ac16_datareajuste == "") {
                $this->ac16_datareajuste_dia = ($this->ac16_datareajuste_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datareajuste_dia"] : $this->ac16_datareajuste_dia);
                $this->ac16_datareajuste_mes = ($this->ac16_datareajuste_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datareajuste_mes"] : $this->ac16_datareajuste_mes);
                $this->ac16_datareajuste_ano = ($this->ac16_datareajuste_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datareajuste_ano"] : $this->ac16_datareajuste_ano);
                if ($this->ac16_datareajuste_dia != "") {
                    $this->ac16_datareajuste = $this->ac16_datareajuste_ano . "-" . $this->ac16_datareajuste_mes . "-" . $this->ac16_datareajuste_dia;
                }
            }
            if ($this->ac16_datapublicacao == "") {
                $this->ac16_datapublicacao_dia = ($this->ac16_datapublicacao_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datapublicacao_dia"] : $this->ac16_datapublicacao_dia);
                $this->ac16_datapublicacao_mes = ($this->ac16_datapublicacao_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datapublicacao_mes"] : $this->ac16_datapublicacao_mes);
                $this->ac16_datapublicacao_ano = ($this->ac16_datapublicacao_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datapublicacao_ano"] : $this->ac16_datapublicacao_ano);
                if ($this->ac16_datapublicacao_dia != "") {
                    $this->ac16_datapublicacao = $this->ac16_datapublicacao_ano . "-" . $this->ac16_datapublicacao_mes . "-" . $this->ac16_datapublicacao_dia;
                }
            }
            $this->ac16_contratado = ($this->ac16_contratado == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_contratado"] : $this->ac16_contratado);
            if ($this->ac16_datainicio == "") {
                $this->ac16_datainicio_dia = ($this->ac16_datainicio_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_dia"] : $this->ac16_datainicio_dia);
                $this->ac16_datainicio_mes = ($this->ac16_datainicio_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_mes"] : $this->ac16_datainicio_mes);
                $this->ac16_datainicio_ano = ($this->ac16_datainicio_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_ano"] : $this->ac16_datainicio_ano);
                if ($this->ac16_datainicio_dia != "") {
                    $this->ac16_datainicio = $this->ac16_datainicio_ano . "-" . $this->ac16_datainicio_mes . "-" . $this->ac16_datainicio_dia;
                }
            }
            if ($this->ac16_datafim == "") {
                $this->ac16_datafim_dia = ($this->ac16_datafim_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datafim_dia"] : $this->ac16_datafim_dia);
                $this->ac16_datafim_mes = ($this->ac16_datafim_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datafim_mes"] : $this->ac16_datafim_mes);
                $this->ac16_datafim_ano = ($this->ac16_datafim_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datafim_ano"] : $this->ac16_datafim_ano);
                if ($this->ac16_datafim_dia != "") {
                    $this->ac16_datafim = $this->ac16_datafim_ano . "-" . $this->ac16_datafim_mes . "-" . $this->ac16_datafim_dia;
                }
            }
            $this->ac16_resumoobjeto = ($this->ac16_resumoobjeto == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_resumoobjeto"] : $this->ac16_resumoobjeto);
            $this->ac16_objeto = ($this->ac16_objeto == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_objeto"] : $this->ac16_objeto);
            $this->ac16_instit = ($this->ac16_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_instit"] : $this->ac16_instit);
            $this->ac16_acordocomissao = ($this->ac16_acordocomissao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_acordocomissao"] : $this->ac16_acordocomissao);
            $this->ac16_lei = ($this->ac16_lei == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_lei"] : $this->ac16_lei);
            $this->ac16_acordogrupo = ($this->ac16_acordogrupo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_acordogrupo"] : $this->ac16_acordogrupo);
            $this->ac16_origem = ($this->ac16_origem == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_origem"] : $this->ac16_origem);
            $this->ac16_reajuste = ($this->ac16_reajuste == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_reajuste"] : $this->ac16_reajuste);
            $this->ac16_criterioreajuste = ($this->ac16_criterioreajuste == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_criterioreajuste"] : $this->ac16_criterioreajuste);
            $this->ac16_periodoreajuste = ($this->ac16_periodoreajuste == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_periodoreajuste"] : $this->ac16_periodoreajuste);
            $this->ac16_indicereajuste = ($this->ac16_indicereajuste == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_indicereajuste"] : $this->ac16_indicereajuste);
            $this->ac16_descricaoreajuste = ($this->ac16_descricaoreajuste == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_descricaoreajuste"] : $this->ac16_descricaoreajuste);
            $this->ac16_descricaoindice = ($this->ac16_descricaoindice == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_descricaoindice"] : $this->ac16_descricaoindice);
            $this->ac16_qtdrenovacao = ($this->ac16_qtdrenovacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_qtdrenovacao"] : $this->ac16_qtdrenovacao);
            $this->ac16_tipounidtempo = ($this->ac16_tipounidtempo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempo"] : $this->ac16_tipounidtempo);
            $this->ac16_deptoresponsavel = ($this->ac16_deptoresponsavel == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_deptoresponsavel"] : $this->ac16_deptoresponsavel);
            $this->ac16_numeroprocesso = ($this->ac16_numeroprocesso == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_numeroprocesso"] : $this->ac16_numeroprocesso);
            $this->ac16_periodocomercial = ($this->ac16_periodocomercial == "f" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_periodocomercial"] : $this->ac16_periodocomercial);
            $this->ac16_qtdperiodo = ($this->ac16_qtdperiodo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_qtdperiodo"] : $this->ac16_qtdperiodo);
            $this->ac16_tipounidtempoperiodo = ($this->ac16_tipounidtempoperiodo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempoperiodo"] : $this->ac16_tipounidtempoperiodo);
            $this->ac16_acordocategoria = ($this->ac16_acordocategoria == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_acordocategoria"] : $this->ac16_acordocategoria);
            $this->ac16_acordoclassificacao = ($this->ac16_acordoclassificacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_acordoclassificacao"] : $this->ac16_acordoclassificacao);
            $this->ac16_numeroacordo = ($this->ac16_numeroacordo == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_numeroacordo"] : $this->ac16_numeroacordo);
            $this->ac16_valor = ($this->ac16_valor == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_valor"] : $this->ac16_valor);
            $this->ac16_tipoorigem = ($this->ac16_tipoorigem == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_tipoorigem"] : $this->ac16_tipoorigem);
            $this->ac16_formafornecimento = ($this->ac16_formafornecimento == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_formafornecimento"] : $this->ac16_formafornecimento);
            $this->ac16_formapagamento = ($this->ac16_formapagamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_formapagamento"] : $this->ac16_formapagamento);
            $this->ac16_licitacao = ($this->ac16_licitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_licitacao"] : $this->ac16_licitacao);
            $this->ac16_veiculodivulgacao = ($this->ac16_veiculodivulgacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_veiculodivulgacao"] : $this->ac16_veiculodivulgacao);

            if ($this->ac16_datarescisao == "") {
                $this->ac16_datarescisao_dia = ($this->ac16_datarescisao_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datarescisao_dia"] : $this->ac16_datarescisao_dia);
                $this->ac16_datarescisao_mes = ($this->ac16_datarescisao_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datarescisao_mes"] : $this->ac16_datarescisao_mes);
                $this->ac16_datarescisao_ano = ($this->ac16_datarescisao_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datarescisao_ano"] : $this->ac16_datarescisao_ano);
                if ($this->ac16_datarescisao_dia != "") {
                    $this->ac16_datarescisao = $this->ac16_datarescisao_ano . "-" . $this->ac16_datarescisao_mes . "-" . $this->ac16_datarescisao_dia;
                }
            }

            $this->ac16_valorrescisao = ($this->ac16_valorrescisao === null ? @$GLOBALS["HTTP_POST_VARS"]["ac16_valorrescisao"] : $this->ac16_valorrescisao);
            $this->ac16_licoutroorgao = ($this->ac16_licoutroorgao === null ? @$GLOBALS["HTTP_POST_VARS"]["ac16_licoutroorgao"] : $this->ac16_licoutroorgao);
            $this->ac16_adesaoregpreco = ($this->ac16_adesaoregpreco === null ? @$GLOBALS["HTTP_POST_VARS"]["ac16_adesaoregpreco"] : $this->ac16_adesaoregpreco);
            $this->ac16_tipocadastro = ($this->ac16_tipocadastro === null ? @$GLOBALS["HTTP_POST_VARS"]["ac16_tipocadastro"] : $this->ac16_tipocadastro);
            $this->ac16_providencia = ($this->ac16_providencia === null ? @$GLOBALS["HTTP_POST_VARS"]["ac16_providencia"] : $this->ac16_providencia);
            $this->ac16_datareferencia = ($this->ac16_datareferencia === null ? @$GLOBALS["HTTP_POST_VARS"]["ac16_datareferencia"] : $this->ac16_datareferencia);
            $this->ac16_vigenciaindeterminada = ($this->ac16_vigenciaindeterminada === null ? @$GLOBALS["HTTP_POST_VARS"]["ac16_vigenciaindeterminada"] : $this->ac16_vigenciaindeterminada);
            $this->ac16_tipopagamento = ($this->ac16_tipopagamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_tipopagamento"] : $this->ac16_tipopagamento);
            $this->ac16_numparcela = ($this->ac16_numparcela == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_numparcela"] : $this->ac16_numparcela);
            $this->ac16_vlrparcela = ($this->ac16_vlrparcela == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_vlrparcela"] : $this->ac16_vlrparcela);
            $this->ac16_identificadorcipi = ($this->ac16_identificadorcipi == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_identificadorcipi"] : $this->ac16_identificadorcipi);
            $this->ac16_urlcipi = ($this->ac16_urlcipi == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_urlcipi"] : $this->ac16_urlcipi);
            $this->ac16_infcomplementares = ($this->ac16_infcomplementares == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_infcomplementares"] : $this->ac16_infcomplementares);
        } else {
            $this->ac16_sequencial = ($this->ac16_sequencial == "" ? @$GLOBALS["HTTP_POST_VARS"]["ac16_sequencial"] : $this->ac16_sequencial);
        }
    }
    // funcao para inclusao
    function incluir($ac16_sequencial)
    {
        $this->atualizacampos();
        if ($this->ac16_acordosituacao == null) {
            $this->erro_sql = " Campo Acordo Situa��o n�o informado.";
            $this->erro_campo = "ac16_acordosituacao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_coddepto == null) {
            $this->erro_sql = " Campo C�digo Departamento n�o informado.";
            $this->erro_campo = "ac16_coddepto";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_numero == null) {
            $this->erro_sql = " Campo N�mero n�o informado.";
            $this->erro_campo = "ac16_numero";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_anousu == null) {
            $this->erro_sql = " Campo Ano Exerc�cios n�o informado.";
            $this->erro_campo = "ac16_anousu";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_dataassinatura == null) {
            $this->ac16_dataassinatura = "null";
        }
        if ($this->ac16_datarescisao == null) {
            $this->ac16_datarescisao = "null";
        }
        if ($this->ac16_contratado == null) {
            $this->erro_sql = " Campo Contratado n�o informado.";
            $this->erro_campo = "ac16_contratado";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->ac16_licoutroorgao == null && $this->ac16_tipoorigem == 5) {
            $this->erro_sql = " Campo Licita��o de Outros Orgaos n�o informado.";
            $this->erro_campo = "ac16_licoutroorgao";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->ac16_adesaoregpreco == null && $this->ac16_adesaoregpreco == 4) {
            $this->erro_sql = " Campo Licita��o de Outros Orgaos n�o informado.";
            $this->erro_campo = "ac16_adesaoregpreco";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->ac16_datainicio == null) {
            $this->erro_sql = " Campo Data de In�cio n�o informado.";
            $this->erro_campo = "ac16_datainicio_dia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_datafim == null && $this->ac16_vigenciaindeterminada != "t") {
            $this->erro_sql = " Campo Data de Fim n�o informado.";
            $this->erro_campo = "ac16_datafim_dia";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->ac16_objeto == null) {
            $this->erro_sql = " Campo Objeto do Contrato n�o informado.";
            $this->erro_campo = "ac16_objeto";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_instit == null) {
            $this->erro_sql = " Campo Institui��o n�o informado.";
            $this->erro_campo = "ac16_instit";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_acordocomissao == null) {
            $this->ac16_acordocomissao = "null";
            //     $this->erro_sql = " Campo Acordo Comiss�o n�o informado.";
            //     $this->erro_campo = "ac16_acordocomissao";
            //     $this->erro_banco = "";
            //     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            //     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            //     $this->erro_status = "0";
            //     return false;
        }
        if ($this->ac16_acordogrupo == null) {
            $this->erro_sql = " Campo Acordo Grupo n�o informado.";
            $this->erro_campo = "ac16_acordogrupo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_origem == null || !$this->ac16_origem) {
            $this->erro_sql = " Campo Origem n�o informado.";
            $this->erro_campo = "ac16_origem";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_reajuste == null || !$this->ac16_reajuste) {
            $this->erro_sql = " Campo reajuste n�o informado.";
            $this->erro_campo = "ac16_reajuste";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->ac16_qtdrenovacao == null) {
            $this->ac16_qtdrenovacao = "null";
            //     $this->erro_sql = " Campo Quantidade de Renova��o n�o informado.";
            //     $this->erro_campo = "ac16_qtdrenovacao";
            //     $this->erro_banco = "";
            //     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            //     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            //     $this->erro_status = "0";
            //     return false;
        }
        if ($this->ac16_tipounidtempo == null) {
            $this->erro_sql = " Campo Unidade do Tempo n�o informado.";
            $this->erro_campo = "ac16_tipounidtempo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_deptoresponsavel == null) {
            $this->erro_sql = " Campo Departamento Respons�vel n�o informado.";
            $this->erro_campo = "ac16_deptoresponsavel";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        // if($this->ac16_periodocomercial == null ){
        //     $this->erro_sql = " Campo Per�odo Comercial n�o informado.";
        //     $this->erro_campo = "ac16_periodocomercial";
        //     $this->erro_banco = "";
        //     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        //     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        //     $this->erro_status = "0";
        //     return false;
        // }
        if ($this->ac16_qtdperiodo == null) {
            $this->ac16_qtdperiodo = "0";
        }
        if ($this->ac16_tipounidtempoperiodo == null || !$this->ac16_tipounidtempoperiodo) {
            $this->erro_sql = " Campo Unid. Execu��o/Entrega n�o informado.";
            $this->erro_campo = "ac16_tipounidtempoperiodo";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if (empty($this->ac16_tipopagamento) || $this->ac16_tipopagamento == 0) {
            $this->erro_sql = " Campo Tipo Pagamento n�o informado1.";
            $this->erro_campo = "ac16_tipopagamento";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";

            return false;
        }

        if (!empty($this->ac16_tipopagamento) && $this->ac16_tipopagamento == 2) {

            if (empty($this->ac16_numparcela)) {
                $this->erro_sql = " Campo N�mero de Parcela n�o informado.";
                $this->erro_campo = "ac16_numparcela";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";

                return false;
            }

            if (empty($this->ac16_vlrparcela)) {
                $this->erro_sql = " Campo Valor da Parcela n�o informado.";
                $this->erro_campo = "ac16_vlrparcela";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";

                return false;
            }

        } else if (!empty($this->ac16_tipopagamento) && $this->ac16_tipopagamento == 1) {

            $this->ac16_numparcela = 1;
            $this->ac16_vlrparcela = $this->ac16_vlrparcela;

        }

        if (!empty($this->ac16_urlcipi) && strlen($this->ac16_urlcipi) < 8 || strlen($this->ac16_urlcipi) > 14) {
            if(strlen($this->ac16_urlcipi) < 8) {
                $this->erro_sql = "Campo Url CIPI n�o pode ser menor que 8 caracteres.";
            } elseif (strlen($this->ac16_urlcipi) > 14) {
                $this->erro_sql = "Campo Url CIPI n�o pode ser maior que 14 caracteres.";
            }

            $this->erro_campo = "ac16_urlcipi";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";

            return false;
        }

        if ($this->ac16_acordocategoria == null) {
            $this->ac16_acordocategoria = "0";
        }

        if ($this->ac16_numeroacordo == null) {
            $this->ac16_numeroacordo = "0";
        }
        if ($this->ac16_valor == null) {
            $this->ac16_valor = "0";
        }
        if ($this->ac16_valorrescisao == null) {
            $this->ac16_valorrescisao = "0";
        }
        if ($this->ac16_formafornecimento == null) {
            $this->erro_sql = " Campo Forma de fornecimento n�o informado.";
            $this->erro_campo = "ac16_formafornecimento";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->ac16_formapagamento == null) {
            $this->erro_sql = " Campo Forma de pagamento n�o informado.";
            $this->erro_campo = "ac16_formapagamento";
            $this->erro_banco = "";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($ac16_sequencial == "" || $ac16_sequencial == null) {
            $result = db_query("select nextval('acordo_ac16_sequencial_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "Verifique o cadastro da sequencia: acordo_ac16_sequencial_seq do campo: ac16_sequencial";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->ac16_sequencial = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from acordo_ac16_sequencial_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $ac16_sequencial)) {
                $this->erro_sql = " Campo ac16_sequencial maior que ult�mo N�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este N�mero.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->ac16_sequencial = $ac16_sequencial;
            }
        }
        if (($this->ac16_sequencial == null) || ($this->ac16_sequencial == "")) {
            $this->erro_sql = " Campo ac16_sequencial nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if (($this->ac16_tipocadastro == null) || ($this->ac16_tipocadastro == "")) {
            $this->ac16_tipocadastro = 1;
        }

        $sql = "insert into acordo(
      ac16_sequencial
     ,ac16_acordosituacao
     ,ac16_coddepto
     ,ac16_numero
     ,ac16_anousu
     ,ac16_dataassinatura
     ,ac16_datarescisao
     ,ac16_contratado
     ,ac16_datainicio
     ,ac16_datafim
     ,ac16_resumoobjeto
     ,ac16_objeto
     ,ac16_instit
     ,ac16_acordocomissao
     ,ac16_lei
     ,ac16_acordogrupo
     ,ac16_origem
     ,ac16_qtdrenovacao
     ,ac16_tipounidtempo
     ,ac16_deptoresponsavel
     ,ac16_numeroprocesso
     ,ac16_periodocomercial
     ,ac16_qtdperiodo
     ,ac16_tipounidtempoperiodo
     ,ac16_acordocategoria
     ,ac16_acordoclassificacao
     ,ac16_numeroacordo
     ,ac16_valor
     ,ac16_valorrescisao
     ,ac16_tipoorigem
     ,ac16_formafornecimento
     ,ac16_formapagamento
     ,ac16_licitacao
     ,ac16_licoutroorgao
     ,ac16_adesaoregpreco
     ,ac16_tipocadastro
     ,ac16_reajuste
     ,ac16_criterioreajuste
     ,ac16_datareajuste
     ,ac16_indicereajuste
     ,ac16_periodoreajuste
     ,ac16_descricaoreajuste
     ,ac16_descricaoindice
     ,ac16_vigenciaindeterminada
     ,ac16_tipopagamento
     ,ac16_numparcela
     ,ac16_vlrparcela
     ,ac16_identificadorcipi
     ,ac16_urlcipi
     ,ac16_infcomplementares
     )
     values (
     $this->ac16_sequencial
     ,$this->ac16_acordosituacao
     ,$this->ac16_coddepto
     ,'$this->ac16_numero'
     ,$this->ac16_anousu
     ," . ($this->ac16_dataassinatura == "null" || $this->ac16_dataassinatura == "" ? "null" : "'" . $this->ac16_dataassinatura . "'") . "
     ," . ($this->ac16_datarescisao == "null" || $this->ac16_datarescisao == "" ? "null" : "'" . $this->ac16_datarescisao . "'") . "
     ,$this->ac16_contratado
     ," . ($this->ac16_datainicio == "null" || $this->ac16_datainicio == "" ? "null" : "'" . $this->ac16_datainicio . "'") . "
     ," . ($this->ac16_datafim == "null" || $this->ac16_datafim == "" ? "null" : "'" . $this->ac16_datafim . "'") . "
     ,'" . substr($this->ac16_objeto, 0, 49) . "'
     ,'$this->ac16_objeto'
     ,$this->ac16_instit
     ,$this->ac16_acordocomissao
     ," . ($this->ac16_lei == "" || $this->ac16_lei == 0 ? 'null' : $this->ac16_lei) . "
     ,$this->ac16_acordogrupo
     ,$this->ac16_origem
     ,$this->ac16_qtdrenovacao
     ,$this->ac16_tipounidtempo
     ,$this->ac16_deptoresponsavel
     ,'$this->ac16_numeroprocesso'
     ,'$this->ac16_periodocomercial'
     ,$this->ac16_qtdperiodo
     ,$this->ac16_tipounidtempoperiodo
     ,$this->ac16_acordocategoria
     ," . ($this->ac16_acordoclassificacao == "null" || $this->ac16_acordoclassificacao == "" ? "null" : "'" . $this->ac16_acordoclassificacao . "'") . "
     ,$this->ac16_numeroacordo
     ,$this->ac16_valor
     ,$this->ac16_valorrescisao
     ," . ($this->ac16_tipoorigem == "null" || $this->ac16_tipoorigem == "" ? 'null' : $this->ac16_tipoorigem) . "
     ,'$this->ac16_formafornecimento'
     ,'$this->ac16_formapagamento'
     ," . ($this->ac16_licitacao == "null" || $this->ac16_licitacao == "" ? 'null' : $this->ac16_licitacao) . "
     ," . ($this->ac16_licoutroorgao == "" ? 'null' : $this->ac16_licoutroorgao) . "
     ," . ($this->ac16_adesaoregpreco == "" ? 'null' : $this->ac16_adesaoregpreco) . "
     ,$this->ac16_tipocadastro
     ," . ($this->ac16_reajuste == 1 ? "'t'" : "'f'") . "
     ," . ($this->ac16_criterioreajuste == "null" || $this->ac16_criterioreajuste == "" ? "" : $this->ac16_criterioreajuste) . "
     ," . ($this->ac16_datareajuste == "null" || $this->ac16_datareajuste == "" ? 'null' : "'" . $this->ac16_datareajuste . "'") . "
     ," . ($this->ac16_indicereajuste == "null" || $this->ac16_indicereajuste == "" ? "" : $this->ac16_indicereajuste) . "
     ," . ($this->ac16_periodoreajuste == "null" || $this->ac16_periodoreajuste == "" ? "''" : "'" . $this->ac16_periodoreajuste . "'") . "
     ," . ($this->ac16_descricaoreajuste == "null" || $this->ac16_descricaoreajuste == "" ? 'null' : "'" . $this->ac16_descricaoreajuste . "'") . "
     ," . ($this->ac16_descricaoindice == "null" || $this->ac16_descricaoindice == "" ? 'null' : "'" . $this->ac16_descricaoindice . "'") . "
     ,".($this->ac16_vigenciaindeterminada == "null" || $this->ac16_vigenciaindeterminada == ""?"'false'":"'".$this->ac16_vigenciaindeterminada."'")."
     ,$this->ac16_tipopagamento
     ,".($this->ac16_numparcela == "" ? 'null' : $this->ac16_numparcela)."
     ,".($this->ac16_vlrparcela == "" ? 'null' : $this->ac16_vlrparcela)."
     ," . ($this->ac16_identificadorcipi == "null" || $this->ac16_identificadorcipi == "" ? "null" : "'" . $this->ac16_identificadorcipi . "'") . "
     ," . ($this->ac16_urlcipi == "null" || $this->ac16_urlcipi == "" ? "null" : "'" . $this->ac16_urlcipi . "'") . "
     ," . ($this->ac16_infcomplementares == "null" || $this->ac16_infcomplementares == "" ? "null" : "'" . $this->ac16_infcomplementares . "'") . "
     )";

        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql   = "Acordo ($this->ac16_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "Acordo j� Cadastrado";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql   = "Acordo ($this->ac16_sequencial) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->ac16_sequencial;
        $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ac16_sequencial));
            if (($resaco != false) || ($this->numrows != 0)) {

                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,16116,'$this->ac16_sequencial','I')");
                $resac = db_query("insert into db_acount values($acount,2828,16116,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16117,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_acordosituacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16118,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_coddepto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16119,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_numero')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16120,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16121,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_dataassinatura')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16122,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_contratado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16123,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_datainicio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16124,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_datafim')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16125,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_resumoobjeto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16126,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_objeto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16127,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16129,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_acordocomissao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16130,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_lei')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16211,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_acordogrupo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16233,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_origem')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16660,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_qtdrenovacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,16659,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_tipounidtempo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,18033,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_deptoresponsavel')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,18487,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_numeroprocesso')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,19736,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_periodocomercial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,19928,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_qtdperiodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,19927,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_tipounidtempoperiodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,19926,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_acordocategoria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,20531,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_acordoclassificacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,20546,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_numeroacordo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,2828,20547,'','" . AddSlashes(pg_result($resaco, 0, 'ac16_valor')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }
    // funcao para alteracao
    function alterar($ac16_sequencial = null)
    {
        $this->atualizacampos();
        $sql = " update acordo set ";
        $virgula = "";
        if (trim($this->ac16_sequencial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_sequencial"])) {
            $sql  .= $virgula . " ac16_sequencial = $this->ac16_sequencial ";
            $virgula = ",";
            if (trim($this->ac16_sequencial) == null) {
                $this->erro_sql = " Campo Acordo n�o informado.";
                $this->erro_campo = "ac16_sequencial";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_acordosituacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordosituacao"])) {
            $sql  .= $virgula . " ac16_acordosituacao = $this->ac16_acordosituacao ";
            $virgula = ",";
            if (trim($this->ac16_acordosituacao) == null) {
                $this->erro_sql = " Campo Acordo Situa��o n�o informado.";
                $this->erro_campo = "ac16_acordosituacao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_coddepto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_coddepto"])) {
            $sql  .= $virgula . " ac16_coddepto = $this->ac16_coddepto ";
            $virgula = ",";
            if (trim($this->ac16_coddepto) == null) {
                $this->erro_sql = " Campo C�digo Departamento n�o informado.";
                $this->erro_campo = "ac16_coddepto";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_numero) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_numero"])) {
            $sql  .= $virgula . " ac16_numero = '$this->ac16_numero' ";
            $virgula = ",";
            if (trim($this->ac16_numero) == null) {
                $this->erro_sql = " Campo N�mero n�o informado.";
                $this->erro_campo = "ac16_numero";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_anousu) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_anousu"])) {
            $sql  .= $virgula . " ac16_anousu = $this->ac16_anousu ";
            $virgula = ",";
            if (trim($this->ac16_anousu) == null) {
                $this->erro_sql = " Campo Ano Exerc�cios n�o informado.";
                $this->erro_campo = "ac16_anousu";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if ((trim($this->ac16_dataassinatura) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_dia"]) &&
            ($GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura_dia"] != "")) && $this->ac16_dataassinatura != 'null') {
            $sql  .= $virgula . " ac16_dataassinatura = '$this->ac16_dataassinatura' ";
            $virgula = ",";
        } elseif ($this->ac16_dataassinatura == 'null') {
            $sql  .= $virgula . " ac16_dataassinatura = null ";
            $virgula = ",";
        }

        if ($this->ac16_datarescisao !== null || isset($GLOBALS["HTTP_POST_VARS"]["ac16_datarescisao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac16_datarescisao_dia"] != "")) {
            if (empty($this->ac16_datarescisao)) {
                $sql  .= $virgula . " ac16_datarescisao = null ";
            } else {
                $sql  .= $virgula . " ac16_datarescisao = '$this->ac16_datarescisao' ";
            }
            $virgula = ",";
        }

        if (trim($this->ac16_datapublicacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_datapublicacao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac16_datapublicacao_dia"] != "")) {
            $sql  .= $virgula . " ac16_datapublicacao = '$this->ac16_datapublicacao' ";
            $virgula = ",";
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_datapublicacao_dia"])) {
                $sql  .= $virgula . " ac16_datapublicacao = null ";
                $virgula = ",";
            }
        }



        if (trim($this->ac16_contratado) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_contratado"])) {
            $sql  .= $virgula . " ac16_contratado = $this->ac16_contratado ";
            $virgula = ",";
            if (trim($this->ac16_contratado) == null) {
                $this->erro_sql = " Campo Contratado n�o informado.";
                $this->erro_campo = "ac16_contratado";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_datainicio) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_dia"] != "")) {
            $sql  .= $virgula . " ac16_datainicio = '$this->ac16_datainicio' ";
            $virgula = ",";
            if (trim($this->ac16_datainicio) == null) {
                $this->erro_sql = " Campo Data de In�cio n�o informado.";
                $this->erro_campo = "ac16_datainicio_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_datainicio_dia"])) {
                $sql  .= $virgula . " ac16_datainicio = null ";
                $virgula = ",";
                if (trim($this->ac16_datainicio) == null) {
                    $this->erro_sql = " Campo Data de In�cio n�o informado.";
                    $this->erro_campo = "ac16_datainicio_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }
        if (trim($this->ac16_datafim) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac16_datafim_dia"] != "")) {
            $sql  .= $virgula . " ac16_datafim = '$this->ac16_datafim' ";
            $virgula = ",";
            if (trim($this->ac16_datafim) == null) {
                $this->erro_sql = " Campo Data de Fim n�o informado.";
                $this->erro_campo = "ac16_datafim_dia";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_datafim_dia"])) {
                $sql  .= $virgula . " ac16_datafim = null ";
                $virgula = ",";
                if (trim($this->ac16_datafim) == null) {
                    $this->erro_sql = " Campo Data de Fim n�o informado.";
                    $this->erro_campo = "ac16_datafim_dia";
                    $this->erro_banco = "";
                    $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }

        if (trim($this->ac16_objeto) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_objeto"])) {
            $sql  .= $virgula . " ac16_objeto = '$this->ac16_objeto', ac16_resumoobjeto = '" . substr($this->ac16_objeto, 0, 49) . "'";
            $virgula = ",";
            if (trim($this->ac16_objeto) == null) {
                $this->erro_sql = " Campo Objeto do Contrato n�o informado.";
                $this->erro_campo = "ac16_objeto";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_instit) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_instit"])) {
            $sql  .= $virgula . " ac16_instit = $this->ac16_instit ";
            $virgula = ",";
            if (trim($this->ac16_instit) == null) {
                $this->erro_sql = " Campo Institui��o n�o informado.";
                $this->erro_campo = "ac16_instit";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_acordocomissao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordocomissao"])) {
            $sql  .= $virgula . " ac16_acordocomissao = $this->ac16_acordocomissao ";
            $virgula = ",";
            // if(trim($this->ac16_acordocomissao) == null ){
            //     $this->erro_sql = " Campo Acordo Comiss�o n�o informado.";
            //     $this->erro_campo = "ac16_acordocomissao";
            //     $this->erro_banco = "";
            //     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            //     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            //     $this->erro_status = "0";
            //     return false;
            // }
        }
        if (trim($this->ac16_lei) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_lei"])) {
            $sql  .= $virgula . " ac16_lei = " . ($this->ac16_lei == "" || $this->ac16_lei == 0 ? 'null' : $this->ac16_lei);
            $virgula = ",";
        }
        if (trim($this->ac16_acordogrupo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordogrupo"])) {
            $sql  .= $virgula . " ac16_acordogrupo = $this->ac16_acordogrupo ";
            $virgula = ",";
            if (trim($this->ac16_acordogrupo) == null) {
                $this->erro_sql = " Campo Acordo Grupo n�o informado.";
                $this->erro_campo = "ac16_acordogrupo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_origem) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_origem"])) {
            $sql  .= $virgula . " ac16_origem = $this->ac16_origem ";
            $virgula = ",";
            if (trim($this->ac16_origem) == null || !$this->ac16_origem) {
                $this->erro_sql = " Campo Origem n�o informado.";
                $this->erro_campo = "ac16_origem";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->ac16_qtdrenovacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_qtdrenovacao"])) {
            $sql  .= $virgula . " ac16_qtdrenovacao = $this->ac16_qtdrenovacao ";
            $virgula = ",";
            // if(trim($this->ac16_qtdrenovacao) == null ){
            //     $this->erro_sql = " Campo Quantidade de Renova��o n�o informado.";
            //     $this->erro_campo = "ac16_qtdrenovacao";
            //     $this->erro_banco = "";
            //     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            //     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            //     $this->erro_status = "0";
            //     return false;
            // }
        }
        if (trim($this->ac16_tipounidtempo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempo"])) {
            $sql  .= $virgula . " ac16_tipounidtempo = $this->ac16_tipounidtempo ";
            $virgula = ",";
            if (trim($this->ac16_tipounidtempo) == null) {
                $this->erro_sql = " Campo Unidade do Tempo n�o informado.";
                $this->erro_campo = "ac16_tipounidtempo";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_deptoresponsavel) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_deptoresponsavel"])) {
            $sql  .= $virgula . " ac16_deptoresponsavel = $this->ac16_deptoresponsavel ";
            $virgula = ",";
            if (trim($this->ac16_deptoresponsavel) == null) {
                $this->erro_sql = " Campo Departamento Respons�vel n�o informado.";
                $this->erro_campo = "ac16_deptoresponsavel";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_numeroprocesso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_numeroprocesso"])) {
            $sql  .= $virgula . " ac16_numeroprocesso = '$this->ac16_numeroprocesso' ";
            $virgula = ",";
        }
        if (trim($this->ac16_periodocomercial) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_periodocomercial"])) {
            $sql  .= $virgula . " ac16_periodocomercial = '$this->ac16_periodocomercial' ";
            $virgula = ",";
            // if(trim($this->ac16_periodocomercial) == null ){
            //     $this->erro_sql = " Campo Per�odo Comercial n�o informado.";
            //     $this->erro_campo = "ac16_periodocomercial";
            //     $this->erro_banco = "";
            //     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
            //     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
            //     $this->erro_status = "0";
            //     return false;
            // }
        }
        if (trim($this->ac16_qtdperiodo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_qtdperiodo"])) {
            if (trim($this->ac16_qtdperiodo) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ac16_qtdperiodo"])) {
                $this->ac16_qtdperiodo = "0";
            }
            $sql  .= $virgula . " ac16_qtdperiodo = $this->ac16_qtdperiodo ";
            $virgula = ",";
        }
        if (trim($this->ac16_tipounidtempoperiodo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempoperiodo"])) {
            if (trim($this->ac16_tipounidtempoperiodo) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempoperiodo"])) {
                $this->ac16_tipounidtempoperiodo = "0";
            }
            $sql  .= $virgula . " ac16_tipounidtempoperiodo = $this->ac16_tipounidtempoperiodo ";
            $virgula = ",";
        }

        if (trim(!empty($this->ac16_tipopagamento)) || isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipopagamento"])) {

            if (trim($this->ac16_tipopagamento) == "" || $this->ac16_tipopagamento == 0 && isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipopagamento"])) {
                $this->erro_sql = " Campo Tipo Pagamento n�o informado.";
                $this->erro_campo = "ac16_tipopagamento";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }

            $sql  .= $virgula . " ac16_tipopagamento = $this->ac16_tipopagamento ";
            $virgula = ",";

        }

        if (trim(!empty($this->ac16_tipopagamento)) && $this->ac16_tipopagamento == 2 || isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipopagamento"]) && $GLOBALS["HTTP_POST_VARS"]["ac16_tipopagamento"] == 2) {

            if (empty($this->ac16_numparcela)) {
                $this->erro_sql = " Campo N�mero de Parcela n�o informado.";
                $this->erro_campo = "ac16_numparcela";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }

            $sql  .= $virgula . " ac16_numparcela = $this->ac16_numparcela ";
            $virgula = ",";

            if (empty($this->ac16_numparcela)) {
                $this->erro_sql = " Campo Valor da Parcela n�o informado.";
                $this->erro_campo = "ac16_vlrparcela";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }

            $sql  .= $virgula . " ac16_vlrparcela = $this->ac16_vlrparcela ";
            $virgula = ",";

        } else if(trim(!empty($this->ac16_tipopagamento)) && $this->ac16_tipopagamento == 1 || isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipopagamento"]) && $GLOBALS["HTTP_POST_VARS"]["ac16_tipopagamento"] == 1) {
            $sql  .= $virgula . " ac16_numparcela = 1";

            if(!empty($this->ac16_vlrparcela)) {
                $sql  .= $virgula . " ac16_vlrparcela = $this->ac16_vlrparcela";
            }
            $virgula = ",";
        }

        if (trim(!empty($this->ac16_identificadorcipi)) || isset($GLOBALS["HTTP_POST_VARS"]["ac16_identificadorcipi"])) {
            $sql  .= $virgula . " ac16_identificadorcipi = '$this->ac16_identificadorcipi'";
            $virgula = ",";
        }

        if (trim(!empty($this->ac16_urlcipi)) || isset($GLOBALS["HTTP_POST_VARS"]["ac16_urlcipi"])) {

            if (strlen($this->ac16_urlcipi) < 8 || strlen($this->ac16_urlcipi) > 14) {
                if(strlen($this->ac16_urlcipi) < 8) {
                    $this->erro_sql = "Campo Url CIPI n�o pode ser menor que 8 caracteres.";
                } elseif (strlen($this->ac16_urlcipi) > 14) {
                    $this->erro_sql = "Campo Url CIPI n�o pode ser maior que 14 caracteres.";
                }

                $this->erro_campo = "ac16_urlcipi";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }

            $sql  .= $virgula . " ac16_urlcipi = '$this->ac16_urlcipi'";
            $virgula = ",";

        }

        if (trim(!empty($this->ac16_infcomplementares)) || isset($GLOBALS["HTTP_POST_VARS"]["ac16_infcomplementares"])) {
            $sql  .= $virgula . " ac16_infcomplementares ='$this->ac16_infcomplementares'";
            $virgula = ",";
        }

        if (trim($this->ac16_acordocategoria) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordocategoria"])) {
            if (trim($this->ac16_acordocategoria) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordocategoria"])) {
                $this->ac16_acordocategoria = "0";
            }
            $sql  .= $virgula . " ac16_acordocategoria = $this->ac16_acordocategoria ";
            $virgula = ",";
        }
        if (trim($this->ac16_acordoclassificacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordoclassificacao"])) {
            $sql  .= $virgula . " ac16_acordoclassificacao = $this->ac16_acordoclassificacao ";
            $virgula = ",";
            if (trim($this->ac16_acordoclassificacao) == null) {
                $this->erro_sql = " Campo Sequencial da Classifica��o do Contrato n�o informado.";
                $this->erro_campo = "ac16_acordoclassificacao";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_numeroacordo) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_numeroacordo"])) {
            if (trim($this->ac16_numeroacordo) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ac16_numeroacordo"])) {
                $this->ac16_numeroacordo = "0";
            }
            $sql  .= $virgula . " ac16_numeroacordo = $this->ac16_numeroacordo ";
            $virgula = ",";
        }
        if (trim($this->ac16_valor) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_valor"])) {
            if (trim($this->ac16_valor) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ac16_valor"])) {
                $this->ac16_valor = "0";
            }
            $sql  .= $virgula . " ac16_valor = $this->ac16_valor ";
            $virgula = ",";
        }
        if (
            !($this->ac16_valorrescisao === null)
            || isset($GLOBALS["HTTP_POST_VARS"]["ac16_valorrescisao"])
        ) {

            $this->ac16_valorrescisao = floatval($this->ac16_valorrescisao);
            $sql  .= $virgula . " ac16_valorrescisao = {$this->ac16_valorrescisao} ";
            $virgula = ",";
        }
        if (trim($this->ac16_tipoorigem) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipoorigem"])) {
            if (trim($this->ac16_tipoorigem) == "" && isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipoorigem"])) {
                $this->ac16_tipoorigem = "0";
            }
            $sql  .= $virgula . " ac16_tipoorigem = $this->ac16_tipoorigem ";
            $virgula = ",";
        }
        if (trim($this->ac16_formafornecimento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_formafornecimento"])) {
            $sql  .= $virgula . " ac16_formafornecimento = '$this->ac16_formafornecimento' ";
            $virgula = ",";
            if (trim($this->ac16_formafornecimento) == null) {
                $this->erro_sql = " Campo Forma de fornecimento do Contrato n�o informado.";
                $this->erro_campo = "ac16_formafornecimento";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->ac16_formapagamento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_formapagamento"])) {
            $sql  .= $virgula . " ac16_formapagamento = '$this->ac16_formapagamento' ";
            $virgula = ",";
            if (trim($this->ac16_formapagamento) == null) {
                $this->erro_sql = " Campo Forma de pagamento do Contrato n�o informado.";
                $this->erro_campo = "ac16_formapagamento";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if ($this->ac16_semvigencia != null && $this->ac16_semvigencia != "") {
            $sql  .= $virgula . " ac16_semvigencia = '$this->ac16_semvigencia' ";
            $virgula = ",";
        }

        if (trim($this->ac16_veiculodivulgacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_veiculodivulgacao"])) {
            $sql  .= $virgula . " ac16_veiculodivulgacao = '$this->ac16_veiculodivulgacao' ";
            $virgula = ",";
        }

        if (($this->ac16_licoutroorgao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_veiculodivulgacao"])) {
            if (($this->ac16_licoutroorgao) == null) {
                $sql  .= $virgula . " ac16_licoutroorgao = null ";
                $virgula = ",";
            } else {
                $sql  .= $virgula . " ac16_licoutroorgao = '$this->ac16_licoutroorgao' ";
                $virgula = ",";
            }
        }

        if (($this->ac16_adesaoregpreco) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_adesaoregpreco"])) {
            $sql  .= $virgula . " ac16_adesaoregpreco = '$this->ac16_adesaoregpreco' ";
            $virgula = ",";

            if (($this->ac16_adesaoregpreco) == null) {
                $this->erro_sql = " Campo ades�o de registro de pre�o n�o informado.";
                $this->erro_campo = "ac16_adesaoregpreco";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (($this->ac16_tipocadastro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipocadastro"])) {
            $sql  .= $virgula . " ac16_tipocadastro = $this->ac16_tipocadastro ";
            $virgula = ",";
            if (($this->ac16_tipocadastro) == null) {
                $this->erro_sql = " Campo Tipo Cadastro n�o informado.";
                $this->erro_campo = "ac16_tipocadastro";
                $this->erro_banco = "";
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if ($this->ac16_datareferencia != null && $this->ac16_datareferencia != "") {
            $sql  .= $virgula . " ac16_datareferencia = '$this->ac16_datareferencia' ";
            $virgula = ",";
        }

        if (trim($this->ac16_licitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_licitacao"])) {
            $sql .= $virgula . " ac16_licitacao = $this->ac16_licitacao ";
        }

        if (trim($this->ac16_providencia) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_providencia"])) {
            $sql .= $virgula . " ac16_providencia = $this->ac16_providencia ";
        }
        //echo $this->ac16_vigenciaindeterminada;
        if (trim($this->ac16_vigenciaindeterminada) != "" || isset($GLOBALS["HTTP_POST_VARS"]["ac16_vigenciaindeterminada"])) {
            if($this->ac16_vigenciaindeterminada != "null"){
                $sql .= $virgula . " ac16_vigenciaindeterminada = '$this->ac16_vigenciaindeterminada' ";
            }
        }

        $sql .= " where ";
        if ($ac16_sequencial != null) {
            $sql .= " ac16_sequencial = $this->ac16_sequencial";
        }

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {

            $resaco = $this->sql_record($this->sql_query_file($this->ac16_sequencial));
            if ($this->numrows > 0) {

                for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,16116,'$this->ac16_sequencial','A')");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_sequencial"]) || $this->ac16_sequencial != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16116,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_sequencial')) . "','$this->ac16_sequencial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordosituacao"]) || $this->ac16_acordosituacao != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16117,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_acordosituacao')) . "','$this->ac16_acordosituacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_coddepto"]) || $this->ac16_coddepto != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16118,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_coddepto')) . "','$this->ac16_coddepto'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_numero"]) || $this->ac16_numero != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16119,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_numero')) . "','$this->ac16_numero'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_anousu"]) || $this->ac16_anousu != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16120,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_anousu')) . "','$this->ac16_anousu'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_dataassinatura"]) || $this->ac16_dataassinatura != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16121,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_dataassinatura')) . "','$this->ac16_dataassinatura'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_contratado"]) || $this->ac16_contratado != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16122,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_contratado')) . "','$this->ac16_contratado'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_datainicio"]) || $this->ac16_datainicio != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16123,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_datainicio')) . "','$this->ac16_datainicio'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_datafim"]) || $this->ac16_datafim != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16124,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_datafim')) . "','$this->ac16_datafim'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_resumoobjeto"]) || $this->ac16_resumoobjeto != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16125,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_resumoobjeto')) . "','$this->ac16_resumoobjeto'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_objeto"]) || $this->ac16_objeto != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16126,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_objeto')) . "','$this->ac16_objeto'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_instit"]) || $this->ac16_instit != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16127,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_instit')) . "','$this->ac16_instit'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordocomissao"]) || $this->ac16_acordocomissao != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16129,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_acordocomissao')) . "','$this->ac16_acordocomissao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_lei"]) || $this->ac16_lei != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16130,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_lei')) . "','$this->ac16_lei'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordogrupo"]) || $this->ac16_acordogrupo != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16211,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_acordogrupo')) . "','$this->ac16_acordogrupo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_origem"]) || $this->ac16_origem != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16233,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_origem')) . "','$this->ac16_origem'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_qtdrenovacao"]) || $this->ac16_qtdrenovacao != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16660,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_qtdrenovacao')) . "','$this->ac16_qtdrenovacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempo"]) || $this->ac16_tipounidtempo != "")
                        $resac = db_query("insert into db_acount values($acount,2828,16659,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_tipounidtempo')) . "','$this->ac16_tipounidtempo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_deptoresponsavel"]) || $this->ac16_deptoresponsavel != "")
                        $resac = db_query("insert into db_acount values($acount,2828,18033,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_deptoresponsavel')) . "','$this->ac16_deptoresponsavel'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_numeroprocesso"]) || $this->ac16_numeroprocesso != "")
                        $resac = db_query("insert into db_acount values($acount,2828,18487,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_numeroprocesso')) . "','$this->ac16_numeroprocesso'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_periodocomercial"]) || $this->ac16_periodocomercial != "")
                        $resac = db_query("insert into db_acount values($acount,2828,19736,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_periodocomercial')) . "','$this->ac16_periodocomercial'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_qtdperiodo"]) || $this->ac16_qtdperiodo != "")
                        $resac = db_query("insert into db_acount values($acount,2828,19928,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_qtdperiodo')) . "','$this->ac16_qtdperiodo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_tipounidtempoperiodo"]) || $this->ac16_tipounidtempoperiodo != "")
                        $resac = db_query("insert into db_acount values($acount,2828,19927,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_tipounidtempoperiodo')) . "','$this->ac16_tipounidtempoperiodo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordocategoria"]) || $this->ac16_acordocategoria != "")
                        $resac = db_query("insert into db_acount values($acount,2828,19926,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_acordocategoria')) . "','$this->ac16_acordocategoria'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_acordoclassificacao"]) || $this->ac16_acordoclassificacao != "")
                        $resac = db_query("insert into db_acount values($acount,2828,20531,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_acordoclassificacao')) . "','$this->ac16_acordoclassificacao'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_numeroacordo"]) || $this->ac16_numeroacordo != "")
                        $resac = db_query("insert into db_acount values($acount,2828,20546,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_numeroacordo')) . "','$this->ac16_numeroacordo'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    if (isset($GLOBALS["HTTP_POST_VARS"]["ac16_valor"]) || $this->ac16_valor != "")
                        $resac = db_query("insert into db_acount values($acount,2828,20547,'" . AddSlashes(pg_result($resaco, $conresaco, 'ac16_valor')) . "','$this->ac16_valor'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   =  "Acordo nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->ac16_sequencial;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Acordo nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->ac16_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->ac16_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }
    // funcao para exclusao
    function excluir($ac16_sequencial = null, $dbwhere = null)
    {

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
            && ($lSessaoDesativarAccount === false))) {

            if ($dbwhere == null || $dbwhere == "") {

                $resaco = $this->sql_record($this->sql_query_file($ac16_sequencial));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {

                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

                    $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac  = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac  = db_query("insert into db_acountkey values($acount,16116,'$ac16_sequencial','E')");
                    $resac  = db_query("insert into db_acount values($acount,2828,16116,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_sequencial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16117,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_acordosituacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16118,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_coddepto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16119,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_numero')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16120,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16121,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_dataassinatura')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16122,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_contratado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16123,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_datainicio')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16124,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_datafim')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16125,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_resumoobjeto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16126,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_objeto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16127,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16129,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_acordocomissao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16130,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_lei')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16211,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_acordogrupo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16233,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_origem')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16660,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_qtdrenovacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,16659,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_tipounidtempo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,18033,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_deptoresponsavel')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,18487,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_numeroprocesso')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,19736,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_periodocomercial')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,19928,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_qtdperiodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,19927,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_tipounidtempoperiodo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,19926,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_acordocategoria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,20531,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_acordoclassificacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,20546,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_numeroacordo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac  = db_query("insert into db_acount values($acount,2828,20547,'','" . AddSlashes(pg_result($resaco, $iresaco, 'ac16_valor')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from acordo
 where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($ac16_sequencial != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " ac16_sequencial = $ac16_sequencial ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        /**
         * Altera��o Oc15013
         */
        if ($ac16_sequencial != "") {
            $sql3 = " delete from conlancamacordo where c87_acordo = $ac16_sequencial";
            $result2 = db_query($sql3);
            if ($result2 == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql   = "conlancamacordo n�o excluido. Exclus�o Abortada.\\n";
                $this->erro_sql .= "Valores : " . $ac16_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                $this->numrows_excluir = 0;
                return false;
            }
        }

        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql   = "Acordo nao Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_sql .= "Valores : " . $ac16_sequencial;
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Acordo nao Encontrado. Exclus�o n�o Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $ac16_sequencial;
                $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $ac16_sequencial;
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
            $this->erro_sql   = "Record Vazio na Tabela:acordo";
            $this->erro_msg   = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg   .=  str_replace('"', "", str_replace("'", "",  "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }
    // funcao do sql
    function sql_query($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from acordo ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
        $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
        $sql .= "      inner join db_depart as responsavel on responsavel.coddepto = acordo.ac16_deptoresponsavel";
        $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
        $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
        $sql .= "      left join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
        //$sql .= "      left  join acordocategoria  on  acordocategoria.ac50_sequencial = acordo.ac16_acordocategoria";
        //$sql .= "      inner join acordoclassificacao  on  acordoclassificacao.ac46_sequencial = acordo.ac16_acordoclassificacao";
        $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
        //$sql .= "      inner join db_datausuarios  on  db_datausuarios.id_usuario = db_depart.id_usuarioresp";
        $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
        $sql .= "      inner join acordotipo  on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
        //        $sql .= "      inner join cgm dpt on acordo.ac16_deptoresponsavel = dpt.z01_numcgm";
        //$sql .= "      inner join db_config  as a on   a.codigo = acordocomissao.ac08_instit";
        //$sql .= "      inner join acordocomissaotipo  on  acordocomissaotipo.ac43_sequencial = acordocomissao.ac08_acordocomissaotipo";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($ac16_sequencial != null) {
                $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
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

    function sql_query_vinculos($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from acordo ";
        $sql .= "      inner join cgm contratado           on  contratado.z01_numcgm = acordo.ac16_contratado";
        $sql .= "      inner join db_depart                on  db_depart.coddepto = acordo.ac16_coddepto";
        $sql .= "      inner join db_depart as responsavel on responsavel.coddepto = acordo.ac16_deptoresponsavel";
        $sql .= "      inner join acordogrupo              on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
        $sql .= "      inner join acordosituacao           on acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
        $sql .= "      left join acordocomissao            on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
        $sql .= "      inner join db_config                on  db_config.codigo = db_depart.instit";
        $sql .= "      inner join acordonatureza           on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
        $sql .= "      inner join acordotipo               on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
        $sql .= "      left join liclicita                 on  liclicita.l20_codigo = acordo.ac16_licitacao";
        $sql .= "      left join liclicitaoutrosorgaos     on liclicitaoutrosorgaos.lic211_sequencial  = acordo.ac16_licoutroorgao";
        $sql .= "      left join cgm orgao                 on orgao.z01_numcgm  = liclicitaoutrosorgaos.lic211_orgao";
        $sql .= "      left join adesaoregprecos           on adesaoregprecos.si06_sequencial  = acordo.ac16_adesaoregpreco";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($ac16_sequencial != null) {
                $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
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

    function sql_query_acordoscredenciamento($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from acordo ";
        $sql .= " inner join liclicita on liclicita.l20_codigo = ac16_licitacao";
        $sql .= " inner join cflicita on cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
        $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
        $sql .= "      inner join db_depart as responsavel on responsavel.coddepto = acordo.ac16_deptoresponsavel";
        $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
        $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
        $sql .= "      left join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
        //$sql .= "      left  join acordocategoria  on  acordocategoria.ac50_sequencial = acordo.ac16_acordocategoria";
        //$sql .= "      inner join acordoclassificacao  on  acordoclassificacao.ac46_sequencial = acordo.ac16_acordoclassificacao";
        $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
        //$sql .= "      inner join db_datausuarios  on  db_datausuarios.id_usuario = db_depart.id_usuarioresp";
        $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
        $sql .= "      inner join acordotipo  on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
        //        $sql .= "      inner join cgm dpt on acordo.ac16_deptoresponsavel = dpt.z01_numcgm";
        //$sql .= "      inner join db_config  as a on   a.codigo = acordocomissao.ac08_instit";
        //$sql .= "      inner join acordocomissaotipo  on  acordocomissaotipo.ac43_sequencial = acordocomissao.ac08_acordocomissaotipo";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($ac16_sequencial != null) {
                $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere ";
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
    function sql_query_file($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from acordo ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($ac16_sequencial != null) {
                $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
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
    function sql_queryLicitacoesVinculadas($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "")
    {

        $sSql = "";
        if (!empty($iCodigoAcordo)) {
            $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
        }

        $sSql .= "select distinct {$sCampos}";
        $sSql .= "       from acordo";
        $sSql .= "            inner join acordoposicao    on acordoposicao.ac26_acordo        = acordo.ac16_sequencial";
        $sSql .= "            inner join liclicita ON liclicita.l20_codigo = acordo.ac16_licitacao";
        $sSql .= "            inner join cgm on z01_numcgm = ac16_contratado  ";
        $sSql .= "  where 1 = 1 ";
        $sSql .= " {$sWhere} {$sOrder} ";

        return $sSql;
    }

    function sql_queryAdesaoVinculadas($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "")
    {

        $sSql = "";
        if (!empty($iCodigoAcordo)) {
            $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
        }

        $sSql .= "select distinct {$sCampos}";
        $sSql .= "       from acordo";
        $sSql .= "            inner join acordoposicao    on acordoposicao.ac26_acordo        = acordo.ac16_sequencial";
        $sSql .= "            inner join adesaoregprecos ON adesaoregprecos.si06_sequencial = acordo.ac16_adesaoregpreco";
        $sSql .= "  where 1 = 1 ";
        $sSql .= " {$sWhere} {$sOrder} ";

        return $sSql;
    }

    function sql_queryLicitacoesOutrosOrgaosVinculadas($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "")
    {

        $sSql = "";
        if (!empty($iCodigoAcordo)) {
            $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
        }

        $sSql .= "select distinct {$sCampos}";
        $sSql .= "       from acordo";
        $sSql .= "            inner join acordoposicao    on acordoposicao.ac26_acordo        = acordo.ac16_sequencial";
        $sSql .= "            INNER JOIN liclicitaoutrosorgaos ON liclicitaoutrosorgaos.lic211_sequencial = acordo.ac16_licoutroorgao";
        $sSql .= "  where 1 = 1 ";
        $sSql .= " {$sWhere} {$sOrder} ";

        return $sSql;
    }

    function sql_query_completo($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from acordo ";
        $sql .= "      inner join cgm contratado on contratado.z01_numcgm          = acordo.ac16_contratado";
        $sql .= "      inner join db_depart      on db_depart.coddepto             = acordo.ac16_coddepto
                       inner join db_depart depresp on depresp.coddepto = acordo.ac16_deptoresponsavel";
        $sql .= "      inner join acordogrupo    on acordogrupo.ac02_sequencial    = acordo.ac16_acordogrupo";
        $sql .= "      inner join acordosituacao on acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
        $sql .= "      left join acordocomissao on acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
        $sql .= "      inner join acordonatureza on acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
        $sql .= "      inner join acordotipo     on acordotipo.ac04_sequencial     = acordogrupo.ac02_acordotipo";
        $sql .= "      inner join acordoorigem   on acordoorigem.ac28_sequencial   = acordo.ac16_origem";
        $sql .= "      left  join acordoleis   on acordo.ac16_lei   = acordoleis.ac54_sequencial";
        $sql .= "
            LEFT JOIN (
                select max(ac26_sequencial) as ac26_sequencial,ac26_acordo,ac26_data
                from acordoposicao
                    GROUP BY ac26_acordo, ac26_data
            ) acordoposicao ON acordoposicao.ac26_acordo = ac16_sequencial
            LEFT JOIN acordoitem on ac20_acordoposicao=ac26_sequencial
            LEFT JOIN acordopcprocitem on ac23_acordoitem=ac20_sequencial
            LEFT JOIN pcprocitem on pc81_codprocitem=ac23_pcprocitem
            LEFT JOIN solicitem on pc11_codigo=pc81_solicitem
            LEFT JOIN solicita on pc10_numero=pc11_numero
            LEFT JOIN solicitemvinculo on pc55_solicitemfilho=pc11_codigo
            LEFT JOIN solicitem pai on pai.pc11_numero = pc55_solicitempai
            LEFT JOIN pcprocitem pclic on pclic.pc81_solicitem=pai.pc11_codigo
            LEFT JOIN liclicitem on l21_codpcprocitem=pclic.pc81_codprocitem
            LEFT JOIN liclicita on l20_codigo=l21_codliclicita
            LEFT JOIN cflicita on l03_codigo=l20_codtipocom
            LEFT JOIN pctipocompra on pc50_codcom=l03_codcom
            LEFT JOIN providencia on providencia.codigo=ac16_providencia";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($ac16_sequencial != null) {
                $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
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
    function sql_queryProcessosVinculados($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "")
    {

        $sSql = "";
        if (!empty($iCodigoAcordo)) {
            $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
        }

        $sSql .= "select distinct {$sCampos}";
        $sSql .= "       from acordo";
        $sSql .= "            inner join acordoposicao    on acordoposicao.ac26_acordo        = acordo.ac16_sequencial";
        $sSql .= "            inner join acordoitem       on acordoitem.ac20_acordoposicao    = acordoposicao.ac26_sequencial";
        $sSql .= "            inner join acordopcprocitem on acordopcprocitem.ac23_acordoitem = acordoitem.ac20_sequencial";
        $sSql .= "            inner join pcprocitem       on pcprocitem.pc81_codprocitem      = acordopcprocitem.ac23_pcprocitem";
        $sSql .= "            inner join pcproc           on pcproc.pc80_codproc              = pcprocitem.pc81_codproc";
        $sSql .= "  where 1 = 1 ";
        $sSql .= " {$sWhere} {$sOrder} ";

        return $sSql;
    }
    function sql_query_acordoitemexecutado($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "", $apostilamento = false)
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
        $sql .= " from acordo ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
        $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
        $sql .= "      inner join db_depart as responsavel on responsavel.coddepto = acordo.ac16_deptoresponsavel";
        $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
        $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
        $sql .= "      left join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
        $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
        $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
        $sql .= "      inner join acordotipo  on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
        $sql .= "      inner join acordoposicao       on acordoposicao.ac26_acordo           = acordo.ac16_sequencial";
        $sql .= "      left  join acordoitem          on acordoitem.ac20_acordoposicao       = acordoposicao.ac26_sequencial";
        $sql .= "      left  join acordoorigem        on acordoorigem.ac28_sequencial        = acordo.ac16_origem";
        $sql .= "      left  join acordomovimentacao  on acordomovimentacao.ac10_acordo      = acordo.ac16_sequencial";

        if ($apostilamento) {
            $sql .= " INNER JOIN apostilamento ON apostilamento.si03_acordo = acordo.ac16_sequencial ";
        }

        $sql2 = "";
        if ($dbwhere == "") {
            if ($ac16_sequencial != null) {
                $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
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
    function sql_query_empenho($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql2 = '';
        if ($dbwhere == "") {
            if ($ac16_sequencial != null) {
                $sql2 = " where acordo.ac16_sequencial = $ac16_sequencial ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sSqlAutorizacoes  =  "select  $campos ";
        $sSqlAutorizacoes .=  "   from acordoposicao ";
        $sSqlAutorizacoes .=  "        inner join acordoitem          on ac20_acordoposicao = ac26_sequencial ";
        $sSqlAutorizacoes .=  "        inner join acordo              on ac26_acordo        = ac16_sequencial ";
        $sSqlAutorizacoes .=  "        inner join db_depart           on ac16_deptoresponsavel = coddepto ";
        $sSqlAutorizacoes .=  "        inner join cgm                 on ac16_contratado       = z01_numcgm ";
        $sSqlAutorizacoes .=  "        inner join acordoitemexecutado on ac20_sequencial    = ac29_acordoitem ";
        $sSqlAutorizacoes .=  "        inner join acordoitemexecutadoempautitem on ac29_sequencial = ac19_acordoitemexecutado ";
        $sSqlAutorizacoes .=  "        inner join empautitem on e55_sequen = ac19_sequen and ac19_autori = e55_autori ";
        $sSqlAutorizacoes .=  "        inner join empautoriza on e54_autori = e55_autori ";
        $sSqlAutorizacoes .=  "        left join empempaut on e61_autori = e54_autori ";
        $sSqlAutorizacoes .=  "        left join empempenho on e61_numemp = e60_numemp {$sql2}";

        /**
         * pesquisa os empenhos vicnulados por baixa Manual
         */
        $sSqlAutorizacoes .=  " UNION ";
        $sSqlAutorizacoes .=  "select  {$campos}";
        $sSqlAutorizacoes .=  "   from acordoposicao ";
        $sSqlAutorizacoes .=  "        inner join acordoitem          on ac20_acordoposicao = ac26_sequencial ";
        $sSqlAutorizacoes .=  "        inner join acordo              on ac26_acordo        = ac16_sequencial ";
        $sSqlAutorizacoes .=  "        inner join db_depart           on ac16_deptoresponsavel = coddepto ";
        $sSqlAutorizacoes .=  "        inner join cgm                 on ac16_contratado       = z01_numcgm ";
        $sSqlAutorizacoes .=  "        inner join acordoitemexecutado on ac20_sequencial    = ac29_acordoitem ";
        $sSqlAutorizacoes .=  "        inner join acordoitemexecutadoperiodo on ac29_sequencial = ac38_acordoitemexecutado";
        $sSqlAutorizacoes .=  "        inner join acordoitemexecutadoempenho on  ac38_sequencial = ac39_acordoitemexecutadoperiodo";
        $sSqlAutorizacoes .=  "        inner join empempenho    on ac39_numemp = e60_numemp ";
        $sSqlAutorizacoes .=  "        left join empempaut      on e60_numemp  = e61_numemp ";
        $sSqlAutorizacoes .=  "        inner join empautoriza   on e54_autori  = e61_autori ";
        $sSqlAutorizacoes .=  "  {$sql2} ";


        if ($ordem != null) {

            $sSqlAutorizacoes .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {

                $sSqlAutorizacoes .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sSqlAutorizacoes;
    }
    function sql_queryEmpenhosVinculados($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "")
    {

        $sSql = "";
        if (!empty($iCodigoAcordo)) {
            $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
        }

        $sSql .= "select  distinct {$sCampos}";
        $sSql .= "       from acordo";
        $sSql .= "            inner join acordoposicao    on acordoposicao.ac26_acordo        = acordo.ac16_sequencial";
        $sSql .= "            inner join acordoitem       on acordoitem.ac20_acordoposicao    = acordoposicao.ac26_sequencial";
        $sSql .= "            inner join acordoempempitem on acordoempempitem.ac44_acordoitem = acordoitem.ac20_sequencial";
        $sSql .= "            inner join empempitem       on empempitem.e62_sequencial        = acordoempempitem.ac44_empempitem";
        $sSql .= "            inner join empempenho       on empempenho.e60_numemp            = empempitem.e62_numemp";
        $sSql .= "            inner join empempenhocontrato on acordo.ac16_sequencial           = empempenhocontrato.e100_acordo ";
        $sSql .= "  where 1 = 1 ";
        $sSql .= " {$sWhere} {$sOrder} ";

        return $sSql;
    }
    function sql_queryEmpenhosVinculadosContrato($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "")
    {

        $sSql = "";
        if (!empty($iCodigoAcordo)) {
            $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
        }

        $sSql .= "select {$sCampos}";
        $sSql .= "  from acordo";
        $sSql .= "       inner join acordoposicao      on acordo.ac16_sequencial = acordoposicao.ac26_acordo";
        $sSql .= "       inner join empempenhocontrato on acordo.ac16_sequencial = empempenhocontrato.e100_acordo";
        $sSql .= "       inner join empempenho         on empempenho.e60_numemp = empempenhocontrato.e100_numemp";
        $sSql .= "       left  join acordoitem          on acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao";
        $sSql .= "       left  join acordoempempitem    on acordoitem.ac20_sequencial = acordoempempitem.ac44_acordoitem";
        $sSql .= "       left  join empempitem          on acordoempempitem.ac44_empempitem = empempitem.e62_sequencial";
        $sSql .= "  where 1 = 1 ";
        $sSql .= " {$sWhere} {$sOrder} ";

        return $sSql;
    }

    function sql_queryItensEmpenhoContrato($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "")
    {

        $sSql = "";
        if (!empty($iCodigoAcordo)) {
            $sWhere .= " and acordo.ac16_sequencial = {$iCodigoAcordo} ";
        }

        $sSql .= " select {$sCampos}                                                                                   ";
        $sSql .= "   from acordo                                                                                       ";
        $sSql .= "        inner join acordoposicao on acordo.ac16_sequencial        = acordoposicao.ac26_acordo        ";
        $sSql .= "        inner join acordoitem    on acordoposicao.ac26_sequencial = acordoitem.ac20_acordoposicao    ";
        $sSql .= "        inner join acordoempempitem on acordoitem.ac20_sequencial = acordoempempitem.ac44_acordoitem ";
        $sSql .= "        inner join empempitem       on acordoempempitem.ac44_empempitem = empempitem.e62_sequencial  ";
        $sSql .= "        inner join empempenho       on empempenho.e60_numemp = empempitem.e62_numemp                 ";
        $sSql .= "        inner join empempenhocontrato on acordo.ac16_sequencial = empempenhocontrato.e100_acordo     ";
        $sSql .= "                                     and empempenho.e60_numemp  = empempenhocontrato.e100_numemp     ";

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere} ";
        }

        if (!empty($sOrder)) {
            $sSql .= " order by {$sOrder} ";
        }

        return $sSql;
    }

    function sql_query_completo_posicao($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from acordo ";
        $sql .= "      inner join cgm contratado  on  contratado.z01_numcgm          = acordo.ac16_contratado          ";
        $sql .= "      inner join db_depart       on  db_depart.coddepto             = acordo.ac16_coddepto            ";
        $sql .= "      inner join acordogrupo     on  acordogrupo.ac02_sequencial    = acordo.ac16_acordogrupo         ";
        $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao      ";
        $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao      ";
        $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza ";
        $sql .= "      inner join acordotipo      on  acordotipo.ac04_sequencial     = acordogrupo.ac02_acordotipo     ";
        $sql .= "      inner join cgm gestor      on  gestor.z01_numcgm              = acordo.ac16_contratado          ";
        $sql .= "      inner join acordoposicao   on  acordoposicao.ac26_acordo      = acordo.ac16_sequencial          ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($ac16_sequencial != null) {
                $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
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
    public function sql_movimentacao_acordo_origem_empenho($ac16_sequencial = null, $sCampos = "*", $sOrder = null, $dbwhere = "")
    {

        $sSql = "select ";
        if ($sCampos != "*") {

            $sCampos_sql = explode("#", $sCampos);
            $virgula    = "";
            for ($i = 0; $i < sizeof($sCampos_sql); $i++) {

                $sSql   .= $virgula . $sCampos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sSql .= $sCampos;
        }

        $sSql .= " from acordo";
        $sSql .= "      inner join empempenhocontrato on empempenhocontrato.e100_acordo = acordo.ac16_sequencial";
        $sSql .= "      inner join empempenho         on empempenhocontrato.e100_numemp = empempenho.e60_numemp";
        $sSql .= "      inner join cgm                on acordo.ac16_contratado         = cgm.z01_numcgm";

        $sSql2 = "";
        if ($dbwhere == "") {

            if ($ac16_sequencial != null) {
                $sSql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
            }
        } else if ($dbwhere != "") {
            $sSql2 = " where $dbwhere";
        }

        $sSql .= $sSql2;
        if ($sOrder != null) {

            $sSql       .= " order by ";
            $sCampos_sql = explode("#", $sOrder);
            $virgula     = "";
            for ($i = 0; $i < sizeof($sCampos_sql); $i++) {

                $sSql   .= $virgula . $sCampos_sql[$i];
                $virgula = ",";
            }
        }
        return $sSql;
    }
    function sql_movimentacao_acordo_empenhado($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql = "select ";
        if ($campos != "*") {

            $campos_sql = explode("#", $campos);
            $virgula    = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {

                $sql    .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $campos;
        }

        $sql .= " from  acordo";
        $sql .= " inner join acordoempautoriza on acordo.ac16_sequencial = acordoempautoriza.ac45_acordo";
        $sql .= " inner join empempaut         on empempaut.e61_autori   = acordoempautoriza.ac45_empautoriza";
        $sql .= " inner join empempenho        on empempenho.e60_numemp  =  empempaut.e61_numemp";
        $sql .= " inner join cgm               on acordo.ac16_contratado = cgm.z01_numcgm ";

        $sql2 = "";
        if ($dbwhere == "") {

            if ($ac16_sequencial != null) {
                $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
            }
        } else if ($dbwhere != "") {

            $sql2 = " where $dbwhere";
        }

        $sql .= $sql2;
        if ($ordem != null) {

            $sql       .= " order by ";
            $campos_sql = explode("#", $ordem);
            $virgula    = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {

                $sql    .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        }
        return $sql;
    }
    function sql_movimentacao_acordo_origem_manual($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from acordo";
        $sql .= "      inner join acordoposicao              on acordo.ac16_sequencial                     = acordoposicao.ac26_acordo";
        $sql .= "      inner join acordoitem                 on acordoposicao.ac26_sequencial              = acordoitem.ac20_acordoposicao";
        $sql .= "      inner join acordoitemexecutado        on acordoitem.ac20_sequencial                 = acordoitemexecutado.ac29_acordoitem";
        $sql .= "      inner join acordoitemprevisao         on acordoitem.ac20_sequencial                 = acordoitemprevisao.ac37_acordoitem";
        $sql .= "      inner join acordoitemexecutadoperiodo on acordoitemexecutado.ac29_sequencial        = acordoitemexecutadoperiodo.ac38_acordoitemexecutado";
        $sql .= "                                           and acordoitemprevisao.ac37_sequencial         = acordoitemexecutadoperiodo.ac38_acordoitemprevisao";
        $sql .= "      inner join acordoitemexecutadoempenho on acordoitemexecutadoperiodo.ac38_sequencial = acordoitemexecutadoempenho.ac39_acordoitemexecutadoperiodo";
        $sql .= "      inner join empempenho                 on acordoitemexecutadoempenho.ac39_numemp     = empempenho.e60_numemp";
        $sql .= "      inner join cgm                        on cgm.z01_numcgm                             = acordo.ac16_contratado";
        $sql .= "      inner join acordoorigem               on acordoorigem.ac28_sequencial               = acordo.ac16_origem";
        $sql .= "                                           and acordoorigem.ac28_sequencial               = 3";

        $sql2 = "";
        if ($dbwhere == "") {

            if ($ac16_sequencial != null) {
                $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
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
     * Retorna os dados dos acordos para a integra��o com o portal da transparencia
     *
     * @param  string $sCampos
     * @param  string $sOrdem
     * @param  string $sWhere
     * @return String
     */
    public function sql_query_transparencia($sCampos = "*", $sOrdem = null, $sWhere = "")
    {

        $sSql  = "select {$sCampos} \n";
        $sSql .= "  from acordo                                                            \n";
        $sSql .= "       left join acordosituacao on ac17_sequencial = ac16_acordosituacao \n";
        $sSql .= "       left join acordogrupo on ac02_sequencial = ac16_acordogrupo       \n";
        $sSql .= "       left join acordocomissao on ac08_sequencial = ac16_acordocomissao \n";
        $sSql .= "       left join cgm on z01_numcgm = ac16_contratado                     \n";

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere} \n";
        }

        if (!empty($sOrdem)) {
            $sSql .= " order by {$sOrdem} ";
        }

        return $sSql;
    }

    /**
     * query para acordos que tenham sido paralisados e retivados
     * @param string $ac16_sequencial
     * @param string $campos
     * @param string $ordem
     * @param string $dbwhere
     * @return string
     */
    function sql_query_acordoReativado($ac16_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from acordo ";
        $sql .= "      inner join cgm  on  cgm.z01_numcgm = acordo.ac16_contratado";
        $sql .= "      inner join db_depart  on  db_depart.coddepto = acordo.ac16_coddepto";
        $sql .= "      inner join acordogrupo  on  acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo";
        $sql .= "      inner join acordosituacao  on  acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao";
        $sql .= "      inner join acordocomissao  on  acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao";
        $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
        $sql .= "      inner join acordonatureza  on  acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza";
        $sql .= "      inner join acordotipo  on  acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo";
        $sql .= "      inner join acordoposicao       on acordoposicao.ac26_acordo           = acordo.ac16_sequencial";
        $sql .= "      left  join acordoitem          on acordoitem.ac20_acordoposicao       = acordoposicao.ac26_sequencial";
        $sql .= "      left  join acordoorigem        on acordoorigem.ac28_sequencial        = acordo.ac16_origem";
        $sql .= "      inner join acordoposicaoperiodo on acordoposicao.ac26_sequencial = acordoposicaoperiodo.ac36_acordoposicao ";
        $sql .= "      inner join acordoparalisacaoperiodo on acordoposicaoperiodo.ac36_sequencial = acordoparalisacaoperiodo.ac49_acordoposicaoperiodo";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($ac16_sequencial != null) {
                $sql2 .= " where acordo.ac16_sequencial = $ac16_sequencial ";
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
     * Busca empenhos que foram vinculados ao acordo ou pela execu��o do item
     *
     * @param integer $iCodigoAcordo
     * @param string $sCampos
     * @param string $sOrdem
     * @param string $sWhere
     * @return string
     */
    function sql_queryDadosMapaExecucao($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "")
    {

        $sSql  = "select {$sCampos}";
        $sSql .= "  from acordo";
        $sSql .= "       inner join acordoposicao       on ac26_acordo           = ac16_sequencial     ";
        $sSql .= "       inner join acordoitem          on ac20_acordoposicao    = ac26_sequencial     ";
        $sSql .= "       inner join acordoitemexecutado on ac20_sequencial       = ac29_acordoitem     ";
        $sSql .= "       inner join acordosituacao      on ac17_sequencial       = ac16_acordosituacao ";
        $sSql .= "       inner join db_depart           on ac16_deptoresponsavel = coddepto            ";
        $sSql .= "       inner join acordoempempitem    on ac20_sequencial       = ac44_acordoitem     ";
        $sSql .= "       inner join empempitem          on e62_sequencial        = ac44_empempitem     ";
        $sSql .= "       inner join empempenho          on e62_numemp            = e60_numemp          ";
        $sSql .= "       inner join acordoorigem        on ac28_sequencial       = ac16_origem         ";

        if (!empty($iCodigoAcordo)) {

            if (!empty($sWhere)) {
                $sWhere .= " and ";
            }

            $sWhere .= " ac16_sequencial = {$iCodigoAcordo} ";
        }

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere} ";
        }

        if (!empty($sOrder)) {
            $sSql .= " order by {$sOrder} ";
        }

        return $sSql;
    }

    function sql_query_movimentacao_financeira($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "")
    {

        $sSql  = "select {$sCampos}";
        $sSql .= "  from acordo";
        $sSql .= "       inner join acordoposicao       on ac26_acordo           = ac16_sequencial     ";
        $sSql .= "       inner join acordoitem          on ac20_acordoposicao    = ac26_sequencial     ";
        $sSql .= "       inner join acordosituacao      on ac17_sequencial       = ac16_acordosituacao ";
        $sSql .= "       inner join db_depart           on ac16_deptoresponsavel = coddepto            ";
        $sSql .= "       inner join acordoempempitem    on ac20_sequencial       = ac44_acordoitem     ";
        $sSql .= "       inner join empempitem          on e62_sequencial        = ac44_empempitem     ";
        $sSql .= "       inner join empempenho          on e62_numemp            = e60_numemp          ";
        $sSql .= "       inner join acordoorigem        on ac28_sequencial       = ac16_origem         ";

        if (!empty($iCodigoAcordo)) {

            if (!empty($sWhere)) {
                $sWhere .= " and ";
            }

            $sWhere .= " ac16_sequencial = {$iCodigoAcordo} ";
        }

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere} ";
        }

        if (!empty($sOrder)) {
            $sSql .= " order by {$sOrder} ";
        }

        return $sSql;
    }

    function sql_query_movimentacao_empenho($iCodigoAcordo = null, $sCampos = "*", $sOrder = null, $sWhere = "")
    {

        $sSql  = "select {$sCampos}";
        $sSql .= "  from acordo";
        $sSql .= "       inner join acordosituacao      on ac17_sequencial       = ac16_acordosituacao ";
        $sSql .= "       inner join db_depart           on ac16_deptoresponsavel = coddepto            ";
        $sSql .= "       inner join empempenhocontrato  on ac16_sequencial        = e100_acordo     ";
        $sSql .= "       inner join empempenho          on e100_numemp            = e60_numemp          ";
        $sSql .= "       inner join acordoorigem        on ac28_sequencial       = ac16_origem         ";
        $sSql .= "       left  join empnota             on empnota.e69_numemp = empempenho.e60_numemp ";
        $sSql .= "       left  join empnotaele          on empnotaele.e70_codnota = empnota.e69_codnota ";
        $sSql .= "       left  join empnotaitem         on empnotaitem.e72_codnota = empnota.e69_codnota";
        $sSql .= "       left  join pagordemnota        on pagordemnota.e71_codnota = empnota.e69_codnota";
        $sSql .= "       left  join pagordem            on pagordem.e50_codord = pagordemnota.e71_codord";
        $sSql .= "       left  join pagordemele         on pagordemele.e53_codord = pagordem.e50_codord";
        $sSql .= "       left  join empnotaord          on empnotaord.m72_codnota = empnota.e69_codnota";
        $sSql .= "       left  join matordem            on matordem.m51_codordem = empnotaord.m72_codordem";

        if (!empty($iCodigoAcordo)) {

            if (!empty($sWhere)) {
                $sWhere .= " and ";
            }

            $sWhere .= " ac16_sequencial = {$iCodigoAcordo} ";
        }

        if (!empty($sWhere)) {
            $sSql .= " where {$sWhere} ";
        }

        if (!empty($sOrder)) {
            $sSql .= " order by {$sOrder} ";
        }

        return $sSql;
    }

    function sql_query_sequencial_acordo($si06_sequencial)
    {

        $sSql  = "select ac16_sequencial";
        $sSql .= "  from acordo";
        $sSql .= "  where ac16_adesaoregpreco = $si06_sequencial";

        return $sSql;
    }

    /**
     * Apaga depend�ncias para apagar o acordo.
     * Tabelas relacionadas:
     * - orcreservaaut
     * - empautorizaprocesso
     * - empautidot
     * - empautitempcprocitem
     * - acordoitemexecutadoempautitem
     * - empautitem
     * - empempaut
     * - acordoempautoriza
     * - empautoriza
     * - acordoitemexecutado
     * - acordoitemexecutadodotacao
     *
     * @param int $iAcordo
     */
    public function apagaDependencias($iAcordo = 0)
    {
        if (empty($iAcordo)) {
            throw new BusinessException("Acordo inexistente");
        }

        $sSql = "
    CREATE TEMP TABLE contratos_excluir ON COMMIT DROP AS
    SELECT acordoempautoriza.ac45_acordo AS contrato,
    acordoempautoriza.ac45_empautoriza AS autorizacao,
    acordoitemexecutado.ac29_acordoitem AS itens_contrato
    FROM acordo
    JOIN acordoempautoriza ON ac45_acordo = ac16_sequencial
    JOIN acordoposicao ON ac26_acordo = ac16_sequencial
    JOIN acordoitem ON ac26_sequencial = ac20_acordoposicao
    JOIN acordoitemexecutado ON ac29_acordoitem = ac20_sequencial
    WHERE acordoempautoriza.ac45_acordo IN ({$iAcordo});

    DELETE FROM orcreservaaut
    WHERE o83_autori IN
    (SELECT autorizacao FROM contratos_excluir);

    DELETE FROM empautorizaprocesso
    WHERE e150_empautoriza IN (SELECT autorizacao FROM contratos_excluir);

    DELETE FROM empautidot
    WHERE e56_autori IN (SELECT autorizacao FROM contratos_excluir);

    DELETE FROM empautitempcprocitem
    WHERE e73_autori IN (SELECT autorizacao FROM contratos_excluir);

    DELETE FROM acordoitemexecutadoempautitem
    WHERE ac19_autori IN (SELECT autorizacao FROM contratos_excluir);

    DELETE FROM empautitem
    WHERE e55_autori IN (SELECT autorizacao FROM contratos_excluir);

    DELETE FROM empempaut
    WHERE e61_autori IN (SELECT autorizacao FROM contratos_excluir);

    DELETE FROM acordoempautoriza
    WHERE ac45_empautoriza IN (SELECT autorizacao FROM contratos_excluir);

    DELETE FROM empautoriza
    WHERE e54_autori IN (SELECT autorizacao FROM contratos_excluir);

    DELETE FROM acordoitemexecutado
    WHERE ac29_acordoitem IN (SELECT itens_contrato FROM contratos_excluir);

    DELETE FROM acordoitemexecutadodotacao
    WHERE ac32_acordoitem IN (SELECT itens_contrato FROM contratos_excluir);
    ";

        $rsExec = db_query($sSql);

        return $rsExec == false ? false : true;
    }

    function sql_query_lancamentos_empenhocontrato($sCampos = "*", $sEmpenho = null)
    {

        $sWhere = " WHERE 1 = 1 ";

        if (!empty($sEmpenho)) {
            $sWhere .= " AND c75_numemp = {$sEmpenho} ";
        }

        $sSql = " SELECT DISTINCT {$sCampos}
    FROM empempenho
    JOIN conlancamemp ON c75_numemp = e60_numemp
    JOIN conlancamdoc ON c71_codlan = c75_codlan
    {$sWhere} ";

        return $sSql;
    }

    function sql_query_lancamentos_contrato($sCampos = "*", $iAcordo = null)
    {

        $sWhere = " WHERE 1 = 1 ";

        if (!empty($iAcordo)) {
            $sWhere .= " AND c87_acordo = {$iAcordo} ";
        }

        $sSql = " SELECT DISTINCT {$sCampos}
                FROM conlancam
                JOIN conlancamacordo ON c87_codlan = c70_codlan
                JOIN conlancamdoc ON c71_codlan = c87_codlan
                {$sWhere} ";

        return $sSql;
    }

    /**
     * Retorna toda a movimenta��o do acordo com seus saldo.
     * @param $iAcordo
     * @return string
     */

    function sql_query_todoacordo($iAcordo, $iPosicao, $where)
    {
        $sSql = "SELECT DISTINCT ac26_acordoposicaotipo,
                ac20_ordem,
                pc01_codmater,
                pc01_descrmater,
                ac20_quantidade AS qtdcontratada,
                SUM(
                        (SELECT coalesce(SUM(e62_quant),0) AS emp
                         FROM empempitem
                         WHERE e62_numemp = e60_numemp
                             AND e62_item = pc01_codmater)) AS quantidadeempenhada,
                ac20_valorunitario AS valorunitario,
                SUM(
                        (SELECT e62_vltot
                         FROM empempitem
                         WHERE e62_numemp = e60_numemp
                             AND e62_item = pc01_codmater)) AS valortotalemp,
                SUM(
                        (SELECT coalesce(SUM(e37_qtd),0)
                         FROM empanuladoitem
                         WHERE e37_empanulado = e94_codanu)) AS qtdanulado,
                SUM(
                        (SELECT coalesce(SUM(e94_valor),0)
                         FROM empanulado
                         WHERE e94_numemp = e60_numemp)) AS valoranulado,
                SUM(
                        (SELECT e72_qtd
                         FROM empnotaitem
                         WHERE e72_empempitem = e62_sequencial)) AS quantidadeliquidada,
                SUM(
                        (SELECT e72_vlrliq
                         FROM empnotaitem
                         WHERE e72_empempitem = e62_sequencial)) AS valortotalliquidado,
                SUM(
                        (SELECT coalesce(SUM(e72_vlranu),0) AS anu
                         FROM empnotaitem
                         WHERE e72_empempitem = e62_sequencial)) AS valoranuladoliq,
                SUM(
                        (SELECT coalesce(SUM(m52_quant),0) AS qtd
                         FROM matordemitem
                         WHERE m52_codordem = m51_codordem)) AS qtdemoc,
                SUM(
                        (SELECT coalesce(SUM(m52_valor),0) AS vlr
                         FROM matordemitem
                         WHERE m52_codordem = m51_codordem)) AS vlremoc
            FROM acordo
            INNER JOIN acordoposicao ON ac26_acordo = ac16_sequencial
            INNER JOIN acordoitem ON ac20_acordoposicao = ac26_sequencial
            INNER JOIN pcmater ON ac20_pcmater = pc01_codmater
            LEFT JOIN acordoitemexecutado ON ac29_acordoitem = ac20_sequencial
            LEFT JOIN acordoitemexecutadoempautitem ON ac19_acordoitemexecutado = ac29_sequencial
            LEFT JOIN empautitem ON (ac19_autori,
                                     ac19_sequen) = (e55_autori,
                                                     e55_sequen)
            AND e55_item = pcmater.pc01_codmater
            LEFT JOIN empautoriza ON e54_autori = e55_autori
            LEFT JOIN empempaut ON e61_autori = e54_autori
            LEFT JOIN empempenho ON e60_numemp = e61_numemp
            LEFT JOIN empanulado ON e94_numemp = e60_numemp
            LEFT JOIN empanuladoitem ON e37_empanulado = e94_codanu
            LEFT JOIN empempitem ON e60_numemp =e62_numemp
            AND e62_item = pcmater.pc01_codmater
            LEFT JOIN empnotaitem ON e72_empempitem=e62_sequencial
            LEFT JOIN empnota ON e69_codnota = e72_codnota
            LEFT JOIN empnotaord ON m72_codnota = e69_codnota
            LEFT JOIN matordem ON m51_codordem = m72_codordem
            LEFT JOIN matordemitem ON m52_codordem = m51_codordem
            INNER JOIN cgm ON z01_numcgm = ac16_contratado
            WHERE ac16_sequencial = $iAcordo
                AND ac26_sequencial = $iPosicao
                AND ac16_origem IN (1,2,3)
                $where
            GROUP BY ac20_ordem,
                     pc01_codmater,
                     ac20_quantidade,
                     valorunitario,
                     ac26_acordoposicaotipo";

        return $sSql;
    }

    public function sql_itens_acordo($sCampos, $iAcordo, $iCodMater, $iPosicao)
    {
        $sSql = "SELECT $sCampos
                    FROM acordo
                    INNER JOIN acordoposicao ON ac26_acordo = ac16_sequencial
                    INNER JOIN acordoitem ON ac20_acordoposicao = ac26_sequencial
                    WHERE ac16_sequencial = $iAcordo
                        AND ac26_sequencial = $iPosicao
                        AND ac20_pcmater = $iCodMater";
        return $sSql;
    }

    public function sql_Contrato_PCNP($whereCustomParam = null)
    {
        $sSql = "
        SELECT * FROM (SELECT DISTINCT ac16_sequencial,
                        ac213_numerocontrolepncp,
                        l213_numerocompra,
                        cgc AS cnpjCompra,
                        ac16_anousu AS anoCompra,
                        l213_numerocompra AS sequencialCompra,
                        ac16_acordocategoria AS tipoContratoId,
                        ac16_numero AS numeroContratoEmpenho,
                        ac16_anousu AS anoContrato,
                        l20_edital||'/'||l20_anousu AS processo,
                        ac16_acordogrupo AS categoriaProcessoId,
                        l20_receita AS receita,
                         z01_cgccpf AS niFornecedor,
                         NULL AS tipoPessoaFornecedor,
                         z01_nome AS nomeRazaoSocialFornecedor,
                         NULL AS niFornecedorSubContratado,
                         NULL AS tipoPessoaFornecedorSubContratado,
                         NULL AS nomeRazaoSocialFornecedorSubContratado,
                         ac16_objeto AS objetoContrato,
                         NULL AS informacaoComplementar,
                         ac16_valor AS valorInicial,
                         ac16_qtdperiodo AS numeroParcelas,
                         NULL AS valorParcela,
                         ac16_valor AS valorGlobal,
                         NULL AS valorAcumulado,
                         ac16_dataassinatura AS dataAssinatura,
                         ac16_datainicio AS dataVigenciaInicio,
                         ac16_datafim AS datavigenciaFim,
                         NULL AS identificadorCipi,
                         NULL AS urlCipi
                    FROM acordo
                    JOIN acordocategoria ON ac50_sequencial=ac16_acordocategoria
                    JOIN liclicita ON l20_codigo = ac16_licitacao
                    JOIN db_depart ON coddepto=ac16_deptoresponsavel
                    JOIN db_departorg ON db01_coddepto=coddepto
                    JOIN cgm ON z01_numcgm=ac16_contratado
                    JOIN db_config ON codigo=instit
                    JOIN liccontrolepncp ON ac16_licitacao = l213_licitacao
                    LEFT JOIN acocontratopncp ON ac213_contrato = ac16_sequencial
                    WHERE ac16_instit = " . db_getsession('DB_instit') . "
                    AND ac16_acordosituacao in (4,2)
                    $whereCustomParam
                    AND ac16_anousu = " . db_getsession('DB_anousu') . "
        union
                    SELECT DISTINCT ac16_sequencial,
                            ac213_numerocontrolepncp,
                            l213_numerocompra,
                            cgc AS cnpjCompra,
                            ac16_anousu AS anoCompra,
                            l213_numerocompra AS sequencialCompra,
                            ac16_acordocategoria AS tipoContratoId,
                            ac16_numero AS numeroContratoEmpenho,
                            ac16_anousu AS anoContrato,
                            pc80_numdispensa||'/'||EXTRACT(YEAR FROM pcproc.pc80_data) AS processo,
                            ac16_acordogrupo AS categoriaProcessoId,
                            FALSE AS receita,
                            z01_cgccpf AS niFornecedor,
                            NULL AS tipoPessoaFornecedor,
                            z01_nome AS nomeRazaoSocialFornecedor,
                            NULL AS niFornecedorSubContratado,
                            NULL AS tipoPessoaFornecedorSubContratado,
                            NULL AS nomeRazaoSocialFornecedorSubContratado,
                            ac16_objeto AS objetoContrato,
                            NULL AS informacaoComplementar,
                            ac16_valor AS valorInicial,
                            ac16_qtdperiodo AS numeroParcelas,
                            NULL AS valorParcela,
                            ac16_valor AS valorGlobal,
                            NULL AS valorAcumulado,
                            ac16_dataassinatura AS dataAssinatura,
                            ac16_datainicio AS dataVigenciaInicio,
                            ac16_datafim AS datavigenciaFim,
                            NULL AS identificadorCipi,
                            NULL AS urlCipi
                    FROM acordo
                    JOIN acordocategoria ON ac50_sequencial=ac16_acordocategoria
                    join acordoposicao on ac26_acordo = ac16_sequencial
                    join acordoitem on ac20_acordoposicao=ac26_sequencial
                    join acordopcprocitem on ac23_acordoitem=ac20_sequencial
                    join pcprocitem on pc81_codprocitem = ac23_pcprocitem
                    join pcproc on pc81_codproc = pc80_codproc
                    JOIN db_depart ON coddepto=ac16_deptoresponsavel
                    JOIN db_departorg ON db01_coddepto=coddepto
                    JOIN cgm ON z01_numcgm=ac16_contratado
                    JOIN db_config ON codigo=instit
                    LEFT JOIN liccontrolepncp ON  l213_processodecompras = pc80_codproc
                    LEFT JOIN acocontratopncp ON ac213_contrato = ac16_sequencial
                    WHERE ac16_instit = " . db_getsession('DB_instit') . "
                    AND ac16_acordosituacao in (4,2)
                    $whereCustomParam
                    AND ac16_anousu = " . db_getsession('DB_anousu') . "
                    AND pc80_dispvalor = 't'
        union
                    SELECT DISTINCT ac16_sequencial,
                            ac213_numerocontrolepncp,
                            l213_numerocompra,
                            cgc AS cnpjCompra,
                            ac16_anousu AS anoCompra,
                            l213_numerocompra AS sequencialCompra,
                            ac16_acordocategoria AS tipoContratoId,
                            ac16_numero AS numeroContratoEmpenho,
                            ac16_anousu AS anoContrato,
                            liclicita.l20_edital||'/'||liclicita.l20_anousu AS processo,
                            ac16_acordogrupo AS categoriaProcessoId,
                            FALSE AS receita,
                                     z01_cgccpf AS niFornecedor,
                                     NULL AS tipoPessoaFornecedor,
                                     z01_nome AS nomeRazaoSocialFornecedor,
                                     NULL AS niFornecedorSubContratado,
                                     NULL AS tipoPessoaFornecedorSubContratado,
                                     NULL AS nomeRazaoSocialFornecedorSubContratado,
                                     ac16_objeto AS objetoContrato,
                                     NULL AS informacaoComplementar,
                                     ac16_valor AS valorInicial,
                                     ac16_qtdperiodo AS numeroParcelas,
                                     NULL AS valorParcela,
                                     ac16_valor AS valorGlobal,
                                     NULL AS valorAcumulado,
                                     ac16_dataassinatura AS dataAssinatura,
                                     ac16_datainicio AS dataVigenciaInicio,
                                     ac16_datafim AS datavigenciaFim,
                                     NULL AS identificadorCipi,
                                     NULL AS urlCipi
            FROM acordo
                    JOIN acordocategoria ON ac50_sequencial=ac16_acordocategoria
                    JOIN acordoposicao ON ac26_acordo = ac16_sequencial
                    JOIN acordoitem ON ac20_acordoposicao=ac26_sequencial
                    JOIN acordopcprocitem ON ac23_acordoitem=ac20_sequencial
                    JOIN pcprocitem ON pcprocitem.pc81_codprocitem = ac23_pcprocitem
                    JOIN pcproc ON pcprocitem.pc81_codproc = pc80_codproc
                    JOIN solicitem ON solicitem.pc11_codigo = pc81_solicitem
                    JOIN solicitemvinculo ON pc55_solicitemfilho = pc11_codigo
                    JOIN solicitem solicitempai ON solicitempai.pc11_codigo = solicitemvinculo.pc55_solicitempai
                    JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                    join pcprocitem pcprocitempai on pcprocitempai.pc81_solicitem = solicitempai.pc11_codigo
                    join liclicitem on l21_codpcprocitem = pcprocitempai.pc81_codprocitem
                    join liclicita on l20_codigo = l21_codliclicita
                    JOIN db_depart ON coddepto=ac16_deptoresponsavel
                    JOIN db_departorg ON db01_coddepto=coddepto
                    AND db01_anousu = ac16_anousu
                    JOIN cgm ON z01_numcgm=ac16_contratado
                    JOIN db_config ON codigo=instit
                    LEFT JOIN liccontrolepncp ON l213_licitacao = l21_codliclicita
                    LEFT JOIN acocontratopncp ON ac213_contrato = ac16_sequencial
                    WHERE ac16_instit = " . db_getsession('DB_instit') . "
                    AND ac16_acordosituacao in (4,2)
                    $whereCustomParam
                    AND ac16_anousu = " . db_getsession('DB_anousu') . "
                        AND solicita.pc10_solicitacaotipo IN (5)
                    ) AS X ORDER BY ac16_sequencial DESC
        ";

        return $sSql;
    }

    public function sql_DadosContrato_PCNP($iContratocodigo)
    {
        $sSql = "SELECT ac16_sequencial,
                        ac213_numerocontrolepncp,
                        cgc AS cnpjCompra,
                        l213_anousu AS anoCompra,
                        l213_numerocompra AS sequencialCompra,
                        ac16_acordocategoria AS tipoContratoId,
                        ac16_numero AS numeroContratoEmpenho,
                        ac16_anousu AS anoContrato,
                        l20_edital||'/'||l20_anousu AS processo,
                        ac16_acordogrupo AS categoriaProcessoId,
                        l20_receita AS receita,
                        db01_unidade AS codigoUnidade,
                        z01_cgccpf AS niFornecedor,
                        NULL AS tipoPessoaFornecedor,
                        z01_nome AS nomeRazaoSocialFornecedor,
                        NULL AS niFornecedorSubContratado,
                        NULL AS tipoPessoaFornecedorSubContratado,
                        NULL AS nomeRazaoSocialFornecedorSubContratado,
                        ac16_objeto AS objetoContrato,
                        NULL AS informacaoComplementar,
                        ac16_valor AS valorInicial,
                        ac16_numparcela AS numeroParcelas,
                        ac16_vlrparcela AS valorParcela,
                        ac16_valor AS valorGlobal,
                        NULL AS valorAcumulado,
                        ac16_dataassinatura AS dataAssinatura,
                        ac16_datainicio AS dataVigenciaInicio,
                        ac16_datafim AS datavigenciaFim,
                        NULL AS identificadorCipi,
                        NULL AS urlCipi
            FROM acordo
            JOIN acordocategoria ON ac50_sequencial=ac16_acordocategoria
            JOIN liclicita ON l20_codigo = ac16_licitacao
            JOIN db_depart ON coddepto=ac16_deptoresponsavel
            JOIN db_departorg ON db01_coddepto=coddepto
            AND db01_anousu = ac16_anousu
            JOIN cgm ON z01_numcgm=ac16_contratado
            JOIN db_config ON codigo=instit
            JOIN liccontrolepncp ON ac16_licitacao = l213_licitacao
            LEFT JOIN acocontratopncp ON ac213_contrato = ac16_sequencial
            WHERE ac16_anousu = " . db_getsession('DB_anousu') . "
                AND ac16_sequencial = $iContratocodigo
        UNION
            SELECT DISTINCT ac16_sequencial,
                            ac213_numerocontrolepncp,
                            cgc AS cnpjCompra,
                            l213_anousu AS anoCompra,
                            l213_numerocompra AS sequencialCompra,
                            ac16_acordocategoria AS tipoContratoId,
                            ac16_numero AS numeroContratoEmpenho,
                            ac16_anousu AS anoContrato,
                            pc80_numdispensa||'/'||EXTRACT(YEAR FROM pcproc.pc80_data) AS processo,
                            ac16_acordogrupo AS categoriaProcessoId,
                            FALSE AS receita,
                            db01_unidade AS codigoUnidade,
                            z01_cgccpf AS niFornecedor,
                            NULL AS tipoPessoaFornecedor,
                            z01_nome AS nomeRazaoSocialFornecedor,
                            NULL AS niFornecedorSubContratado,
                            NULL AS tipoPessoaFornecedorSubContratado,
                            NULL AS nomeRazaoSocialFornecedorSubContratado,
                            pc80_resumo AS objetoContrato,
                            NULL AS informacaoComplementar,
                            ac16_valor AS valorInicial,
                            ac16_qtdperiodo AS numeroParcelas,
                            0 AS valorParcela,
                            ac16_valor AS valorGlobal,
                            NULL AS valorAcumulado,
                            ac16_dataassinatura AS dataAssinatura,
                            ac16_datainicio AS dataVigenciaInicio,
                            ac16_datafim AS datavigenciaFim,
                            NULL AS identificadorCipi,
                            NULL AS urlCipi
                FROM acordo
                JOIN acordocategoria ON ac50_sequencial=ac16_acordocategoria
                JOIN acordoposicao ON ac26_acordo = ac16_sequencial
                JOIN acordoitem ON ac20_acordoposicao=ac26_sequencial
                JOIN acordopcprocitem ON ac23_acordoitem=ac20_sequencial
                JOIN pcprocitem ON pc81_codprocitem = ac23_pcprocitem
                JOIN pcproc ON pc81_codproc = pc80_codproc
                JOIN db_depart ON coddepto=ac16_deptoresponsavel
                JOIN db_departorg ON db01_coddepto=coddepto AND db01_anousu = ac16_anousu
                JOIN cgm ON z01_numcgm=ac16_contratado
                JOIN db_config ON codigo=instit
                LEFT JOIN liccontrolepncp ON l213_processodecompras = pc80_codproc
                LEFT JOIN acocontratopncp ON ac213_contrato = ac16_sequencial
                WHERE ac16_instit = " . db_getsession('DB_instit') . "
                    and ac16_sequencial = $iContratocodigo
                    AND ac16_anousu = " . db_getsession('DB_anousu') . "
                    AND pc80_dispvalor = 't'
        UNION
            SELECT DISTINCT ac16_sequencial,
                ac213_numerocontrolepncp,
                cgc AS cnpjCompra,
                l213_anousu AS anoCompra,
                l213_numerocompra AS sequencialCompra,
                ac16_acordocategoria AS tipoContratoId,
                ac16_numero AS numeroContratoEmpenho,
                ac16_anousu AS anoContrato,
                pc80_numdispensa||'/'||EXTRACT(YEAR
                                               FROM pcproc.pc80_data) AS processo,
                ac16_acordogrupo AS categoriaProcessoId,
                FALSE AS receita,
                         db01_unidade AS codigoUnidade,
                         z01_cgccpf AS niFornecedor,
                         NULL AS tipoPessoaFornecedor,
                         z01_nome AS nomeRazaoSocialFornecedor,
                         NULL AS niFornecedorSubContratado,
                         NULL AS tipoPessoaFornecedorSubContratado,
                         NULL AS nomeRazaoSocialFornecedorSubContratado,
                         ac16_objeto AS objetoContrato,
                         NULL AS informacaoComplementar,
                         ac16_valor AS valorInicial,
                         ac16_qtdperiodo AS numeroParcelas,
                         0 AS valorParcela,
                         ac16_valor AS valorGlobal,
                         NULL AS valorAcumulado,
                         ac16_dataassinatura AS dataAssinatura,
                         ac16_datainicio AS dataVigenciaInicio,
                         ac16_datafim AS datavigenciaFim,
                         NULL AS identificadorCipi,
                         NULL AS urlCipi
                    FROM acordo
                    JOIN acordocategoria ON ac50_sequencial=ac16_acordocategoria
                    JOIN acordoposicao ON ac26_acordo = ac16_sequencial
                    JOIN acordoitem ON ac20_acordoposicao=ac26_sequencial
                    JOIN acordopcprocitem ON ac23_acordoitem=ac20_sequencial
                    JOIN pcprocitem ON pcprocitem.pc81_codprocitem = ac23_pcprocitem
                    JOIN pcproc ON pcprocitem.pc81_codproc = pc80_codproc
                    JOIN solicitem ON solicitem.pc11_codigo = pc81_solicitem
                    JOIN solicitemvinculo ON pc55_solicitemfilho = pc11_codigo
                    JOIN solicitem solicitempai ON solicitempai.pc11_codigo = solicitemvinculo.pc55_solicitempai
                    JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                    join pcprocitem pcprocitempai on pcprocitempai.pc81_solicitem = solicitempai.pc11_codigo
                    join liclicitem on l21_codpcprocitem = pcprocitempai.pc81_codprocitem
                    join liclicita on l20_codigo = l21_codliclicita
                    JOIN db_depart ON coddepto=ac16_deptoresponsavel
                    JOIN db_departorg ON db01_coddepto=coddepto
                    AND db01_anousu = ac16_anousu
                    JOIN cgm ON z01_numcgm=ac16_contratado
                    JOIN db_config ON codigo=instit
                    LEFT JOIN liccontrolepncp ON l213_licitacao = l21_codliclicita
                    LEFT JOIN acocontratopncp ON ac213_contrato = ac16_sequencial
                    WHERE ac16_instit = " . db_getsession('DB_instit') . "
                            and ac16_sequencial = $iContratocodigo
                            AND ac16_anousu = " . db_getsession('DB_anousu') . "
                        AND solicita.pc10_solicitacaotipo IN (5)
        ";
        return $sSql;
    }

    public function sql_query_pncp($aContratocodigo)
    {
        $ano = db_getsession("DB_anousu");
        $instituicao = db_getsession('DB_instit');
        $dbwhere = " ac16_instit =  {$instituicao} and ac16_anousu = {$ano} and ac16_sequencial = $aContratocodigo";

        $sSql = " select
        ac16_sequencial,
        ac213_numerocontrolepncp,
        ac213_sequencialpncp,
        cgc as cnpjCompra,
        ac16_anousu as anoCompra,
        l213_numerocompra as sequencialCompra,
        ac16_acordocategoria as tipoContratoId,
        ac16_numero as numeroContratoEmpenho,
        ac16_anousu as anoContrato,
        l20_edital||'/'||l20_anousu as processo,
        ac16_acordogrupo as categoriaProcessoId,
        l20_receita as receita,
        db01_unidade as codigoUnidade,
        z01_cgccpf as niFornecedor,
        null as tipoPessoaFornecedor, /*verificar se'pessoa juridica gerar PJ se Fisica gerar PF se diferente gear PE*/
        z01_nome as nomeRazaoSocialFornecedor,
        null as niFornecedorSubContratado,
        null as tipoPessoaFornecedorSubContratado,
        null as nomeRazaoSocialFornecedorSubContratado,
        ac16_objeto as objetoContrato,
        null as informacaoComplementar,
        ac16_valor as valorInicial,
        ac16_qtdperiodo as numeroParcelas,
        0 as valorParcela,
        ac16_valor as valorGlobal,
        null as valorAcumulado,
        ac16_dataassinatura as dataAssinatura,
        ac16_datainicio as dataVigenciaInicio,
        ac16_datafim as datavigenciaFim,
        ac16_tipopagamento as ac16_tipopagamento,
        ac16_numparcela as ac16_numparcela,
        ac16_vlrparcela as ac16_vlrparcela,
        ac16_identificadorcipi as ac16_identificadorcipi,
        ac16_urlcipi as ac16_urlcipi,
        ac16_infcomplementares as ac16_infcomplementares,
        ac16_justificativapncp as ac16_justificativapncp
        from acordo
        join acordocategoria on ac50_sequencial=ac16_acordocategoria
        join liclicita on l20_codigo = ac16_licitacao
        join db_depart on coddepto=ac16_deptoresponsavel
        join db_departorg on db01_coddepto=coddepto
        join cgm on z01_numcgm=ac16_contratado
        join db_config on codigo=instit
        join liccontrolepncp on ac16_licitacao = l213_licitacao
        join acocontratopncp on ac213_contrato = ac16_sequencial
        where $dbwhere
        ";

        return $sSql;
    }

    function sql_query_publicacaoEmpenho_pncp($campos = "*", $ordem = null, $dbwhere = "", $groupby = null)
    {
        $ano  = db_getsession("DB_anousu");
        $sql  = "SELECT e60_numemp,
            l213_numerocontrolepncp,
            z01_cgccpf AS cnpjCompra,
            e60_anousu AS anoCompra,
            l213_numerocompra AS sequencialCompra,
            7 AS tipoContratoId,
            e60_codemp AS numeroContratoEmpenho,
            e60_anousu AS anoContrato,
            l20_edital||'/'||l20_anousu AS processo,
            l20_categoriaprocesso AS categoriaProcessoId,
            l20_receita AS receita,
            01001 AS codigoUnidade,
            z01_cgccpf AS niFornecedor,
            CASE
                WHEN length(trim(z01_cgccpf)) = 14 THEN 'PJ'
                WHEN length(trim(z01_cgccpf)) = 11 THEN 'PF'
                ELSE 'PE'
            END AS tipoPessoaFornecedor,
            z01_nome AS nomeRazaoSocialFornecedor,
            NULL AS niFornecedorSubContratado,
            NULL AS tipoPessoaFornecedorSubContratado,
            NULL AS nomeRazaoSocialFornecedorSubContratado,
            l20_objeto AS objetoContrato,
            NULL AS informacaoComplementar,
            0 AS valorParcela,
            NULL AS dataVigenciaInicio,
            NULL AS dataVigenciaFim,
            NULL AS dataAssinatura,
            e60_vlremp AS valorInicial,
            e60_vlremp AS valorGlobal,
            NULL AS numeroParcelas
    FROM empempenho
    JOIN cgm ON z01_numcgm = e60_numcgm
    JOIN empempaut ON e61_numemp=e60_numemp
    JOIN empautoriza ON e54_autori=e61_autori
    LEFT JOIN liclicita ON l20_codigo = e54_codlicitacao
    JOIN liccontrolepncp ON l20_codigo = l213_licitacao
    WHERE e60_emiss >='$ano-01-01'
        AND e60_emiss <='$ano-12-31'
        AND l20_codigo IS NOT NULL
    UNION
    SELECT e60_numemp,
            l213_numerocontrolepncp,
            z01_cgccpf AS cnpjCompra,
            e60_anousu AS anoCompra,
            l213_numerocompra AS sequencialCompra,
            7 AS tipoContratoId,
            e60_codemp AS numeroContratoEmpenho,
            e60_anousu AS anoContrato,
            l20_edital||'/'||l20_anousu AS processo,
            l20_categoriaprocesso AS categoriaProcessoId,
            l20_receita AS receita,
            01001 AS codigoUnidade,
            z01_cgccpf AS niFornecedor,
            CASE
                WHEN length(trim(z01_cgccpf)) = 14 THEN 'PJ'
                WHEN length(trim(z01_cgccpf)) = 11 THEN 'PF'
                ELSE 'PE'
            END AS tipoPessoaFornecedor,
            z01_nome AS nomeRazaoSocialFornecedor,
            NULL AS niFornecedorSubContratado,
            NULL AS tipoPessoaFornecedorSubContratado,
            NULL AS nomeRazaoSocialFornecedorSubContratado,
            l20_objeto AS objetoContrato,
            NULL AS informacaoComplementar,
            0 AS valorParcela,
            NULL AS dataVigenciaInicio,
            NULL AS dataVigenciaFim,
            NULL AS dataAssinatura,
            e60_vlremp AS valorInicial,
            e60_vlremp AS valorGlobal,
            NULL AS numeroParcelas
    FROM empempenho
    JOIN cgm ON z01_numcgm = e60_numcgm
    JOIN empempaut ON e61_numemp=e60_numemp
    JOIN empautoriza ON e54_autori=e61_autori
    join empautitempcprocitem on e73_autori = e54_autori
    join pcprocitem on e73_pcprocitem= pc81_codprocitem
    join liclicitem on pc81_codprocitem=l21_codpcprocitem
    LEFT JOIN liclicita ON l20_codigo = l21_codliclicita
    JOIN liccontrolepncp ON l20_codigo = l213_licitacao
    WHERE e60_emiss >='$ano-01-01'
        AND e60_emiss <='$ano-12-31'
        AND l20_codigo IS NOT NULL
    UNION
    SELECT e60_numemp,
            l213_numerocontrolepncp,
            z01_cgccpf AS cnpjCompra,
            e60_anousu AS anoCompra,
            l213_numerocompra AS sequencialCompra,
            7 AS tipoContratoId,
            e60_codemp AS numeroContratoEmpenho,
            e60_anousu AS anoContrato,
            pc80_numdispensa||'/'||EXTRACT(YEAR FROM pcproc.pc80_data) AS processo,
            null AS categoriaProcessoId,
            FALSE AS receita,
            01001 AS codigoUnidade,
            z01_cgccpf AS niFornecedor,
            CASE
                WHEN length(trim(z01_cgccpf)) = 14 THEN 'PJ'
                WHEN length(trim(z01_cgccpf)) = 11 THEN 'PF'
                ELSE 'PE'
            END AS tipoPessoaFornecedor,
            z01_nome AS nomeRazaoSocialFornecedor,
            NULL AS niFornecedorSubContratado,
            NULL AS tipoPessoaFornecedorSubContratado,
            NULL AS nomeRazaoSocialFornecedorSubContratado,
            pc80_resumo AS objetoContrato,
            NULL AS informacaoComplementar,
            0 AS valorParcela,
            NULL AS dataVigenciaInicio,
            NULL AS dataVigenciaFim,
            NULL AS dataAssinatura,
            e60_vlremp AS valorInicial,
            e60_vlremp AS valorGlobal,
            NULL AS numeroParcelas
    FROM empempenho
    JOIN cgm ON z01_numcgm = e60_numcgm
    JOIN empempaut ON e61_numemp=e60_numemp
    JOIN empautoriza ON e54_autori=e61_autori
    join empautitempcprocitem on e73_autori = e54_autori
    join pcprocitem on e73_pcprocitem= pc81_codprocitem
    join pcproc on pc80_codproc=pc81_codproc
    JOIN liccontrolepncp ON pc80_codproc = l213_processodecompras
    WHERE e60_emiss >='$ano-01-01'
        AND e60_emiss <='$ano-12-31'
        AND pc80_codproc IS NOT NULL ";

        return $sql;
    }

    function sql_query_pncp_empenho($codigoempenho)
    {

        $ano  = db_getsession("DB_anousu");
        $sql  = "SELECT e60_numemp,
                    l213_numerocontrolepncp,
                    z01_cgccpf AS cnpjCompra,
                    e60_anousu AS anoCompra,
                    l213_numerocompra AS sequencialCompra,
                    7 AS tipoContratoId,
                    e60_codemp AS numeroContratoEmpenho,
                    e60_anousu AS anoContrato,
                    l20_edital||'/'||l20_anousu AS processo,
                    l20_categoriaprocesso AS categoriaProcessoId,
                    l20_receita AS receita,
                            01001 AS codigoUnidade,
                            z01_cgccpf AS niFornecedor,
                            CASE
                                WHEN length(trim(z01_cgccpf)) = 14 THEN 'PJ'
                                WHEN length(trim(z01_cgccpf)) = 11 THEN 'PF'
                                ELSE 'PE'
                            END AS tipoPessoaFornecedor,
                            z01_nome AS nomeRazaoSocialFornecedor,
                            NULL AS niFornecedorSubContratado,
                            NULL AS tipoPessoaFornecedorSubContratado,
                            NULL AS nomeRazaoSocialFornecedorSubContratado,
                            e60_resumo AS objetoContrato,
                            NULL AS informacaoComplementar,
                            0 AS valorParcela,
                            e60_emiss AS dataVigenciaInicio,
                            e60_emiss AS dataVigenciaFim,
                            e60_emiss AS dataAssinatura,
                            e60_vlremp AS valorInicial,
                            e60_vlremp AS valorGlobal,
                            1 AS numeroParcelas
            FROM empempenho
            JOIN cgm ON z01_numcgm = e60_numcgm
            JOIN empempaut ON e61_numemp=e60_numemp
            JOIN empautoriza ON e54_autori=e61_autori
            LEFT JOIN liclicita ON l20_codigo = e54_codlicitacao
            JOIN liccontrolepncp ON l20_codigo = l213_licitacao
            WHERE e60_numemp = {$codigoempenho}
                AND e60_emiss >='$ano-01-01'
                AND e60_emiss <='$ano-12-31'
            UNION
            SELECT e60_numemp,
                    l213_numerocontrolepncp,
                    z01_cgccpf AS cnpjCompra,
                    e60_anousu AS anoCompra,
                    l213_numerocompra AS sequencialCompra,
                    7 AS tipoContratoId,
                    e60_codemp AS numeroContratoEmpenho,
                    e60_anousu AS anoContrato,
                    l20_edital||'/'||l20_anousu AS processo,
                    l20_categoriaprocesso AS categoriaProcessoId,
                    FALSE AS receita,
                            01001 AS codigoUnidade,
                            z01_cgccpf AS niFornecedor,
                            CASE
                                WHEN length(trim(z01_cgccpf)) = 14 THEN 'PJ'
                                WHEN length(trim(z01_cgccpf)) = 11 THEN 'PF'
                                ELSE 'PE'
                            END AS tipoPessoaFornecedor,
                            z01_nome AS nomeRazaoSocialFornecedor,
                            NULL AS niFornecedorSubContratado,
                            NULL AS tipoPessoaFornecedorSubContratado,
                            NULL AS nomeRazaoSocialFornecedorSubContratado,
                            e60_resumo AS objetoContrato,
                            NULL AS informacaoComplementar,
                            0 AS valorParcela,
                            e60_emiss AS dataVigenciaInicio,
                            e60_emiss AS dataVigenciaFim,
                            e60_emiss AS dataAssinatura,
                            e60_vlremp AS valorInicial,
                            e60_vlremp AS valorGlobal,
                            1 AS numeroParcelas
            FROM empempenho
            JOIN cgm ON z01_numcgm = e60_numcgm
            JOIN empempaut ON e61_numemp=e60_numemp
            JOIN empautoriza ON e54_autori=e61_autori
            JOIN empautitempcprocitem ON e73_autori = e54_autori
            JOIN pcprocitem ON e73_pcprocitem= pc81_codprocitem
            JOIN liclicitem ON pc81_codprocitem=l21_codpcprocitem
            LEFT JOIN liclicita ON l20_codigo = l21_codliclicita
            JOIN liccontrolepncp ON l20_codigo = l213_licitacao
            WHERE e60_numemp = {$codigoempenho}
                AND e60_emiss >='$ano-01-01'
                AND e60_emiss <='$ano-12-31'
            UNION
            SELECT e60_numemp,
                    l213_numerocontrolepncp,
                    z01_cgccpf AS cnpjCompra,
                    e60_anousu AS anoCompra,
                    l213_numerocompra AS sequencialCompra,
                    7 AS tipoContratoId,
                    e60_codemp AS numeroContratoEmpenho,
                    e60_anousu AS anoContrato,
                    pc80_numdispensa||'/'||EXTRACT(YEAR FROM pcproc.pc80_data) AS processo,
                    null AS categoriaProcessoId,
                    FALSE AS receita,
                                01001 AS codigoUnidade,
                                z01_cgccpf AS niFornecedor,
                                CASE
                                    WHEN length(trim(z01_cgccpf)) = 14 THEN 'PJ'
                                    WHEN length(trim(z01_cgccpf)) = 11 THEN 'PF'
                                    ELSE 'PE'
                                END AS tipoPessoaFornecedor,
                                z01_nome AS nomeRazaoSocialFornecedor,
                                NULL AS niFornecedorSubContratado,
                                NULL AS tipoPessoaFornecedorSubContratado,
                                NULL AS nomeRazaoSocialFornecedorSubContratado,
                                e60_resumo AS objetoContrato,
                                NULL AS informacaoComplementar,
                                0 AS valorParcela,
                                e60_emiss AS dataVigenciaInicio,
                                e60_emiss AS dataVigenciaFim,
                                e60_emiss AS dataAssinatura,
                                e60_vlremp AS valorInicial,
                                e60_vlremp AS valorGlobal,
                                1 AS numeroParcelas
            FROM empempenho
            JOIN cgm ON z01_numcgm = e60_numcgm
            JOIN empempaut ON e61_numemp=e60_numemp
            JOIN empautoriza ON e54_autori=e61_autori
            JOIN empautitempcprocitem ON e73_autori = e54_autori
            JOIN pcprocitem ON e73_pcprocitem= pc81_codprocitem
            JOIN pcproc ON pc80_codproc=pc81_codproc
            JOIN liccontrolepncp ON pc80_codproc = l213_processodecompras
            WHERE e60_numemp = {$codigoempenho}
                AND e60_emiss >='$ano-01-01'
                AND e60_emiss <='$ano-12-31'";
        return $sql;
    }

    public function alteracaoCriterioReajuste($ac16_sequencial,$ac16_reajuste,$ac16_criterioreajuste,$ac16_datareajuste,$ac16_periodoreajuste,$ac16_indicereajuste,$ac16_descricaoreajuste,$ac16_descricaoindice)
    {

        $ac16_datareajuste = implode("-", (array_reverse(explode("/", $ac16_datareajuste))));;

        $ac16_criterioreajuste = $ac16_reajuste == "f" ? "null" : "'$ac16_criterioreajuste'";
        $ac16_datareajuste = $ac16_reajuste == "f" ? "null" : "'$ac16_datareajuste'";
        $ac16_periodoreajuste = $ac16_reajuste == "f" ? "null" : "'$ac16_periodoreajuste'";
        $ac16_indicereajuste = $ac16_reajuste == "f" ? "null" : $ac16_indicereajuste;
        $ac16_descricaoreajuste = $ac16_reajuste == "f" ? "null" : "'$ac16_descricaoreajuste'";
        $ac16_descricaoindice = $ac16_reajuste == "f" ? "null" : "'$ac16_descricaoindice'";

        $sSql =
        "
        update
	        acordo
        set
            ac16_reajuste = '$ac16_reajuste',
            ac16_criterioreajuste = $ac16_criterioreajuste,
            ac16_datareajuste = $ac16_datareajuste,
            ac16_periodoreajuste = $ac16_periodoreajuste,
            ac16_indicereajuste = $ac16_indicereajuste,
            ac16_descricaoreajuste = $ac16_descricaoreajuste,
            ac16_descricaoindice = $ac16_descricaoindice
        where
	        ac16_sequencial = $ac16_sequencial;
        ";

        db_query($sSql);
    }

    public function queryAcordosComAditamento($filtros = []): string
    {
        $sql = "
        SELECT DISTINCT acordo.ac16_sequencial,
            CASE
                WHEN ac16_semvigencia='t' THEN ('-')::varchar
                ELSE (ac16_numeroacordo || '/' || ac16_anousu)::varchar
            END dl_N�_Acordo,
            ac17_descricao AS dl_Situa��o,
            acordo.ac16_contratado,
            cgm.z01_nome,
            acordo.ac16_resumoobjeto::text,
            acordo.ac16_valor,
            acordo.ac16_dataassinatura,
            CASE
                WHEN ac16_semvigencia='t' THEN NULL
                ELSE ac16_datainicio
            END ac16_datainicio,
            CASE
                WHEN ac16_semvigencia='t' THEN NULL
                ELSE ac16_datafim
            END ac16_datafim,
            CASE
                WHEN acordo.ac16_origem = 1 THEN 'Processo de Compras'
                WHEN acordo.ac16_origem = 2 THEN 'Licita��o'
                ELSE 'Manual'
            END ac16_origem,
            db_depart.descrdepto AS dl_Dpto_de_Inclusao,
            responsavel.descrdepto AS dl_Dpto_Responsavel
        FROM acordo
        INNER JOIN cgm ON cgm.z01_numcgm = acordo.ac16_contratado
        INNER JOIN db_depart ON db_depart.coddepto = acordo.ac16_coddepto
        INNER JOIN db_depart AS responsavel ON responsavel.coddepto = acordo.ac16_deptoresponsavel
        INNER JOIN acordogrupo ON acordogrupo.ac02_sequencial = acordo.ac16_acordogrupo
        INNER JOIN acordosituacao ON acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao
        LEFT JOIN acordocomissao ON acordocomissao.ac08_sequencial = acordo.ac16_acordocomissao
        INNER JOIN db_config ON db_config.codigo = db_depart.instit
        INNER JOIN acordonatureza ON acordonatureza.ac01_sequencial = acordogrupo.ac02_acordonatureza
        INNER JOIN acordotipo ON acordotipo.ac04_sequencial = acordogrupo.ac02_acordotipo
        INNER JOIN acordoposicao ON acordoposicao.ac26_acordo = acordo.ac16_sequencial
        INNER JOIN acordoitem ON acordoitem.ac20_acordoposicao = acordoposicao.ac26_sequencial
        LEFT JOIN acordoorigem ON acordoorigem.ac28_sequencial = acordo.ac16_origem
        LEFT JOIN acordomovimentacao ON acordomovimentacao.ac10_acordo = acordo.ac16_sequencial
        WHERE ac16_acordosituacao IN (4)
            AND ac16_acordosituacao = 4
            AND ac16_instit = 1
            AND ac26_acordoposicaotipo in (2,5,6,7,8,9,10,11,12,13,14)
            AND ac26_numeroaditamento IS NOT NULL
            --and para mostrar somente acordos em que a ultima possicao nao tenha autorizacao
            AND ac26_acordo NOT IN
            (SELECT ac26_acordo
             FROM acordoposicao
             INNER JOIN acordoitem ON ac20_acordoposicao= ac26_sequencial
             INNER JOIN acordoitemexecutado ON ac29_acordoitem = ac20_sequencial
             INNER JOIN acordoitemexecutadoempautitem ON ac19_acordoitemexecutado = ac29_sequencial
             WHERE ac26_sequencial IN
                     (SELECT max(ac26_sequencial)
                      FROM acordoposicao
                      WHERE ac26_acordo = ac16_sequencial)) ";

        foreach ($filtros as $key => $filtro) {
            $sql .= " AND {$key} = {$filtro} ";
        }

        $sql .= " ORDER BY ac16_sequencial DESC";

        return $sql;
    }

    public function queryAcordosAssinadosHomologados(int $ac10Sequencial = null)
    {
        $instituicao = db_getsession('DB_instit');
        $sql = "SELECT acordomovimentacao.ac10_acordo,
            acordomovimentacao.ac10_acordomovimentacaotipo,
            acordomovimentacaotipo.ac09_descricao,
            acordomovimentacao.ac10_sequencial,
            acordomovimentacao.ac10_id_usuario,
            acordomovimentacao.ac10_datamovimento,
            acordomovimentacao.ac10_hora,
            acordomovimentacao.ac10_obs
        FROM acordomovimentacao
        INNER JOIN acordomovimentacaotipo ON acordomovimentacaotipo.ac09_sequencial = acordomovimentacao.ac10_acordomovimentacaotipo
        INNER JOIN acordo ON acordo.ac16_sequencial = acordomovimentacao.ac10_acordo
        INNER JOIN db_depart ON db_depart.coddepto = acordo.ac16_coddepto
        INNER JOIN acordosituacao ON acordosituacao.ac17_sequencial = acordo.ac16_acordosituacao
        LEFT JOIN acordomovimentacaocancela ON ac25_acordomovimentacaocancela = ac10_sequencial
        WHERE ac25_acordomovimentacaocancela IS NULL ";

        if(!empty($ac10Sequencial)) {
            $sql .= " AND acordomovimentacao.ac10_acordo = $ac10Sequencial ";
        }

        $sql .= "AND (ac10_acordomovimentacaotipo = 2
            AND ac16_acordosituacao = 1
            AND ac16_dataassinatura IS NOT NULL
            OR ac10_acordomovimentacaotipo = 11
            AND ac16_acordosituacao = 4
            AND NOT EXISTS
                         (SELECT 1
                          FROM acordoempautoriza
                          WHERE ac45_acordo = ac16_sequencial)
           AND ac16_instit = $instituicao )
           ORDER BY ac10_sequencial DESC";

        return $sql;
    }

    function adicionarJustificativasPncp($aSequenciais, $justificativaPncp) {
        if (empty($aSequenciais)) {
            return (object) [
                'erro_banco' => '',
                'erro_sql' => 'Nenhum sequencial fornecido.',
                'erro_msg' => 'Usu�rio: Nenhum sequencial fornecido.',
                'erro_status' => '0',
                'numrows' => 0
            ];
        }

        $justificativaPncp = pg_escape_string($justificativaPncp);

        $sequenciais = implode(', ', array_map(function($obj) {
            return (int) $obj->codigo;
        }, $aSequenciais));

        $sSql = "
            UPDATE
                acordo
            SET
                ac16_justificativapncp = '$justificativaPncp'
            WHERE
                ac16_sequencial IN ($sequenciais);
        ";

        $result = db_query($sSql);

        $oReturn = new stdClass();
        if ($result === false) {
            $erroBanco = str_replace("\n", "", @pg_last_error());
            $erroSql = "Justificativas PNCP n�o efetuada. Exclus�o abortada.\nValores: $sequenciais";

            $oReturn->erro_banco = $erroBanco;
            $oReturn->erro_sql = $erroSql;
            $oReturn->erro_msg = "Usu�rio: $erroSql\nAdministrador: $erroBanco";
            $oReturn->erro_status = "0";
            $oReturn->numrows = 0;
        } else {
            $numRows = pg_affected_rows($result);

            if ($numRows == 0) {
                $erroSql = "Acordo n�o encontrado. Justificativas PNCP n�o efetuada.\nValores: $sequenciais";

                $oReturn->erro_banco = '';
                $oReturn->erro_sql = $erroSql;
                $oReturn->erro_msg = "Usu�rio: $erroSql\nAdministrador: ";
                $oReturn->erro_status = "1";
                $oReturn->numrows = 0;
            } else {
                $sucessoSql = "Justificativas PNCP efetuada com sucesso.\nValores: $sequenciais";

                $oReturn->erro_banco = '';
                $oReturn->erro_sql = $sucessoSql;
                $oReturn->erro_msg = "Usu�rio: $sucessoSql\nAdministrador: ";
                $oReturn->erro_status = "1";
                $oReturn->numrows = $numRows;
            }
        }

        return $oReturn;
    }

}
