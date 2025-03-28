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
require_once ("fpdf151/pdf.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("std/db_stdClass.php");
include ("classes/db_matparam_classe.php");
include ("classes/db_db_departorg_classe.php");
include ("classes/db_db_almox_classe.php");
include ("classes/db_db_almoxdepto_classe.php");
include ("classes/db_matestoque_classe.php");
include ("classes/db_matestoqueitem_classe.php");
include ("classes/db_matmater_classe.php");
include ("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$clmatparam       = new cl_matparam;
$cldb_departorg   = new cl_db_departorg;
$cldb_almox       = new cl_db_almox;
$cldb_almoxdepto  = new cl_db_almoxdepto;
$clmatestoque     = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$clmatmater       = new cl_matmater;
$clrotulo         = new rotulocampo;
$clrotulo->label('');

/**
 *  variaveis para totalizar por movimenta��es
 */

// entradas
$iTotalQtdEntrada    = 0;
// saidas
$iTotalQtdSaida      = 0;
$nValorTotalentrada  = 0;
$nValorTotalSaida    = 0;
$iCasasDecimais      = 9;

$result_matmater=$clmatmater->sql_record($clmatmater->sql_query_file($codmater,"m60_codmater as codmater","m60_descr"));
$sMovimentosNaoConsiderados = "8,9,7,16";

for ($mater=0; $mater < $clmatmater->numrows; $mater++) {
    db_fieldsmemory($result_matmater, $mater);

    $txt_where="";
    $txt_where1="";
    $where_dat="";
    $info="";

    if ($listadepto != "") {
        if (isset ($verdepto) and $verdepto == "com") {
            $txt_where = $txt_where." and m80_coddepto in  ($listadepto)";
            $txt_where1 = " and m70_coddepto in  ($listadepto)";
        } else {
            $txt_where = $txt_where." and m80_coddepto not in  ($listadepto)";
            $txt_where1 = " and m70_coddepto not in  ($listadepto)";
        }
    }
    if ($listatipo != "") {
        if (isset ($vertipo) and $vertipo == "com") {
            $txt_where = $txt_where." and m81_codtipo in  ($listatipo)";
        } else {
            $txt_where = $txt_where." and m81_codtipo not in  ($listatipo)";
        }
    }

    if (($data != "--") && ($data1 != "--")) {
        $where_dat = $where_dat." and m80_data  between '$data' and '$data1'  ";
        $info = "De " . db_formatar($data, "d") . " at� " . db_formatar($data1, "d") . ".";
    } else
        if ($data != "--") {
            $where_dat = $where_dat." and m80_data >= '$data'  ";
            $info = "Apartir de " . db_formatar($data, "d") . ".";
        } else
            if ($data1 != "--") {
                $where_dat = $where_dat."and m80_data <= '$data1'   ";
                $info = "At� " . db_formatar($data1, "d") . ".";
            }
    $where="";
    $inner="";
    $db_where="";
    $db_inner="";
    $depto_atual = db_getsession("DB_coddepto");
    $permissao=db_permissaomenu(db_getsession("DB_anousu"),480,4390);

    if ($permissao=="false"){
        $result_param=$clmatparam->sql_record($clmatparam->sql_query_file());
        if ($clmatparam->numrows){
            db_fieldsmemory($result_param,0);
            if ($m90_tipocontrol=='S'){
                $result_orgao=$cldb_departorg->sql_record($cldb_departorg->sql_query_file($depto_atual));
                if ($cldb_departorg->numrows){
                    db_fieldsmemory($result_orgao,0);
                    $db_where = "and db01_orgao = $db01_orgao";
                    $db_inner = "      inner join db_departorg  on db01_coddepto = db_depart.coddepto and db01_anousu=".db_getsession("DB_anousu");
                    $db_inner .= "      inner join orcorgao  on orcorgao.o40_orgao = db_departorg.db01_orgao and orcorgao.o40_anousu = db_departorg.db01_anousu";
                }
            }else if ($m90_tipocontrol=='G'){
                $result_almox=$cldb_almoxdepto->sql_record($cldb_almoxdepto->sql_query_file(null,$depto_atual));
                if ($cldb_almox->numrows>0){
                    db_fieldsmemory($result_almox);
                    $db_where = "m91_codigo = $m91_codigo";
                    $db_inner = "      inner join db_almoxdepto on m92_depto = db_depart.coddepto ";
                    $db_inner .= "      inner join db_almox on m91_codigo = m92_codalmox";
                }
            }else if ($m90_tipocontrol=='D'){
                $db_where = "D";
            }
        }
    }

    if (isset($db_where)&&$db_where!=""){
        if ($db_where=="D"){
            $depto_atual=db_getsession("DB_coddepto");
            $where.="  and m80_coddepto=$depto_atual ";
        }else{
            $where.="  $db_where  ";
        }
    }
    if (isset($db_inner)&&$db_inner!=""){
        $inner="  $db_inner  ";
    }else{
        $inner="";
    }
    $inner = '';
    if ($listadepto != "") {
        $where = " and m70_coddepto in($listadepto)";
    }
    $sql  = " select        m80_coddepto, ";
    $sql .= "			          m80_codigo, ";
    $sql .= "							  m81_descr, ";
    $sql .= "							  m81_entrada, ";
    $sql .= "							  round(m82_quant, 2) as quantidade, ";
    $sql .= "               coalesce(m89_valorfinanceiro::numeric,0) as valorfinanceiro, ";
    $sql .= "							  preco_medio as preco_medio, ";
    $sql .= "							  m89_valorunitario as valorunitario, ";
    $sql .= "							  descrdepto, ";
    $sql .= "							  m80_data, ";
    $sql .= "							  m80_hora, ";
    $sql .= "							  m60_descr, ";
    $sql .= "							  m71_valor, ";
    $sql .= "							  m71_quant, ";
    $sql .= "							  m75_quant, ";
    $sql .= "							  nome, ";
    $sql .= "							  login, ";
    $sql .= "							  m81_codtipo, ";
    $sql .= "							  m81_tipo ";
    $sql .= "				  from ( select  m80_codigo,    ";
    $sql .= "                        m80_coddepto,  ";
    $sql .= "                        m81_descr,     ";
    $sql .= "                        m81_entrada,   ";
    $sql .= "                        m82_quant,     ";
    $sql .= "                        m89_precomedio as preco_medio, ";
    $sql .= "                        m89_valorunitario, ";
    $sql .= "                        deptousu.descrdepto,    ";
    $sql .= "                        m89_valorfinanceiro, ";
    $sql .= "                        m80_data,      ";
    $sql .= "                        m80_hora,      ";
    $sql .= "                        nome,          ";
    $sql .= "                        login,         ";
    $sql .= "                        m60_descr,     ";
    $sql .= "                        m71_valor,     ";
    $sql .= "                        m71_quant,     ";
    $sql .= "                        m75_quant,     ";
    $sql .= "                        m81_codtipo,   ";
    $sql .= "                        m81_tipo       ";
    $sql .= "								   from matestoqueini   ";
    $sql .= "          							inner join matestoquetipo   on m80_codtipo        = m81_codtipo ";
    $sql .= "          							inner join matestoqueinimei on m82_matestoqueini  = m80_codigo ";
    $sql .= "          							left join db_usuarios      on m80_login          = id_usuario ";
    $sql .= "          							left join db_depart deptousu  on m80_coddepto    = deptousu.coddepto ";
    $sql .= "          							inner join matestoqueitem   on m82_matestoqueitem = m71_codlanc ";
    $sql .= "                       inner join matestoqueinimeipm on m82_codigo       = m89_matestoqueinimei";
    $sql .= "          							 left join matestoqueitemunid on m75_codmatestoqueitem = m71_codlanc  ";
    $sql .= "          							inner join matestoque       on m71_codmatestoque  = m70_codigo ";
    $sql .= "                       inner join db_depart deptoest on m70_coddepto     = deptoest.coddepto ";
    $sql .= "          							inner join matmater         on m60_codmater       = m70_codmatmater ";
    $sql .= "								        $inner		";
    $sql .= "                 where deptoest.instit          = " . db_getsession("DB_instit") . " ";
    $sql .= "                   and m70_codmatmater = $codmater $where and m71_servico is false ";
    $sql .= "                 order by to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'), m80_codigo, m82_codigo ";
    $sql .= "                 ) as x  ";
    $sql .= "				 where 1=1 $where_dat";
    $result = db_query($sql) or die($sql);

//    db_criatabela($result);die($sql);

    if (pg_numrows($result) == 0) {
//		db_redireciona('db_erros.php?fechar=true&db_erro=N�o existem registros cadastrados.');
//		exit;
        continue;
    }
    $nSaldoInicial = 0;
    $nValorInicial = 0;
    if ($data != "--") {

        $sSqlSaldoAnt  = "select sum(coalesce(case when m81_tipo = 1 then round(m82_quant,2) ";
        $sSqlSaldoAnt .= "                when m81_tipo = 2 then round(m82_quant,2) *-1 end, 0)) as saldoInicial,";
        $sSqlSaldoAnt .= "       sum(coalesce(case when m81_tipo = 1 then m89_valorfinanceiro ";
        $sSqlSaldoAnt .= "                when m81_tipo = 2 then m89_valorfinanceiro*-1 end, 0)) as valorInicial ";
        $sSqlSaldoAnt .= "  from (select m81_tipo, ";
        $sSqlSaldoAnt .= "               m82_quant, ";
        $sSqlSaldoAnt .= "               m89_valorunitario, ";
        $sSqlSaldoAnt .= "               m89_valorfinanceiro, ";
        $sSqlSaldoAnt .= "               m89_precomedio ";
        $sSqlSaldoAnt .= "          from matestoqueini  ";
        $sSqlSaldoAnt .= "               inner join matestoquetipo on m80_codtipo = m81_codtipo ";
        $sSqlSaldoAnt .= "                                        AND m80_codtipo <> 4  ";
        $sSqlSaldoAnt .= "               inner join matestoqueinimei   on m82_matestoqueini = m80_codigo  ";
        $sSqlSaldoAnt .= "               inner join matestoqueinimeipm on m82_codigo        = m89_matestoqueinimei ";
        $sSqlSaldoAnt .= "               inner join matestoqueitem on m82_matestoqueitem = m71_codlanc ";
        $sSqlSaldoAnt .= "               inner join matestoque on m71_codmatestoque = m70_codigo ";
        $sSqlSaldoAnt .= "               inner join db_depart on m70_coddepto = coddepto ";
        $sSqlSaldoAnt .= "         where m70_codmatmater = {$codmater} ";
        $sSqlSaldoAnt .= "           and m80_data < '{$data}'::date";
        $sSqlSaldoAnt .= "           and instit =".db_getsession("DB_instit");

        if ($listadepto != "") {
            $sSqlSaldoAnt .= " and m70_coddepto in($listadepto)";
        }
        $sSqlSaldoAnt .= " ) as x ";

        $rsSaldoinicial = db_query($sSqlSaldoAnt);

        if (pg_num_rows($rsSaldoinicial) > 0) {

            $nSaldoInicial = db_utils::fieldsMemory($rsSaldoinicial,0)->saldoinicial;
            $nValorInicial = db_utils::fieldsMemory($rsSaldoinicial,0)->valorinicial;
        }
    }
    $total = 0;

    $oMaterial = db_utils::fieldsmemory($result,0);
    $total = 0;
    $quant_est = 0;
    $vlr_est = 0;
    $tot_est = 0;
    $depto_ant="";
    $vlrunni_final=0;
    $nTransf = 0;
    $inicio_relatorio = 0;

    header("Content-type: text/plain");
    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=file.csv");
    header("Pragma: no-cache");
    readfile( 'file.csv' );

    $head2 = "COD: ".$codmater;
    $head3 = "MATERIAL: ".$oMaterial->m60_descr;
    $head5 = "$info";

    echo "$head2 ;";
    echo "$head3 ;";
    echo "$head5 ;\n";

    echo "Saldo Estoque Anterior :";

    echo "$nSaldoInicial ;";
    echo "$nValorInicial ;\n";

    echo "Data ;";
    echo "Lanc. ;";
    echo "Descr. ;";
    echo "Dep�sito ;";
    echo "Login ;";
    echo "Qtd. Entrada ;";
    echo "Vlr. Unit. ;";
    echo "Total ;";
    echo "Qtd. Saidas ;";
    echo "Vlr. Unit. ;";
    echo "Total ;";
    echo "Qtd.Saldo ;";
    echo "Vlr. Unit. ;";
    echo "Total ;\n";

    for ($x = 0; $x < pg_numrows($result); $x ++) {
        db_fieldsmemory($result, $x);

        if($data != "--") {

            $dt_mktime1 = explode('-',$m80_data);
            $dt_mktime2 = explode('-',$data);
            $dt_inf = mktime(0,0,0,$dt_mktime1[1],$dt_mktime1[2],$dt_mktime1[0]);
            $dt_sup = mktime(0,0,0,$dt_mktime2[1],$dt_mktime2[2],$dt_mktime2[0]);

        }

        if ( $inicio_relatorio == 0  ) {

            $inicio_relatorio = 1;

            $quant_est     = $nSaldoInicial;
            $vlr_medio     = $nValorInicial;
            if($nSaldoInicial>0){
                $vlr_medio     = $nValorInicial;
            }else {
                $vlr_medio = 0;
            }

            $tot_est       = $nValorInicial;

            $nSaldoInicial = 0;
            $nValorInicial = 0;

        }

        $quant_ent = 0;
        $quant_sai = 0;

        $vlr_ent = 0;
        $vlr_sai = 0;
        $vlr_est = 0;

        $tot_ent = 0;
        $tot_sai = 0;

        if ($m81_codtipo == 7) {
            $nTransf += $quantidade;
        }
        if ($m81_tipo == 1) {

            //$quant_ent  = $m75_quant;//$quantidade;
            $quant_ent  = $quantidade;
            $vlr_ent    = $valorunitario;
            $tot_ent    = $valorfinanceiro;
            $quant_est += $quantidade;
            $vlr_est    = $valorunitario;
            $tot_est   += $vlr_ent*$quantidade;
            //$tot_est    = $valorunitario*$quant_est;

        } else if ($m81_tipo == 2) {

            $quant_sai  = $quantidade;
            $vlr_sai    = $preco_medio;
            $tot_sai    = $valorfinanceiro;
            $quant_est -= $quantidade;
            $vlr_est    = $preco_medio;
            $tot_est   -= $vlr_sai*$quantidade;

        } else if ($m81_tipo == 3) {

            $tot_est = $preco_medio* $quant_est;
        }
        $depto_ant=$m80_coddepto;
        $vlruni_final=$preco_medio;

        $iTotalQtdEntrada   += $quant_ent;
        $nValorTotalentrada += $tot_ent;

        $iTotalQtdSaida     += $quant_sai ;
        $nValorTotalSaida   += $tot_sai;

        $iTotalQtdSaldo     = $quant_est;
        $iTotalprecoMedio   = $preco_medio;
        $nVolorTotalSaldo   = $tot_est;

        $oDadosDaLinha = new stdClass();
        $oDadosDaLinha->data                    = $m80_data;
        $oDadosDaLinha->Lanc                    = $m80_codigo;
        $oDadosDaLinha->Desc                    = $m81_descr;
        $oDadosDaLinha->deposito                = $descrdepto;
        $oDadosDaLinha->login                   = $login;

        $oDadosDaLinha->entradas                = $quant_ent;
        $oDadosDaLinha->varlorentrada           = $vlr_ent;
        $oDadosDaLinha->totalentrada            = $tot_ent;

        $oDadosDaLinha->saidas                  = $quant_sai;
        $oDadosDaLinha->valorsaida              = $vlr_sai;
        $oDadosDaLinha->totalsaida              = $tot_sai;

        $oDadosDaLinha->saldo                   = $quant_est;
        $oDadosDaLinha->valor                   = $preco_medio;
        $oDadosDaLinha->total                   = $tot_est;

        $oDataformatada = db_formatar($m80_data, "d");

        echo "$oDataformatada ;";
        echo "$m80_codigo ;";
        echo "$m81_descr ;";
        echo "$descrdepto ;";
        echo "$login ;";

        echo "$quant_ent ;";
        echo db_formatar($vlr_ent,"f")." ;";
        echo db_formatar($tot_ent,"f")." ;";

        echo "$quant_sai ;";
        echo db_formatar($vlr_sai,"f")." ;";
        echo db_formatar($tot_sai,"f")." ;";


        echo "$quant_est ;";
        echo db_formatar($preco_medio,"f")." ;";
        echo db_formatar($tot_est,"f")." ;\n";
    }
}

echo " ;";
echo " ;";
echo " ;";
echo " ;";
echo "Totais: ;";
echo "$iTotalQtdEntrada ;";
echo " ;";
echo db_formatar($nValorTotalentrada,"f")." ;";

echo "$iTotalQtdSaida ;";
echo " ;";
echo db_formatar($nValorTotalSaida,"f")." ;";

echo db_formatar($iTotalQtdSaldo,"f")." ;";
echo db_formatar($iTotalprecoMedio,"f")." ;";
echo db_formatar($nVolorTotalSaldo,"f").";";

function formatar($nValor, $iCasasDecimais = 2)
{
    return number_format((float) $nValor, (int) $iCasasDecimais, ',', '.');
}

?>