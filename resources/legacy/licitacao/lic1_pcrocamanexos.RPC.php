<?php

use App\Repositories\Patrimonial\Licitacao\PcorcamanexosRepository;
use Illuminate\Support\Facades\DB;
use App\Helpers\FileHelper;

global $oErro;
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("std/DBDate.php");
require_once("dbforms/db_funcoes.php");


global $conn;
$oJson    = new Services_JSON();
$oParam   = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();

$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {


    switch ($oParam->sExecuta) {

        case 'processar':


            db_inicio_transacao();
            $pcorcamanexos = new PcorcamanexosRepository();
            $nome_arq = str_replace(' ', '_', $oParam->sNomeArquivo);
            $arquivo = 'tmp/' . $nome_arq;
            $nome_arquivo = $nome_arq;
            $codorc = $oParam->codorc;

            /**
             * Abre um arquivo em formato binario somente leitura
             */
            $rDocumento      = fopen($arquivo, "rb");

            /**
             * Pega todo o conteúdo do arquivo e coloca no resource
             */

            $rDadosDocumento = fread($rDocumento, filesize($arquivo));

            $oOidBanco       = pg_lo_create($conn);


            fclose($rDocumento);


            $dados_arquivo = array(
                'pc98_nomearquivo' => $nome_arquivo,
                'pc98_codorc' => $codorc,
                'pc98_anexo' => $oOidBanco
            );
            if($pcorcamanexos->getDocumentos($codorc)){
                
                $oRetorno->status = 2;
                $oRetorno->message = utf8_encode('Somente uma ata de julgamento é permitida por processo.');
            }else{
                $pcorcamanexos->insert($dados_arquivo);
                $oRetorno->status = 1;
                $oRetorno->message = 'Documento salvo com sucesso!';
            }
            
            
            $oObjetoBanco = pg_lo_open($conn, $oOidBanco, "w");
 
            pg_lo_write($oObjetoBanco, $rDadosDocumento);

            pg_lo_close($oObjetoBanco);
            db_fim_transacao();

            

            break;


        case 'getDocumento':
            $pcorcamanexos = new PcorcamanexosRepository();
            $codorc = $oParam->codorc;
            $documentos = $pcorcamanexos->getDocumentos($codorc);

            $oRetorno->dados = $documentos;
            break;


        case 'excluirDocumentos':
            DB::beginTransaction();
            $pcorcamanexosExcluir = new PcorcamanexosRepository();
            $sequencial = $oParam->sequencial;
            $pcorcamanexosExcluir->excluir($sequencial);


            $oRetorno->status = 1;
            $oRetorno->message = 'Documento excluído com sucesso!';
            DB::commit();
            break;

        case 'download':

            db_inicio_transacao();
            $pcorcamanexos = new PcorcamanexosRepository();
            $oid = $pcorcamanexos->getOid($oParam->sequencial);
            $oidBanco = $oid[0]->pc98_anexo;
            $nomeBanco = $pcorcamanexos->getnome($oParam->sequencial);
            $nomeArquivo = $nomeBanco[0]->pc98_nomearquivo;

            $sNomeArquivo = 'tmp/' . FileHelper::sanitizeFileName(FileHelper::replaceSpecialChars($nomeArquivo));
            pg_lo_export($conn, $oidBanco, $sNomeArquivo);
            db_fim_transacao(true);
            $oRetorno->nomearquivo = $sNomeArquivo;

            break;

        case 'VerificaAnexo':
            $pcorcamanexos = new PcorcamanexosRepository();
            $anexo = $pcorcamanexos->getDocumentos($oParam->codorc);
            $oRetorno->anexo = $anexo;

            break;

    }
} catch (Exception $oErro) {
    DB::rollBack();

    $oRetorno->status   = 2;
    $oRetorno->sMensagem =  $oErro->getMessage();
}

echo $oJson->encode($oRetorno);
