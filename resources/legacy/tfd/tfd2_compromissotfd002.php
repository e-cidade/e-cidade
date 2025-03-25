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

$sCampos  = 'tfd_pedidotfd.*, tfd_agendamentoprestadora.*, cgs_und.*, medicos.sd03_i_crm, ';
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

    if (empty($oDados->z01_v_ender)) {
      $oDados->z01_v_ender = '';
    }
    if (empty($oDados->z01_i_numero)) {
      $oDados->z01_i_numero = '';
    }
    if (empty($oDados->z01_v_bairro)) {
      $oDados->z01_v_bairro = '';
    }
    if (empty($oDados->z01_v_munic)) {
      $oDados->z01_v_munic = '';
    }
    $sSexo = 'FEMININO';
    if ($oDados->z01_v_sexo == 'M') {
      $sSexo = 'MASCULINO';
    }
    if ($oDados->z01_v_ident == 0) {
      $oDados->z01_v_ident = '';
    }
    if ($oDados->z01_v_cgccpf == 0) {
      $oDados->z01_v_cgccpf = '';
    }

    // Cabeçalho
    $oPdf->setfillcolor(255);
    $oPdf->roundedrect(2, $iY - 18, 206, 142.5, 2, 'DF', '1234');
    $oPdf->setfillcolor(255, 255, 255);
    $oPdf->Setfont('arial', 'B', 8);

    $sSituacaoProtocolo = 'PEDIDO TFD N'.chr(176).' '.$oDados->tf01_i_codigo;
    $iPosicaoEscrita    = 168;


    $oPdf->text($iPosicaoEscrita, $iY - 13, $sSituacaoProtocolo);
    $oPdf->text(168, $iY -9, "TERMO DE COMPROMISSO");
    $oPdf->Image('imagens/files/logo_boleto.png', 10, $iY - 16, 20);
    $oPdf->Setfont('arial', 'B', 9);
    $oPdf->text(30, $iY - 13, $oDadosInstit->nome);
    $oPdf->Setfont('arial', '', 9);
    $oPdf->text(30, $iY - 9, $oDadosInstit->ender);
    $oPdf->text(30, $iY - 6, $oDadosInstit->munic);
    $oPdf->text(30, $iY - 3, $oDadosInstit->telef);
    $oPdf->text(30, $iY, $oDadosInstit->email);

    // Retângulo do pedido
    $oPdf->Roundedrect(4, $iY, 202, 10, 2, 'DF', '1234');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 4, 'PEDIDO');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 8, 'Departamento');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 8, ': '.substr($oDados->tf01_i_depto.' - '.$oDados->descrdepto, 0, 35));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(86, $iY + 8, ' Data do Pedido');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 8, ': '.db_formatar($oDados->tf01_d_datapedido, 'd'));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(126, $iY + 8, 'Funcionário');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(144, $iY + 8, ': '.substr($oDados->tf01_i_login.' - '.$oDados->nome, 0, 35));

    // Retângulo do paciente
    $oPdf->Roundedrect(4, $iY + 11, 202, 32, 2, 'DF', '1234');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 16, 'PACIENTE');
    $oPdf->Setfont('arial', 'B', 8);-
    $oPdf->text(13, $iY + 20, 'Paciente');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 20, ': '.$oDados->z01_v_nome);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(101, $iY + 20, 'CGS ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 20, ': '.$oDados->z01_i_cgsund);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(137, $iY + 20, 'Data Nascimento');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 20, ': '.db_formatar($oDados->z01_d_nasc, 'd'));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(20, $iY + 24, 'RG ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 24, ': '.$oDados->z01_v_ident);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(102, $iY + 24, 'CPF ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 24, ': '.$oDados->z01_v_cgccpf);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(144, $iY + 24, 'Cartão SUS ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 24, ': '.getCns($oDados->tf01_i_cgsund));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 28, 'Nome da Mãe');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 28, ': '.substr($oDados->z01_v_mae, 0, 32));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(101, $iY + 28, 'Sexo ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 28, ': '.$sSexo);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(11, $iY + 32, 'Endereço');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 32, ': '.substr($oDados->z01_v_ender.' '.$oDados->z01_v_compl, 0, 36));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(97, $iY + 32, 'Número');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 32, ': '.$oDados->z01_i_numero);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(141, $iY + 32, 'Complemento ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 32, ': '.substr($oDados->z01_v_compl, 0, 23));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(15, $iY + 36, 'Bairro ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 36, ': '.substr($oDados->z01_v_bairro, 0, 23));
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(95, $iY + 36, 'Município ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 36, ': '.$oDados->z01_v_munic);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(155, $iY + 36, 'UF ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 36, ': '.$oDados->z01_v_uf);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(17, $iY + 40, 'CEP ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(26, $iY + 40, ': '.$oDados->z01_v_cep);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(95, $iY + 40, 'Telefone ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(109, $iY + 40, ': '.$oDados->z01_v_telef);
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(150, $iY + 40, 'Celular ');
    $oPdf->Setfont('arial', '', 8);
    $oPdf->text(162, $iY + 40, ': '.$oDados->z01_v_telcel);

     // Retângulo do Compromisso
    $oPdf->Roundedrect(4, $iY + 45, 202, 48, 2, 'DF', '1234');
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->text(6, $iY + 49, 'COMPROMISSO');
    $oPdf->Setfont('arial', '', 8);
    $sCompromisso1 = "Eu comprometo-me a apresentar os comprovantes de viagens no prazo de três dias úteis ao Setor de TFD de ".$oDadosInstit->munic." para prestação de contas,";
    $oPdf->text(9, $iY + 55, trim($sCompromisso1));
    $sCompromisso2 = " sendo orientado que caso não for apresentado no prazo estabelecido estou sujeito a penalidade tais como devolução, bloqueio ou perca do recurso.";
    $oPdf->text(9, $iY + 59, trim($sCompromisso2));
      // Retângulo ASSINATURA
    $oPdf->Setfont('arial', 'B', 8);
    $oPdf->line(64, $iY + 110, 150, $iY + 110);

    $larguraTexto = $oPdf->GetStringWidth($oDados->z01_v_nome);
    $posX = (210 - $larguraTexto)/2;
    $oPdf->text($posX, $iY + 114, $oDados->z01_v_nome);
    $larguraTexto = $oPdf->GetStringWidth($oDadosInstit->munic.', '.strtoupper($sDataExtenso));
    $posX = (210 - $larguraTexto)/2;
    $oPdf->text($posX, $iY + 120, $oDadosInstit->munic.', '.strtoupper($sDataExtenso));
    $oPdf->Setfont('arial', '', 8);

  }

}
$oPdf->Output();
?>
