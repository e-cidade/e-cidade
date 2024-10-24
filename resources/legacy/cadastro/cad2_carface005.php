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
require_once("libs/db_usuariosonline.php");
require_once("classes/db_setor_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_sanitario_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
$clsetor            = new cl_setor;
$cliframe_seleciona = new cl_iframe_seleciona;
$clsetor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script>
  function js_nome(obj) {

    j34_setor = "";
    vir       = "";
    x         = 0;
    for (i = 0; i < setor.document.form1.length; i++) {

     if (setor.document.form1.elements[i].type == "checkbox") {

       if (setor.document.form1.elements[i].checked == true) {

         valor      = setor.document.form1.elements[i].value.split("_")
         j34_setor += vir + valor[0];
         vir        = ",";
         x         += 1;
       }
     }
    }

    parent.iframe_g4.location.href = '../cad2_iptuconstr006.php?j34_setor='+j34_setor;
    if (j34_setor == "") {

      parent.iframe_g1.document.form1.quadra.value = '';
      parent.iframe_g1.document.form1.setor.value = '';
    }
  }

  function js_marca2(obj) {

    var oColInput = document.getElementsByTagName('input');
    for( i in oColInput ) {

      oInput = oColInput[i];
      if( oInput.type == 'checkbox' && oInput.disabled == false) {
         oInput.checked = !(oInput.checked == true);
      }
    }
    js_nome(null);
  }

  </script>
</head>

<body class='body-default' onLoad="a=1" >

  <div class='container'>
    <form name="form1" method="post" action="" target="rel">
      <table>
        <tr>
          <td >
            <?php
              $cliframe_seleciona->campos        = "j30_codi,j30_descr";
              $cliframe_seleciona->legenda       = "SETORES";
              $cliframe_seleciona->sql           = $clsetor->sql_query(""," * ");
              $cliframe_seleciona->textocabec    = "darkblue";
              $cliframe_seleciona->textocorpo    = "black";
              $cliframe_seleciona->fundocabec    = "#aacccc";
              $cliframe_seleciona->fundocorpo    = "#ccddcc";
              $cliframe_seleciona->iframe_height = "250";
              $cliframe_seleciona->iframe_width  = "700";
              $cliframe_seleciona->iframe_nome   = "setor";
              $cliframe_seleciona->chaves        = "j30_codi,j30_descr";
              $cliframe_seleciona->dbscript      = "onClick='parent.js_nome(this)'";
              $cliframe_seleciona->marcador      = true;
              $cliframe_seleciona->js_marcador   = "parent.js_marca2(obj)";
              $cliframe_seleciona->iframe_seleciona(@$db_opcao);
            ?>
          </td>
        </tr>
        <script>
        function js_nome(obj) {

          j34_setor = "";
          vir       = "";
          x         = 0;
          for (i=0;i<setor.document.form1.length;i++) {

            if (setor.document.form1.elements[i].type == "checkbox") {

              if (setor.document.form1.elements[i].checked == true) {

                valor      = setor.document.form1.elements[i].value.split("_")
                j34_setor += vir + valor[0];
                vir        = ",";
                x         += 1;
              }
            }
          }
          parent.iframe_g4.location.href = '../cad2_iptuconstr006.php?j34_setor='+j34_setor;
          if (j34_setor == "") {

            parent.iframe_g1.document.form1.quadra.value = '';
            parent.iframe_g1.document.form1.setor.value = '';
          }
        }
        </script>
      </table>
    </form>
  </div>
</body>
</html>
