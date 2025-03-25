<?
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

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("dbforms/db_funcoes.php");
include("libs/db_libcaixa_ze.php");
include("libs/db_libgertxtfolha.php");
include("classes/db_folha_classe.php");
include("classes/db_pensao_classe.php");
include("classes/db_rharqbanco_classe.php");
include("classes/db_orctiporec_classe.php");
include("classes/db_layouttxt_classe.php");
require_once("libs/db_utils.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$clfolha       = new cl_folha;
$clpensao      = new cl_pensao;
$clrharqbanco  = new cl_rharqbanco;
$clorctiporec  = new cl_orctiporec;
$cldb_layouttxt = new cl_db_layouttxt;
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("r38_liq");
$clrotulo->label("r38_banco");
$clrotulo->label("r38_agenc");
$clrotulo->label("r38_conta");
$clrotulo->label("r70_descr");
$clrotulo->label("db83_tipoconta");
$sqlerro = false;

db_sel_instit();

$result_arqbanco = $clrharqbanco->sql_record($clrharqbanco->sql_query($rh34_codarq));

$result_layout = $cldb_layouttxt->sql_record($cldb_layouttxt->sql_query_file(null, "db50_codigo", null, "db50_descr = 'CNAB200 BANCO SICOOB'"));
/**
 * Soma a quantidade de registros do tipo
 * 0 HEader do Arquivo
 * if
 *   1 Header do Lote
 *     3 Registro Detalhe(A e B)
 *   5 Trailer do Lote
 * 9 Trailer do Arquivo
 */

if ($clrharqbanco->numrows > 0 && $cldb_layouttxt->numrows > 0) {

  $oArqBanco = db_utils::fieldsMemory($result_arqbanco, 0);

  include("dbforms/db_layouttxt.php");
  $posicao       = "A";
  $layoutimprime = db_utils::fieldsMemory($result_layout, 0)->db50_codigo;
  $idcaixa       = "P";
  $idcliente     = "P";
  $banco   = $oArqBanco->rh34_codban;
  $codcooperativa = $oArqBanco->rh34_agencia;
  $conveniobanco = trim($oArqBanco->rh34_convenio);
  $numeroconvenio = trim($oArqBanco->rh34_convenio);
  $brancos = " ";
  $brancos2 = " ";
  $brancos3 = " ";
  $brancos4 = " ";

  $datageracao = $datagera;
  $horageracao = date("H") . ':' . date("i") . ':' . date("s");

  if (isset($datageracao) && $datageracao != "") {
    $datag = explode('-', $datageracao);
    $datag_dia = $datag[2];
    $datag_mes = $datag[1];
    $datag_ano = $datag[0];
  }

  if (isset($datadeposit) && $datadeposit != "") {
    $datad = explode('-', $datadeposit);
    $datad_dia = $datad[2];
    $datad_mes = $datad[1];
    $datad_ano = $datad[0];
  }

  $sequencial  = $oArqBanco->rh34_sequencial;
  $usoprefeitura1 = $oArqBanco->rh34_sequencial;

  $adatadegeracao = $datag_ano . "-" . $datag_mes . "-" . $datag_dia;
  $datadedeposito = $datad_ano . "-" . $datad_mes . "-" . $datad_dia;

  $sequenciaarqui = $oArqBanco->rh34_sequencial;
  $versaodoarquiv = "030";

  $paramnome = $datag_mes . $datag_ano . "_" . $horageracao;
  $nomearquivo = "folha_" . $banco . "_" . $paramnome . ".txt";
  $db_layouttxt = new db_layouttxt($layoutimprime, "tmp/" . $nomearquivo, $posicao);

  $conveniobanco = trim($oArqBanco->rh34_convenio);

  db_setaPropriedadesLayoutTxt($db_layouttxt, 1);

  ///// }

} else {
  $sqlerro = true;
  $erro_msg = "Arquivo ou layout não encontrado";
}

// ESSES NAO VEM DO BANCO
$oArqBanco->rh34_wherefolha = "";
$oArqBanco->rh34_wherepensa = "";

if ($opt_todosbcos) {
  $oArqBanco->rh34_wherefolha .= " r38_banco  = '$oArqBanco->rh34_codban'     ";
  if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
    $oArqBanco->rh34_wherepensa .= " r52_codbco = '$oArqBanco->rh34_codban'  and";
  }
  if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
    $oArqBanco->rh34_wherepensa .= " r52_codbco = '$oArqBanco->rh34_codban' ";
  }
}

