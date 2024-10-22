<?php

use App\Services\Patrimonial\Empenho\EmpAnexoService;

require_once 'libs/db_stdlib.php';
require_once 'dbforms/db_funcoes.php';
require_once 'libs/JSON.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once("model/contrato/PNCP/ContratoPNCP.model.php");


db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$oJson = new services_json();
$oParam = json_decode(str_replace('\\', '', $_POST['json']));

$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->erro = '';

$empAnexoService = new EmpAnexoService();

try {
    switch ($oParam->sExecuta) {
        case 'salvarAnexo':

            db_inicio_transacao();

            $oParam->aDadosEmpAnexo->e100_instit = db_getsession('DB_instit');
            $oParam->aDadosEmpAnexo->e100_usuario = db_getsession('DB_id_usuario');
            $oParam->aDadosEmpAnexo->e100_datalancamento = date('Y-m-d', db_getsession('DB_datausu'));

            if (!file_exists($oParam->sCaminhoArquivo)) {
                throw new Exception('Arquivo do Documento não Encontrado.');
            }

            /**
             * Abre um arquivo em formato binario somente leitura.
             */
            $rDocumento = fopen($oParam->sCaminhoArquivo, 'rb');

            /**
             * Pega todo o conteúdo do arquivo e coloca no resource.
             */
            $rDadosDocumento = fread($rDocumento, filesize($oParam->sCaminhoArquivo));
            fclose($rDocumento);
            $oOidBanco = pg_lo_create();

            $oParam->aDadosEmpAnexo->e100_anexo = $oOidBanco;

            $oRetorno->dados = $empAnexoService->create(get_object_vars($oParam->aDadosEmpAnexo));

            $oObjetoBanco = pg_lo_open($conn, $oOidBanco, 'w');
            pg_lo_write($oObjetoBanco, $rDadosDocumento);
            pg_lo_close($oObjetoBanco);

            db_fim_transacao();

            break;

            case 'carregarAnexosDeEmpenho':

                $oRetorno->aAnexos = $empAnexoService->getAnexosEmpenho($oParam->iNumeroEmpenho);
                break;

            case 'carregarAnexo':

                $aAnexo = $empAnexoService->getAnexo($oParam->iSequencialEmpAnexo);
                $oRetorno->iTipo = $aAnexo->e100_tipoanexo;
                break;

            case 'alterarAnexo':

                $empAnexoService->update($oParam->iSequencialEmpAnexo, get_object_vars($oParam->aDadosEmpAnexo));
                break;

            case 'excluirAnexo':

                $empAnexoService->delete($oParam->iSequencialEmpAnexo);
                break;

            case 'excluirAnexos':

                $empAnexoService->deleteAll($oParam->aSequencialEmpAnexo);
                break;

            case 'downloadAnexo':

                $aAnexo = $empAnexoService->getAnexo($oParam->iCodigoAnexo);

                db_inicio_transacao();

                // Abrindo o objeto no modo leitura "r" passando como parâmetro o OID.
                $sNomeArquivo = "tmp/{$aAnexo->e100_titulo}";
                pg_lo_export($conn, $aAnexo->e100_anexo, $sNomeArquivo);

                db_fim_transacao();
                $oRetorno->nomearquivo = $sNomeArquivo;

                break;

            case 'downloadAnexos':

                $rsAnexos = $empAnexoService->getAnexos($oParam->sSequencialEmpAnexo);

                $aListaAnexos = ' ';
                for ($i = 0; $i < count($rsAnexos); $i++) {
                    $oAnexo = $rsAnexos[$i];
                    $titulo = $oAnexo->e100_titulo;
                    $sNomeArquivo = "tmp/$titulo";
                    db_inicio_transacao();
                    pg_lo_export($conn, $oAnexo->e100_anexo, $sNomeArquivo);
                    db_fim_transacao();
                    $aListaAnexos .= $sNomeArquivo.' ';
                }

                if (trim($aListaAnexos)) {
                    system('rm -f anexosEmpenho.zip');

                    system("bin/zip -q anexosEmpenho.zip $aListaAnexos");

                    $aAnexos = explode(' ', $aListaAnexos);

                    foreach ($aAnexos as $arquivo) {
                        unlink($arquivo);
                    }
                }

                break;

            case 'EnviarAnexoPNCP':

                foreach ($oParam->aAnexos as $oAnexo) {

                    $oEmpanexo = $empAnexoService->getAnexo($oAnexo->sequencial);
                    if ($oEmpanexo->e100_sequencialarquivo != null) {
                        throw new Exception('O Anexo '.$oEmpanexo->e100_titulo.' já foi enviado.');
                    }

                    $clempempenhopncp = new cl_empempenhopncp();
                    $rsEmpempenhopncp = $clempempenhopncp->sql_record($clempempenhopncp->sql_query_file(null, " * ", null, "e213_contrato = " . $oParam->iNumeroEmpenho));
                    $oEmpempenhopncp = db_utils::fieldsMemory($rsEmpempenhopncp, 0);
                    
                    db_inicio_transacao();

                    $sCaminhoArquivo = "tmp/{$oEmpanexo->e100_titulo}";

                    pg_lo_export($conn, $oEmpanexo->e100_anexo, $sCaminhoArquivo);
                    db_fim_transacao(true);

                    $clContratoPNCP = new ContratoPNCP(array());
                    $rsApiPNCP = $clContratoPNCP->enviarAnexoEmpenho($oEmpempenhopncp,$oEmpanexo);

                    if ($rsApiPNCP[1] == "201") {

                        $iSequencialarquivo = explode('x-content-type-options', $rsApiPNCP[0]);
                        $iSequencialarquivo = preg_replace('#\s+#', '', $iSequencialarquivo);
                        $iSequencialarquivo = explode('/', $iSequencialarquivo[0]);
                        $iSequencialarquivo = $iSequencialarquivo[11];

                        $aDadosEmpAnexo = array('e100_sequencialpncp' => $oEmpempenhopncp->e213_sequencialpncp,'e100_sequencialarquivo' => $iSequencialarquivo);

                        $empAnexoService->update($oAnexo->sequencial, $aDadosEmpAnexo);
                        continue;

                    }
                            
                    throw new Exception("Anexo " . $oEmpanexo->e100_titulo. " Formato Inválido<br/>" . $rsApiPNCP[1] . $rsApiPNCP[2]);
                    
                }

                break;

            case 'ExcluirAnexoPNCP':

                foreach ($oParam->aAnexos as $oAnexo) {

                    $oEmpanexo = $empAnexoService->getAnexo($oAnexo->sequencial);

                    $clempempenhopncp = new cl_empempenhopncp();
                    $rsEmpempenhopncp = $clempempenhopncp->sql_record($clempempenhopncp->sql_query_file(null, " * ", null, "e213_contrato = " . $oParam->iNumeroEmpenho));
                    $oEmpempenhopncp = db_utils::fieldsMemory($rsEmpempenhopncp, 0);
                    

                    $clContratoPNCP = new ContratoPNCP(array());
                    $rsApiPNCP = $clContratoPNCP->excluirAnexoEmpenho($oEmpempenhopncp,$oEmpanexo->e100_sequencialarquivo);

                    if ($rsApiPNCP->status == null || $rsApiPNCP->status == '' || $rsApiPNCP->status == 201) {
                        $aDadosEmpAnexo = array('e100_sequencialpncp' => null,'e100_sequencialarquivo' => null);
                        $empAnexoService->update($oAnexo->sequencial, $aDadosEmpAnexo);
                    }

                    if ($rsApiPNCP->status == 404) {
                        throw new Exception(utf8_decode($rsApiPNCP->message));
                    }

                    if ($rsApiPNCP->status == 422) {
                        throw new Exception(utf8_decode($rsApiPNCP->message));
                    }

                    if ($rsApiPNCP->status == 500) {
                        throw new Exception(utf8_decode($rsApiPNCP->message));
                    }

                }

                break;
    }
} catch (Exception $e) {
    $oRetorno->erro = urlencode($e->getMessage());
    $oRetorno->status = 2;
}

echo $oJson->encode(DBString::utf8_encode_all($oRetorno));
