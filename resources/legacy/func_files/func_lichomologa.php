<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_liclicita_classe.php");
include("classes/db_liclicitem_classe.php");
include("classes/db_licitaparam_classe.php");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clliclicitem = new cl_liclicitem;
$clliclicita  = new cl_liclicita;
$cllicitaparam = new cl_licitaparam;

$clliclicita->rotulo->label("l20_codigo");
$clliclicita->rotulo->label("l20_numero");
$clliclicita->rotulo->label("l20_edital");
$clrotulo = new rotulocampo;
$clrotulo->label("l03_descr");

$sWhereContratos = " and 1 = 1 ";
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
        <td height="63" align="center" valign="top">
            <table width="35%" border="0" align="center" cellspacing="0">
                <form name="form2" method="post" action="" >
                    <tr>
                        <td width="4%" align="right" nowrap title="<?=$Tl20_codigo?>">
                            <?=$Ll20_codigo?>
                        </td>
                        <td width="96%" align="left" nowrap>
                            <?
                            db_input("l20_codigo",10,$Il20_codigo,true,"text",4,"","chave_l20_codigo");
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="4%" align="right" nowrap title="<?=$Tl20_edital?>">
                            <?=$Ll20_edital?>
                        </td>
                        <td width="96%" align="left" nowrap>
                            <?
                            db_input("l20_edital",10,$Il20_edital,true,"text",4,"","chave_l20_edital");
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="4%" align="right" nowrap title="<?=$Tl20_numero?>">
                            <?=$Ll20_numero?>
                        </td>
                        <td width="96%" align="left" nowrap>
                            <?
                            db_input("l20_numero",10,$Il20_numero,true,"text",4,"","chave_l20_numero");
                            ?>
                        </td>
                    </tr>
                    <tr>

                    <tr>
                        <td width="4%" align="right" nowrap title="<?=$Tl03_descr?>">
                            <?=$Ll03_descr?>
                        </td>
                        <td width="96%" align="left" nowrap>
                            <?
                            db_input("l03_descr",60,$Il03_descr,true,"text",4,"","chave_l03_descr");
                            db_input("param",10,"",false,"hidden",3);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                            <input name="limpar" type="reset" id="limpar" value="Limpar" >
                            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_liclicita.hide();">
                        </td>
                    </tr>
                </form>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" valign="top">
            <?
            $and            = "and ";
            $dbwhere        = "";
            $dbwhere_busca = '';
            if (isset($tipo) && trim($tipo)!=""){
                $dbwhere   = "l08_altera is true and";
            }

            if (!empty($chave_l03_descr)) {
                $dbwhere_busca = " and pctipocompra.pc50_descr LIKE '%$chave_l03_descr%'";
            }

            if (!empty($chave_l20_numero)) {
                $dbwhere_busca .= " and liclicita.l20_numero = $chave_l20_numero";
            }

            if (!empty($chave_l20_codigo)) {
                $dbwhere_busca .= " and liclicita.l20_codigo = $chave_l20_codigo";
            }

            if (!empty($chave_l20_edital)) {
                $dbwhere_busca .= " and liclicita.l20_edital = $chave_l20_edital";
            }

            $rsParamLic = $cllicitaparam->sql_record($cllicitaparam->sql_query(null, "*", null, "l12_instit = " . db_getsession('DB_instit')));
            $l12_adjudicarprocesso = db_utils::fieldsMemory($rsParamLic, 0)->l12_adjudicarprocesso;

            /**
             * QUANDO FOR ADJUDICACAO NAO DEVE RETORNAR PROCESSO QUE SAO REGISTRO DE PRECO
             */
            if(isset($homologacao) &&trim($homologacao) == "1") {
                if($l12_adjudicarprocesso == "f"){
                    $dbwhere .= "((liclicita.l20_tipnaturezaproced = 2 and l20_licsituacao in (1,10)) or (l20_licsituacao in (13,10))) and l200_data <= '" . date('Y-m-d', db_getsession('DB_datausu')) . "'
                    and l11_data <= '" . date('Y-m-d', db_getsession('DB_datausu')) . "' and ";
                }
                if($l12_adjudicarprocesso == "t"){
                    $dbwhere .= "((liclicita.l20_tipnaturezaproced in (1,2) and l20_licsituacao in (13))) and l200_data <= '" . date('Y-m-d', db_getsession('DB_datausu')) . "'
                    and l11_data <= '" . date('Y-m-d', db_getsession('DB_datausu')) . "' and ";
                }
            }else if(isset($homologacao) &&trim($homologacao) == "0") {
                $dbwhere .= "l202_dataadjudicacao IS NOT NULL AND l202_datahomologacao IS NOT NULL AND ";
            }else{
                $dbwhere .= "l20_licsituacao = 10 and l200_data <= '" . date('Y-m-d', db_getsession('DB_datausu')) . "'
             and l11_data <= '" . date('Y-m-d', db_getsession('DB_datausu')) . "' and l202_datahomologacao is not null and ";
                $dbwhere .= "liclicita.l20_codigo not in (select ac16_licitacao from acordo where ac16_licitacao = liclicita.l20_codigo) and ";
            }
            /**
             * QUANDO FOR ADJUDICACAO NAO DEVE RETORNAR PROCESSO QUE SAO REGISTRO DE PRECO
             */
            if(isset($adjudicacao) &&trim($adjudicacao) != ""){
                $dbwhere .= "l20_tipnaturezaproced != 2 AND ";
            }
            /**
             * INCLUSAO
             */
            if(isset($adjudicacao) && trim($adjudicacao) == "1"){
                $dbwhere .= "l202_dataadjudicacao IS NULL AND l202_datahomologacao IS NULL AND ";
            }
            /**
             * ALTERACAO
             */
            if(isset($adjudicacao) && trim($adjudicacao) == "2"){
                $dbwhere .= "l202_dataadjudicacao IS NOT NULL AND l202_datahomologacao IS NULL AND ";
            }

            $sWhereModalidade = "";

            if (isset($iModalidadeLicitacao) && !empty($iModalidadeLicitacao)) {
                $sWhereModalidade = "and l20_codtipocom = {$iModalidadeLicitacao}";
            }

            $dbwhere_instit = "l20_instit = ".db_getsession("DB_instit"). "{$sWhereModalidade}";


            if (isset($lContratos) && $lContratos == 1 ) {

                $sWhereContratos .= " and ac24_sequencial is null ";
            }
            if($homologacao == "1") {
                $sWhereContratos .= " AND liclicita.l20_codigo IN
                (SELECT DISTINCT liclicitem.l21_codliclicita
                 FROM pcprocitem
                 INNER JOIN pcproc ON pcproc.pc80_codproc = pcprocitem.pc81_codproc
                 INNER JOIN solicitem ON solicitem.pc11_codigo = pcprocitem.pc81_solicitem
                 INNER JOIN solicita ON solicita.pc10_numero = solicitem.pc11_numero
                 LEFT JOIN liclicitem ON liclicitem.l21_codpcprocitem = pcprocitem.pc81_codprocitem
                 LEFT JOIN liclicitemlote ON liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                 LEFT JOIN pcorcamitemlic ON liclicitem.l21_codigo = pcorcamitemlic.pc26_liclicitem
                 LEFT JOIN pcorcamitem ON pcorcamitemlic.pc26_orcamitem = pcorcamitem.pc22_orcamitem
                 LEFT JOIN pcorcamjulg ON pcorcamitem.pc22_orcamitem = pcorcamjulg.pc24_orcamitem
                 LEFT JOIN pcorcamval ON (pc24_orcamitem,pc24_orcamforne) = (pc23_orcamitem,pc23_orcamforne)
                 LEFT JOIN pcorcamforne ON pc24_orcamforne = pc21_orcamforne
                 LEFT JOIN cgm cgmforncedor ON pcorcamforne.pc21_numcgm = cgmforncedor.z01_numcgm
                 LEFT JOIN homologacaoadjudica ON l202_licitacao = l21_codliclicita
                 LEFT JOIN itenshomologacao ON l203_homologaadjudicacao = l202_sequencial
                 AND l203_item = pc81_codprocitem
                 WHERE liclicitem.l21_codliclicita = liclicita.l20_codigo
                     AND pc24_pontuacao = 1
                     AND pc81_codprocitem NOT IN
                         (SELECT l203_item
                          FROM homologacaoadjudica
                          INNER JOIN itenshomologacao ON l203_homologaadjudicacao = l202_sequencial
                          WHERE l202_licitacao = liclicita.l20_codigo))";
            }

            $whereHab .= "AND l03_pctipocompratribunal NOT IN (100,101,102,103)";

            if(!isset($pesquisa_chave)){

                if(isset($campos)==false){
                    if(file_exists("funcoes/db_func_liclicita.php")==true){
                        include("funcoes/db_func_liclicita.php");
                    }else{
                        $campos = "liclicita.*, liclicitasituacao.l11_sequencial";
                    }
                }
                if(isset($homologacao) &&trim($homologacao) == "1") {
                    $campos .= ', l08_descr as dl_Situa��o';
                }else{
                    $campos .= ', l08_descr as dl_Situa��o,l202_dataadjudicacao,l202_datahomologacao,l202_sequencial';
                }
                if(isset($chave_l20_codigo) && (trim($chave_l20_codigo)!="") ){
                    $sql = $clliclicita->sql_queryContratosContass(null," " . $campos,"l20_codigo","l20_codigo = $chave_l20_codigo $and $dbwhere $dbwhere_instit $sWhereContratos $whereHab",$situacao);
                }else if(isset($chave_l20_numero) && (trim($chave_l20_numero)!="") ){
                    $sql = $clliclicita->sql_queryContratosContass(null," " .$campos,"l20_codigo","l20_numero=$chave_l20_numero $and $dbwhere $dbwhere_instit $sWhereContratos $whereHab",$situacao);
                }else if(isset($chave_l03_descr) && (trim($chave_l03_descr)!="") ){
                    $sql = $clliclicita->sql_queryContratosContass(null," " .$campos,"l20_codigo","l03_descr like '$chave_l03_descr%' $and $dbwhere $dbwhere_instit $sWhereContratos $whereHab",$situacao);
                }else if(isset($chave_l03_codigo) && (trim($chave_l03_codigo)!="") ){
                    $sql = $clliclicita->sql_queryContratosContass(null," " .$campos,"l20_codigo","l03_codigo=$chave_l03_codigo $and $dbwhere $dbwhere_instit $sWhereContratos $whereHab",$situacao);
                }else if(isset($chave_l20_edital) && (trim($chave_l20_edital)!="")){
                    $sql = $clliclicita->sql_queryContratosContass(null," " .$campos,"l20_codigo","l20_edital=$chave_l20_edital $and $dbwhere $dbwhere_instit $sWhereContratos $whereHab",$situacao);
                }else{
                    $sql = $clliclicita->sql_queryContratosContass(""," " .$campos,"l20_codigo","$dbwhere $dbwhere_instit $sWhereContratos $whereHab",$situacao);
                }

                if (isset($param) && trim($param) != ""){
                    $dbwhere = " and (e55_sequen is null or (e55_sequen is not null and e54_anulad is not null))";
                    if(isset($chave_l20_codigo) && (trim($chave_l20_codigo)!="") ){
                        $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","l20_codigo = $chave_l20_codigo$dbwhere $whereHab");
                    }else if(isset($chave_l20_numero) && (trim($chave_l20_numero)!="") ){
                        $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","l20_numero=$chave_l20_numero$dbwhere $whereHab");
                    }else if(isset($chave_l03_descr) && (trim($chave_l03_descr)!="") ){
                        $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","l03_descr like '$chave_l03_descr%'$dbwhere $whereHab");
                    }else if(isset($chave_l03_codigo) && (trim($chave_l03_codigo)!="") ){
                        $sql = $clliclicitem->sql_query_inf(null,$campos,"l20_codigo","l03_codigo=$chave_l03_codigo$dbwhere $whereHab");
                    } else {
                        $sql = $clliclicitem->sql_query_inf("",$campos,"l20_codigo","1=1$dbwhere $whereHab");
                    }
                }

                if($relatorio=="1"){
                    $sql =  "select distinct liclicita.l20_codigo,
                liclicita.l20_edital,
                l20_anousu,
                pctipocompra.pc50_descr,
                liclicita.l20_numero,
                (case
                    when l20_nroedital is null then '-'
                    else l20_nroedital::varchar
                end) as l20_nroedital,
                liclicita.l20_datacria as dl_Data_Abertura_Proc_Adm,
                liclicita.l20_dataaber as dl_Data_Emis_Alt_Edital_Convite,
                liclicita.l20_dtpublic as dl_Data_Publica��o_DO,
                liclicita.l20_objeto,
                l08_descr as dl_Situa��o,
                homologacaoadjudica.l202_dataadjudicacao,
                homologacaoadjudica.l202_datahomologacao,
                homologacaoadjudica.l202_sequencial
                from
                liclicita
                inner join db_config on
                db_config.codigo = liclicita.l20_instit
                inner join db_usuarios on
                db_usuarios.id_usuario = liclicita.l20_id_usucria
                inner join cflicita on
                cflicita.l03_codigo = liclicita.l20_codtipocom
                inner join liclocal on
                liclocal.l26_codigo = liclicita.l20_liclocal
                inner join liccomissao on
                liccomissao.l30_codigo = liclicita.l20_liccomissao
                inner join licsituacao on
                licsituacao.l08_sequencial = liclicita.l20_licsituacao
                inner join cgm on
                cgm.z01_numcgm = db_config.numcgm
                inner join db_config as dbconfig on
                dbconfig.codigo = cflicita.l03_instit
                inner join pctipocompra on
                pctipocompra.pc50_codcom = cflicita.l03_codcom
                inner join bairro on
                bairro.j13_codi = liclocal.l26_bairro
                inner join ruas on
                ruas.j14_codigo = liclocal.l26_lograd
                left join liclicitaproc on
                liclicitaproc.l34_liclicita = liclicita.l20_codigo
                left join protprocesso on
                protprocesso.p58_codproc = liclicitaproc.l34_protprocesso
                left join liclicitem on
                liclicita.l20_codigo = l21_codliclicita
                left join acordoliclicitem on
                liclicitem.l21_codigo = acordoliclicitem.ac24_liclicitem
                left join liclancedital on
                liclancedital.l47_liclicita = liclicita.l20_codigo
                left join homologacaoadjudica on
                    homologacaoadjudica.l202_licitacao = liclicita.l20_codigo
                where
                l20_licsituacao = 10
                and l20_instit = ".db_getsession("DB_instit")."
                and l03_pctipocompratribunal NOT IN (100,101,102,103)
                and l202_datahomologacao is not null
                $dbwhere_busca
                order by
                l20_codigo";
                }
                db_lovrot($sql.' desc ',15,"()","",$funcao_js);

            } else {

                if ($pesquisa_chave != null && $pesquisa_chave != "") {
                    if (isset($param) && trim($param) != ""){

                        $result = $clliclicitem->sql_record($clliclicitem->sql_query_inf($pesquisa_chave));

                        if ($clliclicitem->numrows!=0) {

                            db_fieldsmemory($result,0);
                            /**
                             *
                             * Adicionado o campo pc50_descr, removido o campo $l20_codigo e, coforme solicitado por Deborah@contass,
                             * inserido a numera��o da modalidade. linhas: 187 e 197.
                             *
                             */

                            echo "<script>".$funcao_js."('$pc50_descr $l20_numero',false);</script>";
                        }else{
                            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
                        }
                    } else {
                        $dbwhere .= " pctipocompra.pc50_pctipocompratribunal not in (100, 101, 102, 103) AND ";
                        $result = $clliclicita->sql_record($clliclicita->sql_queryContratosContass(null,"*",null,"l20_codigo = $pesquisa_chave $and $dbwhere $dbwhere_instit"));
                        if($clliclicita->numrows != 0){

                            db_fieldsmemory($result,0);
                            echo "<script>".$funcao_js."('$pc50_descr $l20_numero',false);</script>";

                        } else {

                            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
                        }
                    }

                } else {
                    echo "<script>".$funcao_js."('',false);</script>";
                }
            }
            ?>
        </td>
    </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
    ?>
    <script>
    </script>
    <?
}
?>
