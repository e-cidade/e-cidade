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

require_once('fpdf151/scpdf.php');
require_once('libs/db_utils.php');

$oDaoTfdPedidoTfd = db_utils::getdao('tfd_pedidotfd');
$oDaoCgsCartaoSus = db_utils::getdao('cgs_cartaosus');
$oDaoDbConfig     = db_utils::getdao('db_config');
$sData            = db_getsession('DB_datausu');
$sDataExtenso     = db_dataextenso($sData);

function getCns($iCgs) {

  global $oDaoCgsCartaoSus;

  $sSql = $oDaoCgsCartaoSus->sql_query(null, ' s115_c_cartaosus ', ' s115_c_tipo asc ',
                                       ' s115_i_cgs = '.$iCgs
                                      );
  $rsCgsCartaoSus = $oDaoCgsCartaoSus->sql_record($sSql);
  if ($oDaoCgsCartaoSus->numrows != 0) { // se o paciente tem um cartao sus

    $oDadosCgsCartaoSus = db_utils::fieldsmemory($rsCgsCartaoSus, 0);
    $sCartaoSus         = $oDadosCgsCartaoSus->s115_c_cartaosus;

  }  else {
    $sCartaoSus = '';
  }

  return $sCartaoSus;

}

$sCampos  = 'tfd_pedidotfd.*, tfd_agendamentoprestadora.*, cgs_und.*, medicos.sd03_i_crm, z01_i_numtfd,';
$sCampos .= 'db_usuarios.nome, rhcbo.rh70_estrutural, rhcbo.rh70_descr, db_depart.descrdepto, ';
$sCampos .= 'case when medicos.sd03_i_tipo = 1 then cgmmedico.z01_nome else s154_c_nome end as nomemedico, ';
$sCampos .= 'case when medicos.sd03_i_tipo = 1 then cgmdoc.z02_i_cns else s154_c_cns end as cnsmedico, ';
$sCampos .= 'cgm.z01_nome as nomeprest, cgm.z01_munic as municprest, cgm.z01_bairro as bairroprest,  ';
$sCampos .= 'cgm.z01_compl as complprest, cgm.z01_uf as ufprest, cgm.z01_numero as numprest, ';
$sCampos .= 'cgm.z01_ender as enderprest, ';
$sCampos .= "(select array_to_string(array(select sd63_c_nome from sau_procedimento join tfd_procpedidotfd on tfd_procpedidotfd.tf23_i_procedimento = sau_procedimento.sd63_i_codigo
where tfd_procpedidotfd.tf23_i_pedidotfd =tfd_pedidotfd.tf01_i_codigo), '\\n')) as sd63_c_nome ";

$sSql     = $oDaoTfdPedidoTfd->sql_query_protocolo('', $sCampos, 'tf01_i_codigo', "tf01_i_codigo = $tf01_i_pedidotfd");
$rs       = $oDaoTfdPedidoTfd->sql_record($sSql);

if ($oDaoTfdPedidoTfd->numrows == 0) {?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
        <b>Nenhum registro encontrado<br>
        <input type='button' value='Fechar' onclick='window.close()'></b>
        </font>
      </td>
    </tr>
  </table>
<?
exit;
}

$sCamposInstit   = 'nomeinst as nome, ender, munic, uf, telef, email, url, logo';
$sSqlDadosInstit = $oDaoDbConfig->sql_query_file(db_getsession('DB_instit'), $sCamposInstit);
$rsDadosInstit   = $oDaoDbConfig->sql_record($sSqlDadosInstit);
$oDadosInstit    = db_utils::fieldsMemory($rsDadosInstit, 0);

$oPdf            = new FPDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->settopmargin(1);
$oPdf->SetAutoPageBreak(true, 0);
$oPdf->line(2, 148.5, 208, 148.5);
$oPdf->AddPage();

