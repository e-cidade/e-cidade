<?php
use App\Models\Patrimonial\Licitacao\Licpropostavinc;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitaRepository;
use App\Repositories\Patrimonial\Licitacao\LicpropostaRepository;
use App\Repositories\Patrimonial\Licitacao\PcorcamforneRepository;
use App\Services\Patrimonial\Licitacao\LicpropostaService;
use App\Services\Patrimonial\Licitacao\LicpropostavincService;
use Illuminate\Database\Capsule\Manager as DB;
use App\Services\ExcelService;

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



$oJson    = new Services_JSON();
$oParam   = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();

$oRetorno->dados   = array();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

    switch ($oParam->exec) {

        case 'getDadosItenseFornecedores':
        $pcorcamforneRepository = new PcorcamforneRepository();
        $oRetorno->forne = $pcorcamforneRepository->getfornecedoreslicitacao($oParam->l20_codigo);

        foreach ($oRetorno->forne as $forne) {
            $itemforne = new stdClass();
            $itemforne->z01_numcgm = $forne->z01_numcgm;
            $itemforne->z01_nome = utf8_encode($forne->z01_nome);
            $itemforne->z01_cgccpf = $forne->z01_cgccpf;
            $itensFornecedor[] = $itemforne;
        }
        $oRetorno->fornecedores = $itensFornecedor;
        
        break;

        case 'getDadosItensLicitacao':
            $liclicitaRepository = new LicilicitemRepository();
            $licpropostaservicevinc = new LicpropostavincService();
            $licpropostaservice = new LicpropostaService();

            $rsitensProposta = $liclicitaRepository->getItensLicitacao($oParam->l20_codigo,$oParam->fornecedor,$oParam->lote);
            $rsProposta = $licpropostaservicevinc->getLicpropostavinc($oParam->l20_codigo,$oParam->fornecedor);
            $rsCriterio = $licpropostaservice->getCriterio($oParam->l20_codigo);
            $rsPrecoReferencia = $liclicitaRepository->getPrecoReferencia($oParam->l20_codigo);
            
            foreach($rsPrecoReferencia as $pcref){
                
                $itemPreco = new stdClass();
                $itemPreco = $pcref->valortabela;
                $precoreferencia[] = $itemPreco;
                
            }
            
            $oRetorno->preco = $precoreferencia;
           

            foreach ($rsitensProposta as $item) {
                
                $itemNovo = new stdClass();
                $itemNovo->l04_descricao = $item->l04_descricao;
                $itemNovo->l21_ordem     = $item->l21_ordem;
                $itemNovo->pc01_codmater = $item->pc01_codmater;
                $itemNovo->pc01_descrmater = utf8_encode($item->pc01_descrmater);
                $itemNovo->unidade = utf8_encode($item->unidade);
                $itemNovo->quantidade = $item->quantidade;
                $itemNovo->percentual = $item->percentual == "0" ? '' : $item->percentual;
                $itemNovo->vlr_unitario = $item->vlr_unitario == "0" ? '' : $item->vlr_unitario;
                $itemNovo->vlr_total = $item->vlr_total == "0" ? '' : $item->vlr_total;
                $itemNovo->marca = $item->marca;
                $itemNovo->pc81_codprocitem = $item->pc81_codprocitem;
                $itemNovo->pc01_complmater = $item->pc01_complmater;
                $itemNovo->pc01_taxa = $item->pc01_taxa;
                $itemNovo->pc01_tabela = $item->pc01_tabela;
                $itemNovo->l20_criterioadjudicacao = $item->l20_criterioadjudicacao;
                $itensProposta[] = $itemNovo;
            }
            
            
            $oRetorno->itens = $itensProposta;
            $oRetorno->proposta = $rsProposta->l223_codigo;
            $oRetorno->criterio = $rsCriterio[0]->l20_criterioadjudicacao;
            $oRetorno->tipojulg = $rsCriterio[0]->l20_tipojulg;
            $oRetorno->pc01_taxa = $rsitensProposta[0]->pc01_taxa;
            $oRetorno->pc01_tabela = $rsitensProposta[0]->pc01_tabela;



            break;

        case 'getDadosItenseLote':
            $itensLote  = new LicpropostaService();

            $oRetorno->lote = $itensLote->getLote($oParam->l20_codigo,$oParam->fornecedor);

            break;


        case 'salvarProposta':

            $service = new LicpropostaService();
            $serviceVinc = new LicpropostavincService();
            $rspropostavinc = $serviceVinc->getLicpropostavinc($oParam->l20_codigo,$oParam->l224_forne);

            DB::beginTransaction();

                if(!$rspropostavinc->l223_codigo){
                    $oPropostaVinc = $serviceVinc->salvarItensLicpropostaVinc($oParam);

                    if (empty($oPropostaVinc)) {
                        throw new Exception('Não foi possível inserir o vínculo na tabela Licpropostavinc');
                    }
                }

                foreach($oParam->aItens as $item){

                        $item->l224_forne = $oParam->l224_forne;
                        if($item->l224_porcent == ''){
                            $item->l224_porcent = 0;
                        }
                        if($item->l224_vlrun == ''){
                            $item->l224_vlrun = $item->l224_valor;
                        }
                        if($l224_marca){
                            $item->marca = utf8_encode($item->marca);
                        }
                        if(!$rspropostavinc->l223_codigo){
                            $item->l224_codigo = $oPropostaVinc->l223_codigo;
                            $oProposta = $service->salvarItensLicproposta($item);

                        if (empty($oProposta->l224_sequencial)) {
                            throw new Exception('Não foi possivel inserir a proposta na tabela Licproposta');
                        }
                        }else{
                            $item->l224_codigo = $rspropostavinc->l223_codigo;
                        }

                }
            DB::commit();

            $oRetorno->proposta = $oPropostaVinc->l223_codigo;
            $oRetorno->status = 1;
            $oRetorno->message = 'Proposta Salvo com Sucesso!';
            break;

            case 'alterarProposta':
                $service = new LicpropostaService();
                DB::beginTransaction();

                foreach ($oParam->aItens as $item) {
                    $item->l224_codigo = $oParam->l224_codigo;
                    $itemsequecial = $service->getSequencial($item->l224_codigo,$item->l224_propitem);
                    $item->l224_sequencial = $itemsequecial;
                    if($item->l224_porcent == ''){
                        $item->l224_porcent = 0;
                    }
                    if($item->l224_vlrun == ''){
                        $item->l224_vlrun = $item->l224_valor;
                    }

                    if(isset($item->l224_sequencial)){
                        $oPropostaAtualizada = $service->atualizarProposta($item);
                    }
                    else{
                        $oPropostaAtualizada = $service->salvarItensLicproposta($item);
                    }


                    if ($oPropostaAtualizada == false) {
                        throw new Exception('Não foi possível atualizar a proposta na tabela Licproposta');
                    }

                }

                DB::commit();

                $oRetorno->proposta = $oPropostaAtualizada->l224_codigo;
                $oRetorno->status = 1;
                $oRetorno->message = 'Proposta atualizada com sucesso!';
                break;

                case 'importarProposta':

                    $excelService = new ExcelService();
                    $nome_arquivo = $oParam->sCaminhoArquivo;

                    $aDadosImportar = [];
                    $aDadosPlanilha = array_slice($excelService->importFile($nome_arquivo),7);
                    $aDadosNome = array_slice($excelService->importFile($nome_arquivo),1);
                    $aDadosCnpj = array_slice($excelService->importFile($nome_arquivo),1);
                    $nomeforne = $aDadosNome[4]['A'];
                    $nomeLimpo = str_replace('Nome / Razao Social:   ', '', $nomeforne);
                    $cnpjCompleto = $aDadosCnpj[3]['A'];

                    // Usando preg_replace para remover todos os caracteres não numéricos
                    $cnpjNumero = preg_replace('/\D/', '', $cnpjCompleto);

                    
                    foreach ($aDadosPlanilha as $dados) {
                        $aDadosImportar[] = [
                            'vlr_unit' => ($dados['H'] == 0 ? '' : $dados['H']),
                            'vlr_total'=>($dados['I'] == 0 ? '' : $dados['I']),
                            'marca' => $dados['J'],
                            'percentual' => $dados['G'],
                            'ordem' => $dados['A']
                        ];
                    }
                    
                    
                    $resultadoChunks = array_chunk($aDadosImportar, 500);
                    $oRetorno->import = $resultadoChunks;
                    $oRetorno->cnpj = $cnpjNumero;
                    $oRetorno->nome = $nomeLimpo;
                    $oRetorno->status = 1;
                break;

                case 'excluirProposta':
                    $serviceexclusao = new LicpropostaService();
                    $servicevincexclusao = new LicpropostavincService();

                    $serviceexclusaoproposta = $serviceexclusao->deletaProposta($oParam->l224_codigo);
                    $serviceexclusaopropostavinc = $servicevincexclusao->deletaProposta($oParam->l223_codigo);

                    if (!$serviceexclusaoproposta) {
                        throw new Exception('Não foi possível excluir a proposta');
                    }

                    DB::commit();
                    $oRetorno->status = 1;
                    $oRetorno->message = 'Proposta excluída com sucesso!';
                    break;

                    case 'getPropostas':
                        $serviceProposta = new LicpropostaService();
                        $rsResultProposta = $serviceProposta->getProposta($oParam->l223_liclicita,$oParam->fornecedor);

                        if ($rsResultProposta){
                            $oRetorno->proposta = $rsResultProposta[0]->l224_codigo;
                            $oRetorno->forne = $oParam->l223_codigo;
                            $oRetorno->status = 1;
                            $oRetorno->dados = $rsResultProposta;
                        }else{
                            $oRetorno->status = 2;
                            $oRetorno->message = 'Nenhum Item encontrado!';
                        }
                        break;

    }
}catch(Exception $e){
    $oRetorno->message = urlencode($e);
    $oRetorno->status = 2;
}
echo $oJson->encode($oRetorno);
