<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");

?>
<style>
  .gridcontainer {
    height: 249px;
    max-height: 300px; /* Defina a altura máxima desejada */
    overflow-y: auto;  /* Adiciona a rolagem vertical */
    overflow-x: auto;  /* Adiciona a rolagem horizontal se necessário */
    border: 1px solid #ccc; /* Apenas para visualização */
  }
</style>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
      db_app::load("datagrid.widget.js, grid.style.css, DBHint.widget.js, DBAbas.widget.js");

      $clempempenho = new cl_empempenho;
      
    ?>
  </head>
  <body style="background-color: #ccc;">
    <div id="ctnGlobalItemEmpenho">

      <div id="divConvenios">
        <fieldset>
          <legend><b>Convênios do Empenho</b></legend>

            <div class="gridcontainer">
              <table>

                <tr>
                  <td class="table_header cell" style="width:2%">Cod. Convênio</td>
                  <td class="table_header cell" style="width:8%">Número do Convênio</td>
                  <td class="table_header cell" style="width:5%">Data de Assinatura</td>
                  <td class="table_header cell" style="width:20%">Objeto do Convênio</td>
                </tr>

                <?php 
                  $aConvenios = $clempempenho->getConveniosByEmpenho($_GET['e60_numemp']);

                  if(empty($aConvenios)) { ?>
                    <tr class="normal">
                      <td class="linhagrid cell" colspan="4" style="text-align: center;">Nenhum convênio foi encontrado</td>
                    </tr> <?php
                  }

                  if(!empty($aConvenios)) {
                    foreach ($aConvenios as $key => $convenio) { ?>
                      <tr class="normal" style="height:1em;">
                        <td class="linhagrid" style="width:2%"  nowrap=""> <?php echo $convenio['cod_convenio'] ?> </td>
                        <td class="linhagrid" style="width:8%"  nowrap=""> <?php echo $convenio['num_convenio'] ?> </td>
                        <td class="linhagrid" style="width:5%"  nowrap=""> <?php echo db_formatar($convenio['data_assinatura'], "d") ?> </td>
                        <td class="linhagrid" style="width:20%" nowrap=""> <?php echo $convenio['obj_convenio'] ?> </td>
                      </tr> <?php  
                    }
                  }
                ?>

              </table>
            </div>

          </fieldset>
          
        </div>    
      </div>
      
    </div>
  </body>
</html>
