<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBselller Servicos de Informatica
 *    www.dbseller.com.br
 * e-cidade@dbseller.com.br
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
 *        licenca/licenca_pt.txt
 */

require_once("fpdf151/scpdf.php");
require_once("fpdf151/impcarne.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
include("classes/db_db_docparag_classe.php");
$oGet = db_utils::postMemory($_GET);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);


$sWhere  = " db02_descr like 'ASS. RESP. DEC. DE RECURSOS FINANCEIROS' ";
$sWhere .= " AND db03_instit = db02_instit ";
$sWhere .= " AND db02_instit = " . db_getsession('DB_instit');

$cl_docparag = new cl_db_docparag;

$sAssinatura = $cl_docparag->sql_query_doc('', '', 'db02_texto', '', $sWhere);
$rs = $cl_docparag->sql_record($sAssinatura);
$oLinha = db_utils::fieldsMemory($rs, 0)->db02_texto;


$sWhere  = " db02_descr like '%RECURSOS FINANCEIROS%' ";
$sWhere .= " AND db03_instit = db02_instit ";
$sWhere .= " AND db02_instit = " . db_getsession('DB_instit');

$sSqlCotacao = $cl_docparag->sql_query_doc('', '', 'db02_texto', '', $sWhere);
$rsCotacao = $cl_docparag->sql_record($sSqlCotacao);

$sAssinaturaCotacao = db_utils::fieldsMemory($rsCotacao, 0)->db02_texto;

$sqlpref  = "select db_config.*, cgm.z01_incest as inscricaoestadualinstituicao ";
$sqlpref .= "  from db_config     ";
$sqlpref .= " inner join cgm on cgm.z01_numcgm = db_config.numcgm     ";
$sqlpref .=  "	where codigo = " . db_getsession("DB_instit");

$resultpref = db_query($sqlpref);
db_fieldsmemory($resultpref, 0);


$sSql = "SELECT itemprecoreferencia.*,precoreferencia.*,pc11_reservado
FROM itemprecoreferencia
INNER JOIN precoreferencia ON si01_sequencial = si02_precoreferencia
INNER JOIN pcorcamitem ON si02_itemproccompra = pc22_orcamitem
INNER JOIN pcorcamitemproc ON pc31_orcamitem = pc22_orcamitem
INNER JOIN pcprocitem ON pc31_pcprocitem = pc81_codprocitem
INNER JOIN solicitem ON pc81_solicitem = pc11_codigo
INNER JOIN solicitempcmater ON pc16_solicitem = pc11_codigo
WHERE si02_precoreferencia =
        (SELECT si01_sequencial
         FROM precoreferencia
         WHERE si01_processocompra = {$codigo_preco})
ORDER BY pc11_seq;";

$rsResult = db_query($sSql) or die(pg_last_error());
$pc80_criterioadjudicacao = db_utils::fieldsMemory($rsResult, 0)->si02_criterioadjudicacao;
$codigoItem = db_utils::fieldsMemory($rsResult, 0)->si02_coditem;
$precoreferencia = db_utils::fieldsMemory($rsResult, 0)->si02_precoreferencia;
$datacotacao = db_utils::fieldsMemory($rsResult, 0)->si01_datacotacao;

$sqlProc = "select pc80_criterioadjudicacao, pc80_tipoprocesso
            from pcproc
            where pc80_codproc = {$codigo_preco}";

$rsLotes = db_query("select distinct  pc68_sequencial,pc68_nome
                    from pcproc
                    join pcprocitem on pc80_codproc = pc81_codproc
                    left join processocompraloteitem on pc69_pcprocitem = pcprocitem.pc81_codprocitem
                    left join processocompralote on pc68_sequencial = pc69_processocompralote
                    where pc80_codproc = {$codigo_preco} and pc68_sequencial is not null
                    order by pc68_sequencial asc");

$rsResultProc = db_query($sqlProc) or die(pg_last_error());
$oDadosProc = db_utils::fieldsMemory($rsResultProc, 0);

$pdf = new scpdf();
$pdf->Open();

$pdf1 = new db_impcarne($pdf, '105');

$pdf1->prefeitura   = $nomeinst;
$pdf1->enderpref    = trim($ender) . "," . $numero;
$pdf1->municpref    = $munic;
$pdf1->uf           = $uf;
$pdf1->telefpref    = $telef;
$pdf1->logo       = $logo;
$pdf1->emailpref    = $email;
$pdf1->inscricaoestadualinstituicao = $inscricaoestadualinstituicao;
$pdf1->codpreco     = $codigo_preco;
$pdf1->precoreferencia = db_utils::fieldsMemory($rsResult, 0)->si02_precoreferencia;
$pdf1->datacotacao = db_utils::fieldsMemory($rsResult, 0)->si01_datacotacao;
$pdf1->pc80_tipoprocesso = $oDadosProc->pc80_tipoprocesso;
$pdf1->pc80_criterioadjudicacao = db_utils::fieldsMemory($rsResult, 0)->si02_criterioadjudicacao;
$pdf1->impjust = $impjust;
$pdf1->tipoprecoreferencia = $tipoprecoreferencia;
$pdf1->rsLotes = $rsLotes;
$pdf1->quant_casas = db_utils::fieldsMemory($rsResult, 0)->si01_casasdecimais;
$pdf1->sqlitens = $rsResult;
$pdf1->sAssinaturaCotacao = $sAssinaturaCotacao;


$pdf1->imprime();
$pdf1->objpdf->Output();

