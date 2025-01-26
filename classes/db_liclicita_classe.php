<?php

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

//MODULO: licitacao
//CLASSE DA ENTIDADE liclicita
class cl_liclicita
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
    var $l20_codigo = 0;
    var $l20_codtipocom = 0;
    var $l20_numero = 0;
    var $l20_id_usucria = 0;
    var $l20_datacria_dia = null;
    var $l20_datacria_mes = null;
    var $l20_datacria_ano = null;
    var $l20_datacria = null;
    var $l20_horacria = null;
    var $l20_dataaber_dia = null;
    var $l20_dataaber_mes = null;
    var $l20_dataaber_ano = null;
    var $l20_dataaber = null;
    var $l20_dtpublic_dia = null;
    var $l20_dtpublic_mes = null;
    var $l20_dtpublic_ano = null;
    var $l20_dtpublic = null;
    var $l20_horaaber = null;
    var $l20_local = null;
    var $l20_objeto = null;
    var $l20_tipojulg = null;
    var $l20_liccomissao = null;
    var $l20_liclocal = null;
    var $l20_procadmin = null;
    var $l20_correto = 'f';
    var $l20_instit = null;
    var $l20_licsituacao = null;
    var $l20_edital = null;
    var $l20_anousu = null;
    var $l20_usaregistropreco = 'f';
    var $l20_localentrega = null;
    var $l20_prazoentrega = null;
    var $l20_condicoespag = null;
    var $l20_validadeproposta = null;
    var $l20_razao = null;
    var $l20_formacontroleregistropreco = 0;
    var $l20_justificativa = null;
    var $l20_aceitabilidade = null;
    var $l20_equipepregao = null;
    var $l20_nomeveiculo2 = null;
    var $l20_datapublicacao2_dia = null;
    var $l20_datapublicacao2_mes = null;
    var $l20_datapublicacao2_ano = null;
    var $l20_datapublicacao2 = null;
    var $l20_nomeveiculo1 = null;
    var $l20_datapublicacao1_dia = null;
    var $l20_datapublicacao1_mes = null;
    var $l20_datapublicacao1_ano = null;
    var $l20_datapublicacao1 = null;
    var $l20_datadiario_dia = null;
    var $l20_datadiario_mes = null;
    var $l20_datadiario_ano = null;
    var $l20_datadiario = null;
    var $l20_recdocumentacao_dia = null;
    var $l20_recdocumentacao_mes = null;
    var $l20_recdocumentacao_ano = null;
    var $l20_recdocumentacao = null;
    var $l20_numeroconvidado = null;
    var $l20_descontotab = null;
    var $l20_regimexecucao = null;
    var $l20_naturezaobjeto = null;
    var $l20_tipliticacao = null;
    var $l20_tipnaturezaproced = null;
    var $l20_dtpubratificacao_dia = null;
    var $l20_dtpubratificacao_mes = null;
    var $l20_dtpubratificacao_ano = null;
    var $l20_dtpubratificacao = null;
    var $l20_dtlimitecredenciamento = null;
    var $l20_critdesempate = null;
    var $l20_destexclusiva = null;
    var $l20_subcontratacao = null;
    var $l20_limitcontratacao = null;
    var $l20_tipoprocesso = null;
    var $l20_regata = null;
    var $l20_interporrecurso = null;
    var $l20_descrinterporrecurso = null;
    var $l20_veicdivulgacao = null;
    var $l20_clausulapro = null;
    var $l20_codepartamento = null;
    var $l20_diames = null;
    var $l20_execucaoentrega = null;
    var $l20_criterioadjudicacao = null;
    var $l20_nroedital = null;
    var $l20_exercicioedital = null;
    /* Valor 1 para cadastro inicial da Licita��o - demanda para atender o SICOM 2020 */
    var $l20_cadinicial = null;
    var $l20_leidalicitacao = null;
    var $l20_dtpulicacaopncp = null;
    var $l20_linkpncp = null;
    var $l20_diariooficialdivulgacao = null;
    var $l20_dtpulicacaoedital = null;
    var $l20_linkedital = null;
    var $l20_mododisputa = null;
    var $l20_dataaberproposta = null;
    var $l20_dataaberproposta_dia = null;
    var $l20_dataaberproposta_mes = null;
    var $l20_dataaberproposta_ano = null;
    var $l20_dataencproposta = null;
    var $l20_dataencproposta_dia = null;
    var $l20_dataencproposta_mes = null;
    var $l20_dataencproposta_ano = null;
    var $l20_amparolegal = null;
    var $l20_categoriaprocesso = null;
    var $l20_justificativapncp = null;
    var $l20_receita = null;
    var $l20_horaaberturaprop = null;
    var $l20_horaencerramentoprop = null;
    var $l20_dispensaporvalor = null;
    var $l20_lances = null;

    // cria propriedade com as variaveis do arquivo
    var $campos = "
                 l20_codigo = int8 = Sequencial
                 l20_codtipocom = int4 = C�digo do tipo de compra
                 l20_numero = int8 = Numera��o
                 l20_id_usucria = int4 = Cod. Usu�rio
                 l20_datacria = date = Data Cria��o
                 l20_horacria = char(5) = Hora Cria��o
                 l20_dataaber = date = Data Edital/Convite
                 l20_dtpublic = date = Data Publica��o
                 l20_horaaber = char(5) = Hora Abertura
                 l20_local = text = Local da Licita��o
                 l20_objeto = text = Objeto
                 l20_tipojulg = int4 = Tipo de Julgamento
                 l20_liccomissao = int4 = C�digo da Comiss�o
                 l20_liclocal = int4 = C�digo do Local da Licita��o
                 l20_procadmin = varchar(50) = Processo Administrativo
                 l20_correto = bool = Correto
                 l20_instit = int4 = Institui��o
                 l20_licsituacao = int4 = Situa��o da Licita��o
                 l20_edital = int8 = Licitacao
                 l20_anousu = int4 = Exerc�cio
                 l20_usaregistropreco = bool = Registro Pre�o
                 l20_localentrega = text = Local de Entrega
                 l20_prazoentrega = text = Prazo Entrega
                 l20_condicoespag = text = Forma  de Pagamento
                 l20_validadeproposta = text = Validade da Proposta
                 l20_razao = text = Raz�o
                 l20_formacontroleregistropreco = int4 = Forma de Controle RP
                 l20_justificativa = int8 = Justificativa
                 l20_aceitabilidade = text = Cit�rio de Aceitabilidade
                 l20_equipepregao = int8 = Equipe Preg�o
                 l20_nomeveiculo2 = varchar(50) = Nome Ve�culo Divulga��o 2
                 l20_datapublicacao2 = date = Data Publica��o Edital Veiculo 2
                 l20_nomeveiculo1 = varchar(50) = Nome Ve�culo Divulga��o 1
                 l20_datapublicacao1 = date = Data Publica��o Edital Veiculo 1
                 l20_datadiario = date = Data de Publica��o em Di�rio Oficial
                 l20_recdocumentacao = date = Abertura das Propostas
                 l20_numeroconvidado = int8 = N�mero de convidados
                 l20_descontotab = int8 = Desconto Tabela
                 l20_regimexecucao = int8 = Regime da Execu��o
                 l20_naturezaobjeto = int8 = Natureza do Objeto
                 l20_tipliticacao = int8 = Tipo da Licita��o
                 l20_tipnaturezaproced = int8 = Natureza do Procedimento
                 l20_dtpubratificacao = date= Data Publica��o Termo Ratifica��o
                 l20_dtlimitecredenciamento = date = Data limite para credenciamento
                 l20_critdesempate = int8= Crit�rio de Desempate
                 l20_destexclusiva = int8= Destina��o Exclusiva
                 l20_subcontratacao = int8= Sub. Contrata��o
                 l20_limitcontratacao = int8= Limite Contrata��o
                 l20_tipoprocesso = int8= Tipo  de processo
                 l20_regata = int8 = Registrado Presen�a em Ata
                 l20_interporrecurso = Interpor Recurso
                 l20_descrinterporrecurso = Descri��o
                 l20_veicdivulgacao= Veiculo de Divulga��o
                 l20_clausulapro= text = Prorrogacao
                 l20_codepartamento=int8= Codigo departamento
                 l20_diames=int8= Dia mes
                 l20_execucaoentrega=int8= Execucao da entrega
                 l20_criterioadjudicacao = int4 = Criterio de adjudicacao
                 l20_nroedital = int8 = N�mero Edital Licita��o
                 l20_cadinicial = int8 = Identificador cadastro inicial
                 l20_exercicioedital = int8 = Exerc�cio do Edital
                 l20_leidalicitacao = int8 = Lei de licitacao
                 l20_dtpulicacaopncp = date = Data Publica��o Termo Ratifica��o
                 l20_linkpncp = text = Prorrogacao
                 l20_diariooficialdivulgacao = int8 = Lei de licitacao
                 l20_dtpulicacaoedital = date = Data Publica��o Termo Ratifica��o
                 l20_linkedital = text = Prorrogacao
                 l20_mododisputa = int8 = Lei de licitacao
                 l20_dataaberproposta = date = Data encerramento Proposta;
                 l20_dataencproposta = date = Data encerramento Proposta;
                 l20_amparolegal = Amparo legal;
                 l20_categoriaprocesso = int4 = Categoria Processo;
                 l20_justificativapncp = text = justificativa para pncp
                 l20_receita = bool = receita
                 l20_horaaberturaprop = string = hora de abertura das propostas
                 l20_horaencerramentoprop = string = hora de encerramento das propostas
                 l20_dispensaporvalor = bool = dispensa por valor
                 l20_lances = bool = apuracao de lances pelo sistema
                 ";

    //funcao construtor da classe
    function cl_liclicita()
    {
        //classes dos rotulos dos campos
        $this->rotulo = new rotulo("liclicita");
        $this->pagina_retorno = basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
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
            $this->l20_codigo = ($this->l20_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_codigo"] : $this->l20_codigo);
            $this->l20_codtipocom = ($this->l20_codtipocom == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_codtipocom"] : $this->l20_codtipocom);
            $this->l20_numero = ($this->l20_numero == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_numero"] : $this->l20_numero);
            $this->l20_id_usucria = ($this->l20_id_usucria == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_id_usucria"] : $this->l20_id_usucria);
            if ($this->l20_datacria == "") {
                $this->l20_datacria_dia = ($this->l20_datacria_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datacria_dia"] : $this->l20_datacria_dia);
                $this->l20_datacria_mes = ($this->l20_datacria_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datacria_mes"] : $this->l20_datacria_mes);
                $this->l20_datacria_ano = ($this->l20_datacria_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datacria_ano"] : $this->l20_datacria_ano);
                if ($this->l20_datacria_dia != "") {
                    $this->l20_datacria = $this->l20_datacria_ano . "-" . $this->l20_datacria_mes . "-" . $this->l20_datacria_dia;
                }
            }
            $this->l20_horacria = ($this->l20_horacria == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_horacria"] : $this->l20_horacria);
            if ($this->l20_dataaber == "") {
                $this->l20_dataaber_dia = ($this->l20_dataaber_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dataaber_dia"] : $this->l20_dataaber_dia);
                $this->l20_dataaber_mes = ($this->l20_dataaber_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dataaber_mes"] : $this->l20_dataaber_mes);
                $this->l20_dataaber_ano = ($this->l20_dataaber_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dataaber_ano"] : $this->l20_dataaber_ano);
                if ($this->l20_dataaber_dia != "") {
                    $this->l20_dataaber = $this->l20_dataaber_ano . "-" . $this->l20_dataaber_mes . "-" . $this->l20_dataaber_dia;
                }
            }
            if ($this->l20_dtpublic == "") {
                $this->l20_dtpublic_dia = ($this->l20_dtpublic_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpublic_dia"] : $this->l20_dtpublic_dia);
                $this->l20_dtpublic_mes = ($this->l20_dtpublic_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpublic_mes"] : $this->l20_dtpublic_mes);
                $this->l20_dtpublic_ano = ($this->l20_dtpublic_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpublic_ano"] : $this->l20_dtpublic_ano);
                if ($this->l20_dtpublic_dia != "") {
                    $this->l20_dtpublic = $this->l20_dtpublic_ano . "-" . $this->l20_dtpublic_mes . "-" . $this->l20_dtpublic_dia;
                }
            }

            if ($this->l20_dtpubratificacao == "") {
                $this->l20_dtpubratificacao_dia = ($this->l20_dtpubratificacao_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpubratificacao_dia"] : $this->l20_dtpubratificacao_dia);
                $this->l20_dtpubratificacao_mes = ($this->l20_dtpubratificacao_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpubratificacao_mes"] : $this->l20_dtpubratificacao_mes);
                $this->l20_dtpubratificacao_ano = ($this->l20_dtpubratificacao_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpubratificacao_ano"] : $this->l20_dtpubratificacao_ano);
                if ($this->l20_dtpubratificacao_dia != "") {
                    $this->l20_dtpubratificacao = $this->l20_dtpubratificacao_ano . "-" . $this->l20_dtpubratificacao_mes . "-" . $this->l20_dtpubratificacao_dia;
                }
            }


            $this->l20_horaaber = ($this->l20_horaaber == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_horaaber"] : $this->l20_horaaber);
            $this->l20_local = ($this->l20_local == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_local"] : $this->l20_local);
            $this->l20_objeto = ($this->l20_objeto == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_objeto"] : $this->l20_objeto);
            $this->l20_tipojulg = ($this->l20_tipojulg == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_tipojulg"] : $this->l20_tipojulg);
            $this->l20_liccomissao = ($this->l20_liccomissao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_liccomissao"] : $this->l20_liccomissao);
            $this->l20_liclocal = ($this->l20_liclocal == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_liclocal"] : $this->l20_liclocal);
            $this->l20_procadmin = ($this->l20_procadmin == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_procadmin"] : $this->l20_procadmin);
            $this->l20_correto = ($this->l20_correto == "f" ? @$GLOBALS["HTTP_POST_VARS"]["l20_correto"] : $this->l20_correto);
            $this->l20_instit = ($this->l20_instit == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_instit"] : $this->l20_instit);
            $this->l20_licsituacao = ($this->l20_licsituacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_licsituacao"] : $this->l20_licsituacao);
            $this->l20_edital = ($this->l20_edital == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_edital"] : $this->l20_edital);
            $this->l20_anousu = ($this->l20_anousu == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_anousu"] : $this->l20_anousu);
            $this->l20_usaregistropreco = ($this->l20_usaregistropreco == "f" ? @$GLOBALS["HTTP_POST_VARS"]["l20_usaregistropreco"] : $this->l20_usaregistropreco);
            $this->l20_localentrega = ($this->l20_localentrega == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_localentrega"] : $this->l20_localentrega);
            $this->l20_prazoentrega = ($this->l20_prazoentrega == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_prazoentrega"] : $this->l20_prazoentrega);
            $this->l20_condicoespag = ($this->l20_condicoespag == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_condicoespag"] : $this->l20_condicoespag);
            $this->l20_validadeproposta = ($this->l20_validadeproposta == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_validadeproposta"] : $this->l20_validadeproposta);
            $this->l20_razao = ($this->l20_razao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_razao"] : $this->l20_razao);
            $this->l20_formacontroleregistropreco = ($this->l20_formacontroleregistropreco == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_formacontroleregistropreco"] : $this->l20_formacontroleregistropreco);
            $this->l20_justificativa = ($this->l20_justificativa == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_justificativa"] : $this->l20_justificativa);
            $this->l20_aceitabilidade = ($this->l20_aceitabilidade == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_aceitabilidade"] : $this->l20_aceitabilidade);
            $this->l20_equipepregao = ($this->l20_equipepregao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_equipepregao"] : $this->l20_equipepregao);
            $this->l20_nomeveiculo2 = ($this->l20_nomeveiculo2 == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_nomeveiculo2"] : $this->l20_nomeveiculo2);
            if ($this->l20_datapublicacao2 == "") {
                $this->l20_datapublicacao2_dia = ($this->l20_datapublicacao2_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datapublicacao2_dia"] : $this->l20_datapublicacao2_dia);
                $this->l20_datapublicacao2_mes = ($this->l20_datapublicacao2_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datapublicacao2_mes"] : $this->l20_datapublicacao2_mes);
                $this->l20_datapublicacao2_ano = ($this->l20_datapublicacao2_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datapublicacao2_ano"] : $this->l20_datapublicacao2_ano);
                if ($this->l20_datapublicacao2_dia != "") {
                    $this->l20_datapublicacao2 = $this->l20_datapublicacao2_ano . "-" . $this->l20_datapublicacao2_mes . "-" . $this->l20_datapublicacao2_dia;
                }
            }
            $this->l20_nomeveiculo1 = ($this->l20_nomeveiculo1 == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_nomeveiculo1"] : $this->l20_nomeveiculo1);
            if ($this->l20_datapublicacao1 == "") {
                $this->l20_datapublicacao1_dia = ($this->l20_datapublicacao1_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datapublicacao1_dia"] : $this->l20_datapublicacao1_dia);
                $this->l20_datapublicacao1_mes = ($this->l20_datapublicacao1_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datapublicacao1_mes"] : $this->l20_datapublicacao1_mes);
                $this->l20_datapublicacao1_ano = ($this->l20_datapublicacao1_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datapublicacao1_ano"] : $this->l20_datapublicacao1_ano);
                if ($this->l20_datapublicacao1_dia != "") {
                    $this->l20_datapublicacao1 = $this->l20_datapublicacao1_ano . "-" . $this->l20_datapublicacao1_mes . "-" . $this->l20_datapublicacao1_dia;
                }
            }
            if ($this->l20_datadiario == "") {
                $this->l20_datadiario_dia = ($this->l20_datadiario_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datadiario_dia"] : $this->l20_datadiario_dia);
                $this->l20_datadiario_mes = ($this->l20_datadiario_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datadiario_mes"] : $this->l20_datadiario_mes);
                $this->l20_datadiario_ano = ($this->l20_datadiario_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_datadiario_ano"] : $this->l20_datadiario_ano);
                if ($this->l20_datadiario_dia != "") {
                    $this->l20_datadiario = $this->l20_datadiario_ano . "-" . $this->l20_datadiario_mes . "-" . $this->l20_datadiario_dia;
                }
            }
            if ($this->l20_recdocumentacao == "") {
                $this->l20_recdocumentacao_dia = ($this->l20_recdocumentacao_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_recdocumentacao_dia"] : $this->l20_recdocumentacao_dia);
                $this->l20_recdocumentacao_mes = ($this->l20_recdocumentacao_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_recdocumentacao_mes"] : $this->l20_recdocumentacao_mes);
                $this->l20_recdocumentacao_ano = ($this->l20_recdocumentacao_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_recdocumentacao_ano"] : $this->l20_recdocumentacao_ano);
                if ($this->l20_recdocumentacao_dia != "") {
                    $this->l20_recdocumentacao = $this->l20_recdocumentacao_ano . "-" . $this->l20_recdocumentacao_mes . "-" . $this->l20_recdocumentacao_dia;
                }
            }

            if ($this->l20_dataaberproposta == "") {
                $this->l20_dataaberproposta_dia = ($this->l20_dataaberproposta_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dataaberproposta_dia"] : $this->l20_dataaberproposta_dia);
                $this->l20_dataaberproposta_mes = ($this->l20_dataaberproposta_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dataaberproposta_mes"] : $this->l20_dataaberproposta_mes);
                $this->l20_dataaberproposta_ano = ($this->l20_dataaberproposta_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dataaberproposta_ano"] : $this->l20_dataaberproposta_ano);
                if ($this->l20_dataaberproposta_dia != "") {
                    $this->l20_dataaberproposta = $this->l20_dataaberproposta_ano . "-" . $this->l20_dataaberproposta_mes . "-" . $this->l20_dataaberproposta_dia;
                }
            }

            if ($this->l20_dataencproposta == "") {
                $this->l20_dataencproposta_dia = ($this->l20_dataencproposta_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dataencproposta_dia"] : $this->l20_dataencproposta_dia);
                $this->l20_dataencproposta_mes = ($this->l20_dataencproposta_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dataencproposta_mes"] : $this->l20_dataencproposta_mes);
                $this->l20_dataencproposta_ano = ($this->l20_dataencproposta_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dataencproposta_ano"] : $this->l20_dataencproposta_ano);
                if ($this->l20_dataencproposta_dia != "") {
                    $this->l20_dataencproposta = $this->l20_dataencproposta_ano . "-" . $this->l20_dataencproposta_mes . "-" . $this->l20_dataencproposta_dia;
                }
            }

            $this->l20_numeroconvidado = ($this->l20_numeroconvidado == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_numeroconvidado"] : $this->l20_numeroconvidado);
            $this->l20_descontotab = ($this->l20_descontotab == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_descontotab"] : $this->l20_descontotab);
            $this->l20_regimexecucao = ($this->l20_regimexecucao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_regimexecucao"] : $this->l20_regimexecucao);
            $this->l20_naturezaobjeto = ($this->l20_naturezaobjeto == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_naturezaobjeto"] : $this->l20_naturezaobjeto);
            $this->l20_tipliticacao = ($this->l20_tipliticacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_tipliticacao"] : $this->l20_tipliticacao);
            $this->l20_tipnaturezaproced = ($this->l20_tipnaturezaproced == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_tipnaturezaproced"] : $this->l20_tipnaturezaproced);

            $this->l20_critdesempate = ($this->l20_critdesempate == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_critdesempate"] : $this->l20_critdesempate);
            $this->l20_destexclusiva = ($this->l20_destexclusiva == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_destexclusiva"] : $this->l20_destexclusiva);
            $this->l20_subcontratacao = ($this->l20_subcontratacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_subcontratacao"] : $this->l20_subcontratacao);
            $this->l20_limitcontratacao = ($this->l20_limitcontratacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_limitcontratacao"] : $this->l20_limitcontratacao);
            $this->l20_tipoprocesso = ($this->l20_tipoprocesso == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_tipoprocesso"] : $this->l20_tipoprocesso);
            $this->l20_regata = ($this->l20_regata == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_regata"] : $this->l20_regata);
            $this->l20_interporrecurso = ($this->l20_interporrecurso == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_interporrecurso"] : $this->l20_interporrecurso);
            $this->l20_descrinterporrecurso = ($this->l20_descrinterporrecurso == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_descrinterporrecurso"] : $this->l20_descrinterporrecurso);
            $this->l20_veicdivulgacao = ($this->l20_veicdivulgacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_veicdivulgacao"] : $this->l20_veicdivulgacao);

            $this->l20_clausulapro = ($this->l20_clausulapro == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_clausulapro"] : $this->l20_clausulapro);
            $this->l20_codepartamento = ($this->l20_codepartamento == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_codepartamento"] : $this->l20_codepartamento);
            $this->l20_diames = ($this->l20_diames == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_diames"] : $this->l20_diames);
            $this->l20_execucaoentrega = ($this->l20_execucaoentrega == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_execucaoentrega"] : $this->l20_execucaoentrega);
            $this->l20_nroedital = ($this->l20_nroedital == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_nroedital"] : $this->l20_nroedital);
            $this->l20_cadinicial = ($this->l20_cadinicial == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_cadinicial"] : $this->l20_cadinicial);
            $this->l20_exercicioedital = ($this->l20_exercicioedital == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_exercicioedital"] : $this->l20_exercicioedital);
            $this->l20_leidalicitacao = ($this->l20_leidalicitacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_leidalicitacao"] : $this->l20_leidalicitacao);

            if ($this->l20_dtpulicacaopncp == "") {
                $this->l20_dtpulicacaopncp_dia = ($this->l20_dtpulicacaopncp_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpulicacaopncp_dia"] : $this->l20_dtpulicacaopncp_dia);
                $this->l20_dtpulicacaopncp_mes = ($this->l20_dtpulicacaopncp_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpulicacaopncp_mes"] : $this->l20_dtpulicacaopncp_mes);
                $this->l20_dtpulicacaopncp_ano = ($this->l20_dtpulicacaopncp_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpulicacaopncp_ano"] : $this->l20_dtpulicacaopncp_ano);
                if ($this->l20_dtpulicacaopncp_dia != "") {
                    $this->l20_dtpulicacaopncp = $this->l20_dtpulicacaopncp_ano . "-" . $this->l20_dtpulicacaopncp_mes . "-" . $this->l20_dtpulicacaopncp_dia;
                }
            }

            $this->l20_linkpncp = ($this->l20_linkpncp == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_linkpncp"] : $this->l20_linkpncp);
            $this->l20_diariooficialdivulgacao = ($this->l20_diariooficialdivulgacao == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_diariooficialdivulgacao"] : $this->l20_diariooficialdivulgacao);

            if ($this->l20_dtpulicacaoedital == "") {
                $this->l20_dtpulicacaoedital_dia = ($this->l20_dtpulicacaoedital_dia == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpulicacaoedital_dia"] : $this->l20_dtpulicacaoedital_dia);
                $this->l20_dtpulicacaoedital_mes = ($this->l20_dtpulicacaoedital_mes == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpulicacaoedital_mes"] : $this->l20_dtpulicacaoedital_mes);
                $this->l20_dtpulicacaoedital_ano = ($this->l20_dtpulicacaoedital_ano == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dtpulicacaoedital_ano"] : $this->l20_dtpulicacaoedital_ano);
                if ($this->l20_dtpulicacaoedital_dia != "") {
                    $this->l20_dtpulicacaoedital = $this->l20_dtpulicacaoedital_ano . "-" . $this->l20_dtpulicacaoedital_mes . "-" . $this->l20_dtpulicacaoedital_dia;
                }
            }

            $this->l20_linkedital = ($this->l20_linkedital == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_linkedital"] : $this->l20_linkedital);
            $this->l20_mododisputa = ($this->l20_mododisputa == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_mododisputa"] : $this->l20_mododisputa);
        } else {
            $this->l20_codigo = ($this->l20_codigo == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_codigo"] : $this->l20_codigo);
        }
        $this->l20_amparolegal = ($this->l20_amparolegal == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_amparolegal"] : $this->l20_amparolegal);
        $this->l20_categoriaprocesso = ($this->l20_categoriaprocesso == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_categoriaprocesso"] : $this->l20_categoriaprocesso);
        $this->l20_justificativapncp = ($this->l20_justificativapncp == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_justificativapncp"] : $this->l20_justificativapncp);
        $this->l20_receita = ($this->l20_receita == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_receita"] : $this->l20_receita);
        $this->l20_horaaberturaprop = ($this->l20_horaaberturaprop == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_horaaberturaprop"] : $this->l20_horaaberturaprop);
        $this->l20_horaencerramentoprop = ($this->l20_horaencerramentoprop == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_horaencerramentoprop"] : $this->l20_horaencerramentoprop);
        $this->l20_dispensaporvalor = ($this->l20_dispensaporvalor == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_dispensaporvalor"] : $this->l20_dispensaporvalor);
        $this->l20_lances = ($this->l20_lances == "" ? @$GLOBALS["HTTP_POST_VARS"]["l20_lances"] : $this->l20_lances);
    }

    // funcao para inclusao aqui
    function incluir($l20_codigo, $convite)
    {
        $this->atualizacampos();

        $tribunal = $this->buscartribunal($this->l20_codtipocom);

        if ($tribunal == 48 && ($this->l20_numeroconvidado == "" || $this->l20_numeroconvidado == null)) {
            $this->erro_sql = "Voc� informou o tipo de modalidade  CONVITE. Para esta modalidade � \\n\\n obrigatorio preencher o campo Numero Convidado";
            $this->erro_campo = "l20_numeroconvidado";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_condicoespag == null || $this->l20_condicoespag == "") {
            $this->erro_sql = " Campo condicoes de pagamento nao Informado.";
            $this->erro_campo = "l20_condicoespag";
            $this->erro_banco = "";
            $this->erro_msg = "Usu?rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($tribunal == 100 || $tribunal == 101 || $tribunal == 102 || $tribunal == 103) {

            if (strlen($this->l20_justificativa) < 10 || strlen($this->l20_justificativa) > 250) {
                $this->erro_msg = "Usu�rio: \\n\\n O campo Justificativa deve ter no m�nimo 10 caracteres e no m�ximo 250 \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }

            if (trim($this->l20_justificativa) == null) {
                $this->erro_sql = "Voc� informou um tipo de 'INEXIGIBILIDADE'. Para este tipo �  \\n\\n obrigatorio preencher os campos: Justificativa";
                $this->erro_campo = "l20_justificativa";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if ($tribunal == 100 || $tribunal == 101 || $tribunal == 102 || $tribunal == 103) {

            if (trim($this->l20_razao) == null || (strlen($this->l20_razao) < 10 || strlen($this->l20_razao) > 250)) {
                $this->erro_sql = "Usu�rio: \\n\\n O campo Raz�o deve ter no m�nimo 10 caracteres e no m�ximo 250 \\n\\n";
                $this->erro_campo = "l20_razao";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if ($this->l20_numero == null) {
            $this->erro_sql = " Campo Numera��o nao Informado.";
            $this->erro_campo = "l20_numero";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_id_usucria == null) {
            $this->erro_sql = " Campo Cod. Usu�rio nao Informado.";
            $this->erro_campo = "l20_id_usucria";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_datacria == null) {
            $this->erro_sql = " Campo Data Cria��o nao Informado.";
            $this->erro_campo = "l20_datacria";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_horacria == null) {
            $this->erro_sql = " Campo Hora Cria��o nao Informado.";
            $this->erro_campo = "l20_horacria";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_horaaber == null) {
            $this->l20_horaaber = $this->l20_horacria;
        }

        if ($this->l20_dataaberproposta == "null" || $this->l20_dataaberproposta == "" and $tribunal != 100 and $tribunal != 101 and $tribunal != 102 and $tribunal != 103) {
            $this->erro_sql = "Campo Abertura das Propostas n�o Informado";
            $this->erro_campo = "l20_dataaberproposta";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_dataaber == null and $tribunal != 100 and $tribunal != 101 and $tribunal != 102 and $tribunal != 103) {
            $this->erro_sql = " Campo Data Edital/Convite n�o Informado.";
            $this->erro_campo = "l20_dataaber";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_objeto == null) {
            $this->erro_sql = " Campo Objeto n�o Informado.";
            $this->erro_campo = "l20_objeto";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        } else {

            if (strlen($this->l20_objeto) < 15 and strlen($this->l20_objeto) > 1000) {
                $this->erro_msg = "Usu�rio: \\n\\n O campo Objeto deve ter no m�nimo 15 caracteres e no m�ximo 1000 \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if ($this->l20_tipojulg == null || !$this->l20_tipojulg) {
            $this->erro_sql = " Campo Tipo de Julgamento n�o Informado.";
            $this->erro_campo = "l20_tipojulg";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_liclocal == null) {
            $this->erro_sql = " Campo C�digo do Local da Licita��o n�o Informado.";
            $this->erro_campo = "l20_liclocal";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_procadmin == null) {
            $this->l20_procadmin = "";
        }
        if ($this->l20_correto == null) {
            $this->erro_sql = " Campo Correto n�o Informado.";
            $this->erro_campo = "l20_correto";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_instit == null) {
            $this->erro_sql = " Campo Institui��o n�o Informado.";
            $this->erro_campo = "l20_instit";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_licsituacao == null) {
            $this->erro_sql = " Campo Situa��o da Licita��o n�o Informado.";
            $this->erro_campo = "l20_licsituacao";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_edital == null) {
            $this->erro_sql = " Campo Licitacao n�o Informado.";
            $this->erro_campo = "l20_edital";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if($this->l20_dispensaporvalor == null || $this->l20_dispensaporvalor == ""){
            $this->l20_dispensaporvalor = 'f';
          }
        if ($this->l20_nroedital == null) {
            if (in_array($tribunal, array(48, 49, 50, 52, 53, 54)) && db_getsession('DB_anousu') >= 2020) {
                $this->erro_sql = " Campo Numero Edital n�o Informado.";
                $this->erro_campo = "l20_nroedital";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->l20_nroedital = 'null';
            }
        }
        if ($this->l20_anousu == null) {
            $this->erro_sql = " Campo Exerc�cio n�o Informado.";
            $this->erro_campo = "l20_anousu";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_usaregistropreco == null) {
            $this->erro_sql = " Campo Registro Pre�o n�o Informado.";
            $this->erro_campo = "l20_usaregistropreco";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($tribunal != 100 and $tribunal != 101 and $tribunal != 102 and $tribunal != 103) {
            if ($this->l20_equipepregao == null) {
                $this->erro_sql = " Campo Comiss�o de Licita��o n�o Informado!";
                $this->erro_campo = "l20_equipepregao";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if ($this->l20_tipoprocesso == null) {
            $this->l20_tipoprocesso = 'null';
        }

        if ($this->l20_numeroconvidado == null) {
            $this->l20_numeroconvidado = 'null';
        }

        if ($this->l20_datapublicacao1 == null) {
            $this->l20_datapublicacao1 = 'null';
        } else {
            $this->l20_datapublicacao1 = "'$this->l20_datapublicacao1'";
        }
        if ($this->l20_datapublicacao2 == null) {
            $this->l20_datapublicacao2 = 'null';
        } else {
            $this->l20_datapublicacao2 = "'$this->l20_datapublicacao2'";
        }

        if ($this->l20_recdocumentacao == null and $tribunal != 100 and $tribunal != 101 and $tribunal != 102 and $tribunal != 103) {
            $this->l20_recdocumentacao = 'null';
        }

        if ($this->l20_numeroconvidado == null) {
            $this->l20_numeroconvidado = 'null';
        }

        if ($this->l20_naturezaobjeto == null) {
            $this->erro_sql = " Campo Natureza do Objeto n�o Informado.";
            $this->erro_campo = "l20_naturezaobjeto";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_regimexecucao == 0 || $this->l20_regimexecucao == "0") {
            $this->l20_regimexecucao = 'NULL';
        }

        if ($this->l20_prazoentrega == null) {
            $this->erro_sql = " Campo Prazo de entrega n�o Informado.";
            $this->erro_campo = "l20_prazoentrega";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_tipnaturezaproced == null) {
            $this->erro_sql = " Campo Tipo da Natureza do Procedimento n�o foi informada.";
            $this->erro_campo = "l20_tipnaturezaproced";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        /*valida combos */
        if ($this->l20_critdesempate == null || !$this->l20_critdesempate) {
            $this->erro_sql = " Campo  Crit�rio de desempate n�o foi informado.";
            $this->erro_campo = "l20_critdesempate";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_destexclusiva == null || !$this->l20_destexclusiva) {
            $this->erro_sql = " Campo Destina��o Exclusiva n�o foi informada.";
            $this->erro_campo = "l20_destexclusiva";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_subcontratacao == null || !$this->l20_subcontratacao) {
            $this->erro_sql = " Campo Sub. Contrata��o  n�o foi informada.";
            $this->erro_campo = "l20_subcontratacao ";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        if ($this->l20_limitcontratacao == null || !$this->l20_limitcontratacao) {
            $this->erro_sql = " Campo Limite Contrata��o n�o foi informada.";
            $this->erro_campo = "l20_limitcontratacao";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }


        if ($this->l20_execucaoentrega == null) {
            $this->erro_sql = " Campo execucao entrega n�o foi informado.";
            $this->erro_campo = "l20_execucaoentrega";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_codepartamento == null) {
            $this->erro_sql = " Campo codigo departamento n�o foi informado.";
            $this->erro_campo = "l20_codepartamento";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_diames == null) {
            $this->erro_sql = " Campo Unid.Execucao/Entrega entrega n�o foi informado.";
            $this->erro_campo = "l20_diames";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_horaaberturaprop == null) {
            $this->erro_sql = " Campo Hora de Abertura Proposta n�o foi informado.";
            $this->erro_campo = "l20_horaaberturaprop";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_horaencerramentoprop == null) {
            $this->erro_sql = " Campo Hora Encerramento Proposta n�o foi informado.";
            $this->erro_campo = "l20_horaencerramentoprop";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_criterioadjudicacao == '0') {
            $this->erro_sql = " Campo Criterio de Adjudica��o invalido.";
            $this->erro_campo = "l20_criterioadjudicacao";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($l20_codigo == "" || $l20_codigo == null) {
            $result = db_query("select nextval('liclicita_l20_codigo_seq')");
            if ($result == false) {
                $this->erro_banco = str_replace("\n", "", @pg_last_error());
                $this->erro_sql = "Verifique o cadastro da sequencia: liclicita_l20_codigo_seq do campo: l20_codigo";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
            $this->l20_codigo = pg_result($result, 0, 0);
        } else {
            $result = db_query("select last_value from liclicita_l20_codigo_seq");
            if (($result != false) && (pg_result($result, 0, 0) < $l20_codigo)) {
                $this->erro_sql = " Campo l20_codigo maior que �ltimo n�mero da sequencia.";
                $this->erro_banco = "Sequencia menor que este n�mero.";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                $this->l20_codigo = $l20_codigo;
            }
        }
        if (($this->l20_codigo == null) || ($this->l20_codigo == "")) {
            $this->erro_sql = " Campo l20_codigo nao declarado.";
            $this->erro_banco = "Chave Primaria zerada.";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if (($this->l20_leidalicitacao == null) || ($this->l20_leidalicitacao == "0")) {
            $this->erro_sql = " Campo l20_leidalicitacao nao informado.";
            $this->erro_banco = "l20_leidalicitacao.";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($convite != "" || $convite != null) {
            $sql = "select  l45_data from  licpregao  inner join liclicita on liclicita.l20_equipepregao=licpregao.l45_sequencial where l20_codigo= $this->l20_codigo";

            $result = db_query($sql);
            $l45_data = pg_result($result, 0, 0);
            if ($l45_data > $this->l20_datacria) {
                $this->erro_sql = " A data da equipe de preg�o  n�o deve ser superior a data da cria��o .";
                $this->erro_campo = "l20_equipepregao";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if ($this->l20_amparolegal == null) {
            $this->erro_sql = " Campo Tipo da Natureza do Procedimento n�o foi informada.";
            $this->erro_campo = "l20_amparolegal";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }

        if ($this->l20_tipoprocesso == "0") {
            $this->erro_sql = " Campo Tipo de processo n�o foi informado.";
            $this->erro_campo = "l20_tipoprocesso";
            $this->erro_banco = "";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        
        if (db_getsession('DB_anousu') >= 2020 && !$this->l20_dispensaporvalor) {
            $this->l20_cadinicial = 1;
            $this->l20_exercicioedital = db_getsession('DB_anousu');
        } else {
            $this->l20_cadinicial = $this->l20_cadinicial;
            $this->l20_exercicioedital = 'null';
        }
        
        $sql = "insert into liclicita(
                                 l20_codigo
                ,l20_edital
                ,l20_codtipocom
                ,l20_numero
                ,l20_id_usucria
                ,l20_tipliticacao
                ,l20_naturezaobjeto
                ,l20_regimexecucao
                ,l20_numeroconvidado
                ,l20_dataaber
                ,l20_horaaber
                ,l20_datacria
                ,l20_horacria
                ,l20_recdocumentacao
                ,l20_tipojulg
                ,l20_procadmin
                ,l20_usaregistropreco
                ,l20_liclocal
                ,l20_liccomissao
                ,l20_equipepregao
                ,l20_local
                ,l20_objeto
                ,l20_localentrega
                ,l20_prazoentrega
                ,l20_condicoespag
                ,l20_validadeproposta
                ,l20_aceitabilidade
                ,l20_justificativa
                ,l20_razao
                ,l20_instit
                ,l20_tipnaturezaproced
                ,l20_tipoprocesso
                ,l20_critdesempate
                ,l20_destexclusiva
                ,l20_subcontratacao
                ,l20_limitcontratacao
                ,l20_veicdivulgacao
                ,l20_clausulapro
                ,l20_codepartamento
                ,l20_diames
                ,l20_execucaoentrega
                ,l20_anousu
                ,l20_dtpubratificacao
                ,l20_formacontroleregistropreco
                ,l20_criterioadjudicacao
                ,l20_dtlimitecredenciamento
                ,l20_nroedital
                ,l20_cadinicial
                ,l20_exercicioedital
                ,l20_leidalicitacao
                ,l20_mododisputa
                ,l20_dataaberproposta
                ,l20_dataencproposta
                ,l20_amparolegal
                ,l20_categoriaprocesso
                ,l20_justificativapncp
                ,l20_receita
                ,l20_horaaberturaprop
                ,l20_horaencerramentoprop
                ,l20_dispensaporvalor
                ,l20_lances
                       )
                values (
                 $this->l20_codigo
                ,$this->l20_edital
                ,$this->l20_codtipocom
                ,$this->l20_numero
                ,$this->l20_id_usucria
                ,$this->l20_tipliticacao
                ,$this->l20_naturezaobjeto
                ,$this->l20_regimexecucao
                ,$this->l20_numeroconvidado
                ," . ($this->l20_dataaber == "null" || $this->l20_dataaber == "" ? "null" : "'" . $this->l20_dataaber . "'") . "
                ,'$this->l20_horaaber'
                ," . ($this->l20_datacria == "null" || $this->l20_datacria == "" ? "null" : "'" . $this->l20_datacria . "'") . "
                ,'$this->l20_horacria'
                ," . ($this->l20_recdocumentacao == "null" || $this->l20_recdocumentacao == "" ? "null" : "'" . $this->l20_recdocumentacao . "'") . "
                ,$this->l20_tipojulg
                ,'$this->l20_procadmin'
                ,'$this->l20_usaregistropreco'
                ,'$this->l20_liclocal'
                ," . ($this->l20_liccomissao == "null" || $this->l20_liccomissao == "" ? "0" : "'" . $this->l20_liccomissao . "'") . "
                ," . ($this->l20_equipepregao == "null" || $this->l20_equipepregao == "" ? "0" : "'" . $this->l20_equipepregao . "'") . "
                ,'$this->l20_local'
                ,'$this->l20_objeto'
                ,'$this->l20_localentrega'
                ,'$this->l20_prazoentrega'
                ,'$this->l20_condicoespag'
                ,'$this->l20_validadeproposta'
                ,'$this->l20_aceitabilidade'
                ,'$this->l20_justificativa'
                ,'$this->l20_razao'
                ,$this->l20_instit
                ,$this->l20_tipnaturezaproced
                ,$this->l20_tipoprocesso
                ,$this->l20_critdesempate
                ,$this->l20_destexclusiva
                ,$this->l20_subcontratacao
                ,$this->l20_limitcontratacao
                ,'$this->l20_veicdivulgacao'
                ,'$this->l20_clausulapro'
                ,$this->l20_codepartamento
                ,$this->l20_diames
                ,$this->l20_execucaoentrega
                ,$this->l20_anousu
                ," . ($this->l20_dtpubratificacao == "null" || $this->l20_dtpubratificacao == "" ? "null" : "'" . $this->l20_dtpubratificacao . "'") . "
                ,$this->l20_formacontroleregistropreco
                ,$this->l20_criterioadjudicacao
                ," . ($this->l20_dtlimitecredenciamento == "null" || $this->l20_dtlimitecredenciamento == "" ? "null" : "'" . $this->l20_dtlimitecredenciamento . "'") . "
                ,$this->l20_nroedital
                ,$this->l20_cadinicial
                ,$this->l20_exercicioedital
                ,$this->l20_leidalicitacao
                ,$this->l20_mododisputa
                ," . ($this->l20_dataaberproposta == "null" || $this->l20_dataaberproposta == "" ? "null" : "'" . $this->l20_dataaberproposta . "'") . "
                ," . ($this->l20_dataencproposta == "null" || $this->l20_dataencproposta == "" ? "null" : "'" . $this->l20_dataencproposta . "'") . "
                ,$this->l20_amparolegal
                ,$this->l20_categoriaprocesso
                ,'$this->l20_justificativapncp'
                ,'$this->l20_receita'
                ,'$this->l20_horaaberturaprop'
                ,'$this->l20_horaencerramentoprop'
                ,'$this->l20_dispensaporvalor'
                ,'$this->l20_lances'
                      )";
        $result = db_query($sql);
        // echo $sql;
        // exit;
        
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            if (strpos(strtolower($this->erro_banco), "duplicate key") != 0) {
                $this->erro_sql = "liclicita ($this->l20_codigo) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_banco = "liclicita j� Cadastrado";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            } else {
                $this->erro_sql = "liclicita ($this->l20_codigo) nao Inclu�do. Inclusao Abortada.";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            }
            $this->erro_status = "0";
            $this->numrows_incluir = 0;
            return false;
        }
        
        $this->erro_banco = "";
        $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : " . $this->l20_codigo;
        $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
        $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
        $this->erro_status = "1";
        $this->numrows_incluir = pg_affected_rows($result);
        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (
            !isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))
        ) {
            $resaco = $this->sql_record($this->sql_query_file($this->l20_codigo));
            if (($resaco != false) || ($this->numrows != 0)) {
                $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                $acount = pg_result($resac, 0, 0);
                $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                $resac = db_query("insert into db_acountkey values($acount,7589,'$this->l20_codigo','I')");
                $resac = db_query("insert into db_acount values($acount,1260,7589,'','" . AddSlashes(pg_result($resaco, 0, 'l20_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7590,'','" . AddSlashes(pg_result($resaco, 0, 'l20_codtipocom')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7594,'','" . AddSlashes(pg_result($resaco, 0, 'l20_numero')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7592,'','" . AddSlashes(pg_result($resaco, 0, 'l20_id_usucria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7591,'','" . AddSlashes(pg_result($resaco, 0, 'l20_datacria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7593,'','" . AddSlashes(pg_result($resaco, 0, 'l20_horacria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7595,'','" . AddSlashes(pg_result($resaco, 0, 'l20_dataaber')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7596,'','" . AddSlashes(pg_result($resaco, 0, 'l20_dtpublic')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7597,'','" . AddSlashes(pg_result($resaco, 0, 'l20_horaaber')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7598,'','" . AddSlashes(pg_result($resaco, 0, 'l20_local')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7599,'','" . AddSlashes(pg_result($resaco, 0, 'l20_objeto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7782,'','" . AddSlashes(pg_result($resaco, 0, 'l20_tipojulg')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7909,'','" . AddSlashes(pg_result($resaco, 0, 'l20_liccomissao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,7908,'','" . AddSlashes(pg_result($resaco, 0, 'l20_liclocal')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,8986,'','" . AddSlashes(pg_result($resaco, 0, 'l20_procadmin')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,10010,'','" . AddSlashes(pg_result($resaco, 0, 'l20_correto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,10103,'','" . AddSlashes(pg_result($resaco, 0, 'l20_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,10287,'','" . AddSlashes(pg_result($resaco, 0, 'l20_licsituacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,12605,'','" . AddSlashes(pg_result($resaco, 0, 'l20_edital')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,12606,'','" . AddSlashes(pg_result($resaco, 0, 'l20_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,15270,'','" . AddSlashes(pg_result($resaco, 0, 'l20_usaregistropreco')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,15424,'','" . AddSlashes(pg_result($resaco, 0, 'l20_localentrega')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,15425,'','" . AddSlashes(pg_result($resaco, 0, 'l20_prazoentrega')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,15426,'','" . AddSlashes(pg_result($resaco, 0, 'l20_condicoespag')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,15427,'','" . AddSlashes(pg_result($resaco, 0, 'l20_validadeproposta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009528,'','" . AddSlashes(pg_result($resaco, 0, 'l20_razao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009527,'','" . AddSlashes(pg_result($resaco, 0, 'l20_justificativa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009526,'','" . AddSlashes(pg_result($resaco, 0, 'l20_aceitabilidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009525,'','" . AddSlashes(pg_result($resaco, 0, 'l20_equipepregao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009524,'','" . AddSlashes(pg_result($resaco, 0, 'l20_nomeveiculo2')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009522,'','" . AddSlashes(pg_result($resaco, 0, 'l20_datapublicacao2')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009520,'','" . AddSlashes(pg_result($resaco, 0, 'l20_nomeveiculo1')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009519,'','" . AddSlashes(pg_result($resaco, 0, 'l20_datapublicacao1')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009518,'','" . AddSlashes(pg_result($resaco, 0, 'l20_datadiario')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009517,'','" . AddSlashes(pg_result($resaco, 0, 'l20_recdocumentacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009515,'','" . AddSlashes(pg_result($resaco, 0, 'l20_numeroconvidado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009514,'','" . AddSlashes(pg_result($resaco, 0, 'l20_descontotab')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009513,'','" . AddSlashes(pg_result($resaco, 0, 'l20_regimexecucao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009512,'','" . AddSlashes(pg_result($resaco, 0, 'l20_naturezaobjeto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                $resac = db_query("insert into db_acount values($acount,1260,2009511,'','" . AddSlashes(pg_result($resaco, 0, 'l20_tipliticacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
            }
        }
        return true;
    }
    
    // funcao para alteracao
    function alterar($l20_codigo = null, $convite, $ibuscartribunal = null)
    {


        if (empty($this->l20_naturezaobjeto)) {
            $this->l20_naturezaobjeto = 0;
        }

        $this->atualizacampos();
        $convite = trim(strtoupper($convite));


        if ($ibuscartribunal == null) {
            $tribunal = $this->buscartribunal($this->l20_codtipocom);
        }

        $sql = " update liclicita set ";
        $virgula = "";

        if ($tribunal == 100 || $tribunal == 101 || $tribunal == 102 || $tribunal == 103) {
            $sql .= $virgula . " l20_local = null ";
            $virgula = ",";
        } else {
            if (trim($this->l20_local != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_local"]))) {

                $sql .= $virgula . " l20_local = '$this->l20_local' ";
                $virgula = ",";
            } else {
                $sql .= $virgula . " l20_local = ''";
                $virgula = ",";
            }
        }
        if (trim($this->l20_localentrega != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_localentrega"]))) {

            $sql .= $virgula . " l20_localentrega = '$this->l20_localentrega' ";
            $virgula = ",";
        } else {
            $sql .= $virgula . " l20_localentrega = ''";
            $virgula = ",";
        }

        //altera??o do campo l20_tipliticacao e l20_naturezaobjeto

        if (trim($this->l20_tipliticacao != 0 || isset($GLOBALS["HTTP_POST_VARS"]["l20_tipliticacao"]))) {
            $sql .= $virgula . " l20_tipliticacao = '$this->l20_tipliticacao' ";
            $virgula = ",";
        }

        if (trim($this->l20_naturezaobjeto != 0 || isset($GLOBALS["HTTP_POST_VARS"]["l20_naturezaobjeto"]))) {
            $sql .= $virgula . " l20_naturezaobjeto = '$this->l20_naturezaobjeto' ";
            $virgula = ",";
        }

        if (trim($this->l20_validadeproposta != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_validadeproposta"]))) {
            $sql .= $virgula . " l20_validadeproposta = '$this->l20_validadeproposta' ";
            $virgula = ",";
        } else {
            $sql .= $virgula . " l20_validadeproposta = ''";
            $virgula = ",";
        }
        if (trim($this->l20_dispensaporvalor != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_dispensaporvalor"]))) {
            $sql .= $virgula . " l20_dispensaporvalor = '$this->l20_dispensaporvalor' ";
            $virgula = ",";
        }
        if (trim($this->l20_lances != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_lances"]))) {
            $sql .= $virgula . " l20_lances = '$this->l20_lances' ";
            $virgula = ",";
        }

        if (trim($this->l20_aceitabilidade != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_aceitabilidade"]))) {
            $sql .= $virgula . " l20_aceitabilidade = '$this->l20_aceitabilidade' ";
            $virgula = ",";
        } else {
            $sql .= $virgula . " l20_aceitabilidade = ''";
            $virgula = ",";
        }

        /*
        if (trim($this->l20_nomeveiculo1 != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_nomeveiculo1"]))) {
            $sql .= $virgula . " l20_nomeveiculo1 = '$this->l20_nomeveiculo1' ";
            $virgula = ",";
        } else {
            $sql .= $virgula . " l20_nomeveiculo1 = ''";
            $virgula = ",";
        }

        if (trim($this->l20_nomeveiculo2 != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_nomeveiculo2"]))) {
            $sql .= $virgula . " l20_nomeveiculo2 = '$this->l20_nomeveiculo2' ";
            $virgula = ",";
        } else {
            $sql .= $virgula . " l20_nomeveiculo2 = ''";
            $virgula = ",";
        } */


        if (trim($this->l20_numeroconvidado != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_numeroconvidado"]))) {
            if (trim($this->l20_numeroconvidado == null)) {
                $this->l20_numeroconvidado = 'null';
            }
            $sql .= $virgula . " l20_numeroconvidado = $this->l20_numeroconvidado ";
            $virgula = ",";
            if ($this->l20_numeroconvidado == null && $tribunal == 30) {
                $this->erro_sql = "Voc� informou o tipo de modalidade  CONVITE. Para esta modalidade � \\n\\n obrigatorio preencher o campo Numero Convidado";
                $this->erro_campo = "l20_numeroconvidado";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_dtpubratificacao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_dtpubratificacao"])) && ($tribunal == 100 || $tribunal == 101 || $tribunal == 102 || $tribunal == 103)) {
            if (trim($this->l20_dtpubratificacao == null)) {
                $sql .= $virgula . " l20_dtpubratificacao = null ";
                $virgula = ",";
            } else {
                $sql .= $virgula . " l20_dtpubratificacao = '$this->l20_dtpubratificacao' ";
                $virgula = ",";
            }
        }

        if (trim($this->l20_dtlimitecredenciamento != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_dtlimitecredenciamento"])) && ($tribunal == 102 || $tribunal == 103)) {
            $sql .= $virgula . " l20_dtlimitecredenciamento = '$this->l20_dtlimitecredenciamento '";
            $virgula = ",";
        } else {
            $sql .= $virgula . " l20_dtlimitecredenciamento = null";
            $virgula = ",";
        }

        if (trim($this->l20_tipoprocesso != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_tipoprocesso"])) && ($tribunal == 100 || $tribunal == 101 || $tribunal == 102 || $tribunal == 103)) {
            $sql .= $virgula . " l20_tipoprocesso = '$this->l20_tipoprocesso' ";
            $virgula = ",";
            if ($this->l20_tipoprocesso == "0") {
                $this->erro_sql = " Campo Tipo de processo n�o foi informado.";
                $this->erro_campo = "l20_tipoprocesso";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_veicdivulgacao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_veicdivulgacao"])) && ($tribunal == 100 || $tribunal == 101 || $tribunal == 102 || $tribunal == 103)) {

            $sql .= $virgula . " l20_veicdivulgacao = '$this->l20_veicdivulgacao'";
            $virgula = ",";
        }

        if (trim($this->l20_justificativa != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_justificativa"])) && ($tribunal == 100 || $tribunal == 101 || $tribunal == 102 || $tribunal == 103)) {
            $sql .= $virgula . " l20_justificativa = '$this->l20_justificativa' ";
            $virgula = ",";
        }

        if (trim($this->l20_razao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_razao"])) && ($tribunal == 100 || $tribunal == 101 || $tribunal == 102 || $tribunal == 103)) {
            $sql .= $virgula . " l20_razao = '$this->l20_razao' ";
            $virgula = ",";
        }

        if (trim($this->l20_condicoespag != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_condicoespag"]))) {
            $sql .= $virgula . " l20_condicoespag = '$this->l20_condicoespag' ";
            $virgula = ",";
            if ($this->l20_condicoespag == null) {
                $this->erro_sql = " Campo condicoes de pagamento nao Informado.";
                $this->erro_campo = "l20_condicoespag";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_numero != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_numero"]))) {
            $sql .= $virgula . " l20_numero = $this->l20_numero ";
            $virgula = ",";
            if ($this->l20_numero == null) {
                $this->erro_sql = " Campo Numera��o nao Informado.";
                $this->erro_campo = "l20_numero";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }


        if (trim($this->l20_id_usucria != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_id_usucria"]))) {
            $sql .= $virgula . " l20_id_usucria = $this->l20_id_usucria ";
            $virgula = ",";
            if ($this->l20_id_usucria == null) {
                $this->erro_sql = " Campo Cod. Usu�rio nao Informado.";
                $this->erro_campo = "l20_id_usucria";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }


        if (trim($this->l20_datacria != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_datacria"]))) {
            $sql .= $virgula . " l20_datacria = '$this->l20_datacria '";
            $virgula = ",";

            if ($this->l20_datacria == null) {
                $this->erro_sql = " Campo Data Cria��o nao Informado.";
                $this->erro_campo = "l20_datacria";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }


        if (trim($this->l20_horacria != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_horacria"]))) {
            $sql .= $virgula . " l20_horacria = '$this->l20_horacria' ";
            $virgula = ",";
            if ($this->l20_horacria == null) {
                $this->erro_sql = " Campo Hora Cria��o nao Informado.";
                $this->erro_campo = "l20_horacria";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if ($this->l20_horaaber == null) {

            $this->l20_horaaber = '$this->l20_horacria';
            $sql .= $virgula . " l20_horaaber = '$this->l20_horacria'";
            $virgula = ",";
        } else {
            $sql .= $virgula . " l20_horaaber = '$this->l20_horaaber'";
            $virgula = ",";
        }

        if (trim($this->l20_recdocumentacao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_recdocumentacao"]))) {
            if ($this->l20_recdocumentacao == null) {
                $sql .= $virgula . " l20_recdocumentacao = null ";
                $virgula = ",";
            } else {
                $sql .= $virgula . " l20_recdocumentacao = ' $this->l20_recdocumentacao '";
                $virgula = ",";
            }
        }

        if (trim($this->l20_dataaber != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_dataaber"]))) {

            if (trim($this->l20_dataaber) == null and $tribunal != 100 and $tribunal != 101 and $tribunal != 102 and $tribunal != 103) {
                $this->erro_sql = " Campo Data Edital/Convite nao Informado.";
                $this->erro_campo = "l20_dataaber";
                $this->erro_banco = "";
                $this->erro_msg = "Usuario: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else if (trim($this->l20_dataaber) == null and $tribunal == 100 || $tribunal == 101 || $tribunal == 102 || $tribunal == 103) {
                $sql .= $virgula . " l20_dataaber = null ";
                $virgula = ",";
            } else {
                $sql .= $virgula . " l20_dataaber ='$this->l20_dataaber' ";
                $virgula = ",";
            }
        }

        if (trim($this->l20_objeto != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_objeto"]))) {
            $sql .= $virgula . " l20_objeto =' $this->l20_objeto' ";
            $virgula = ",";
            if (trim($this->l20_objeto) == null) {
                $this->erro_sql = " Campo Objeto nao Informado.";
                $this->erro_campo = "l20_objeto";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            } else {
                if (strlen($this->l20_objeto) < 15 and strlen($this->l20_objeto) > 1000) {
                    $this->erro_msg = "Usu�rio: \\n\\n O campo Objeto deve ter no m�nimo 15 caracteres e no m�ximo 1000 \\n\\n";
                    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }

        if (trim($this->l20_tipojulg != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_tipojulg"]))) {
            $sql .= $virgula . " l20_tipojulg = $this->l20_tipojulg ";
            $virgula = ",";
            if (trim($this->l20_tipojulg) == null || !trim($this->l20_tipojulg)) {
                $this->erro_sql = " Campo Tipo de Julgamento nao Informado.";
                $this->erro_campo = "l20_tipojulg";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_liccomissao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_liccomissao"]))) {
            $sql .= $virgula . " l20_liccomissao = $this->l20_liccomissao ";
            $virgula = ",";
            if (trim($this->l20_liccomissao) == null) {
                $this->erro_sql = " Campo C�digo da Comiss�o nao Informado.";
                $this->erro_campo = "l20_liccomissao";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        } else {
            if ($convite != "" || $convite != null) {
                $sql = "select l30_data  from liccomissao where l30_codigo=$this->l20_liccomissao";
                $result = db_query($sql);
                $l30_data = pg_result($result, 0, 0);
                if ($l30_data > $this->l20_datacria) {
                    $this->erro_sql = " A data da comiss�o nao deve ser superior a data da criacao .";
                    $this->erro_campo = "l20_liccomissao";
                    $this->erro_banco = "";
                    $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }

        if (trim($this->l20_liclocal != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_liclocal"]))) {
            $sql .= $virgula . " l20_liclocal = $this->l20_liclocal ";
            $virgula = ",";
            if (trim($this->l20_liclocal) == null) {
                $this->erro_sql = " Campo C�digo do Local da Licita��o nao Informado.";
                $this->erro_campo = "l20_liclocal";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_procadmin) == null || trim($this->l20_procadmin) == "") {
            $sql .= $virgula . " l20_procadmin =null ";
            $virgula = ",";
        } else {
            $sql .= $virgula . " l20_procadmin = '$this->l20_procadmin' ";
            $virgula = ",";
        }

        if (trim($this->l20_dataaberproposta) != null || trim($this->l20_dataaberproposta) != "") {
            $sql .= $virgula . " l20_dataaberproposta = '$this->l20_dataaberproposta' ";
            $virgula = ",";
            if (trim($this->l20_dataaberproposta) == null) {
                $this->erro_sql = " Campo data da abertura da proposta n�o informado.";
                $this->erro_campo = "l20_dataaberproposta";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_dataencproposta) != null || trim($this->l20_dataencproposta) != "") {
            $sql .= $virgula . " l20_dataencproposta = '$this->l20_dataencproposta' ";
            $virgula = ",";
        }

        if (trim($this->l20_correto != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_correto"]))) {
            $sql .= $virgula . " l20_correto = '$this->l20_correto' ";
            $virgula = ",";
            if (trim($this->l20_correto) == null) {
                $this->erro_sql = " Campo Correto nao Informado.";
                $this->erro_campo = "l20_correto";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_instit != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_instit"]))) {
            $sql .= $virgula . " l20_instit = $this->l20_instit ";
            $virgula = ",";
            if (trim($this->l20_instit) == null) {
                $this->erro_sql = " Campo Institui��o nao Informado.";
                $this->erro_campo = "l20_instit";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_licsituacao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_licsituacao"]))) {
            $sql .= $virgula . " l20_licsituacao = $this->l20_licsituacao ";
            $virgula = ",";
            if (trim($this->l20_licsituacao) == null) {
                $this->erro_sql = " Campo Situa��o da Licita��o nao Informado.";
                $this->erro_campo = "l20_licsituacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_edital != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_edital"]))) {
            $sql .= $virgula . " l20_edital = $this->l20_edital ";
            $virgula = ",";
            if (trim($this->l20_edital) == null) {
                $this->erro_sql = " Campo Licitacao nao Informado.";
                $this->erro_campo = "l20_edital";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_nroedital != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_nroedital"]))) {
            if (trim($this->l20_nroedital) != null) {
                $sql .= $virgula . " l20_nroedital = $this->l20_nroedital ";
                $virgula = ",";
            } else if (trim($this->l20_nroedital) == null && in_array($tribunal, array(48, 49, 50, 52, 53, 54)) && $this->l20_anousu >= 2020) {
                $this->erro_sql = " Campo N�mero Edital nao Informado.";
                $this->erro_campo = "l20_nroedital";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_cadinicial != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_cadinicial"]))) {
            $sql .= $virgula . " l20_cadinicial = $this->l20_cadinicial ";
            $virgula = ",";
        }

        if (trim($this->l20_exercicioedital != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_exercicioedital"]))) {
            $sql .= $virgula . " l20_exercicioedital = '$this->l20_exercicioedital' ";
            $virgula = ",";
        }


        if (trim($this->l20_anousu != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_anousu"]))) {
            $sql .= $virgula . " l20_anousu = $this->l20_anousu ";
            $virgula = ",";
            if (trim($this->l20_anousu) == null) {
                $this->erro_sql = " Campo Exerc�cio nao Informado.";
                $this->erro_campo = "l20_anousu";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_usaregistropreco != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_usaregistropreco"]))) {
            $sql .= $virgula . " l20_usaregistropreco = '$this->l20_usaregistropreco' ";
            $virgula = ",";
            if (trim($this->l20_usaregistropreco) == null) {
                $this->erro_sql = " Campo Registro Pre�o nao Informado.";
                $this->erro_campo = "l20_usaregistropreco";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }
        if (trim($this->l20_formacontroleregistropreco != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_formacontroleregistropreco"]))) {
            $sql .= $virgula . " l20_formacontroleregistropreco = '$this->l20_formacontroleregistropreco' ";
            $virgula = ",";
            if (trim($this->l20_formacontroleregistropreco) == null) {
                $this->erro_sql = " Campo Registro Pre�o nao Informado.";
                $this->erro_campo = "l20_formacontroleregistropreco";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if($this->l20_equipepregao == null && in_array($tribunal, array(100,101,102,103))){
            $this->l20_equipepregao = '0';
          }

        if (trim($this->l20_equipepregao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_equipepregao"])) {
            $sql .= $virgula . " l20_equipepregao = $this->l20_equipepregao";
            $virgula = ",";
            if (trim($this->l20_equipepregao) == null and $tribunal != 100 and $tribunal != 101 and $tribunal != 102 and $tribunal != 103) {
                $this->erro_sql = " Campo Comiss�o de Licita��o n�o Informado.";
                $this->erro_campo = "l20_equipepregao";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }


        if ($convite != "" || $convite != null) {
            //$sql=  "select l45_data from licpregao where l45_sequencial=$this->l20_equipepregao";  $sql .= " l20_codigo = $this->l20_codigo";
            $comissao = "select  l45_data from  licpregao  inner join liclicita on liclicita.l20_equipepregao=licpregao.l45_sequencial where l20_codigo= $this->l20_codigo";
            //echo $sql;exit;
            $result = db_query($comissao);
            if (pg_num_rows($result) > 1) {
                $l45_data = pg_result($result, 0, 0);
                if ($l45_data > $this->l20_datacria) {
                    $this->erro_sql = " A data da equipe de pregao  nao deve ser superior a data da criacao .";
                    $this->erro_campo = "l20_equipepregao";
                    $this->erro_banco = "";
                    $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                    $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                    $this->erro_status = "0";
                    return false;
                }
            }
        }


        if (trim($this->l20_descontotab != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_descontotab"]))) {
            $sql .= $virgula . " l20_descontotab = $this->l20_descontotab ";
            $virgula = ",";
            if ($this->l20_descontotab == null) {
                $this->erro_sql = " Campo Desconto Tabela nao Informado.";
                $this->erro_campo = "l20_descontotab";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_regimexecucao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_regimexecucao"]))) {
            $sql .= $virgula . " l20_regimexecucao = $this->l20_regimexecucao ";
            $virgula = ",";
        }

        if (trim($this->l20_prazoentrega != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_prazoentrega"]))) {
            $sql .= $virgula . " l20_prazoentrega =' $this->l20_prazoentrega' ";
            $virgula = ",";
            if ($this->l20_prazoentrega == null) {
                $this->erro_sql = " Campo Prazo de entrega nao Informado.";
                $this->erro_campo = "l20_prazoentrega";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_tipnaturezaproced != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_tipnaturezaproced"]))) {
            $sql .= $virgula . " l20_tipnaturezaproced = $this->l20_tipnaturezaproced ";
            $virgula = ",";
            if ($this->l20_tipnaturezaproced == null) {
                $this->erro_sql = " Campo Tipo da Natureza do Procedimento nao foi informada.";
                $this->erro_campo = "l20_tipnaturezaproced";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_amparolegal != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_amparolegal"]))) {
            $sql .= $virgula . " l20_amparolegal = $this->l20_amparolegal ";
            $virgula = ",";
            if ($this->l20_amparolegal == null) {
                $this->erro_sql = " Campo Tipo da Natureza do Procedimento nao foi informada.";
                $this->erro_campo = "l20_amparolegal";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_categoriaprocesso != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_categoriaprocesso"]))) {
            $sql .= $virgula . " l20_categoriaprocesso = $this->l20_categoriaprocesso ";
            $virgula = ",";
        }


        if (trim($this->l20_critdesempate != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_critdesempate"]))) {
            $sql .= $virgula . " l20_critdesempate = $this->l20_critdesempate ";
            $virgula = ",";
            if ($this->l20_critdesempate == null || !$this->l20_critdesempate) {
                $this->erro_sql = " Campo  Crit�rio de desempate nao foi informado.";
                $this->erro_campo = "l20_critdesempate";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_destexclusiva != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_destexclusiva"]))) {
            $sql .= $virgula . " l20_destexclusiva = $this->l20_destexclusiva ";
            $virgula = ",";
            if ($this->l20_destexclusiva == null || !$this->l20_destexclusiva) {
                $this->erro_sql = " Campo Destina��o Exclusiva  nao foi informada.";
                $this->erro_campo = "l20_destexclusiva";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_subcontratacao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_subcontratacao"]))) {
            $sql .= $virgula . " l20_subcontratacao = $this->l20_subcontratacao ";
            $virgula = ",";
            if ($this->l20_subcontratacao == null || !$this->l20_subcontratacao) {
                $this->erro_sql = " Campo Sub. Contrata��o  nao foi informada.";
                $this->erro_campo = "l20_subcontratacao ";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_limitcontratacao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_limitcontratacao"]))) {
            $sql .= $virgula . " l20_limitcontratacao = $this->l20_limitcontratacao ";
            $virgula = ",";
            if ($this->l20_limitcontratacao == null || !$this->l20_limitcontratacao) {
                $this->erro_sql = " Campo Limite Contrata��o nao foi informada.";
                $this->erro_campo = "l20_limitcontratacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }


        if (trim($this->l20_regata) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_regata"])) {
            $sql .= $virgula . " l20_regata = $this->l20_regata ";
            $virgula = ",";
        }

        if (trim($this->l20_interporrecurso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_interporrecurso"])) {
            $sql .= $virgula . " l20_interporrecurso = $this->l20_interporrecurso ";
            $virgula = ",";
        }

        if (trim($this->l20_descrinterporrecurso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_descrinterporrecurso"])) {
            $sql .= $virgula . " l20_descrinterporrecurso = '$this->l20_descrinterporrecurso' ";
            $virgula = ",";
            if (trim($this->l20_descrinterporrecurso) == null && $this->l20_interporrecurso == 1) {
                $this->erro_sql = " Campo Descri��o nao foi informado.";
                $this->erro_campo = "l20_descrinterporrecurso";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        /**
         * Estes Dois blocos est?o duplicados para o campo l20_descrinterporrecurso. Verificar a vers?o anterior deste arquivo.
         */
        if (trim($this->l20_descrinterporrecurso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_descrinterporrecurso"])) {
            $sql .= $virgula . " l20_descrinterporrecurso = '$this->l20_descrinterporrecurso' ";
            $virgula = ",";
            if (trim($this->l20_descrinterporrecurso) == null && $this->l20_interporrecurso == 1) {
                $this->erro_sql = " Campo Descri��o nao foi informado.";
                $this->erro_campo = "l20_descrinterporrecurso";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_descrinterporrecurso) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_descrinterporrecurso"])) {
            $sql .= $virgula . " l20_descrinterporrecurso = '$this->l20_descrinterporrecurso' ";
            $virgula = ",";
            if (trim($this->l20_descrinterporrecurso) == null && $this->l20_interporrecurso == 1) {
                $this->erro_sql = " Campo Descri��o nao foi informado.";
                $this->erro_campo = "l20_descrinterporrecurso";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_clausulapro) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_clausulapro"])) {
            $sql .= $virgula . " l20_clausulapro =' $this->l20_clausulapro' ";
            $virgula = ",";
        }

        if (trim($this->l20_criterioadjudicacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_criterioadjudicacao"])) {
            $sql .= $virgula . " l20_criterioadjudicacao = $this->l20_criterioadjudicacao ";
            $virgula = ",";
        }


        if (trim($this->l20_codepartamento) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_codepartamento"])) {
            $sql .= $virgula . " l20_codepartamento = $this->l20_codepartamento ";
            $virgula = ",";
            if (trim($this->l20_codepartamento) == null) {
                $this->erro_sql = " Campo codigo departamento nao Informado.";
                $this->erro_campo = "l20_codepartamento";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }


        if (trim($this->l20_diames) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_diames"])) {
            $sql .= $virgula . " l20_diames = $this->l20_diames ";
            $virgula = ",";
            if (trim($this->l20_diames) == null) {
                $this->erro_sql = " Campo dia/mes nao Informado.";
                $this->erro_campo = "l20_diames";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }


        if (trim($this->l20_execucaoentrega) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_execucaoentrega"])) {
            $sql .= $virgula . " l20_execucaoentrega = $this->l20_execucaoentrega ";
            $virgula = ",";
            if (trim($this->l20_execucaoentrega) == null) {
                $this->erro_sql = " Campo execucao entrega nao Informado.";
                $this->erro_campo = "l20_execucaoentrega";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_codtipocom) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_codtipocom"])) {
            $sql .= $virgula . " l20_codtipocom = $this->l20_codtipocom ";
            $virgula = ",";
            if (trim($this->l20_codtipocom) == null) {
                $this->erro_sql = " Campo C�digo do tipo de compra nao Informado.";
                $this->erro_campo = "l20_codtipocom";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_leidalicitacao) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_leidalicitacao"])) {
            $sql .= $virgula . " l20_leidalicitacao = $this->l20_leidalicitacao ";
            $virgula = ",";
            if (trim($this->l20_leidalicitacao) == null) {
                $this->erro_sql = " Campo Lei de Licita��o n�o Informado.";
                $this->erro_campo = "l20_leidalicitacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (!empty($this->l20_datadiario) || isset($GLOBALS["HTTP_POST_VARS"]["l20_datadiario"])) {
            $data = ($this->l20_datadiario !== null) ? "'" . $this->l20_datadiario . "'" : 'null';
            $sql .= $virgula . " l20_datadiario = $data";
            $virgula = ",";
        }

        if (!empty($this->l20_dtpulicacaopncp) || isset($GLOBALS["HTTP_POST_VARS"]["l20_dtpulicacaopncp"])) {
            $data = ($this->l20_dtpulicacaopncp !== null) ? "'" . $this->l20_dtpulicacaopncp . "'" : 'null';
            $sql .= $virgula . " l20_dtpulicacaopncp = $data";
            $virgula = ",";
        }

        if (!empty($this->l20_datapublicacao1) || isset($GLOBALS["HTTP_POST_VARS"]["l20_datapublicacao1"])) {
            $data = ($this->l20_datapublicacao1 !== null) ? "'" . $this->l20_datapublicacao1 . "'" : 'null';
            $sql .= $virgula . " l20_datapublicacao1 = $data";
            $virgula = ",";
        }

        if (!empty($this->l20_datapublicacao2) || isset($GLOBALS["HTTP_POST_VARS"]["l20_datapublicacao2"])) {
            $data = ($this->l20_datapublicacao2 !== null) ? "'" . $this->l20_datapublicacao2 . "'" : 'null';
            $sql .= $virgula . " l20_datapublicacao2 = $data";
            $virgula = ",";
        }

        if (!empty($this->l20_dtpulicacaoedital) || isset($GLOBALS["HTTP_POST_VARS"]["l20_dtpulicacaoedital"])) {
            $data = ($this->l20_dtpulicacaoedital !== null) ? "'" . $this->l20_dtpulicacaoedital . "'" : 'null';
            $sql .= $virgula . " l20_dtpulicacaoedital = $data";
            $virgula = ",";
        }

        if (trim($this->l20_mododisputa) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_mododisputa"])) {
            $sql .= $virgula . " l20_mododisputa = '$this->l20_mododisputa'";
            $virgula = ",";
            if (trim($this->l20_mododisputa) == null) {
                $this->erro_sql = " Campo Modo Disputa n�o Informado.";
                $this->erro_campo = "l20_mododisputa";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_justificativapncp) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_justificativapncp"])) {
            $sql .= $virgula . " l20_justificativapncp = '$this->l20_justificativapncp'";
            $virgula = ",";
        }

        if (trim($this->l20_receita) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_receita"])) {
            $sql .= $virgula . " l20_receita = '$this->l20_receita'";
            $virgula = ",";
        }

        if (trim($this->l20_horaaberturaprop) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_horaaberturaprop"])) {

            $sql .= $virgula . " l20_horaaberturaprop = '$this->l20_horaaberturaprop'";
            $virgula = ",";
            if (trim($this->l20_horaaberturaprop) == null) {
                $this->erro_sql = " Hora Abertura Proposta n�o Informado.";
                $this->erro_campo = "l20_horaaberturaprop";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_horaencerramentoprop) != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_horaencerramentoprop"])) {

            $sql .= $virgula . " l20_horaencerramentoprop = '$this->l20_horaencerramentoprop'";
            $virgula = ",";
            if (trim($this->l20_horaencerramentoprop) == null) {
                $this->erro_sql = " Hora Encerramento Proposta n�o Informado.";
                $this->erro_campo = "l20_horaencerramentoprop";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        $sql .= " where ";
        if ($l20_codigo != null) {
            $sql .= " l20_codigo = $this->l20_codigo";
        }
        $result = db_query($sql);


        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "liclicita nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->l20_codigo;
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "liclicita nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->l20_codigo;
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $this->l20_codigo;
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    // funcao para exclusao
    public function excluir($l20_codigo = null, $dbwhere = null)
    {

        $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
        if (
            !isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
                && ($lSessaoDesativarAccount === false))
        ) {
            if ($dbwhere == null || $dbwhere == "") {
                $resaco = $this->sql_record($this->sql_query_file($l20_codigo));
            } else {
                $resaco = $this->sql_record($this->sql_query_file(null, "*", null, $dbwhere));
            }
            if (($resaco != false) || ($this->numrows != 0)) {
                for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {
                    $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
                    $acount = pg_result($resac, 0, 0);
                    $resac = db_query("insert into db_acountacesso values($acount," . db_getsession("DB_acessado") . ")");
                    $resac = db_query("insert into db_acountkey values($acount,7589,'$l20_codigo','E')");
                    $resac = db_query("insert into db_acount values($acount,1260,7589,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_codigo')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7590,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_codtipocom')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7594,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_numero')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7592,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_id_usucria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7591,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_datacria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7593,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_horacria')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7595,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_dataaber')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7596,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_dtpublic')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7597,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_horaaber')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7598,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_local')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7599,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_objeto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7782,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_tipojulg')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7909,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_liccomissao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,7908,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_liclocal')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,8986,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_procadmin')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,10010,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_correto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,10103,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_instit')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,10287,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_licsituacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,12605,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_edital')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,12606,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_anousu')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,15270,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_usaregistropreco')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,15424,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_localentrega')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,15425,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_prazoentrega')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,15426,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_condicoespag')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,15427,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_validadeproposta')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009528,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_razao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009527,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_justificativa')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009526,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_aceitabilidade')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009525,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_equipepregao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009524,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_nomeveiculo2')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009522,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_datapublicacao2')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009520,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_nomeveiculo1')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009519,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_datapublicacao1')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009518,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_datadiario')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009517,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_recdocumentacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");

                    $resac = db_query("insert into db_acount values($acount,1260,2009515,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_numeroconvidado')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009514,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_descontotab')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009513,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_regimexecucao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009512,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_naturezaobjeto')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,2009511,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_tipliticacao')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                    $resac = db_query("insert into db_acount values($acount,1260,20854,'','" . AddSlashes(pg_result($resaco, $iresaco, 'l20_formacontroleregistropreco')) . "'," . db_getsession('DB_datausu') . "," . db_getsession('DB_id_usuario') . ")");
                }
            }
        }
        $sql = " delete from liclicita
                    where ";
        $sql2 = "";
        if ($dbwhere == null || $dbwhere == "") {
            if ($l20_codigo != "") {
                if ($sql2 != "") {
                    $sql2 .= " and ";
                }
                $sql2 .= " l20_codigo = $l20_codigo ";
            }
        } else {
            $sql2 = $dbwhere;
        }
        $result = db_query($sql . $sql2);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "liclicita nao Exclu�do. Exclus�o Abortada.\\n";
            $this->erro_sql .= "Valores : " . $l20_codigo;
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_excluir = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "liclicita nao Encontrado. Exclus�o n�o Efetuada.\\n";
                $this->erro_sql .= "Valores : " . $l20_codigo;
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_excluir = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
                $this->erro_sql .= "Valores : " . $l20_codigo;
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
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
        if ($result == false) {
            $this->numrows = 0;
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Erro ao selecionar os registros.";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        $this->numrows = pg_numrows($result);
        if ($this->numrows == 0) {
            $this->erro_banco = "";
            $this->erro_sql = "Record Vazio na Tabela:liclicita";
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            return false;
        }
        return $result;
    }

    // funcao do sql

    function sql_query($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $groupby = null)
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
        $sql .= " from liclicita ";
        $sql .= "      inner join db_config         on db_config.codigo = liclicita.l20_instit";
        $sql .= "      inner join db_usuarios       on db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita          on cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join pctipocompratribunal on pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal";
        $sql .= "      inner join liclocal          on liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao       on liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      inner join licsituacao       on licsituacao.l08_sequencial = liclicita.l20_licsituacao";
        $sql .= "      inner join cgm               on  cgm.z01_numcgm = db_config.numcgm";
        $sql .= "      inner join db_config as dbconfig on  dbconfig.codigo = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra      on pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      inner join bairro            on bairro.j13_codi = liclocal.l26_bairro";
        $sql .= "      inner join ruas              on ruas.j14_codigo = liclocal.l26_lograd";
        $sql .= "      left join homologacaoadjudica on l202_licitacao = l20_codigo";
        $sql .= "      left join liclicitaproc     on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
        $sql .= "      left join protprocesso      on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";
        $sql .= "      left join habilitacaoforn   on l206_licitacao = l20_codigo";
        $sql .= "      left join cgm as cgmfornecedor on cgmfornecedor.z01_numcgm = l206_fornecedor";
        $sql .= "      left join liclicitem on l21_codliclicita = l20_codigo";
        $sql .= "      left join pcorcamitemlic on pc26_liclicitem=l21_codigo";
        $sql .= "      left join pcorcamitem on pc22_orcamitem=pc26_orcamitem";
        $sql .= "      left join pcorcamjulg on pc24_orcamitem=pc22_orcamitem and pc24_pontuacao = 1";
        $sql .= "      left join pcorcamforne on pc21_orcamforne=pc24_orcamforne";
        $sql .= "      left join pcorcamval on pc23_orcamitem = pc22_orcamitem and pc23_orcamforne = pc21_orcamforne";
        $sql .= "      left join pcorcam on pc20_codorc = pc22_codorc";
        $sql .= "      left join acordo on ac16_licitacao = l20_codigo";
        $sql .= "      left join liccontrolepncp on l213_licitacao = l20_codigo";
        $sql .= "      left join licontroleatarppncp on l215_licitacao = l20_codigo";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($groupby != null) {
            $sql .= " group by ";
            $campos_sql = explode("#", $groupby);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $groupby;
        }
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

    function sql_query_old($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join db_config         on db_config.codigo = liclicita.l20_instit";
        $sql .= "      inner join db_usuarios       on db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita          on cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join liclocal          on liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao       on liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      inner join licsituacao       on licsituacao.l08_sequencial = liclicita.l20_licsituacao";
        $sql .= "      inner join cgm               on  cgm.z01_numcgm = db_config.numcgm";
        $sql .= "      inner join db_config as dbconfig on  dbconfig.codigo = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra      on pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      inner join bairro            on bairro.j13_codi = liclocal.l26_bairro";
        $sql .= "      inner join ruas              on ruas.j14_codigo = liclocal.l26_lograd";
        $sql .= "      left  join liclicitaproc     on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
        $sql .= "      left  join protprocesso      on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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
        //echo $sql;
        return $sql;
    }

    function sql_query_relatorio($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $groupby = null)
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
        $sql .= " from liclicita ";
        $sql .= "      inner join db_config         on db_config.codigo = liclicita.l20_instit";
        $sql .= "      inner join db_usuarios       on db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita          on cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join pctipocompratribunal on pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal";
        $sql .= "      inner join liclocal          on liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao       on liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      inner join licsituacao       on licsituacao.l08_sequencial = liclicita.l20_licsituacao";
        $sql .= "      inner join cgm               on  cgm.z01_numcgm = db_config.numcgm";
        $sql .= "      inner join db_config as dbconfig on  dbconfig.codigo = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra      on pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      inner join bairro            on bairro.j13_codi = liclocal.l26_bairro";
        $sql .= "      inner join ruas              on ruas.j14_codigo = liclocal.l26_lograd";
        //    $sql .= "      left join homologacaoadjudica on l202_licitacao = l20_codigo";
        //    $sql .= "      left join liclicitaproc     on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
        //    $sql .= "      left join protprocesso      on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";
        //    $sql .= "      left join habilitacaoforn   on l206_licitacao = l20_codigo";
        //    $sql .= "      left join cgm as cgmfornecedor on cgmfornecedor.z01_numcgm = l206_fornecedor";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($groupby != null) {
            $sql .= " group by ";
            $campos_sql = explode("#", $groupby);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $groupby;
        }
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

    function sql_query_edital($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $groupby = null)
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
        $sql .= " from liclicita ";
        $sql .= "      inner join db_config         on db_config.codigo = liclicita.l20_instit";
        $sql .= "      inner join db_usuarios       on db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita          on cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join pctipocompratribunal on pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal";
        $sql .= "      inner join liclocal          on liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao       on liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      inner join licsituacao       on licsituacao.l08_sequencial = liclicita.l20_licsituacao";
        $sql .= "      inner join cgm               on  cgm.z01_numcgm = db_config.numcgm";
        $sql .= "      inner join db_config as dbconfig on  dbconfig.codigo = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra      on pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      inner join bairro            on bairro.j13_codi = liclocal.l26_bairro";
        $sql .= "      inner join ruas              on ruas.j14_codigo = liclocal.l26_lograd";
        $sql .= "      left join homologacaoadjudica on l202_licitacao = l20_codigo";
        $sql .= "      left join liclicitaproc     on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
        $sql .= "      left join protprocesso      on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";
        $sql .= "      left join habilitacaoforn   on l206_licitacao = l20_codigo";
        $sql .= "      left join cgm as cgmfornecedor on cgmfornecedor.z01_numcgm = l206_fornecedor";
        $sql .= "      left join liclicitem on l21_codliclicita = l20_codigo";
        $sql .= "       left join pcorcamitemlic on pc26_liclicitem=l21_codigo";
        $sql .= "       left join pcorcamitem on pc22_orcamitem=pc26_orcamitem";
        $sql .= "       left join pcorcamjulg on pc24_orcamitem=pc22_orcamitem and pc24_pontuacao = 1";
        $sql .= "       left join pcorcamforne on pc21_orcamforne=pc24_orcamforne";
        $sql .= "       left join pcorcamval on pc23_orcamitem = pc22_orcamitem and pc23_orcamforne = pc21_orcamforne";
        $sql .= "       left join pcorcam on pc20_codorc = pc22_codorc";
        $sql .= "       left join liclancedital ON liclancedital.l47_liclicita = liclicita.l20_codigo";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($groupby != null) {
            $sql .= " group by ";
            $campos_sql = explode("#", $groupby);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $groupby;
        }
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

    //fun??o sql equipregao - relatorio homologaprocesso

    function sql_query_equipepregao($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $groupby = null)
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
        $sql .= " from liclicita ";
        $sql .= "      inner join db_config         on db_config.codigo = liclicita.l20_instit";
        $sql .= "      inner join db_usuarios       on db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita          on cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join pctipocompratribunal on pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal";
        $sql .= "      inner join liclocal          on liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao       on liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      inner join licsituacao       on licsituacao.l08_sequencial = liclicita.l20_licsituacao";
        $sql .= "      inner join cgm               on  cgm.z01_numcgm = db_config.numcgm";
        $sql .= "      inner join db_config as dbconfig on  dbconfig.codigo = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra      on pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      inner join bairro            on bairro.j13_codi = liclocal.l26_bairro";
        $sql .= "      inner join ruas              on ruas.j14_codigo = liclocal.l26_lograd";
        $sql .= "      left join homologacaoadjudica on l202_licitacao = l20_codigo";
        $sql .= "      left join liclicitaproc     on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
        $sql .= "      left join protprocesso      on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";
        $sql .= "      left join habilitacaoforn   on l206_licitacao = l20_codigo";
        $sql .= "      left join cgm as cgmfornecedor on cgmfornecedor.z01_numcgm = l206_fornecedor";
        $sql .= "      left join liclicitem on l21_codliclicita = l20_codigo";
        $sql .= "       left join pcorcamitemlic on pc26_liclicitem=l21_codigo";
        $sql .= "       left join pcorcamitem on pc22_orcamitem=pc26_orcamitem";
        $sql .= "       left join pcorcamjulg on pc24_orcamitem=pc22_orcamitem and pc24_pontuacao = 1";
        $sql .= "       left join pcorcamforne on pc21_orcamforne=pc24_orcamforne";
        $sql .= "       left join pcorcamval on pc23_orcamitem = pc22_orcamitem and pc23_orcamforne = pc21_orcamforne";
        $sql .= "       left join pcorcam on pc20_codorc = pc22_codorc";
        $sql .= "       inner join licpregao on l45_sequencial = l20_equipepregao";
        $sql .= "       inner join liccomissaocgm on l31_licitacao = l20_codigo";
        $sql .= "       inner join cgm cgmrepresentante on cgmrepresentante.z01_numcgm = l31_numcgm";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
            }
        } else if ($dbwhere != "") {
            $sql2 = " where $dbwhere";
        }
        $sql .= $sql2;
        if ($groupby != null) {
            $sql .= " group by ";
            $campos_sql = explode("#", $groupby);
            $virgula = "";
            for ($i = 0; $i < sizeof($campos_sql); $i++) {
                $sql .= $virgula . $campos_sql[$i];
                $virgula = ",";
            }
        } else {
            $sql .= $groupby;
        }
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
    function sql_query_file($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function sql_query_consulta($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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
     * query para chegar at? o vinculo de contratos
     */
    function sql_queryContratos($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join db_config             on db_config.codigo            = liclicita.l20_instit";
        $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario      = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita              on cflicita.l03_codigo         = liclicita.l20_codtipocom";
        $sql .= "      inner join liclocal              on liclocal.l26_codigo         = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao           on liccomissao.l30_codigo      = liclicita.l20_liccomissao";
        $sql .= "      inner join licsituacao           on licsituacao.l08_sequencial  = liclicita.l20_licsituacao";
        $sql .= "      inner join cgm                   on cgm.z01_numcgm              = db_config.numcgm";
        $sql .= "      inner join db_config as dbconfig on dbconfig.codigo             = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra          on pctipocompra.pc50_codcom    = cflicita.l03_codcom";
        $sql .= "      inner join bairro                on bairro.j13_codi             = liclocal.l26_bairro";
        $sql .= "      inner join ruas                  on ruas.j14_codigo             = liclocal.l26_lograd";
        $sql .= "       left join liclicitaproc         on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
        $sql .= "       left join protprocesso          on protprocesso.p58_codproc    = liclicitaproc.l34_protprocesso";
        $sql .= "       left join liclicitem            on liclicita.l20_codigo        = l21_codliclicita ";
        $sql .= "       left join acordoliclicitem      on liclicitem.l21_codigo       = acordoliclicitem.ac24_liclicitem ";
        $sql .= "       left join liclancedital         on liclancedital.l47_liclicita = liclicita.l20_codigo ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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
        //      echo $sql;
        return $sql;
    }

    function sql_queryContratosContass($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join db_config             on db_config.codigo            = liclicita.l20_instit";
        $sql .= "      inner join db_usuarios           on db_usuarios.id_usuario      = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita              on cflicita.l03_codigo         = liclicita.l20_codtipocom";
        $sql .= "      inner join liclocal              on liclocal.l26_codigo         = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao           on liccomissao.l30_codigo      = liclicita.l20_liccomissao";
        $sql .= "      inner join licsituacao           on licsituacao.l08_sequencial  = liclicita.l20_licsituacao";
        $sql .= "      inner join cgm                   on cgm.z01_numcgm              = db_config.numcgm";
        $sql .= "      inner join db_config as dbconfig on dbconfig.codigo             = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra          on pctipocompra.pc50_codcom    = cflicita.l03_codcom";
        $sql .= "      inner join bairro                on bairro.j13_codi             = liclocal.l26_bairro";
        $sql .= "      inner join ruas                  on ruas.j14_codigo             = liclocal.l26_lograd";
        $sql .= "       left join liclicitaproc         on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
        $sql .= "       left join protprocesso          on protprocesso.p58_codproc    = liclicitaproc.l34_protprocesso";
        $sql .= "       left join liclicitem            on liclicita.l20_codigo        = l21_codliclicita ";
        $sql .= "       left join acordoliclicitem      on liclicitem.l21_codigo       = acordoliclicitem.ac24_liclicitem ";
        $sql .= "      inner join parecerlicitacao     on parecerlicitacao.l200_licitacao     = liclicita.l20_codigo ";
        $sql .= "      inner join liclicitasituacao     on liclicitasituacao.l11_liclicita     = liclicita.l20_codigo ";
        $sql .= "      left  join liclancedital         on liclancedital.l47_liclicita = liclicita.l20_codigo ";
        $sql .= "      left  join homologacaoadjudica        on homologacaoadjudica.l202_licitacao = liclicita.l20_codigo ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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
        //      echo $sql;
        return $sql;
    }

    function sql_query_modelos($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join cflicitatemplate     on cflicitatemplate.l35_cflicita        = liclicita.l20_codtipocom                 ";
        $sql .= "      inner join db_documentotemplate on db_documentotemplate.db82_sequencial = cflicitatemplate.l35_db_documentotemplate";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function sql_query_modelosatas($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join cflicitatemplateata  on cflicitatemplateata.l37_cflicita     = liclicita.l20_codtipocom                     ";
        $sql .= "      inner join db_documentotemplate on db_documentotemplate.db82_sequencial = cflicitatemplateata.l37_db_documentotemplate ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function sql_query_pco($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join db_config  on  db_config.codigo = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      inner join liclicitem on liclicitem.l21_codliclicita = liclicita.l20_codigo";
        $sql .= "      inner join pcorcamitemlic on pcorcamitemlic.pc26_liclicitem = liclicitem.l21_codigo";
        $sql .= "      inner join pcorcamitem on pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem";
        $sql .= "      inner join pcorcam on pcorcam.pc20_codorc = pcorcamitem.pc22_codorc";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function sql_query_baixa($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      inner join db_config  on  db_config.codigo = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      inner join bairro  on  bairro.j13_codi = liclocal.l26_bairro";
        $sql .= "      inner join ruas  on  ruas.j14_codigo = liclocal.l26_lograd";
        $sql .= "    inner join licbaixa on l20_codigo=l28_liclicita";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function sql_query_lib($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita  on  cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join liclocal  on  liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao  on  liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      inner join db_config  on  db_config.codigo = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      inner join bairro  on  bairro.j13_codi = liclocal.l26_bairro";
        $sql .= "      inner join ruas  on  ruas.j14_codigo = liclocal.l26_lograd";
        $sql .= "    left join liclicitaweb on l20_codigo=l29_liclicita";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function sql_query_modelosminutas($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join cflicitatemplateminuta on cflicitatemplateminuta.l41_cflicita  = liclicita.l20_codtipocom                     ";
        $sql .= "      inner join db_documentotemplate   on db_documentotemplate.db82_sequencial = cflicitatemplateminuta.l41_db_documentotemplate ";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function sql_query_pcodireta($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from pcorcam";
        $sql .= "      left join pcorcamitem on pcorcamitem.pc22_codorc = pc20_codorc";
        $sql .= "      left join pcorcamitemproc on pcorcamitemproc.pc31_orcamitem = pcorcamitem.pc22_orcamitem";
        $sql .= "      left join pcprocitem on pcorcamitemproc.pc31_pcprocitem = pc81_codprocitem";
        $sql .= "      left join pcorcamval on pc23_orcamitem = pc22_orcamitem";
        $sql .= "      left join pcorcamitemlic on pcorcamitemlic.pc26_orcamitem = pcorcamitemproc.pc31_orcamitem";
        $sql .= "      left join liclicitem on liclicitem.l21_codigo= pcorcamitemlic.pc26_liclicitem";
        $sql .= "      left join liclicita on liclicitem.l21_codliclicita= l20_codigo";
        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function sql_query_julgamento_licitacao($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join liclicitem               on liclicitem.l21_codliclicita         = liclicita.l20_codigo              ";
        $sql .= "      inner join pcprocitem               on pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem      ";
        $sql .= "      inner join pcproc                   on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc           ";
        $sql .= "      inner join solicitem                on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem         ";
        $sql .= "      inner join solicita                 on solicita.pc10_numero                = solicitem.pc11_numero             ";
        $sql .= "      inner join solicitempcmater         on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo             ";
        $sql .= "      inner join pcmater                  on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater    ";
        $sql .= "      inner join pcorcamitemlic           on pcorcamitemlic.pc26_liclicitem      = liclicitem.l21_codigo             ";
        $sql .= "      inner join pcorcamval               on pcorcamval.pc23_orcamitem           = pcorcamitemlic.pc26_orcamitem     ";
        $sql .= "      inner join pcorcamforne             on pcorcamforne.pc21_orcamforne        = pcorcamval.pc23_orcamforne        ";
        $sql .= "      inner join pcorcamjulgamentologitem on pcorcamjulgamentologitem.pc93_pcorcamitem  = pcorcamval.pc23_orcamitem  ";
        $sql .= "                                         and pcorcamjulgamentologitem.pc93_pcorcamforne = pcorcamval.pc23_orcamforne ";
        $sql .= "      inner join pcorcamjulgamentolog     on pcorcamjulgamentolog.pc92_sequencial       = pcorcamjulgamentologitem.pc93_pcorcamjulgamentolog ";
        $sql .= "      inner join db_usuarios              on db_usuarios.id_usuario = pcorcamjulgamentolog.pc92_usuario   ";
        $sql .= "      inner join cgm as fornecedor        on fornecedor.z01_numcgm  = pcorcamforne.pc21_numcgm            ";

        $sql2 = "";
        if ($dbwhere == "") {

            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function sql_query_consulta_regpreco($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
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
        $sql .= " from liclicita ";
        $sql .= "      inner join liclicitem               on liclicitem.l21_codliclicita         = liclicita.l20_codigo              ";
        $sql .= "      inner join pcprocitem               on pcprocitem.pc81_codprocitem         = liclicitem.l21_codpcprocitem      ";
        $sql .= "      inner join pcproc                   on pcproc.pc80_codproc                 = pcprocitem.pc81_codproc           ";
        $sql .= "      inner join solicitem                on solicitem.pc11_codigo               = pcprocitem.pc81_solicitem         ";
        $sql .= "      inner join solicita                 on solicita.pc10_numero                = solicitem.pc11_numero             ";
        $sql .= "      inner join solicitempcmater         on solicitempcmater.pc16_solicitem     = solicitem.pc11_codigo             ";
        $sql .= "      inner join pcmater                  on pcmater.pc01_codmater               = solicitempcmater.pc16_codmater    ";
        $sql .= "      inner join pcorcamitemlic           on pcorcamitemlic.pc26_liclicitem      = liclicitem.l21_codigo             ";
        $sql .= "      inner join pcorcamval               on pcorcamval.pc23_orcamitem           = pcorcamitemlic.pc26_orcamitem     ";
        $sql .= "      inner join pcorcamforne             on pcorcamforne.pc21_orcamforne        = pcorcamval.pc23_orcamforne        ";
        $sql .= "      inner join cgm as fornecedor        on fornecedor.z01_numcgm  = pcorcamforne.pc21_numcgm            ";

        $sql2 = "";
        if ($dbwhere == "") {

            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function sql_query_dados_licitacao($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $sSituacao = '')
    {


        $sCampo = "";

        if ($sSituacao != '') {

            $sSituacao = "and l11_licsituacao = {$sSituacao}";
            $sCampo = ",liclicitasituacao.l11_obs";
            //echo "teste: ".$sCampo;
        }

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

        $sql .= $sCampo;

        $sql .= " from liclicita ";
        $sql .= "      inner join db_config     on db_config.codigo = liclicita.l20_instit";
        $sql .= "      inner join db_usuarios   on db_usuarios.id_usuario = liclicita.l20_id_usucria";
        $sql .= "      inner join cflicita      on cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join liclocal      on liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      inner join liccomissao   on liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      inner join licsituacao   on licsituacao.l08_sequencial = liclicita.l20_licsituacao";
        $sql .= "      inner join cgm           on  cgm.z01_numcgm = db_config.numcgm";
        $sql .= "      inner join db_config as dbconfig on  dbconfig.codigo = cflicita.l03_instit";
        $sql .= "      inner join pctipocompra  on pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      inner join bairro        on bairro.j13_codi = liclocal.l26_bairro";
        $sql .= "      inner join ruas          on ruas.j14_codigo = liclocal.l26_lograd";
        $sql .= "      left  join liclicitaproc on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
        $sql .= "      left  join protprocesso  on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";
        $sql .= "      inner join liclicitasituacao on liclicitasituacao.l11_liclicita = liclicita.l20_codigo $sSituacao";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function alterar_dtpubratificacao($l20_codigo)
    {
        $sql = "update liclicita set l20_dtpubratificacao = '$this->l20_dtpubratificacao' where l20_codigo = $l20_codigo";
        $result = db_query($sql);
    }

    function alterar_liclicitajulgamento($l20_codigo)
    {

        $sql = " update liclicita set ";
        $virgula = "";

        if (trim($this->l20_regata) != "") {
            $sql .= $virgula . " l20_regata = $this->l20_regata ";
            $virgula = ",";
        }

        if (trim($this->l20_interporrecurso) != "") {
            $sql .= $virgula . " l20_interporrecurso = $this->l20_interporrecurso ";
            $virgula = ",";
        }

        if (trim($this->l20_descrinterporrecurso) != "") {
            $sql .= $virgula . " l20_descrinterporrecurso = '$this->l20_descrinterporrecurso' ";
            $virgula = ",";
            if (trim($this->l20_descrinterporrecurso) == null && $this->l20_interporrecurso == 1) {
                $this->erro_sql = " Campo Descri��o nao foi informado.";
                $this->erro_campo = "l20_descrinterporrecurso";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        if (trim($this->l20_licsituacao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_licsituacao"]))) {
            $sql .= $virgula . " l20_licsituacao = $this->l20_licsituacao ";
            $virgula = ",";
            if (trim($this->l20_licsituacao) == null) {
                $this->erro_sql = " Campo Situa��o da Licita��o nao Informado.";
                $this->erro_campo = "l20_licsituacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        $sql .= " where ";
        if ($l20_codigo != null) {
            $sql .= " l20_codigo = $l20_codigo";
        } //die($sql);
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "liclicita nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->l20_codigo;
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "liclicita nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->l20_codigo;
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                //$this->erro_sql .= "Valores : ".$this->l20_codigo;
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }

    function alterarSituacaoCredenciamento($l20_codigo, $l20_licsituacao)
    {
        $sql = "update liclicita set l20_licsituacao = $l20_licsituacao where l20_codigo = $l20_codigo";

        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "liclicita nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->l20_codigo;
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        }
    }

    function getTipocomTribunal($l20_codigo)
    {
        $sqltipocom = "SELECT l03_pctipocompratribunal FROM liclicita
                       INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                       WHERE l20_codigo = $l20_codigo";
        return $sqltipocom;
    }

    function buscartribunal($l20_codtipocom)
    {


        $sSql = "SELECT a.l44_sequencial
      FROM cflicita
      INNER JOIN db_config ON db_config.codigo = cflicita.l03_instit
      INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
      INNER JOIN pctipocompratribunal ON pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal
      INNER JOIN cgm ON cgm.z01_numcgm = db_config.numcgm
      INNER JOIN db_tipoinstit ON db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit
      INNER JOIN pctipocompratribunal AS a ON a.l44_sequencial = pctipocompra.pc50_pctipocompratribunal
      WHERE cflicita.l03_codigo = $l20_codtipocom";
        $result = db_query($sSql);
        $tribunal = pg_result($result, 0, 0);

        /* Chave identica para todos os clientes , este ? o codigo do tribunal
        CONVITE=30
        INEXIGIBILIDADE=29
        Dispensa de Licitacao=101
        Inexigibilidade Por Credenciamento=102
      */
        return $tribunal;
    }


    function alterar_situacao($l20_codigo)
    {

        $sql = " update liclicita set ";
        $virgula = "";

        if (trim($this->l20_licsituacao != "" || isset($GLOBALS["HTTP_POST_VARS"]["l20_licsituacao"]))) {
            $sql .= $virgula . " l20_licsituacao = $this->l20_licsituacao ";
            $virgula = ",";
            if (trim($this->l20_licsituacao) == null) {
                $this->erro_sql = " Campo Situa��o da Licita��o nao Informado.";
                $this->erro_campo = "l20_licsituacao";
                $this->erro_banco = "";
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "0";
                return false;
            }
        }

        $sql .= " where ";
        if ($l20_codigo != null) {
            $sql .= " l20_codigo = $l20_codigo";
        } //die($sql);
        $result = db_query($sql);
        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "liclicita nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->l20_codigo;
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "liclicita nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->l20_codigo;
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
                //$this->erro_sql .= "Valores : ".$this->l20_codigo;
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }


    function sql_query_licitacao_transparencia($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "", $sSituacao = '')
    {


        $sCampo = "";

        if ($sSituacao != '') {

            $sSituacao = "and l11_licsituacao = {$sSituacao}";
            $sCampo = ",liclicitasituacao.l11_obs";
            echo "teste: " . $sCampo;
        }

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

        $sql .= $sCampo;

        $sql .= " from liclicita ";
        $sql .= "      left join db_config     on db_config.codigo = liclicita.l20_instit";
        $sql .= "      left join cflicita      on cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      left join liclocal      on liclocal.l26_codigo = liclicita.l20_liclocal";
        $sql .= "      left join liccomissao   on liccomissao.l30_codigo = liclicita.l20_liccomissao";
        $sql .= "      left join licsituacao   on licsituacao.l08_sequencial = liclicita.l20_licsituacao";
        $sql .= "      left join cgm           on  cgm.z01_numcgm = db_config.numcgm";
        $sql .= "      left join pctipocompra  on pctipocompra.pc50_codcom = cflicita.l03_codcom";
        $sql .= "      left join bairro        on bairro.j13_codi = liclocal.l26_bairro";
        $sql .= "      left join ruas          on ruas.j14_codigo = liclocal.l26_lograd";
        $sql .= "      left  join liclicitaproc on liclicitaproc.l34_liclicita = liclicita.l20_codigo";
        $sql .= "      left  join protprocesso  on protprocesso.p58_codproc = liclicitaproc.l34_protprocesso";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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


    function sql_query_comissao_pregao($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {


        $sCampo = "";

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

        $sql .= $sCampo;

        $sql .= " from liclicita ";
        $sql .= "      inner join db_config on l20_instit = codigo";
        $sql .= "      inner join cflicita      on cflicita.l03_codigo = liclicita.l20_codtipocom";
        $sql .= "      inner join licpregao on l45_sequencial = l20_equipepregao";
        $sql .= "      inner join licpregaocgm on l46_licpregao = l45_sequencial";
        $sql .= "      inner join cgm on z01_numcgm = l46_numcgm";

        $sql2 = "";
        if ($dbwhere == "") {
            if ($l20_codigo != null) {
                $sql2 .= " where liclicita.l20_codigo = $l20_codigo ";
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

    function verificaMembrosModalidade($modalidade, $equipepregao)
    {

        $sSQL = "";
        switch ($modalidade) {

            case 'pregao':

                $sSQL .= "

                  SELECT mapoio.l46_tipo, pregoeiro.l46_tipo FROM

                    (SELECT l46_licpregao, l46_tipo
                      FROM licpregaocgm
                        INNER JOIN cgm ON cgm.z01_numcgm = licpregaocgm.l46_numcgm
                        INNER JOIN licpregao ON licpregao.l45_sequencial = licpregaocgm.l46_licpregao
                          WHERE l46_tipo = 2) AS mapoio

                    INNER JOIN

                    (SELECT l46_licpregao, l46_tipo
                      FROM licpregaocgm
                        INNER JOIN cgm ON cgm.z01_numcgm = licpregaocgm.l46_numcgm
                        INNER JOIN licpregao ON licpregao.l45_sequencial = licpregaocgm.l46_licpregao
                          WHERE l46_tipo = 6) AS pregoeiro

                      ON pregoeiro.l46_licpregao = mapoio.l46_licpregao

                        WHERE pregoeiro.l46_licpregao = {$equipepregao} LIMIT 1

                  ";

                $rsResult = db_query($sSQL);

                if (pg_num_rows($rsResult) > 0) {
                    return TRUE;
                } else {
                    return FALSE;
                }

                break;

            case 'outros':

                $sSQL .= "

                  SELECT mapoio.l46_tipo, presidente.l46_tipo, secretario.l46_tipo FROM

                    (SELECT l46_licpregao, l46_tipo
                      FROM licpregaocgm
                        INNER JOIN cgm ON cgm.z01_numcgm = licpregaocgm.l46_numcgm
                        INNER JOIN licpregao ON licpregao.l45_sequencial = licpregaocgm.l46_licpregao
                          WHERE l46_tipo = 2) AS mapoio

                    INNER JOIN

                    (SELECT l46_licpregao, l46_tipo
                      FROM licpregaocgm
                        INNER JOIN cgm ON cgm.z01_numcgm = licpregaocgm.l46_numcgm
                        INNER JOIN licpregao ON licpregao.l45_sequencial = licpregaocgm.l46_licpregao
                          WHERE l46_tipo = 3) AS presidente ON presidente.l46_licpregao = mapoio.l46_licpregao

                    INNER JOIN

                    (SELECT l46_licpregao, l46_tipo
                      FROM licpregaocgm
                        INNER JOIN cgm ON cgm.z01_numcgm = licpregaocgm.l46_numcgm
                        INNER JOIN licpregao ON licpregao.l45_sequencial = licpregaocgm.l46_licpregao
                          WHERE l46_tipo = 4) AS secretario

                      ON secretario.l46_licpregao = presidente.l46_licpregao

                        WHERE secretario.l46_licpregao = {$equipepregao} LIMIT 1

                  ";

                $rsResult = db_query($sSQL);

                if (pg_num_rows($rsResult) > 0) {
                    return TRUE;
                } else {
                    return FALSE;
                }

                break;
        }
    }

    function excluirpublicacaocredenciamento($l20_codigo)
    {
        $sql = "Update liclicita set l20_dtpubratificacao = null,l20_dtlimitecredenciamento = null
        where l20_codigo = $l20_codigo";
        $result = db_query($sql);

        if ($result == false) {
            $this->erro_banco = str_replace("\n", "", @pg_last_error());
            $this->erro_sql = "Publica��o nao Alterado. Alteracao Abortada.\\n";
            $this->erro_sql .= "Valores : " . $this->l20_codigo;
            $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
            $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
            $this->erro_status = "0";
            $this->numrows_alterar = 0;
            return false;
        } else {
            if (pg_affected_rows($result) == 0) {
                $this->erro_banco = "";
                $this->erro_sql = "Publica��o nao foi Alterado. Alteracao Executada.\\n";
                $this->erro_sql .= "Valores : " . $this->l20_codigo;
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = 0;
                return true;
            } else {
                $this->erro_banco = "";
                $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
                //$this->erro_sql .= "Valores : ".$this->l20_codigo;
                $this->erro_msg = "Usu�rio: \\n\\n " . $this->erro_sql . " \\n\\n";
                $this->erro_msg .= str_replace('"', "", str_replace("'", "", "Administrador: \\n\\n " . $this->erro_banco . " \\n"));
                $this->erro_status = "1";
                $this->numrows_alterar = pg_affected_rows($result);
                return true;
            }
        }
    }


    function getPcmaterObras($liclicita)
    {
        $sql = "
            select pc16_codmater from liclicitem
                  inner join pcprocitem on pc81_codprocitem = l21_codpcprocitem
                  inner join solicitem on pc11_codigo = pc81_solicitem
                  inner join solicitempcmater on pc16_solicitem = pc11_codigo
                  inner join pcmater on pc01_codmater = pc16_codmater
                  where l21_codliclicita = $liclicita";
        $rsResult = db_query($sql);
        $aItensPcmater = array();
        for ($icont = 0; $icont < pg_num_rows($rsResult); $icont++) {
            $aItensPcmater[] = db_utils::fieldsMemory($rsResult, $icont);
        }
        return $aItensPcmater;
    }

    public function sql_query_licitacao_exporta($l20_codigo = null, $campos = "*", $ordem = null, $dbwhere = "")
    {
        $sql  = " select distinct {$campos} ";
        $sql .= "  from liclicita
        join db_depart on coddepto=l20_codepartamento
        join db_config on codigo=instit
        join infocomplementaresinstit on si09_instit=instit
        join liclicitem on l21_codliclicita=l20_codigo
        join pcprocitem on pc81_codprocitem=l21_codpcprocitem
        join pcproc on pc80_codproc=pc81_codproc
        join solicitem on pc11_codigo=pc81_solicitem
        join solicitempcmater on pc16_solicitem=pc11_codigo
        join pcmater on pc16_codmater = pc01_codmater
        join solicitemunid on pc17_codigo=pc11_codigo
        join matunid on m61_codmatunid=pc17_unid
        left JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
        left JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
        left JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
        left JOIN itemprecoreferencia ON pc23_orcamitem = si02_itemproccompra
        left JOIN precoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial
        LEFT JOIN liclicitemlote ON l04_liclicitem=l21_codigo";

        if (!empty($dbwhere)) {
            $sql .= " where {$dbwhere} ";
        } else if (!empty($l20_codigo)) {
            $sql .= " where liclicita.l20_codigo = $l20_codigo ";
        }

        if (!empty($ordem)) {
            $sql .= " order by {$ordem} ";
        }

        return $sql;
    }

    public function sql_query_pncp($l20_codigo = null)
    {
        $sql  = "
       SELECT DISTINCT CASE
            WHEN l03_pctipocompratribunal IN (110,51,53,54,52,50) THEN 1
            WHEN l03_pctipocompratribunal = 101 THEN 2
            WHEN l03_pctipocompratribunal = 100 THEN 3
            WHEN l03_pctipocompratribunal IN (102,103) THEN 4
       END AS tipoInstrumentoConvocatorioId,
       CASE
           WHEN l03_pctipocompratribunal = 110 THEN 2
           WHEN l03_pctipocompratribunal = 51 THEN 3
           WHEN l03_pctipocompratribunal = 53 THEN 6
           WHEN l03_pctipocompratribunal = 52 THEN 7
           WHEN l03_pctipocompratribunal = 50 and l03_presencial='f' THEN 4
           WHEN l03_pctipocompratribunal = 50 and l03_presencial='t' THEN 5
           WHEN l03_pctipocompratribunal = 54 and l03_presencial='f' THEN 13
           WHEN l03_pctipocompratribunal = 54 and l03_presencial='t' THEN 1
           WHEN l03_pctipocompratribunal = 101 THEN 8
           WHEN l03_pctipocompratribunal = 100 THEN 9
           WHEN l03_pctipocompratribunal in (102,103) THEN 12
       END AS modalidadeId,
       CASE
           WHEN l03_pctipocompratribunal IN (100,101,102,103) THEN 5
           ELSE liclicita.l20_mododisputa
       END AS modoDisputaId,
        liclicita.l20_edital AS numeroCompra,
        liclicita.l20_anousu AS anoCompra,
        liclicita.l20_edital||'/'||liclicita.l20_anousu AS numeroProcesso,
        liclicita.l20_objeto AS objetoCompra,
        '' as informacaoComplementar,
        CASE
            WHEN l03_pctipocompratribunal =54 THEN 'f'
            ELSE liclicita.l20_usaregistropreco
        END AS srp,
        liclicita.l20_dataaberproposta AS dataAberturaProposta,
        liclicita.l20_horaaberturaprop AS horaAberturaProposta,
        liclicita.l20_dataencproposta AS dataEncerramentoProposta,
        liclicita.l20_horaencerramentoprop AS horaEncerramentoProposta,
        liclicita.l20_amparolegal as amparoLegalId,
        liclicita.l20_linkpncp as linkSistemaOrigem,
        liclicita.l20_justificativapncp as justificativaPresencial,
        CASE
            WHEN l20_licsituacao IN (0,1,10,13) THEN 1
            WHEN l20_licsituacao IN (5,12) THEN 3
            WHEN l20_licsituacao IN (11) THEN 4
            ELSE l20_licsituacao
        END AS situacaoCompraId
        from liclicita
        join db_depart on coddepto=l20_codepartamento
        join db_config on codigo=instit
        join infocomplementaresinstit on si09_instit=instit
        join liclicitem on l21_codliclicita=l20_codigo
        join pcprocitem on pc81_codprocitem=l21_codpcprocitem
        join pcproc on pc80_codproc=pc81_codproc
        join solicitem on pc11_codigo=pc81_solicitem
        join solicitempcmater on pc16_solicitem=pc11_codigo
        join pcmater on pc16_codmater = pc01_codmater
        join solicitemunid on pc17_codigo=pc11_codigo
        join matunid on m61_codmatunid=pc17_unid
        left JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
        left JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
        left JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
        left JOIN itemprecoreferencia ON pc23_orcamitem = si02_itemproccompra
        left JOIN precoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial
        LEFT JOIN liclicitemlote ON l04_liclicitem=l21_codigo
        INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
        where liclicita.l20_codigo = {$l20_codigo}";
        return $sql;
    }

    public function sql_query_pncp_itens($l20_codigo = null)
    {
        $sql  = "SELECT DISTINCT    liclicitem.l21_ordem AS numeroItem,
                                    CASE
                                        WHEN pcmater.pc01_servico='t' AND substring(o56_elemento
                                                                                   FROM 0
                                                                                   FOR 8) IN
                                                 (SELECT DISTINCT substring(o56_elemento
                                                                            FROM 0
                                                                            FOR 8)
                                                  FROM orcelemento
                                                  WHERE o56_elemento LIKE '%3449052%') THEN 'M'
                                        WHEN pcmater.pc01_servico='t' THEN 'S'
                                        WHEN pcmater.pc01_servico='f' THEN 'M'
                                    END AS materialOuServico,
                                    COALESCE ((case when liclicita.l20_destexclusiva = 1 then 1 else null end),
                                            (case when liclicita.l20_subcontratacao = 1 then 2 else null end),
                                            (case when solicitem.pc11_exclusivo = 't' then 1 ELSE null end),
                                            (case when liclicitem.l21_reservado = 't' then 3 ELSE null end),
                                            4) AS tipoBeneficioId,
                                    FALSE AS incentivoProdutivoBasico,
                                    pcmater.pc01_descrmater AS descricao,
                                    matunid.m61_descr AS unidadeMedida,
                                    si02_vlprecoreferencia AS valorUnitarioEstimado,
                                    liclicita.l20_tipliticacao AS criterioJulgamentoId,
                                    pcmater.pc01_codmater,
                                    solicitem.pc11_numero,
                                    solicitem.pc11_reservado,
                                    solicitem.pc11_quant,
                                    liclicita.l20_codigo,
                                    CASE
                                        WHEN liclicitem.l21_sigilo IS NOT NULL THEN liclicitem.l21_sigilo
                                        ELSE 'f'
                                    END AS l21_sigilo,
                                    CASE
                                        WHEN substring(o56_elemento
                                                        FROM 0
                                                        FOR 8) IN
                                                (SELECT DISTINCT substring(o56_elemento
                                                                            FROM 0
                                                                            FOR 8)
                                                FROM orcelemento
                                                WHERE o56_elemento LIKE '%3449061%') THEN 1
                                        WHEN substring(o56_elemento
                                                        FROM 0
                                                        FOR 8) IN
                                                (SELECT DISTINCT substring(o56_elemento
                                                                            FROM 0
                                                                            FOR 8)
                                                FROM orcelemento
                                                WHERE o56_elemento LIKE '%3449052%') THEN 2
                                        ELSE 3
                                    END AS itemCategoriaId,
                                    pcmater.pc01_regimobiliario AS codigoRegistroImobiliario
                        FROM liclicita
                        JOIN db_depart ON coddepto=l20_codepartamento
                        JOIN db_config ON codigo=instit
                        JOIN infocomplementaresinstit ON si09_instit=instit
                        JOIN liclicitem ON l21_codliclicita=l20_codigo
                        JOIN pcprocitem ON pc81_codprocitem=l21_codpcprocitem
                        JOIN pcproc ON pc80_codproc=pc81_codproc
                        JOIN solicitem ON pc11_codigo=pc81_solicitem
                        JOIN solicitempcmater ON pc16_solicitem=pc11_codigo
                        JOIN pcmater ON pc16_codmater = pc01_codmater
                        LEFT JOIN solicitemele ON pc18_solicitem = pc11_codigo
                        LEFT JOIN orcelemento ON o56_codele = pc18_codele
                        AND o56_anousu=l20_anousu
                        JOIN solicitemunid ON pc17_codigo=pc11_codigo
                        JOIN matunid ON m61_codmatunid=pc17_unid
                        LEFT JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
                        LEFT JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
                        LEFT JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
                        LEFT JOIN itemprecoreferencia ON pc23_orcamitem = si02_itemproccompra
                        LEFT JOIN precoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial
                        LEFT JOIN liclicitemlote ON l04_liclicitem=l21_codigo
                        INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                        WHERE liclicita.l20_codigo = $l20_codigo
                        ORDER BY numeroitem";
        return $sql;
    }

    public function sql_query_pncp_itens_retifica_situacao ($l20_codigo,$ordem){
        return "SELECT DISTINCT    liclicitem.l21_ordem AS numeroItem,
                CASE
                    WHEN pcmater.pc01_servico='t' THEN 'S'
                    ELSE 'M'
                END AS materialOuServico,
                COALESCE ((case when liclicita.l20_destexclusiva = 1 then 1 else null end),
                        (case when liclicita.l20_subcontratacao = 1 then 2 else null end),
                        (case when solicitem.pc11_exclusivo = 't' then 1 ELSE null end),
                        (case when liclicitem.l21_reservado = 't' then 3 ELSE null end),
                        4) AS tipoBeneficioId,
                FALSE AS incentivoProdutivoBasico,
                pcmater.pc01_descrmater AS descricao,
                matunid.m61_descr AS unidadeMedida,
                si02_vlprecoreferencia AS valorUnitarioEstimado,
                liclicita.l20_tipliticacao AS criterioJulgamentoId,
                pcmater.pc01_codmater,
                solicitem.pc11_numero,
                solicitem.pc11_reservado,
                solicitem.pc11_quant,
                liclicita.l20_codigo,
                solicitem.pc11_reservado,
                CASE
                    WHEN liclicitem.l21_sigilo IS NOT NULL THEN liclicitem.l21_sigilo
                    ELSE 'f'
                END AS l21_sigilo,
                CASE
                    WHEN substring(o56_elemento
                                    FROM 0
                                    FOR 8) IN
                            (SELECT DISTINCT substring(o56_elemento
                                                        FROM 0
                                                        FOR 8)
                            FROM orcelemento
                            WHERE o56_elemento LIKE '%3449061%') THEN 1
                    WHEN substring(o56_elemento
                                    FROM 0
                                    FOR 8) IN
                            (SELECT DISTINCT substring(o56_elemento
                                                        FROM 0
                                                        FOR 8)
                            FROM orcelemento
                            WHERE o56_elemento LIKE '%3449052%') THEN 2
                    ELSE 3
                END AS itemCategoriaId,
                pcmater.pc01_regimobiliario AS codigoRegistroImobiliario,
                                  CASE
                                      WHEN l03_pctipocompratribunal IN (
                                                                        102,
                                                                        103)
                                           AND credenciamento.l205_fornecedor IS NOT NULL THEN 2
                                      WHEN l03_pctipocompratribunal IN (
                                                                        102,
                                                                        103)
                                           AND credenciamento.l205_fornecedor IS NULL THEN 4
                                      ELSE l217_codsituacao
                                  END AS situacaoCompraItemId,
                l218_motivoanulacao as justificativa,
                l217_sequencial,
                CASE
                    WHEN l03_pctipocompratribunal = 110 THEN 2
                    WHEN l03_pctipocompratribunal = 51 THEN 3
                    WHEN l03_pctipocompratribunal = 53 THEN 6
                    WHEN l03_pctipocompratribunal = 52 THEN 7
                    WHEN l03_pctipocompratribunal = 50 and l03_presencial='t' THEN 5
                    WHEN l03_pctipocompratribunal = 50 and l03_presencial='f' THEN 4
                    WHEN l03_pctipocompratribunal = 54 and l03_presencial='f' THEN 13
                    WHEN l03_pctipocompratribunal = 54 and l03_presencial='t' THEN 1
                    WHEN l03_pctipocompratribunal = 101 THEN 8
                    WHEN l03_pctipocompratribunal = 100 THEN 9
                    WHEN l03_pctipocompratribunal = 102 THEN 12
                    WHEN l03_pctipocompratribunal = 103 THEN 12
                END AS modalidadeId,
                pc23_orcamforne
        FROM liclicita
        JOIN db_depart ON coddepto=l20_codepartamento
        JOIN db_config ON codigo=instit
        JOIN infocomplementaresinstit ON si09_instit=instit
        JOIN liclicitem ON l21_codliclicita=l20_codigo
        JOIN pcprocitem ON pc81_codprocitem=l21_codpcprocitem
        JOIN pcproc ON pc80_codproc=pc81_codproc
        JOIN solicitem ON pc11_codigo=pc81_solicitem
        JOIN solicitempcmater ON pc16_solicitem=pc11_codigo
        JOIN pcmater ON pc16_codmater = pc01_codmater
        LEFT JOIN solicitemele ON pc18_solicitem = pc11_codigo
        LEFT JOIN orcelemento ON o56_codele = pc18_codele
        AND o56_anousu=l20_anousu
        JOIN solicitemunid ON pc17_codigo=pc11_codigo
        JOIN matunid ON m61_codmatunid=pc17_unid
        LEFT JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
        LEFT JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
        LEFT JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
        LEFT JOIN itemprecoreferencia ON pc23_orcamitem = si02_itemproccompra
        LEFT JOIN precoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial
        LEFT JOIN liclicitemlote ON l04_liclicitem=l21_codigo
        INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
        LEFT JOIN situacaoitemcompra ON l218_codigolicitacao=l20_codigo
        AND l218_liclicitem=l21_codigo
        LEFT JOIN situacaoitemlic ON l219_codigo=l218_codigo
        LEFT JOIN situacaoitem ON l217_sequencial=l219_situacao
        LEFT JOIN credenciamento ON credenciamento.l205_licitacao = l20_codigo
        AND pc81_codprocitem = credenciamento.l205_item
        WHERE liclicita.l20_codigo = $l20_codigo
        AND liclicitem.l21_ordem = $ordem
        ORDER BY l217_sequencial desc limit 1";
    }

    public function sql_query_valor_item_reservado($pc11_numero = null, $pc01_codmater = false)
    {

        $sql  = " SELECT DISTINCT
                    si02_vlprecoreferencia AS valorUnitarioEstimado
                FROM liclicita
                JOIN db_depart ON coddepto=l20_codepartamento
                JOIN db_config ON codigo=instit
                JOIN infocomplementaresinstit ON si09_instit=instit
                JOIN liclicitem ON l21_codliclicita=l20_codigo
                JOIN pcprocitem ON pc81_codprocitem=l21_codpcprocitem
                JOIN pcproc ON pc80_codproc=pc81_codproc
                JOIN solicitem ON pc11_codigo=pc81_solicitem
                JOIN solicitempcmater ON pc16_solicitem=pc11_codigo
                JOIN pcmater ON pc16_codmater = pc01_codmater
                JOIN solicitemunid ON pc17_codigo=pc11_codigo
                JOIN matunid ON m61_codmatunid=pc17_unid
                LEFT JOIN pcorcamitemproc ON pc81_codprocitem = pc31_pcprocitem
                LEFT JOIN pcorcamitem ON pc31_orcamitem = pc22_orcamitem
                LEFT JOIN pcorcamval ON pc22_orcamitem = pc23_orcamitem
                LEFT JOIN itemprecoreferencia ON pc23_orcamitem = si02_itemproccompra
                LEFT JOIN precoreferencia ON itemprecoreferencia.si02_precoreferencia = precoreferencia.si01_sequencial
                LEFT JOIN liclicitemlote ON l04_liclicitem=l21_codigo
                INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                WHERE pcmater.pc01_codmater = {$pc01_codmater}
                            AND solicitem.pc11_numero = {$pc11_numero}
                            AND si02_vlprecoreferencia != 0";
        return $sql;
    }

    public function sql_query_item_pncp($l20_codigo)
    {

        $sql  = " SELECT pc01_codmater,
                           l21_ordem,
                           pc01_descrmater,
                           CASE
                               WHEN l20_tipojulg = 3 THEN l04_descricao
                               ELSE NULL
                           END AS l04_descricao,
                           cgm.z01_numcgm,
                           cgm.z01_nome,
                           matunid.m61_descr,
                           solicitem.pc11_quant,
                           pcorcamval.pc23_valor
                    FROM liclicitem
                    INNER JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                    INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                    LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                    INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                    INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                    INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                    INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
                    INNER JOIN licsituacao ON l08_sequencial = l20_licsituacao
                    INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                    INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
                    INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                    INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                    LEFT JOIN pcorcamitemlic ON l21_codigo = pc26_liclicitem
                    LEFT JOIN pcorcamitem ON pc22_orcamitem = pc26_orcamitem
                    LEFT JOIN pcorcam ON pc20_codorc = pc22_codorc
                    LEFT JOIN pcorcamforne ON pc21_codorc = pc20_codorc
                    LEFT JOIN cgm ON pc21_numcgm = z01_numcgm
                    LEFT JOIN pcorcamval ON pc26_orcamitem = pc23_orcamitem
                    AND pc23_orcamforne=pc21_orcamforne
                    LEFT JOIN pcorcamjulg ON pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
                    AND pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
                    AND pc24_pontuacao =1
                    LEFT JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                    LEFT JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                    WHERE l21_codliclicita = $l20_codigo
                        AND pc24_pontuacao =1
                    UNION
                    SELECT pc01_codmater,
                           l21_ordem,
                           pc01_descrmater,
                           CASE
                               WHEN l20_tipojulg = 3 THEN l04_descricao
                               ELSE NULL
                           END AS l04_descricao,
                           cgm.z01_numcgm,
                           cgm.z01_nome,
                           matunid.m61_descr,
                           solicitem.pc11_quant,
                           itemprecoreferencia.si02_vlprecoreferencia as pc23_valor
                    FROM liclicitem
                    INNER JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                    INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                    INNER JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                    INNER JOIN pcorcamitem ON pc22_orcamitem = pc31_orcamitem
                    INNER JOIN pcorcam ON pc20_codorc = pc22_codorc
                    INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                    INNER JOIN pcorcamforne ON pc21_codorc = pc20_codorc
                    INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                    INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                    INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
                    INNER JOIN licsituacao ON l08_sequencial = l20_licsituacao
                    INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                    INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
                    INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                    INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                    INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                    INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                    INNER JOIN credenciamento ON credenciamento.l205_licitacao = l20_codigo
                    AND pc81_codprocitem = credenciamento.l205_item
                    inner join cgm on z01_numcgm = credenciamento.l205_fornecedor
                    inner join itemprecoreferencia on itemprecoreferencia.si02_itemproccompra = pcorcamitem.pc22_orcamitem
                    WHERE l21_codliclicita = $l20_codigo";

        return $sql;
    }

    public function sql_query_item_pncp_retifica($l20_codigo)
    {

        $sql  = " SELECT pc01_codmater,
                        l21_ordem,
                        pc24_pontuacao,
                        pc01_descrmater,
                        CASE
                            WHEN l20_tipojulg = 3 THEN l04_descricao
                            ELSE NULL
                        END AS l04_descricao,
                        cgm.z01_numcgm,
                        cgm.z01_nome,
                        matunid.m61_descr,
                        solicitem.pc11_quant,
                        pcorcamval.pc23_valor
                FROM liclicitem
                INNER JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
                INNER JOIN licsituacao ON l08_sequencial = l20_licsituacao
                INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
                INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                LEFT JOIN pcorcamitemlic ON l21_codigo = pc26_liclicitem
                LEFT JOIN pcorcamitem ON pc22_orcamitem = pc26_orcamitem
                LEFT JOIN pcorcam ON pc20_codorc = pc22_codorc
                LEFT JOIN pcorcamforne ON pc21_codorc = pc20_codorc
                LEFT JOIN cgm ON pc21_numcgm = z01_numcgm
                LEFT JOIN pcorcamval ON pc26_orcamitem = pc23_orcamitem
                AND pc23_orcamforne=pc21_orcamforne
                INNER JOIN pcorcamjulg ON pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
                AND pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
                AND pc24_pontuacao =1
                LEFT JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                LEFT JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                WHERE l21_codliclicita = $l20_codigo
                    AND pc24_pontuacao = 1
                UNION
                SELECT DISTINCT pc01_codmater,
                l21_ordem,
                pc24_pontuacao,
                pc01_descrmater,
                CASE
                    WHEN l20_tipojulg = 3 THEN l04_descricao
                    ELSE NULL
                END AS l04_descricao,
                0 AS z01_numcgm,
                '' AS z01_nome,
                matunid.m61_descr,
                solicitem.pc11_quant,
                pcorcamval.pc23_valor
                FROM liclicitem
                INNER JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
                INNER JOIN licsituacao ON l08_sequencial = l20_licsituacao
                INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
                INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                LEFT JOIN pcorcamitemlic ON l21_codigo = pc26_liclicitem
                LEFT JOIN pcorcamitem ON pc22_orcamitem = pc26_orcamitem
                LEFT JOIN pcorcam ON pc20_codorc = pc22_codorc
                LEFT JOIN pcorcamforne ON pc21_codorc = pc20_codorc
                LEFT JOIN cgm ON pc21_numcgm = z01_numcgm
                LEFT JOIN pcorcamval ON pc26_orcamitem = pc23_orcamitem
                AND pc23_orcamforne=pc21_orcamforne
                LEFT JOIN pcorcamjulg ON pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
                AND pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
                AND pc24_pontuacao =1
                LEFT JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                LEFT JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                WHERE l21_codliclicita = $l20_codigo
                    AND pc23_orcamitem NOT IN
                        (SELECT pc24_orcamitem
                         FROM pcorcamjulg)
                UNION
                SELECT pc01_codmater,
                       l21_ordem,
                       1 as pc24_pontuacao,
                       pc01_descrmater,
                       CASE
                           WHEN l20_tipojulg = 3 THEN l04_descricao
                           ELSE NULL
                       END AS l04_descricao,
                       cgm.z01_numcgm AS z01_numcgm,
                       cgm.z01_nome AS z01_nome,
                       matunid.m61_descr,
                       solicitem.pc11_quant,
                       itemprecoreferencia.si02_vlprecoreferencia AS pc23_valor
                FROM liclicitem
                INNER JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                INNER JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                INNER JOIN pcorcamitem ON pc22_orcamitem = pc31_orcamitem
                INNER JOIN pcorcam ON pc20_codorc = pc22_codorc
                INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                LEFT JOIN pcorcamforne ON pc21_codorc = pc20_codorc
                INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
                INNER JOIN licsituacao ON l08_sequencial = l20_licsituacao
                INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
                INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                LEFT JOIN credenciamento ON credenciamento.l205_licitacao = l20_codigo
                AND pc81_codprocitem = credenciamento.l205_item
                LEFT JOIN cgm ON z01_numcgm = credenciamento.l205_fornecedor
                INNER JOIN itemprecoreferencia ON itemprecoreferencia.si02_itemproccompra = pcorcamitem.pc22_orcamitem
                WHERE l21_codliclicita = $l20_codigo
                AND l03_pctipocompratribunal IN (102,103)
                ORDER BY l21_ordem";

        return $sql;
    }

    public function sql_query_resultado_pncp($l20_codigo, $ordem,$z01_numcgm)
    {

        $sql  = "SELECT pcorcamval.pc23_quant AS quantidadeHomologada,
                           pcorcamval.pc23_vlrun AS valorUnitarioHomologado,
                           pcorcamval.pc23_valor AS valorTotalHomologado,
                           pcorcamval.pc23_percentualdesconto AS percentualDesconto,
                           CASE
                               WHEN length(trim(cgm.z01_cgccpf)) = 14 THEN 'PJ'
                               ELSE 'PF'
                           END AS tipoPessoaId,
                           cgm.z01_cgccpf AS niFornecedor,
                           cgm.z01_nome AS nomeRazaoSocialFornecedor,
                           CASE
                               WHEN pc31_liclicitatipoempresa = 2 THEN 1
                               WHEN pc31_liclicitatipoempresa = 3 THEN 2
                               ELSE 3
                           END AS porteFornecedorId,
                           'BRA' AS codigoPais,
                           liclicita.l20_subcontratacao AS indicadorSubcontratacao,
                           CASE
                               WHEN pc50_pctipocompratribunal IN (100,
                                                                  101,
                                                                  102,
                                                                  103) THEN l20_dtpubratificacao
                               ELSE l202_datahomologacao
                           END AS dataResultado
                    FROM liclicitem
                    INNER JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                    INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                    LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                    INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                    INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                    INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                    INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
                    LEFT JOIN homologacaoadjudica ON l202_licitacao = l20_codigo
                    INNER JOIN licsituacao ON l08_sequencial = l20_licsituacao
                    INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                    INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
                    INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                    INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                    LEFT JOIN pcorcamitemlic ON l21_codigo = pc26_liclicitem
                    LEFT JOIN pcorcamitem ON pc22_orcamitem = pc26_orcamitem
                    LEFT JOIN pcorcam ON pc20_codorc = pc22_codorc
                    LEFT JOIN pcorcamforne ON pc21_codorc = pc20_codorc
                    LEFT JOIN cgm ON pc21_numcgm = z01_numcgm
                    LEFT JOIN pcorcamfornelic ON pc31_orcamforne = pc21_orcamforne
                    LEFT JOIN pcorcamval ON pc26_orcamitem = pc23_orcamitem
                    AND pc23_orcamforne=pc21_orcamforne
                    LEFT JOIN pcorcamjulg ON pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
                    AND pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
                    AND pc24_pontuacao = 1
                    LEFT  JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                    LEFT  JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                    WHERE l21_codliclicita = $l20_codigo
                        AND l21_ordem = $ordem
                        AND l202_datahomologacao IS NOT NULL
                        AND pc24_pontuacao = 1
                    union
                    SELECT DISTINCT itemprecoreferencia.si02_qtditem AS quantidadeHomologada,
                                    itemprecoreferencia.si02_vlprecoreferencia AS valorUnitarioHomologado,
                                    itemprecoreferencia.si02_vltotalprecoreferencia AS valorTotalHomologado,
                                    itemprecoreferencia.si02_mediapercentual AS percentualDesconto,
                                    CASE
                                        WHEN length(trim(cgm.z01_cgccpf)) = 14 THEN 'PJ'
                                        ELSE 'PF'
                                    END AS tipoPessoaId,
                                    cgm.z01_cgccpf AS niFornecedor,
                                    cgm.z01_nome AS nomeRazaoSocialFornecedor,
                                    CASE
                                        WHEN pc31_liclicitatipoempresa = 2 THEN 1
                                        WHEN pc31_liclicitatipoempresa = 3 THEN 2
                                        ELSE 3
                                    END AS porteFornecedorId,
                                    'BRA' AS codigoPais,
                                    liclicita.l20_subcontratacao AS indicadorSubcontratacao,
                                    CASE
                                        WHEN pc50_pctipocompratribunal IN (100,
                                                                           101,
                                                                           102,
                                                                           103) THEN l20_dtpubratificacao
                                        ELSE l202_datahomologacao
                                    END AS dataResultado
                    FROM liclicitem
                    INNER JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                    INNER JOIN pcorcamitemlic ON pc26_liclicitem = l21_codigo
                    INNER JOIN pcorcamitem pcorcamitemlicitacao ON pcorcamitemlicitacao.pc22_orcamitem = pcorcamitemlic.pc26_orcamitem
                    INNER JOIN pcorcam pcorcamlicitacao ON pcorcamlicitacao.pc20_codorc = pcorcamitemlicitacao.pc22_codorc
                    INNER JOIN pcorcamforne ON pc21_codorc = pcorcamlicitacao.pc20_codorc
                    INNER JOIN pcorcamfornelic ON pc31_orcamforne = pc21_orcamforne
                    INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                    INNER JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                    INNER JOIN pcorcamitem ON pcorcamitem.pc22_orcamitem = pc31_orcamitem
                    INNER JOIN pcorcam ON pcorcam.pc20_codorc = pcorcamitem.pc22_codorc
                    INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                    INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                    INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                    INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
                    LEFT JOIN homologacaoadjudica ON l202_licitacao = l20_codigo
                    INNER JOIN licsituacao ON l08_sequencial = l20_licsituacao
                    INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                    INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
                    INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                    INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                    INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                    INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                    INNER JOIN credenciamento ON credenciamento.l205_licitacao = l20_codigo
                    AND pc81_codprocitem = credenciamento.l205_item
                    INNER JOIN cgm ON z01_numcgm = credenciamento.l205_fornecedor
                    INNER JOIN itemprecoreferencia ON itemprecoreferencia.si02_itemproccompra = pcorcamitem.pc22_orcamitem
                    WHERE l21_codliclicita = $l20_codigo
                        AND l21_ordem = $ordem
                        AND credenciamento.l205_fornecedor = $z01_numcgm";

        return $sql;
    }

    public function sql_query_resultado_retifica_pncp($l20_codigo, $ordem, $z01_numcgm)
    {

        $sql  = "SELECT pcorcamval.pc23_quant AS quantidadeHomologada,
                           pcorcamval.pc23_vlrun AS valorUnitarioHomologado,
                           pcorcamval.pc23_valor AS valorTotalHomologado,
                           pcorcamval.pc23_percentualdesconto AS percentualDesconto,
                           CASE
                               WHEN length(trim(cgm.z01_cgccpf)) = 14 THEN 'PJ'
                               ELSE 'PF'
                           END AS tipoPessoaId,
                           cgm.z01_cgccpf AS niFornecedor,
                           cgm.z01_nome AS nomeRazaoSocialFornecedor,
                           CASE
                               WHEN pc31_liclicitatipoempresa = 2 THEN 1
                               WHEN pc31_liclicitatipoempresa = 3 THEN 2
                               ELSE 3
                           END AS porteFornecedorId,
                           'BRA' AS codigoPais,
                           liclicita.l20_subcontratacao AS indicadorSubcontratacao,
                           CASE
                               WHEN pc50_pctipocompratribunal IN (100,
                                                                  101,
                                                                  102,
                                                                  103) THEN l20_dtpubratificacao
                               ELSE l202_datahomologacao
                           END AS dataResultado,
                        l214_sequencialresultado
                    FROM liclicitem
                    INNER JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                    INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                    LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                    INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                    INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                    INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                    INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
                    LEFT JOIN homologacaoadjudica ON l202_licitacao = l20_codigo
                    INNER JOIN licsituacao ON l08_sequencial = l20_licsituacao
                    INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                    INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
                    INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                    INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                    LEFT JOIN pcorcamitemlic ON l21_codigo = pc26_liclicitem
                    LEFT JOIN pcorcamitem ON pc22_orcamitem = pc26_orcamitem
                    LEFT JOIN pcorcam ON pc20_codorc = pc22_codorc
                    LEFT JOIN pcorcamforne ON pc21_codorc = pc20_codorc
                    LEFT JOIN cgm ON pc21_numcgm = z01_numcgm
                    LEFT JOIN pcorcamfornelic ON pc31_orcamforne = pc21_orcamforne
                    LEFT JOIN pcorcamval ON pc26_orcamitem = pc23_orcamitem
                    AND pc23_orcamforne=pc21_orcamforne
                    LEFT JOIN pcorcamjulg ON pcorcamval.pc23_orcamitem = pcorcamjulg.pc24_orcamitem
                    AND pcorcamval.pc23_orcamforne = pcorcamjulg.pc24_orcamforne
                    AND pc24_pontuacao = 1
                    LEFT  JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                    LEFT  JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                    INNER JOIN liccontrolepncpitens ON liccontrolepncpitens.l214_licitacao = l21_codliclicita
                    WHERE l21_codliclicita = $l20_codigo
                        AND l21_ordem = $ordem
                        AND l202_datahomologacao IS NOT NULL
                        AND pc24_pontuacao = 1
                    union
                    SELECT DISTINCT itemprecoreferencia.si02_qtditem AS quantidadeHomologada,
                                    itemprecoreferencia.si02_vlprecoreferencia AS valorUnitarioHomologado,
                                    itemprecoreferencia.si02_vltotalprecoreferencia AS valorTotalHomologado,
                                    itemprecoreferencia.si02_mediapercentual AS percentualDesconto,
                                    CASE
                                        WHEN length(trim(cgm.z01_cgccpf)) = 14 THEN 'PJ'
                                        ELSE 'PF'
                                    END AS tipoPessoaId,
                                    cgm.z01_cgccpf AS niFornecedor,
                                    cgm.z01_nome AS nomeRazaoSocialFornecedor,
                                    CASE
                                        WHEN pc31_liclicitatipoempresa = 2 THEN 1
                                        WHEN pc31_liclicitatipoempresa = 3 THEN 2
                                        ELSE 3
                                    END AS porteFornecedorId,
                                    'BRA' AS codigoPais,
                                    liclicita.l20_subcontratacao AS indicadorSubcontratacao,
                                    CASE
                                        WHEN pc50_pctipocompratribunal IN (100,
                                                                           101,
                                                                           102,
                                                                           103) THEN l20_dtpubratificacao
                                        ELSE l202_datahomologacao
                                    END AS dataResultado,
                        l214_sequencialresultado
                    FROM liclicitem
                    INNER JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                    INNER JOIN pcorcamitemlic ON pc26_liclicitem = l21_codigo
                    INNER JOIN pcorcamitem pcorcamitemlicitacao ON pcorcamitemlicitacao.pc22_orcamitem = pcorcamitemlic.pc26_orcamitem
                    INNER JOIN pcorcam pcorcamlicitacao ON pcorcamlicitacao.pc20_codorc = pcorcamitemlicitacao.pc22_codorc
                    INNER JOIN pcorcamforne ON pc21_codorc = pcorcamlicitacao.pc20_codorc
                    INNER JOIN pcorcamfornelic ON pc31_orcamforne = pc21_orcamforne
                    INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                    INNER JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
                    INNER JOIN pcorcamitem ON pcorcamitem.pc22_orcamitem = pc31_orcamitem
                    INNER JOIN pcorcam ON pcorcam.pc20_codorc = pcorcamitem.pc22_codorc
                    INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                    INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                    INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                    INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
                    LEFT JOIN homologacaoadjudica ON l202_licitacao = l20_codigo
                    INNER JOIN licsituacao ON l08_sequencial = l20_licsituacao
                    INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
                    INNER JOIN pctipocompra ON pctipocompra.pc50_codcom = cflicita.l03_codcom
                    INNER JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
                    INNER JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
                    INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                    INNER JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
                    INNER JOIN credenciamento ON credenciamento.l205_licitacao = l20_codigo
                    AND pc81_codprocitem = credenciamento.l205_item
                    INNER JOIN cgm ON z01_numcgm = credenciamento.l205_fornecedor
                    INNER JOIN itemprecoreferencia ON itemprecoreferencia.si02_itemproccompra = pcorcamitem.pc22_orcamitem
                    INNER JOIN liccontrolepncpitens ON liccontrolepncpitens.l214_licitacao = l21_codliclicita
                    AND l214_fornecedor = l205_fornecedor
                    WHERE l21_codliclicita = $l20_codigo
                        AND l21_ordem = $ordem
                        AND credenciamento.l205_fornecedor = $z01_numcgm";

        return $sql;
    }

    public function sql_query_ata_pncp($l20_codigo,$l221_numata)
    {
        $sql = "SELECT   l221_numata AS numeroAtaRegistroPreco,
                        l221_exercicio AS anoAta,
                        l221_dataini AS dataAssinatura,
                        l221_dataini AS dataVigenciaInicio,
                        l221_datafinal AS dataVigenciaFim
        FROM licatareg
        WHERE l221_licitacao = $l20_codigo and l221_numata = '$l221_numata'";

        return $sql;
    }

    function sql_query_publicacaoEmpenho_pncp()
    {
        $ano  = db_getsession("DB_anousu");
        $sql  = "SELECT *
        FROM
            (SELECT DISTINCT e60_numemp,
                             e213_numerocontrolepncp,
                             z01_cgccpf AS cnpjCompra,
                             e213_ano AS anoCompra,
                             e213_sequencialpncp AS sequencialCompra,
                             7 AS tipoContratoId,
                             e60_codemp AS numeroContratoEmpenho,
                             e60_anousu AS anoContrato,
                             e54_numerl AS processo,
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
                                      e60_emiss AS dataVigenciaInicio,
                                      '$ano-12-31' AS dataVigenciaFim,
                                      e60_emiss AS dataAssinatura,
                                      e60_vlremp AS valorInicial,
                                      e60_vlremp AS valorGlobal,
                                      CASE
                                          WHEN
                                                   (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date))) > 0 THEN
                                                   (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date)))
                                          ELSE 1
                                      END AS numeroParcelas
             FROM empautitem
             LEFT JOIN empautitempcprocitem ON empautitempcprocitem.e73_sequen = empautitem.e55_sequen
             AND empautitempcprocitem.e73_autori = empautitem.e55_autori
             INNER JOIN liclicitem ON liclicitem.l21_codpcprocitem = empautitempcprocitem.e73_pcprocitem
             INNER JOIN empautoriza ON empautoriza.e54_autori = empautitem.e55_autori
             INNER JOIN liclicita ON liclicitem.l21_codliclicita = liclicita.l20_codigo
             OR liclicita.l20_codigo = empautoriza.e54_codlicitacao
             LEFT JOIN cflicita ON liclicita.l20_codtipocom = cflicita.l03_codigo
             INNER JOIN empempaut ON e61_autori = e54_autori
             INNER JOIN empempenho ON e60_numemp = e61_numemp
             JOIN cgm ON z01_numcgm = e60_numcgm
             LEFT JOIN empempenhopncp ON e213_contrato = e60_numemp
             INNER JOIN liccontrolepncp ON l213_licitacao = l20_codigo
             UNION SELECT DISTINCT e60_numemp,
                                   e213_numerocontrolepncp,
                                   z01_cgccpf AS cnpjCompra,
                                   e213_ano AS anoCompra,
                                   e213_sequencialpncp AS sequencialCompra,
                                   7 AS tipoContratoId,
                                   e60_codemp AS numeroContratoEmpenho,
                                   e60_anousu AS anoContrato,
                                   e54_numerl AS processo,
                                   pc80_categoriaprocesso AS categoriaProcessoId,
                                   TRUE AS receita,
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
                                            e60_emiss AS dataVigenciaInicio,
                                            '$ano-12-31' AS dataVigenciaFim,
                                            e60_emiss AS dataAssinatura,
                                            e60_vlremp AS valorInicial,
                                            e60_vlremp AS valorGlobal,
                                            CASE
                                                WHEN
                                                         (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date))) > 0 THEN
                                                         (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date)))
                                                ELSE 1
                                            END AS numeroParcelas
             FROM empautitem
             LEFT JOIN empautitempcprocitem ON empautitempcprocitem.e73_sequen = empautitem.e55_sequen
             AND empautitempcprocitem.e73_autori = empautitem.e55_autori
             JOIN pcprocitem ON pc81_codprocitem = e73_pcprocitem
             JOIN pcproc ON pc80_codproc= pc81_codproc
             INNER JOIN empautoriza ON empautoriza.e54_autori = empautitem.e55_autori
             INNER JOIN empempaut ON e61_autori = e54_autori
             INNER JOIN empempenho ON e60_numemp = e61_numemp
             JOIN cgm ON z01_numcgm = e60_numcgm
             LEFT JOIN empempenhopncp ON e213_contrato = e60_numemp
             INNER JOIN liccontrolepncp ON l213_processodecompras = pc80_codproc
             UNION SELECT DISTINCT e60_numemp,
                                   e213_numerocontrolepncp,
                                   z01_cgccpf AS cnpjCompra,
                                   e213_ano AS anoCompra,
                                   e213_sequencialpncp AS sequencialCompra,
                                   7 AS tipoContratoId,
                                   e60_codemp AS numeroContratoEmpenho,
                                   e60_anousu AS anoContrato,
                                   e54_numerl AS processo,
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
                                            e60_emiss AS dataVigenciaInicio,
                                            '$ano-12-31' AS dataVigenciaFim,
                                            e60_emiss AS dataAssinatura,
                                            e60_vlremp AS valorInicial,
                                            e60_vlremp AS valorGlobal,
                                            CASE
                                                WHEN
                                                         (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date))) > 0 THEN
                                                         (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date)))
                                                ELSE 1
                                            END AS numeroParcelas
             FROM empautitem
             INNER JOIN empautoriza ON empautoriza.e54_autori = empautitem.e55_autori
             INNER JOIN liclicita ON liclicita.l20_codigo = empautoriza.e54_codlicitacao
             LEFT JOIN cflicita ON liclicita.l20_codtipocom = cflicita.l03_codigo
             INNER JOIN empempaut ON e61_autori = e54_autori
             INNER JOIN empempenho ON e60_numemp = e61_numemp
             JOIN cgm ON z01_numcgm = e60_numcgm
             LEFT JOIN empempenhopncp ON e213_contrato = e60_numemp
             INNER JOIN liccontrolepncp ON l213_licitacao = l20_codigo ) AS x
        ORDER BY x.e60_numemp DESC
        ";
        return $sql;
    }

    function sql_query_pncp_empenho($codigoempenho, $data)
    {
        $ano  = substr($data, 0, 4);

        $sql  = "SELECT DISTINCT e60_numemp,
                    e213_numerocontrolepncp,
                    z01_cgccpf AS cnpjCompra,
                    l213_anousu AS anoCompra,
                    l213_numerocompra AS sequencialCompra,
                    7 AS tipoContratoId,
                    e60_codemp AS numeroContratoEmpenho,
                    e60_anousu AS anoContrato,
                    e54_numerl AS processo,
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
                 e60_emiss AS dataVigenciaInicio,
                 '$ano-12-31' AS dataVigenciaFim,
                 e60_emiss AS dataAssinatura,
                 e60_vlremp AS valorInicial,
                 e60_vlremp AS valorGlobal,
                 CASE
                     WHEN
                              (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date))) > 0 THEN
                              (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date)))
                     ELSE 1
                 END AS numeroParcelas,
                    l213_anousu as anoCompra,
                    e213_sequencialpncp
                FROM empautitem
                LEFT JOIN empautitempcprocitem ON empautitempcprocitem.e73_sequen = empautitem.e55_sequen
                AND empautitempcprocitem.e73_autori = empautitem.e55_autori
                INNER JOIN liclicitem ON liclicitem.l21_codpcprocitem = empautitempcprocitem.e73_pcprocitem
                INNER JOIN empautoriza ON empautoriza.e54_autori = empautitem.e55_autori
                INNER JOIN liclicita ON liclicitem.l21_codliclicita = liclicita.l20_codigo
                OR liclicita.l20_codigo = empautoriza.e54_codlicitacao
                LEFT JOIN cflicita ON liclicita.l20_codtipocom = cflicita.l03_codigo
                INNER JOIN empempaut ON e61_autori = e54_autori
                INNER JOIN empempenho ON e60_numemp = e61_numemp
                JOIN cgm ON z01_numcgm = e60_numcgm
                LEFT JOIN empempenhopncp ON e213_contrato = e60_numemp
                LEFT JOIN liccontrolepncp ON l213_licitacao = l20_codigo
                WHERE e60_numemp = $codigoempenho
            UNION
                SELECT DISTINCT e60_numemp,
                        e213_numerocontrolepncp,
                        z01_cgccpf AS cnpjCompra,
                        l213_anousu AS anoCompra,
                        l213_numerocompra AS sequencialCompra,
                        7 AS tipoContratoId,
                        e60_codemp AS numeroContratoEmpenho,
                        e60_anousu AS anoContrato,
                        e54_numerl AS processo,
                        pc80_categoriaprocesso AS categoriaProcessoId,
                        TRUE AS receita,
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
                 e60_emiss AS dataVigenciaInicio,
                 '$ano-12-31' AS dataVigenciaFim,
                 e60_emiss AS dataAssinatura,
                 e60_vlremp AS valorInicial,
                 e60_vlremp AS valorGlobal,
                 CASE
                     WHEN
                              (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date))) > 0 THEN
                              (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date)))
                     ELSE 1
                 END AS numeroParcelas,
                        l213_anousu as anoCompra,
                        e213_sequencialpncp
                FROM empautitem
                LEFT JOIN empautitempcprocitem ON empautitempcprocitem.e73_sequen = empautitem.e55_sequen
                AND empautitempcprocitem.e73_autori = empautitem.e55_autori
                JOIN pcprocitem ON pc81_codprocitem = e73_pcprocitem
                JOIN pcproc ON pc80_codproc= pc81_codproc
                INNER JOIN empautoriza ON empautoriza.e54_autori = empautitem.e55_autori
                INNER JOIN empempaut ON e61_autori = e54_autori
                INNER JOIN empempenho ON e60_numemp = e61_numemp
                JOIN cgm ON z01_numcgm = e60_numcgm
                LEFT JOIN empempenhopncp ON e213_contrato = e60_numemp
                INNER JOIN liccontrolepncp ON l213_processodecompras = pc80_codproc
                WHERE e60_numemp = $codigoempenho
            UNION
            SELECT DISTINCT e60_numemp,
                    e213_numerocontrolepncp,
                    z01_cgccpf AS cnpjCompra,
                    l213_anousu AS anoCompra,
                    l213_numerocompra AS sequencialCompra,
                    7 AS tipoContratoId,
                    e60_codemp AS numeroContratoEmpenho,
                    e60_anousu AS anoContrato,
                    e54_numerl AS processo,
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
                 e60_emiss AS dataVigenciaInicio,
                 '$ano-12-31' AS dataVigenciaFim,
                 e60_emiss AS dataAssinatura,
                 e60_vlremp AS valorInicial,
                 e60_vlremp AS valorGlobal,
                 CASE
                     WHEN
                              (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date))) > 0 THEN
                              (SELECT (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 + (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date)))
                     ELSE 1
                 END AS numeroParcelas,
                    l213_anousu as anoCompra,
                    e213_sequencialpncp
                FROM empautitem
                INNER JOIN empautoriza ON empautoriza.e54_autori = empautitem.e55_autori
                INNER JOIN liclicita ON liclicita.l20_codigo = empautoriza.e54_codlicitacao
                LEFT JOIN cflicita ON liclicita.l20_codtipocom = cflicita.l03_codigo
                INNER JOIN empempaut ON e61_autori = e54_autori
                INNER JOIN empempenho ON e60_numemp = e61_numemp
                JOIN cgm ON z01_numcgm = e60_numcgm
                LEFT JOIN empempenhopncp ON e213_contrato = e60_numemp
                INNER JOIN liccontrolepncp ON l213_licitacao = l20_codigo
                WHERE e60_numemp = $codigoempenho
                ";

        return $sql;
    }

    function sql_query_pncp_empenho_enviado()
    {
        $ano  = db_getsession("DB_anousu");
        $sql  = "select  distinct
        e60_numemp,
        e213_numerocontrolepncp,
        z01_cgccpf as cnpjCompra,
        l213_anousu as anoCompra,
        l213_numerocompra as sequencialCompra,
        7 as tipoContratoId,
        e60_codemp as numeroContratoEmpenho,
        e60_anousu as anoContrato,
        e54_numerl as processo,
        l20_categoriaprocesso as categoriaProcessoId,
        l20_receita as receita,
        01001 as codigoUnidade,
        z01_cgccpf as niFornecedor,
        case
            when length(trim(z01_cgccpf)) = 14 then 'PJ'
            when length(trim(z01_cgccpf)) = 11 then 'PF'
            else
                            'PE'
        end as tipoPessoaFornecedor,
        z01_nome as nomeRazaoSocialFornecedor,
        null as niFornecedorSubContratado,
        null as tipoPessoaFornecedorSubContratado,
        null as nomeRazaoSocialFornecedorSubContratado,
        l20_objeto as objetoContrato,
        null as informacaoComplementar,
        0 as valorParcela,
        e60_emiss as dataVigenciaInicio,
        '$ano-12-31' as dataVigenciaFim,
        e60_emiss as dataAssinatura,
        e60_vlremp as valorInicial,
        e60_vlremp as valorGlobal,
        case
            when
                         (
            select
                (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 +
                          (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date))) > 0 then
                          (
            select
                (DATE_PART('year', '$ano-12-31'::date) - DATE_PART('year', e60_emiss::date)) * 12 +
                          (DATE_PART('month', '$ano-12-31'::date) - DATE_PART('month', e60_emiss::date)))
            else
                         1
            end as numeroParcelas
        from
            empautitem
        left join empautitempcprocitem on
            empautitempcprocitem.e73_sequen = empautitem.e55_sequen
            and empautitempcprocitem.e73_autori = empautitem.e55_autori
        left join liclicitem on
            liclicitem.l21_codpcprocitem = empautitempcprocitem.e73_pcprocitem
        inner join empautoriza on
            empautoriza.e54_autori = empautitem.e55_autori
        left join liclicita on
        liclicitem.l21_codliclicita = liclicita.l20_codigo
		or
		liclicita.l20_codigo = empautoriza.e54_codlicitacao
        left join cflicita on
            liclicita.l20_codtipocom = cflicita.l03_codigo
        inner join empempaut on
            e61_autori = e54_autori
        inner join empempenho on
            e60_numemp = e61_numemp
        join cgm on
            z01_numcgm = e60_numcgm
        left join liccontrolepncp on
            l20_codigo = l213_licitacao
            or e54_codlicitacao = l213_licitacao
        join empempenhopncp on
            e213_contrato = e60_numemp

        where e60_emiss >='$ano-01-01' and e60_emiss <='$ano-12-31' ";

        return $sql;
    }

    public function queryFornecedoresGanhadores(int $codigoLicitacao): string
    {
        return "SELECT DISTINCT
             z01_numcgm,
             z01_nome,
             z01_cgccpf
         FROM liclicita
         INNER JOIN liclicitem ON l21_codliclicita=l20_codigo
         INNER JOIN pcorcamitemlic ON pc26_liclicitem=l21_codigo
         INNER JOIN pcorcamitem ON pc22_orcamitem=pc26_orcamitem
         INNER JOIN pcorcamjulg ON pc24_orcamitem=pc22_orcamitem
         INNER JOIN pcorcamforne ON pc21_orcamforne=pc24_orcamforne
         INNER JOIN pcorcamval ON pc23_orcamitem=pc22_orcamitem
             AND pc23_orcamforne = pc21_orcamforne
         INNER JOIN cgm ON z01_numcgm=pc21_numcgm
         WHERE
             l20_codigo={$codigoLicitacao}
             AND pc24_pontuacao = 1
             ORDER BY z01_nome;";
    }

    public function sql_atapncp()
    {
        return "
            SELECT DISTINCT l20_codigo,
                l20_edital,
                l20_objeto,
                (SELECT l213_numerocontrolepncp
                 FROM liccontrolepncp
                 WHERE l213_situacao = 1
                     AND l213_licitacao=l20_codigo
                     AND l213_licitacao NOT IN
                         (SELECT l213_licitacao
                          FROM liccontrolepncp
                          WHERE l213_situacao = 3
                              AND l213_licitacao=l20_codigo)
                 ORDER BY l213_sequencial DESC
                 LIMIT 1) AS l213_numerocontrolepncp,
                l221_fornecedor ||' - '|| z01_nome as fornecedor,
                l03_descr,
                l20_numero,
                l221_numata,
                l215_ata
            FROM liclicita
            inner join licatareg on l221_licitacao = l20_codigo
            inner join cgm on z01_numcgm = l221_fornecedor
            INNER JOIN cflicita ON cflicita.l03_codigo = liclicita.l20_codtipocom
            INNER JOIN pctipocompratribunal ON pctipocompratribunal.l44_sequencial = cflicita.l03_pctipocompratribunal
            LEFT JOIN homologacaoadjudica ON l202_licitacao = l20_codigo
            LEFT JOIN liccontrolepncp ON l213_licitacao = l20_codigo
            LEFT JOIN licontroleatarppncp ON l215_licitacao = l20_codigo and l221_numata::int =  l215_numataecidade
            WHERE l20_usaregistropreco ='t'
                AND l03_pctipocompratribunal IN (110,51,53,52,102,101,100,101,50,103)
                AND l213_numerocontrolepncp IS NOT NULL
                AND liclicita.l20_leidalicitacao = 1
                AND l20_instit = ".db_getsession('DB_instit')."
            ORDER BY l20_codigo,l221_numata DESC
        ";
    }

    public function verificaMembrosModalidadeParaLei1($modalidade,$sequencialComissao){

        if($modalidade == "pregao"){
            $rsLicPregao = db_query("
            select
                *
            from
                licpregao
            inner join licpregaocgm on
                l46_licpregao = l45_sequencial
            where
                l45_sequencial = $sequencialComissao and l46_tipo = 6;");

               if(pg_num_rows($rsLicPregao) > 0){
                    return true;
               }

               return false;
        }

        $rsLicPregao = db_query("
        select
	        *
        from
            licpregao
        inner join licpregaocgm on
            l46_licpregao = l45_sequencial
        where
            l45_sequencial = $sequencialComissao and l46_tipo in(7, 8)");

           if(pg_num_rows($rsLicPregao) > 0){
                return true;
           }

           return false;

    }
}