if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
  $oArqBanco->rh34_wherepensa .= " r52_anousu = " . db_anofolha() . " and r52_mesusu = " . db_mesfolha();
}

$titrelatorio = "Todos os funcionários";
$titarquivo   = "pagtofuncionarios";
$lPensionista = false;

if ($sqlerro == false || 1 !== 1) {

  if ($tiparq == 0) {

    $sql = $clfolha->sql_query_gerarqbag(
      null,
      "folha.*,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia, contabancaria.*,
                                               r70_descr,
                                               case when trim(translate(r38_conta,'0','')) = '' then '02'
                                                    when (select rh02_fpagto
                                                            from rhpessoalmov
                                                           where rh02_regist = r38_regist
                                                             and rh02_anousu = " . db_anofolha() . "
                                                            and rh02_mesusu     = " . db_mesfolha() . "
                                                            and rh02_instit     = " . db_getsession("DB_instit") . ") = 4 then '02'
                                                else '01' end as tipo_pagamento,
                                               length(trim(z01_cgccpf)) as tam,
                                               r38_liq as valorori",
      "r38_banco,tipo_pagamento, r38_nome",
      "$oArqBanco->rh34_wherefolha"
    );

    $result  = $clfolha->sql_record($sql);
    $numrows = $clfolha->numrows;
  } else {

    $titarquivo = "pensaojudicial";
    $titrelatorio = "PENSÃO JUDICIAL ";

    if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

      if ($qfolha == 1) {
        $campovalor = " r52_valor+r52_valfer ";
        $oArqBanco->rh34_wherepensa .= " and (r52_valor > 0 or r52_valfer > 0 ) ";
      } else if ($qfolha == 2) {
        $campovalor = " r52_valcom ";
        $oArqBanco->rh34_wherepensa .= " and r52_valcom > 0 ";
      } else if ($qfolha == 3) {
        $campovalor = " r52_val13 ";
        $oArqBanco->rh34_wherepensa .= " and r52_val13 > 0 ";
      } else if ($qfolha == 4) {
        $campovalor = " r52_valres ";
        $oArqBanco->rh34_wherepensa .= " and r52_valres > 0 ";
      } else if ($qfolha == 5) {
        $campovalor = " r52_valor  ";
        $oArqBanco->rh34_wherepensa .= " and r52_valor > 0 ";
      }
    }

    /**
     * Se a variável $DB_COMPLEMENTAR estiver setada, o valor do "r38_liq" será da tabela ("rhhistoricopensao")
     * lembrando que a folha de pagamento precisa ter registros na geração de disco ("folhapagamentogeracao").
     */
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

      switch ($qfolha) {

        case 1:
          $iTipoFolha  = FolhaPagamento::TIPO_FOLHA_SALARIO;
          $sOperacao   = "+r52_valfer";
          break;

        case 2:
          $iTipoFolha  = FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR;
          $sOperacao   = "";
          break;

        case 5:
          $iTipoFolha  = FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR;
          $sOperacao   = "";
          break;
      }

      $sCampo  = "(                                                                                      \n";
      $sCampo .= " SELECT SUM(rh145_valor)                                                               \n";
      $sCampo .= "   FROM rhhistoricopensao                                                              \n";
      $sCampo .= "        INNER JOIN rhfolhapagamento       ON rh141_sequencial = rh145_rhfolhapagamento \n";
      $sCampo .= "        INNER JOIN folhapagamentogeracao  ON rh141_sequencial = rh146_folhapagamento   \n";
      $sCampo .= "  WHERE rh141_tipofolha = {$iTipoFolha}                                                \n";
      $sCampo .= "    and rh141_aberto is false                                                          \n";
      $sCampo .= "    and rh145_pensao    = r52_sequencial                                               \n";
      $sCampo .= " ){$sOperacao}                                                                         \n";

      $campovalor       = $sCampo;
    }

    $sWhere = "$oArqBanco->rh34_wherepensa and $campovalor > 0";

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
      $sWhere =  $opt_todosbcos ? "$oArqBanco->rh34_wherepensa and $campovalor > 0" : "$campovalor > 0";
    }

    $sql = $clpensao->sql_query_gerarqbag(
      null,
      null,
      null,
      null,
      "$campovalor as r38_liq, length(trim(r52_codage)||trim(r52_dvagencia)) as qtddigitosagencia, contabancaria.*,
                                               r52_numcgm as r38_regist,
                                               r52_codbco as r38_banco,
                                               trim(r52_conta)||trim(coalesce(r52_dvconta,'')) as r38_conta,
                                               trim(r52_codage)||trim(coalesce(r52_dvagencia,'')) as r38_agenc,
                                               cgm.*,func.z01_nome as nomefuncionario,
                                               '01' as tipo_pagamento,
                                               r70_descr,
                                               length(trim(cgm.z01_cgccpf)) as tam,
                                               $campovalor as valorori",
      "r52_codbco,tipo_pagamento,cgm.z01_nome",
      $sWhere
    );

    $lPensionista = true;
    $result  = $clpensao->sql_record($sql);
    $numrows = $clpensao->numrows;
  }

  if ($numrows > 0) {

    $nomearquivo_impressao = "/tmp/folha_" . $banco . "_" . $paramnome . ".pdf";

    if (!is_writable("/tmp/")) {
      $sqlerro = true;
      $erro_msg = 'Sem permissão de gravar o arquivo. Contate suporte.';
    }

    ///// INICIA IMPRESSÃO DO RELATÓRIO
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->setfillcolor(235);
    $total = 0;
    $alt   = 4;

    $head3 = "ARQUIVO DE PAGAMENTO DA FOLHA";
    $head4 = "SEQUENCIAL DO ARQUIVO:  " . $sequenciaarqui;
    $head5 = "DATA DA GERAÇÃO:  " . db_formatar($datagera, "d") . ' ÀS ' . $horageracao . ' HS';
    $head6 = "DATA DO PAGAMENTO:  " . db_formatar($datadedeposito, "d");
    if ($opt_todosbcos) {
      $head7 = 'BANCO: ' . $oArqBanco->rh34_codban . ' - ' . $oArqBanco->db90_descr;
    } else {
      $head7 = 'BANCO: TODOS';
    }

    $valortotal             = 0;

    /**
     * Monta cabecalho para o PDF
     */
    $pdf->addpage("L");
    $pdf->cell(15, $alt, $RLrh01_regist, 1, 0, "C", 1);
    if ($tiparq < 5) {

      $pdf->cell(15, $alt, $RLz01_numcgm, 1, 0, "C", 1);
      $pdf->cell(20, $alt, $RLz01_cgccpf, 1, 0, "C", 1);
      $pdf->cell(65, $alt, $RLz01_nome, 1, 0, "C", 1);
      $pdf->cell(65, $alt, $RLr70_descr, 1, 0, "C", 1);
      $pdf->cell(10, $alt, 'Banco', 1, 0, "C", 1);
    } else {

      $pdf->cell(65, $alt, "Pensionista", 1, 0, "C", 1);
      $pdf->cell(65, $alt, "Funcionário", 1, 0, "C", 1);
      $pdf->cell(15, $alt, $RLz01_numcgm, 1, 0, "C", 1);
      $pdf->cell(20, $alt, $RLz01_cgccpf, 1, 0, "C", 1);
    }

    $pdf->cell(13, $alt, $RLr38_agenc, 1, 0, "C", 1);
    $pdf->cell(20, $alt, $RLr38_conta, 1, 0, "C", 1);
    $pdf->cell(20, $alt, $RLdb83_tipoconta, 1, 0, "C", 1);
    $pdf->cell(17, $alt, $RLr38_liq, 1, 1, "C", 1);

    $pdf->ln(3);


    /**
     * Cria um array associativo com os codigos do tipo de conta do
     * sistema para o codigos definidos pelo FEBRABAN.
     * 1 => 01 -> Conta Corrente
     * 2 => 05 -> Conta Poupança
     * 4 => 04 -> Conta Salário
     * @var array Tipos de conta
     */
    $aTiposConta = array(
      '1' => '01',
      '2' => '05',
      '4' => '04',
      '0' => '02' //SEM CONTA/CONTA_ZERADA
    );

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() and $lPensionista) {
      $oArqBanco->rh34_wherepensa .=  $opt_todosbcos ? "and $campovalor > 0" : "$campovalor > 0";
    }

    if ($lPensionista) {

      $sCampos = "case when trim(translate(r38_conta,'0','')) = '' then '02'                     ";
      $sCampos .= "     when db89_db_bancos <> '756'                then '03'                     ";
      $sCampos .= " else '01' end as tipo_pagamento,                                              ";
      $sCampos .= "r52_codbco as r38_banco, r52_codage || r52_dvagencia as r38_agenc, r52_conta || r52_dvconta as r38_conta, $campovalor as r38_liq,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia,
      contabancaria.*,
      r70_descr,
      rh01_regist as r38_regist,
      db89_codagencia,
      db89_digito, ";
      $sCampos .= "length(trim(cgm.z01_cgccpf)) as tam,                                           ";
      $sCampos .= "r38_liq as valorori                                                            ";
      $sWhere   = $oArqBanco->rh34_wherepensa;

      $sSql     = $clpensao->sql_query_gerarqbag(null, null, null, null, $sCampos, "r52_codbco,db83_tipoconta,tipo_pagamento,cgm.z01_nome", $sWhere);
    } else {

      $sCampos = "folha.*,cgm.*, length(trim(r38_agenc)) as qtddigitosagencia, contabancaria.*, r70_descr,
      db89_codagencia,
      db89_digito, ";
      //$sCampos .= "case when trim(translate(r38_conta,'0','')) = '' then '02'                                       ";
      $sCampos .= "    case when (select rh02_fpagto                                                                ";
      $sCampos .= "             from rhpessoalmov                                                                   ";
      $sCampos .= "            where rh02_regist = r38_regist                                                       ";
      $sCampos .= "              and rh02_anousu = " . db_anofolha();
      $sCampos .= "              and rh02_mesusu = " . db_mesfolha();
      $sCampos .= "              and rh02_instit = " . db_getsession("DB_instit") . ") = 4 then '02'                ";
      $sCampos .= "     when r38_banco <> '756' then '03'                                                           ";
      $sCampos .= " else '01' end as tipo_pagamento,                                                                ";
      $sCampos .= "length(trim(cgm.z01_cgccpf)) as tam,                                                             ";
      $sCampos .= "r38_liq as valorori                                                                              ";
      $sWhere    = $oArqBanco->rh34_wherefolha;

      $sSql      = $clfolha->sql_query_gerarqbag(null, $sCampos, "r38_banco,db83_tipoconta,tipo_pagamento,cgm.z01_nome", $sWhere);
    }

    $rsDados   = db_query($sSql);

    $iQuantidadeRegistrosArquivo = 0;

    for ($iServidor = 0; $iServidor < pg_num_rows($rsDados); $iServidor++) {

      $oDados = db_utils::fieldsMemory($rsDados, $iServidor);

      $iTipoConta       = $oDados->db83_tipoconta;
      $iCodigoTipoConta = $aTiposConta[$iTipoConta];

      if (($iTipoConta == "0" && $oDados->tipo_pagamento != "02")
        || ($iTipoConta != "0" && $oDados->tipo_pagamento == "02")
      ) {
        continue;
      }

      //Se está no primeiro laço as variáveis não existem e precisam ser iniciadas
      if (!isset($sTipoContaAnterior)) {
        $sTipoContaAnterior = $oDados->db83_tipoconta;
      }
      if (!isset($sBancoAnterior)) {
        $sBancoAnterior    = $oDados->r38_banco;
      }

      if ($oDados->tipo_pagamento == "02") {
        $oDados->r38_banco = '';
      }
      //////////////////////////////////////////////
      $z01_nome = $oDados->z01_nome;
      $z01_cgccpf = $oDados->z01_cgccpf;
      $matricula = $oDados->r38_regist;

      $oDados->db83_dvconta        = trim($oDados->db83_dvconta);
      $numeroconta   = str_replace('.', '', str_replace('-', '', $oDados->db83_conta)) . $oDados->db83_dvconta[0];
      $valorpagamento = $oDados->r38_liq;
      $dataprocessamento = $datadedeposito;

      ///// GRAVA REGISTRO DETALHE
      $iQuantidadeRegistrosArquivo += 1;
      $valortotal += $oDados->r38_liq;
      db_setaPropriedadesLayoutTxt($db_layouttxt, 3);
      ///// FINAL DO REGISTRO DETALHE


      if ($pdf->gety() > $pdf->h - 30) {
        $pdf->addpage("L");
        $pdf->cell(15, $alt, $RLrh01_regist, 1, 0, "C", 1);
        if ($tiparq < 5) {

          $pdf->cell(15, $alt, $RLz01_numcgm, 1, 0, "C", 1);
          $pdf->cell(20, $alt, $RLz01_cgccpf, 1, 0, "C", 1);
          $pdf->cell(65, $alt, $RLz01_nome, 1, 0, "C", 1);
          if (intval(strlen($RLr70_descr)) > 44) {
            $pdf->cell(65, $alt, substr($RLr70_descr, 0, 30) . '...', 1, 0, "C", 1);
          } else {
            $pdf->cell(65, $alt, $RLr70_descr, 1, 0, "C", 1);
          }
          $pdf->cell(10, $alt, 'Banco', 1, 0, "C", 1);
        } else {

          $pdf->cell(65, $alt, "Pensionista", 1, 0, "C", 1);
          $pdf->cell(65, $alt, "Funcionário", 1, 0, "C", 1);
          $pdf->cell(15, $alt, $RLz01_numcgm, 1, 0, "C", 1);
          $pdf->cell(20, $alt, $RLz01_cgccpf, 1, 0, "C", 1);
        }

        $pdf->cell(13, $alt, $RLr38_agenc, 1, 0, "C", 1);
        $pdf->cell(20, $alt, $RLr38_conta, 1, 0, "C", 1);
        $pdf->cell(20, $alt, $RLdb83_tipoconta, 1, 0, "C", 1);
        $pdf->cell(17, $alt, $RLr38_liq, 1, 1, "C", 1);
        $pdf->ln(3);
      }

      $pdf->setfont('arial', '', 7);
      $pdf->cell(15, $alt, $oDados->r38_regist, 1, 0, "C", 0);

      if ($tiparq < 5) {

        $pdf->cell(15, $alt, $oDados->z01_numcgm, 1, 0, "C", 0);
        $pdf->cell(20, $alt, $oDados->z01_cgccpf, 1, 0, "C", 0);
        $pdf->cell(65, $alt, $oDados->z01_nome, 1, 0, "L", 0);
        if (intval(strlen($oDados->r70_descr)) >= 40) {
          $pdf->cell(65, $alt, substr($oDados->r70_descr, 0, 30) . '...', 1, 0, "L", 0);
        } else {
          $pdf->cell(65, $alt, $oDados->r70_descr, 1, 0, "L", 0);
        }
        $pdf->cell(10, $alt, $oDados->r38_banco, 1, 0, "R", 0);
      } else {

        $pdf->cell(65, $alt, $oDados->z01_nome, 1, 0, "L", 0);
        $pdf->cell(65, $alt, $oDados->nomefuncionario, 1, 0, "L", 0);
        $pdf->cell(15, $alt, $oDados->z01_numcgm, 1, 0, "C", 0);
        $pdf->cell(20, $alt, $oDados->z01_cgccpf, 1, 0, "C", 0);
      }

      $pdf->cell(13, $alt, $oDados->r38_agenc, 1, 0, "R", 0);
      $pdf->cell(20, $alt, $oDados->r38_conta, 1, 0, "R", 0);
      $sTipoConta      = '';
      switch ($oDados->db83_tipoconta) {

        case '1':
          $sTipoConta = "Conta Corrente";
          break;
        case '2':
          $sTipoConta = "Conta Poupança";
          break;
        case '3':
          $sTipoConta = "Conta Aplicação";
          break;
        case '4':
          $sTipoConta = "Conta Salário";
          break;
      }
      $pdf->cell(20, $alt, $sTipoConta, 1, 0, "L", 0);
      $pdf->cell(17, $alt, db_formatar($oDados->r38_liq, 'f'), 1, 1, "R", 0);

      $valortotallote += $oDados->r38_liq;

      //Variáveis que guardam banco e tipo de conta para comparar no prÃ³xima repetição do laço
      $sTipoContaAnterior = $oDados->db83_tipoconta;
      $sBancoAnterior     = $oDados->r38_banco;
    }


    $pdf->setfont('arial', 'b', 8);
    $pdf->cell(240, $alt, 'TOTAL DE FUNCIONÁRIOS', 1, 0, "C", 1);
    $pdf->cell(20, $alt, $iQuantidadeRegistrosArquivo, 1, 1, "R", 1);

    $pdf->cell(240, $alt, 'TOTAL GERAL', 1, 0, "C", 1);
    $pdf->cell(20, $alt, db_formatar($valortotal, 'f'), 1, 1, "R", 1);

    $quantidaderegistarq  = $iQuantidadeRegistrosArquivo;
    $somavalores = $valortotal;
    db_setaPropriedadesLayoutTxt($db_layouttxt, 5);
    ///// FINAL DO TRAILLER DE ARQUIVO
    //////////////////////////////////
    $pdf->Output($nomearquivo_impressao, false, true);
  } else {
    $sqlerro = true;
    $erro_msg = "Nenhum registro encontrado. Contate o suporte.";
  }
}
// die;
if ($sqlerro == false) {
  echo "
    <script>
      parent.js_detectaarquivo('tmp/$nomearquivo','$nomearquivo_impressao');
    </script>
    ";
} else {
  echo "
    <script>
      parent.js_erro('$erro_msg');
    </script>
    ";
}

db_fim_transacao($sql);
