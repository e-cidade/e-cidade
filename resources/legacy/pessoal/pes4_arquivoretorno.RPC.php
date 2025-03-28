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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("model/pessoal/ArquivoRetorno.model.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMsg    = "";

try {

    switch ($oParam->sMethod) {
        case 'importar':
            if (strtolower(end(explode('.', $_FILES["arquivo"]['name']))) != "processado") {
                $oRetorno->iStatus = 2;
                $oRetorno->sMsg    = urlencode(str_replace("\\n","\n","Arquivo inv�lido."));
            } else {
                if (move_uploaded_file($_FILES["arquivo"]['tmp_name'], "tmp/".$_FILES['arquivo']['name'])) {
                    $oRetorno->sMsg = urlencode(str_replace("\\n","\n","Arquivo importado com sucesso!"));
                } else {
                    $oRetorno->iStatus = 2;
                    $oRetorno->sMsg    = urlencode(str_replace("\\n","\n","N�o foi poss�vel enviar o arquivo."));
                }
            }
            break;

        case 'processar':
            $oArquivoRetorno = new ArquivoRetorno($oParam->sFile);
            $oArquivoRetorno->import();
            $oRetorno->sMsg = urlencode(str_replace("\\n","\n","Arquivo importado com sucesso!"));
            break;

        case 'excluirImportacao':
            $oArquivoRetorno = new ArquivoRetorno('');
            $oArquivoRetorno->excluir($oParam->dataImportacao);
            $oRetorno->sMsg = urlencode(str_replace("\\n","\n","Importa��o exclu�da com sucesso!"));
            break;
        
        default:
            break;
    }

} catch ( Exception $eException ) {
    $oRetorno->iStatus = 2;
    $oRetorno->sMsg    = urlencode(str_replace("\\n","\n",$eException->getMessage()));
}

echo $oJson->encode($oRetorno);

?>