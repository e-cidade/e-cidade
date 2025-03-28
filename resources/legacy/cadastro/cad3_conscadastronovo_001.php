<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_iptubaseregimovel_classe.php");
require_once("classes/db_setorloc_classe.php");
$cliptubaseregimovel = new cl_iptubaseregimovel;

$clsetorloc = new cl_setorloc();
$rsSetorLoc = $clsetorloc->sql_record($clsetorloc->sql_query_file(null, 'j05_codigoproprio, j05_descr', 'j05_codigoproprio, j05_descr'));
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" onLoad="document.form1.j01_matric.focus()" >

<table width="790" height="100%" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td height="430" align="left" valign="top">
<?php
    // Cria a janela para visualizacao de todas as matriculas cadastradas
    $VisualizacaoTodasMatCad = new janela("VisualizacaoTodasMatCad","");
	  $VisualizacaoTodasMatCad->posX=1;
	  $VisualizacaoTodasMatCad->posY=20;
	  $VisualizacaoTodasMatCad->largura=785;
	  $VisualizacaoTodasMatCad->altura=430;
	  $VisualizacaoTodasMatCad->titulo="Visualiza��o das matriculas cadastradas";
	  $VisualizacaoTodasMatCad->iniciarVisivel = false;
	  $VisualizacaoTodasMatCad->mostrar();

    // Cria a janela para visualizacao da matricula
    $VisualizacaoMatricula = new janela("VisualizacaoMatricula","");
	  $VisualizacaoMatricula->posX=1;
	  $VisualizacaoMatricula->posY=20;
	  $VisualizacaoMatricula->largura=785;
	  $VisualizacaoMatricula->altura=430;
	  $VisualizacaoMatricula->titulo="Visualiza��o dos dados do im�vel";
	  $VisualizacaoMatricula->iniciarVisivel = false;
	  $VisualizacaoMatricula->mostrar();

    // Cria a janela para visualizacao da lista com os nomes de proprietarios
    $VisualizacaoProprietario = new janela("VisualizacaoProprietario","");
	  $VisualizacaoProprietario->posX=1;
	  $VisualizacaoProprietario->posY=20;
	  $VisualizacaoProprietario->largura=785;
	  $VisualizacaoProprietario->altura=430;
	  $VisualizacaoProprietario->titulo="Lista com nomes de propriet�rios";
	  $VisualizacaoProprietario->iniciarVisivel = false;
	  $VisualizacaoProprietario->mostrar();

    // Cria a janela para visualizacao da lista com os nomes das imobiliarias
    $VisualizacaoImobiliaria = new janela("VisualizacaoImobiliaria","");
	  $VisualizacaoImobiliaria->posX=1;
	  $VisualizacaoImobiliaria->posY=20;
	  $VisualizacaoImobiliaria->largura=785;
	  $VisualizacaoImobiliaria->altura=430;
	  $VisualizacaoImobiliaria->titulo="Lista com nomes de imobili�rias";
	  $VisualizacaoImobiliaria->iniciarVisivel = false;
	  $VisualizacaoImobiliaria->mostrar();

    // Cria a janela para visualizacao da lista com Setor, Quadra e Lote
    $VisualizacaoSetQuaLotLoc = new janela("VisualizacaoSetorQuadraLoteLoc","");
	  $VisualizacaoSetQuaLotLoc->posX=1;
	  $VisualizacaoSetQuaLotLoc->posY=20;
	  $VisualizacaoSetQuaLotLoc->largura=785;
	  $VisualizacaoSetQuaLotLoc->altura=430;
	  $VisualizacaoSetQuaLotLoc->titulo="Lista com setores, quadras e lotes de localiza��o";
	  $VisualizacaoSetQuaLotLoc->iniciarVisivel = false;
	  $VisualizacaoSetQuaLotLoc->mostrar();

	  // Cria a janela para visualizacao da lista com Setor, Quadra e Lote
    $VisualizacaoSetorQuadraLote = new janela("VisualizacaoSetorQuadraLote","");
	  $VisualizacaoSetorQuadraLote->posX=1;
	  $VisualizacaoSetorQuadraLote->posY=20;
	  $VisualizacaoSetorQuadraLote->largura=785;
	  $VisualizacaoSetorQuadraLote->altura=430;
	  $VisualizacaoSetorQuadraLote->titulo="Lista com setores, quadras e lotes";
	  $VisualizacaoSetorQuadraLote->iniciarVisivel = false;
	  $VisualizacaoSetorQuadraLote->mostrar();

    // Cria a janela para visualizacao da lista com os c�digos das ruas
    $VisualizacaoRuas = new janela("VisualizacaoRuas","");
	  $VisualizacaoRuas->posX=1;
	  $VisualizacaoRuas->posY=20;
	  $VisualizacaoRuas->largura=785;
	  $VisualizacaoRuas->altura=430;
	  $VisualizacaoRuas->titulo="Lista com c�digo das ruas";
	  $VisualizacaoRuas->iniciarVisivel = false;
	  $VisualizacaoRuas->mostrar();

    // Cria a janela para visualizacao da lista com os nomes das ruas
    $VisualizacaoNomeRuas = new janela("VisualizacaoNomeRuas","");
	  $VisualizacaoNomeRuas->posX=1;
	  $VisualizacaoNomeRuas->posY=20;
	  $VisualizacaoNomeRuas->largura=785;
	  $VisualizacaoNomeRuas->altura=430;
	  $VisualizacaoNomeRuas->titulo="Lista com nomes das ruas";
	  $VisualizacaoNomeRuas->iniciarVisivel = false;
	  $VisualizacaoNomeRuas->mostrar();

    // Cria a janela para visualizacao da lista com os c�digos dos bairros
    $VisualizacaoBairros = new janela("VisualizacaoBairros","");
	  $VisualizacaoBairros->posX=1;
	  $VisualizacaoBairros->posY=20;
	  $VisualizacaoBairros->largura=785;
	  $VisualizacaoBairros->altura=430;
	  $VisualizacaoBairros->titulo="Lista com c�digos dos bairros";
	  $VisualizacaoBairros->iniciarVisivel = false;
	  $VisualizacaoBairros->mostrar();

    // Cria a janela para visualizacao da lista com os nomes dos bairros
    $VisualizacaoNomeBairro = new janela("VisualizacaoNomeBairro","");
	  $VisualizacaoNomeBairro->posX=1;
	  $VisualizacaoNomeBairro->posY=20;
	  $VisualizacaoNomeBairro->largura=785;
	  $VisualizacaoNomeBairro->altura=430;
	  $VisualizacaoNomeBairro->titulo="Lista com nomes dos bairros";
	  $VisualizacaoNomeBairro->iniciarVisivel = false;
	  $VisualizacaoNomeBairro->mostrar();


    include("forms/db_frmconsultacadastronovo.php");
    ?>
    </td>
  </tr>
</table>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