$iVias = 1;
for ($iCont = 0; $iCont < $oDaoTfdPedidoTfd->numrows; $iCont++) {

  $oDados = db_utils::fieldsmemory($rs, $iCont);
  for ($iCont2 = 0; $iCont2 < $iVias; $iCont2++) {
    $iY = 20;

    // Cabeçalho
    $oPdf->setfillcolor(255);
    $oPdf->setfillcolor(255, 255, 255);
    $oPdf->Setfont('arial', 'B', 8);

    $oPdf->Setfont('arial', 'B', 9);
    $oPdf->text(58, $iY - 13, 'SECRETARIA DE ESTADO DA SAÚDE DE MINGAS GERAIS');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(68, $iY - 10 , 'SUPERINTENDÊNDIA OPERACIONAL DE SAÚDE');
    $oPdf->text(75, $iY - 7, 'CENTRO DE APOIO ASSISTENCIAL');
    $oPdf->text(65, $iY - 4, 'COORDENADORIA DE ASSISTÊNCIA SUPLEMENTAR');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(55, $iY , 'RELATÓRIO DE ATENDIMENTO - TRATAMENTO FORA DE DOMICÍLIO');

    // Retângulo do USO ORGAO
      $oPdf->Rect(4, $iY + 2, 202, 70, 2, 'DF', '1234');
      $oPdf->Setfont('arial', 'B', 9);
      $oPdf->text(6, $iY + 6, 'PARA USO DO ÓRGÃO DE DESTINO');
      $oPdf->line(4, $iY + 7, 206, $iY + 7);
      $oPdf->Setfont('arial', 'B', 8);
      $oPdf->text(8, $iY + 10, '01 - NOME DA UNIDADE MÉDICA ASSISTENCIAL');
      $oPdf->Setfont('arial', '', 7);
      $oPdf->text(8, $iY + 14, $oDados->nomeprest);
      $oPdf->Setfont('arial', 'B', 8);
      $oPdf->text(98, $iY + 10, '02 - CLÍNICA');
      $oPdf->Setfont('arial', '', 7);
      $oPdf->text(98, $iY + 14, $oDados->nomeprest);
      $oPdf->Setfont('arial', 'B', 7);
      $oPdf->line(95, $iY+7, 95, $iY+60);
      $oPdf->line(4, $iY + 16, 206, $iY + 16);
      $oPdf->Setfont('arial', 'B', 8);
      $oPdf->text(8, $iY + 20, '03 - NOME DO PACIENTE');
      $oPdf->Setfont('arial', '', 7);
      $oPdf->text(8, $iY + 24, $oDados->z01_v_nome);

      $oPdf->Setfont('arial', 'B', 8);
      $oPdf->text(98, $iY + 20, '04 - TFD NUMERO');
      $oPdf->Setfont('arial', '', 7);
      $oPdf->text(98, $iY + 24, $oDados->z01_i_numtfd);
      $oPdf->Setfont('arial', 'B', 7);

      $oPdf->Setfont('arial', 'B', 8);
      $oPdf->text(8, $iY + 30, '05 - ENDEREÇO');
      $oPdf->Setfont('arial', '', 7);
      $oPdf->text(12, $iY + 34, substr($oDados->z01_v_ender.' '.$oDados->z01_v_compl, 0, 36));
      $oPdf->text(12, $iY + 38, 'Número: '.$oDados->z01_i_numero);
      $oPdf->text(12, $iY + 42, 'Complemento: ');
      $oPdf->text(12, $iY + 42, substr($oDados->z01_v_compl, 0, 23));
      $oPdf->text(12, $iY + 46, 'Bairro: ');
      $oPdf->text(22, $iY + 46, substr($oDados->z01_v_bairro, 0, 23));
      $oPdf->text(12, $iY + 50, 'Município: ');
      $oPdf->text(24, $iY + 50, $oDados->z01_v_munic.'  UF: '.$oDados->z01_v_uf);
      $oPdf->text(12, $iY + 55, 'CEP: ');
      $oPdf->text(24, $iY + 55, $oDados->z01_v_cep);

      $oPdf->line(95, $iY + 36, 206, $iY + 36);
      $oPdf->Setfont('arial', 'B', 8);
      $oPdf->text(98, $iY + 40, '06 - TELEFONE');
      $oPdf->Setfont('arial', '', 7);
      $oPdf->text(98, $iY + 46, $oDados->z01_v_telcel);

      $oPdf->line(4, $iY + 60, 206, $iY + 60);
      $oPdf->Setfont('arial', 'B', 8);
      $oPdf->text(8, $iY + 65, '07 - PROCEDÊNCIA');
      $oPdf->Setfont('arial', '', 7);
      $oPdf->text(12, $iY + 69, $oDadosInstit->munic.'  UF: '.$oDados->z01_v_uf);

     // Retângulo USO DA UNIDADE
      $oPdf->Rect(4, $iY + 75, 202, 102, 2, 'DF', '1234');
      $oPdf->Setfont('arial', 'B', 9);
      $oPdf->text(6, $iY + 79, 'PARA USO DA UNIDADE ASSISTENCIAL ');
      $oPdf->line(4, $iY + 81, 206, $iY + 81);
      $oPdf->Setfont('arial', 'B', 8);
      $oPdf->text(8, $iY + 85, '08 - TRATAMENTOS REALIZAODS (SUMULA)');
      $oPdf->line(4, $iY + 89, 206, $iY + 89);
      $oPdf->text(8, $iY + 95, '09 - NECESSIDADE DE COMPLEMENTAÇÃO DO TRATAMENTO');
      $oPdf->Setfont('arial', '', 8);
      $oPdf->Rect(10, $iY + 102, 2, 2);
      $oPdf->text(15, $iY + 104, 'NESTA UNIDADE');
      $oPdf->Rect(65, $iY + 102, 2, 2);
      $oPdf->text(70, $iY + 104, 'SIM');
      $oPdf->Rect(80, $iY + 102, 2, 2);
      $oPdf->text(85, $iY + 104, 'NÃO');
      $oPdf->Rect(10, $iY + 108, 2, 2);
      $oPdf->text(15, $iY + 110, 'NO ÓRGAO DE ORIGEM');
      $oPdf->Rect(65, $iY + 108, 2, 2);
      $oPdf->text(70, $iY + 110, 'SIM');
      $oPdf->Rect(80, $iY + 108, 2, 2);
      $oPdf->text(85, $iY + 110, 'NÃO');


      $oPdf->line(115, $iY+120, 115, $iY+89);
      $oPdf->Setfont('arial', 'B', 8);
      $oPdf->text(118, $iY + 95, '10 - JUSTIFICATIVA DO RETORNO');
      $oPdf->Setfont('arial', '', 8);
      $oPdf->text(150, $iY + 118, 'RETORNO EM ');
      $oPdf->Setfont('arial', '', 10);
      $oPdf->text(173, $iY + 118, '____/____/______');
      $oPdf->line(4, $iY + 120, 206, $iY + 120);
      $oPdf->Setfont('arial', 'B', 8);
      $oPdf->text(8, $iY + 125, '11 - OUTRAS OBSERVAÇÕES');
      $oPdf->line(4, $iY + 130, 206, $iY + 130);

      $oPdf->text(8, $iY + 135, '12 - DATA');
      $oPdf->line(80, $iY+130, 80, $iY+177);
      $oPdf->text(83, $iY + 135, '13 CARIMBO - CREMEGE - ASSINATURA MÉDICO ASSISTENTE');

    // Retângulo obs
      $oPdf->Rect(4, $iY + 180, 202, 75, 2, 'DF', '1234');
      $oPdf->Setfont('arial', 'B', 9);
      $oPdf->text(55, $iY + 185, 'RELATÓRIO MÉDICO QUANTO A NECESSIDADE DE ACOMPANHANTE');
      $oPdf->Setfont('arial', '', 8);
      $oPdf->text(58, $iY + 190, '(Sr. Médico, favor justificar abaixo a NECESSIDADE ou NÃO de acompanhante)');
      $oPdf->text(75, $iY + 195, '(Solicitação da Secretaria Municipal de Saúde)');


  }

}
$oPdf->Output();
?>
