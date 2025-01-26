<?

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_liclicitem_classe.php");
require_once("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');
require_once("classes/licitacao.model.php");
$clliclicitem = new cl_liclicitem;
$clrotulo     = new rotulocampo;
$clrotulo->label("l20_codigo");

db_postmemory($HTTP_GET_VARS);

$oGet         = db_utils::postMemory($_GET);

$iLicitacao   = $oGet->l20_codigo;

if ($oGet->extensao == 1) {
    $extensao = 'txt';
} else if ($oGet->extensao == 2) {
    $extensao = 'csv';
} else if ($oGet->extensao == 3) {
    $extensao = 'imp';
}

$leiaute = $oGet->leiaute;


if ($leiaute == 1) {

    //====================  instanciamos a classe da solicita��o selecionada para retornar os itens


    $oLicitacao = new licitacao($iLicitacao);
    try {
        $aEditalLicitacao   = $oLicitacao->getEditalExport();
        $aItensLicitacao    = $oLicitacao->getItensExport();
        $aLoteLicitacao    = $oLicitacao->getLoteExport();
    } catch (Exception $oErro) {
        db_redireciona('db_erros.php?fechar=true&db_erro=' . $oErro->getMessage());
    }

    system("cd tmp; rm -f *.$extensao; cd ..");
    $numeroEdital = $aEditalLicitacao[0]->numeroedital;
    $anoProcessoLicitatorio = $aEditalLicitacao[0]->anoprocessolicitatorio;
    $processolicitatorio = $aEditalLicitacao[0]->processolicitatorio;
    $anoEdital = $aEditalLicitacao[0]->anoedital;
    $cnpj = $aEditalLicitacao[0]->cnpj;
    $codigotcemg = $aEditalLicitacao[0]->codigotcemg;

    $clabre_arquivo = new cl_abre_arquivo("tmp/Edital_" . $numeroEdital . "_" . $anoEdital . "_" . $iLicitacao . "_" . $anoProcessoLicitatorio . "." . $extensao);

    if ($clabre_arquivo->arquivo != false) {

        $vir = $separador;
        $del = $delimitador;

        fputs($clabre_arquivo->arquivo, "");

        foreach ($aEditalLicitacao as $iItens => $oItens) {

            $iTipoRegistro                   = $oItens->tiporegistro;
            $iCodigoOrgao                    = $oItens->codigotcemg;
            $iTipoOrgao                      = $oItens->tipodeinstituicao;
            $iCnpj                           = $oItens->cnpj;
            $sNomeOrgao                      = $oItens->nomedainstituicao;
            $iProcessoLicitatorio            = $oItens->processolicitatorio;
            $iExercicio                      = $oItens->anoprocessolicitatorio;
            $iNroEdital                      = $oItens->numeroedital;
            $iExercicioEdital                = $oItens->anoedital;
            $sProcessoObjeto                 = $oItens->objeto;
            $iNaturezaObjeto                 = $oItens->naturezadoobjeto;
            $iRegistroPreco                  = $oItens->registrodepreco;

            fputs($clabre_arquivo->arquivo, formatarCampo($iTipoRegistro, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iCodigoOrgao, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iTipoOrgao, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iCnpj, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($sNomeOrgao, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iProcessoLicitatorio, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iExercicio, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iNroEdital, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iExercicioEdital, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($sProcessoObjeto, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iNaturezaObjeto, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iRegistroPreco, $vir, $del));

            fputs($clabre_arquivo->arquivo, "\n");
        }

        fclose($clabre_arquivo->arquivo);
    }

    $clabre_arquivo = new cl_abre_arquivo("tmp/Itens_" . $iLicitacao . "_" . $anoProcessoLicitatorio . "_" . $cnpj . "." . $extensao);

    if ($clabre_arquivo->arquivo != false) {

        $vir = $separador;
        $del = $delimitador;

        fputs($clabre_arquivo->arquivo, "");

        foreach ($aItensLicitacao as $iItens => $oItens) {

            $iTipoRegistro                   = $oItens->tiporegistro;
            $iCnpj                           = $oItens->cnpj;
            $iProcessoLicitatorio            = $oItens->processolicitatorio;
            $iExercicio                      = $oItens->anoprocessolicitatorio;
            $iCodMater                       = $oItens->codigodoitem;
            $iOrdem                          = $oItens->sequencialdoitemnoprocesso;
            $sDescrItem                      = $oItens->descricaodoitem;
            $sUnidadeMedida                  = $oItens->unidadedemedida;
            $iQtdLicitada                    = $oItens->quantidadelicitada;
            $iValorUnitMedio                 = $oItens->valorunitariomedio;
            $iCodigodolote                   = $oItens->codigodolote;

            if ($oItens->l21_reservado != 'f')
                $sReservado = 'Sim';
            else
                $sReservado = 'Nao';

            //$sReservado = $oItens->l21_reservado;

            fputs($clabre_arquivo->arquivo, formatarCampo($iTipoRegistro, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iCnpj, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iProcessoLicitatorio, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iExercicio, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iCodMater, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iOrdem, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($sDescrItem, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($sUnidadeMedida, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iQtdLicitada, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iValorUnitMedio, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($iCodigodolote, $vir, $del));
            fputs($clabre_arquivo->arquivo, formatarCampo($sReservado, $vir, $del));

            fputs($clabre_arquivo->arquivo, "\n");
        }

        fclose($clabre_arquivo->arquivo);
    }

    $clabre_arquivo = new cl_abre_arquivo("tmp/Lote_" . $iLicitacao . "_" . $anoProcessoLicitatorio . "_" . $cnpj . "." . $extensao);

    if ($clabre_arquivo->arquivo != false) {

        $vir = $separador;
        $del = $delimitador;

        fputs($clabre_arquivo->arquivo, "");

        if ($aEditalLicitacao[0]->l20_tipojulg == 3) {
            foreach ($aLoteLicitacao as $iItens => $oItens) {
                $iTipoRegistro                   = $oItens->tiporegistro;
                $iProcessoLicitatorio            = $oItens->processolicitatorio;
                $iExercicio                      = $oItens->anoprocessolicitatorio;
                $iLote                           = $oItens->codigodolote;
                $iCodItem                        = $oItens->codigodoitemvinculadoaolote;
                $sDescrLote                      = $oItens->descricaodolote;

                if ($oItens->l21_reservado != 'f')
                    $sReservado = 'Sim';
                else
                    $sReservado = 'Nao';

                //$sReservado = $oItens->l21_reservado;

                fputs($clabre_arquivo->arquivo, formatarCampo($iTipoRegistro, $vir, $del));
                fputs($clabre_arquivo->arquivo, formatarCampo($iProcessoLicitatorio, $vir, $del));
                fputs($clabre_arquivo->arquivo, formatarCampo($iExercicio, $vir, $del));
                fputs($clabre_arquivo->arquivo, formatarCampo($iLote, $vir, $del));
                fputs($clabre_arquivo->arquivo, formatarCampo($iCodItem, $vir, $del));
                fputs($clabre_arquivo->arquivo, formatarCampo($sDescrLote, $vir, $del));
                fputs($clabre_arquivo->arquivo, formatarCampo($sReservado, $vir, $del));
                fputs($clabre_arquivo->arquivo, "\n");
            }
        } else {
            fputs($clabre_arquivo->arquivo, '99');
            fputs($clabre_arquivo->arquivo, "\n");
        }
        fclose($clabre_arquivo->arquivo);
    }
    $aArquivosGerados = array();
    $aArquivosGerados[] = "Edital_" . $numeroEdital . "_" . $anoEdital . "_" . $iLicitacao . "_" . $anoProcessoLicitatorio . "." . $extensao;
    $aArquivosGerados[] = "Itens_" . $iLicitacao . "_" . $anoProcessoLicitatorio . "_" . $cnpj . "." . $extensao;
    $aArquivosGerados[] = "Lote_" . $iLicitacao . "_" . $anoProcessoLicitatorio . "_" . $cnpj . "." . $extensao;

    $sNomeAbsoluto = $cnpj . "_" . $codigotcemg . "_" . $processolicitatorio . "_" . $anoProcessoLicitatorio;

    //compactaArquivos($aArquivosGerados, $sNomeArquivo);

    foreach ($aArquivosGerados as $sArquivo) {
        $sArquivos .= " $sArquivo";
    }

    system("cd tmp; rm -f {$sNomeAbsoluto}.zip; cd ..");
    system("cd tmp; ../bin/zip -q {$sNomeAbsoluto}.zip $sArquivos 2> erro.txt; cd ..");

    echo "<script>";
    echo "  jan = window.open('db_download.php?arquivo=" . "tmp/{$sNomeAbsoluto}.zip" . "','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
    echo "  jan.moveTo(0,0);";
    echo "</script>";
} else if ($leiaute == 2) {


    $resultRegistro1 = db_query("select
    1 as tiporegistro,
    l20_nroedital as edital,
    l20_exercicioedital as exercicioedital,
    l20_edital as numprocesso,
    l20_numero,
    l20_tipojulg,
    nomeinst as orgao,
    l20_usaregistropreco,
    12 as mesesvigencia
    from liclicita
    join db_config on codigo=l20_instit
    where l20_codigo= $l20_codigo");

    if (pg_numrows($resultRegistro1) == 0) {
        db_redireciona('db_erros.php?fechar=true&db_erro=Dados do Edital Incompletos.');
        exit;
    }

    $tipoJulgamento = pg_result($resultRegistro1, 0, "l20_tipojulg");

    if($tipoJulgamento == 3){

        $resultRegistro2 = db_query("select distinct on (l04_numerolote)
        2 as tiporegistro,
        l20_nroedital as edital,
        l20_exercicioedital as exercicioedital,
        l04_descricao as  descricaolote,
        l20_liclocal as localentrega,
        l20_localentrega as localdeentrega,
        l20_prazoentrega as datadeentrega,
        l20_numero,
        l04_numerolote,
        l21_ordem,
        pc01_descrmater,
        0 as garantia /*gerar em branco*/
        from liclicita
        join liclicitem on l21_codliclicita=l20_codigo
        INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
        INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
        INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
        INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
        INNER JOIN pcmater ON pc01_codmater = pc16_codmater
        join liclicitemlote on l04_liclicitem=l21_codigo
        where l20_codigo= $l20_codigo and pc11_quant != 0 order by l04_numerolote,l21_ordem");
    } else {

        $resultRegistro2 = db_query("  SELECT 
        2 AS tiporegistro,
        l20_nroedital AS edital,
        l20_exercicioedital AS exercicioedital,
        l20_liclocal AS localentrega,
        l20_localentrega AS localdeentrega,
        l20_prazoentrega AS datadeentrega,
        l20_numero,
        l21_ordem,
        pc01_descrmater,
        0 AS garantia /* gerar em branco */
    FROM liclicita
    JOIN liclicitem ON l21_codliclicita = l20_codigo
    INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
    INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
    INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
    INNER JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
    INNER JOIN pcmater ON pc01_codmater = pc16_codmater
    WHERE l20_codigo = $l20_codigo AND pc11_quant != 0
    ORDER BY l21_ordem;");
    }


    if (pg_numrows($resultRegistro2) == 0) {
        db_redireciona('db_erros.php?fechar=true&db_erro=Dados do Lote Edital Incompletos.');
        exit;
    }

    $resultRegistro3 = db_query("SELECT 
    3 as tiporegistro,
    pc16_codmater,
    l20_nroedital as edital,
    l20_numero,
    l20_exercicioedital as exercicioedital,
    l04_descricao as  descricaolote,
    l04_codigo as numerodolote,
    l21_ordem as numerodoitem,
    m61_descr as unidade,
    pc11_quant,
    pc80_codproc,
    l04_numerolote,
    l21_ordem,
    CASE
               WHEN pc80_criterioadjudicacao = 3 THEN si02_vlprecoreferencia
               ELSE si02_vlpercreferencia
           END AS vlrun,
    pc01_descrmater||'. '||pc01_complmater as pc01_descrmater
    FROM liclicitem
    LEFT JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
    INNER JOIN liclicita ON liclicita.l20_codigo = liclicitem.l21_codliclicita
    INNER JOIN pcprocitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
    INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
    INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
    INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
    INNER JOIN db_depart ON db_depart.coddepto = solicita.pc10_depto
    INNER JOIN db_usuarios ON solicita.pc10_login = db_usuarios.id_usuario
    LEFT JOIN pcorcamitemproc ON pc31_pcprocitem = pc81_codprocitem
    LEFT JOIN itemprecoreferencia ON si02_itemproccompra = pc31_orcamitem
    LEFT JOIN solicitemunid ON solicitemunid.pc17_codigo = solicitem.pc11_codigo
    LEFT JOIN matunid ON matunid.m61_codmatunid = solicitemunid.pc17_unid
    LEFT JOIN solicitempcmater ON solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
    LEFT JOIN pcmater ON pcmater.pc01_codmater = solicitempcmater.pc16_codmater
    LEFT JOIN solicitemele ON solicitemele.pc18_solicitem = solicitem.pc11_codigo
    where l20_codigo= $l20_codigo and pc11_quant != 0 ORDER BY l04_numerolote,l21_ordem;");

    if (pg_numrows($resultRegistro3) == 0) {
        db_redireciona('db_erros.php?fechar=true&db_erro=Dados do Edital Incompletos.');
        exit;
    }

    $clabre_arquivo = new cl_abre_arquivo("/tmp/licitacao_$l20_codigo.txt");

    $numeroprocesso = 0;

    if ($clabre_arquivo->arquivo != false) {

        for ($w = 0; $w < pg_numrows($resultRegistro1); $w++) {


            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro1, $w, "tiporegistro") . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro1, $w, "l20_numero") . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro1, $w, "exercicioedital") . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro1, $w, "numprocesso") . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro1, $w, "orgao") . "|");

            $usaregistropreco = pg_result($resultRegistro1, $w, "l20_usaregistropreco");

            if ($usaregistropreco == "f") {
                $usaregistropreco = "0";
            } else {
                $usaregistropreco = "1";
            }

            fputs($clabre_arquivo->arquivo, $usaregistropreco . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro1, $w, "mesesvigencia"));
            fputs($clabre_arquivo->arquivo, "\n");
        }

        $descricaoLote = "";
        $sequencial = 0;
        $sequencialLote = array();

        for ($w = 0; $w < pg_numrows($resultRegistro2); $w++) {

            if ($descricaoLote != pg_result($resultRegistro2, $w, "descricaolote")) {
                $sequencial++;
                $sequencialLote[pg_result($resultRegistro2, $w, "numerodolote")] = $sequencial;
                $descricaoLote = pg_result($resultRegistro2, $w, "descricaolote");
            } else {
                $sequencialLote[pg_result($resultRegistro2, $w, "numerodolote")] = $sequencial;
            }

            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro2, $w, "tiporegistro") . "|");

            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro2, $w, "l20_numero") . "|");

            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro2, $w, "exercicioedital") . "|");
            if($tipoJulgamento != 3){
                fputs($clabre_arquivo->arquivo, $w+1 . "|");
                fputs($clabre_arquivo->arquivo, pg_result($resultRegistro2, $w, "pc01_descrmater") . "|");
            }else {
                fputs($clabre_arquivo->arquivo, $sequencialLote[pg_result($resultRegistro2, $w, "numerodolote")] . "|");
                fputs($clabre_arquivo->arquivo, pg_result($resultRegistro2, $w, "descricaolote") . "|");
            }
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro2, $w, "localentrega") . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro2, $w, "localdeentrega") . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro2, $w, "datadeentrega") . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro2, $w, "garantia"));
            //fputs($clabre_arquivo->arquivo, pg_result($resultRegistro2, $w, "numerodolote"));
            fputs($clabre_arquivo->arquivo, "\n");
        }

        $descricaoLote = "";
        $sequencial = 0;
        $sequencialLote = array();

        $valores = array();

        for ($w = 0; $w < pg_numrows($resultRegistro3); $w++) {
            if (pg_result($resultRegistro3, $w, "vlrun") != null) {
                $valores[pg_result($resultRegistro3, $w, "pc16_codmater")] = pg_result($resultRegistro3, $w, "vlrun");
            }
        }



        for ($w = 0; $w < pg_numrows($resultRegistro3); $w++) {

            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro3, $w, "tiporegistro") . "|");

            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro3, $w, "l20_numero") . "|");


            if ($descricaoLote != pg_result($resultRegistro3, $w, "descricaolote")) {
                $sequencial++;
                $sequencialLote[pg_result($resultRegistro3, $w, "numerodolote")] = $sequencial;
                $descricaoLote = pg_result($resultRegistro3, $w, "descricaolote");
            } else {
                $sequencialLote[pg_result($resultRegistro3, $w, "numerodolote")] = $sequencial;
            }

            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro3, $w, "exercicioedital") . "|");
            fputs($clabre_arquivo->arquivo, $sequencialLote[pg_result($resultRegistro3, $w, "numerodolote")] . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro3, $w, "numerodoitem") . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro3, $w, "unidade") . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro3, $w, "pc11_quant") . "|");

            if (pg_result($resultRegistro3, $w, "vlrun") == null) {
                $processoDeCompra = pg_result($resultRegistro3,$w, "pc80_codproc");
                $rsPrecoReferencia = db_query("select si01_sequencial from precoreferencia where si01_processocompra = $processoDeCompra;");
                $si01_sequencial = db_utils::fieldsMemory($rsPrecoReferencia, 0)->si01_sequencial;
                $pc16_codmater = pg_result($resultRegistro3, $w, "pc16_codmater");
                $rsValorPrecoReferencia = db_query("select si02_vlprecoreferencia from itemprecoreferencia where si02_coditem = $pc16_codmater and si02_precoreferencia = $si01_sequencial;");
                $valorPrecoReferencia = pg_result($rsValorPrecoReferencia, 0, "si02_vlprecoreferencia");
                fputs($clabre_arquivo->arquivo,  $valorPrecoReferencia . "|");
            } else {
                fputs($clabre_arquivo->arquivo, pg_result($resultRegistro3, $w, "vlrun") . "|");
            }

            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro3, $w, "pc01_descrmater") . "|");
            fputs($clabre_arquivo->arquivo, pg_result($resultRegistro3, $w, "numerodolote"));
            fputs($clabre_arquivo->arquivo, "\n");
        }


        fclose($clabre_arquivo->arquivo);

        echo "<script>";
        echo "  jan = window.open('db_download.php?arquivo=" . $clabre_arquivo->nomearq . "','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
        echo "  jan.moveTo(0,0);";
        echo "</script>";
    }
}

// Funcao para formatar um campo
function formatarCampo($valor, $separador, $delimitador)
{

    $del = "";
    if ($delimitador == "1") {
        $del = "|";
    } else if ($delimitador == "2") {
        $del = ";";
    } else if ($delimitador == "3") {
        $del = ",";
    }

    $valor = str_replace("\n", " ", $valor);
    $valor = str_replace("\r", " ", $valor);

    return "{$valor}{$del}";
}
