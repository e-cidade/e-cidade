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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link type="text/css" rel="stylesheet" href="estilos.css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/saude/ambulatorial/DBViewTriagem.classe.js"></script>
</head>
<body class="body-default">
<div id="cntTriagem" class="container"></div>
</body>
</html>
<script>
var oGet = js_urlToObject();
var oTriagem = new DBViewTriagem( DBViewTriagem.prototype.TELA_TRIAGEM_CONSULTA );
    oTriagem.setProntuario( oGet.iProntuario );
    oTriagem.setCgs( oGet.iCgs );
    oTriagem.iTriagem = oGet.iTriagem;
    oTriagem.temProntuario( true );
    oTriagem.bloqueiaFormulario( true );
    oTriagem.show($('cntTriagem'));
    oTriagem.buscaCGS();

function js_comparaDatasoInputDataConsulta(dia, mes, ano) {

  var objData   = document.getElementById('oInputDataConsultaValor');
  objData.value = dia + "/" + mes + "/" + ano;
}

function js_comparaDatasoInputDataPrimeiroSintoma(dia, mes, ano) {

  var objData   = document.getElementById('oInputDataPrimeiroSintomaValor');
  objData.value = dia + "/" + mes + "/" + ano;
}

</script>