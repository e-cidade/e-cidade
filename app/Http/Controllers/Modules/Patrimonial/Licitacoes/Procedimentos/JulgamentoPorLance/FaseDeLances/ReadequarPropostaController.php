<?php

namespace App\Http\Controllers\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances;

use App\Exceptions\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\JulgamentoException;
use App\Helpers\FileHelper;
use App\Helpers\StringHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\ReadequarProposta\DeletarPropostaRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\ReadequarProposta\ExportarItensRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\ReadequarProposta\SalvarPropostaRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\ReadequarProposta\UploadXlsxRequest;
use App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\ReadequarProposta\VerificarPropostaExistenteRequest;
use App\Repositories\Patrimonial\Licitacao\JulgItemRepository;
use App\Repositories\Patrimonial\Licitacao\JulgLanceRepository;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Repositories\Patrimonial\Licitacao\PcorcamforneRepository;
use App\Services\ExcelService;
use App\Services\Patrimonial\Licitacao\LicpropostaService;
use App\Services\Patrimonial\Licitacao\LicpropostavincService;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\FaseDeLancesService;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\ReadequarPropostaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ReadequarPropostaController extends Controller
{
    protected $faseDeLancesService;
    protected $licilicitemRepository;
    protected $licpropostavincService;
    protected $licpropostaService;
    protected $pcorcamforneRepository;
    protected $julgitemRepository;
    protected $julgLanceRepository;
    protected $readequarPropostaService;

    public function __construct(
        FaseDeLancesService $faseDeLancesService, 
        LicilicitemRepository $licilicitemRepository,
        LicpropostavincService $licpropostavincService,
        LicpropostaService $licpropostaService,
        PcorcamforneRepository $pcorcamforneRepository,
        JulgItemRepository $julgitemRepository,
        JulgLanceRepository $julgLanceRepository,
        ReadequarPropostaService $readequarPropostaService
    )
    {
        $this->faseDeLancesService = $faseDeLancesService;
        $this->licilicitemRepository = $licilicitemRepository;
        $this->licpropostavincService = $licpropostavincService;
        $this->licpropostaService = $licpropostaService;
        $this->pcorcamforneRepository = $pcorcamforneRepository;
        $this->julgitemRepository = $julgitemRepository;
        $this->julgLanceRepository = $julgLanceRepository;
        $this->readequarPropostaService = $readequarPropostaService;
    }

    /**
     * Exibe a página inicial de readequar proposta.
     *
     * @return \Illuminate\View\View
     */
    public function index($licitacaoCodigo, $numeroLote)
    {
        $param = $this->faseDeLancesService->obterParametros();

        $getTheLowestBidLot = $this->licilicitemRepository->getTheLowestBidLot($licitacaoCodigo, $numeroLote, $param->l13_clapercent);
        $orcamforne = $getTheLowestBidLot->pc21_orcamforne;

        $julgItem = $this->julgitemRepository->find('l30_numerolote', $numeroLote);
        $lanceVencedor = $this->julgLanceRepository->findLastBidNotNull($julgItem[0]->l30_codigo);

        $fornecedores = $this->pcorcamforneRepository->getSupplierAndTheirDataFromCgm($orcamforne);

        $rsitensProposta = $this->readequarPropostaService->obterItensDaReadequecaoDeProposta($licitacaoCodigo, $orcamforne, $numeroLote);
        $rsProposta = $this->licpropostavincService->getLicpropostavinc($licitacaoCodigo, $orcamforne);

        if (empty($lanceVencedor)) {
            $menorProposta = $this->licilicitemRepository->getLowestBidWithoutBidding($licitacaoCodigo, $numeroLote);
            $lanceVencedor = $menorProposta->total_valor;
        } else {
            $lanceVencedor = $lanceVencedor->l32_lance;
        }

        $array = $rsitensProposta->map(function ($item) {
            return ['orcamitem' => $item->pc22_orcamitem];
        })->toArray();

        $propostaExistente = $this->readequarPropostaService->verificarPropostaExistente($array);

        $data['itens'] = $rsitensProposta;
        $data['licitacaoCodigo'] = $licitacaoCodigo;
        $data['numeroLoteCodigo'] = $numeroLote;
        $data['propostaCodigo'] = $rsProposta->l223_codigo;
        $data['fornecedoresDados'] = $fornecedores;
        $data['lanceVencedor'] = $lanceVencedor;
        $data['propostaExistente'] = $propostaExistente;
        
        return view()->file(base_path('resources/legacy/licitacao/lic_fasedelance005.php'), [
            'data' => $data
        ]);
    }

    /**
     * Obtém os itens da readequação da proposta com base nos parâmetros fornecidos.
     *
     * - Recebe o código da licitação, código do fornecedor e código do lote.
     * - Chama o serviço `readequarPropostaService` para obter os itens da readequação da proposta.
     * - Retorna os itens obtidos como resposta JSON com o status HTTP 200.
     *
     * @param string $codigoLicitacao O código da licitação.
     * @param string $codigoOrcamforne O código do fornecedor (orçamento).
     * @param string $codigoLote O código do lote.
     * 
     * @return \Illuminate\Http\JsonResponse A resposta JSON com os itens da proposta.
     */
    public function obterItensDaReadequecaoDeProposta($codigoLicitacao, $codigoOrcamforne, $codigoLote)
    {
        $rsitensProposta = $this->readequarPropostaService->obterItensDaReadequecaoDeProposta($codigoLicitacao, $codigoOrcamforne, $codigoLote);

        return response()->json([
            'itensProposta' => $rsitensProposta,
        ], 200);
    }

    /**
     * Verifica se uma proposta existente já foi cadastrada com base nos itens fornecidos.
     *
     * - Valida os dados recebidos na requisição usando o `VerificarPropostaExistenteRequest`.
     * - Extrai a lista de itens da proposta e verifica se a proposta já existe, consultando o serviço `readequarPropostaService`.
     * - Se a proposta existir, retorna os dados da proposta.
     * - Se ocorrer um erro, retorna uma resposta com o erro apropriado.
     *
     * @param VerificarPropostaExistenteRequest $request A requisição que contém os dados para verificar a proposta.
     * 
     * @return \Illuminate\Http\JsonResponse A resposta JSON com a proposta ou uma mensagem de erro.
     */
    public function verificarPropostaExistente(VerificarPropostaExistenteRequest $request)
    {
        $validated = $request->validated();
        
        try {
            
            $orcamItens = array_column($validated['itens'], 'orcamitem');
            $proposta = $this->readequarPropostaService->verificarPropostaExistente($orcamItens);

            return response()->json([
                'message' => 'Verificado com sucesso.',
                'proposta' => $proposta,
            ], 200);
            
        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }   
    }

    /**
     * Salva uma proposta com os dados fornecidos na requisição.
     *
     * - Valida os dados recebidos utilizando o `SalvarPropostaRequest`.
     * - Chama o serviço `readequarPropostaService` para salvar a proposta com os dados validados.
     * - Retorna uma resposta com o resultado da operação ou uma mensagem de erro, dependendo do caso.
     *
     * @param SalvarPropostaRequest $request A requisição contendo os dados necessários para salvar a proposta.
     * 
     * @return \Illuminate\Http\JsonResponse A resposta JSON com a proposta salva ou uma mensagem de erro.
     */
    public function salvarProposta(SalvarPropostaRequest $request)
    {
        $validated = $request->validated();

        try {

            $salvar = $this->readequarPropostaService->salvarProposta($validated['licitacao'], $validated['lote'], $validated['orcamforne'], $validated['itens']);

            return response()->json([
                'message' => StringHelper::toUtf8('Readequeção de proposta salva com sucesso.'),
                'salvar' => $salvar,
            ], 200);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }   
    }

    /**
     * Deleta uma proposta com base nos itens fornecidos na requisição.
     *
     * - Valida os dados recebidos utilizando o `DeletarPropostaRequest`.
     * - Chama o serviço `readequarPropostaService` para deletar a proposta com os itens fornecidos.
     * - Retorna uma resposta com o resultado da operação ou uma mensagem de erro, dependendo do caso.
     *
     * @param DeletarPropostaRequest $request A requisição contendo os itens da proposta a ser deletada.
     * 
     * @return \Illuminate\Http\JsonResponse A resposta JSON com o resultado da exclusão ou uma mensagem de erro.
     */
    public function deletarProposta(DeletarPropostaRequest $request)
    {
        $validated = $request->validated();

        try {

            $salvar = $this->readequarPropostaService->deletarProposta($validated['itens'], $validated['lote']);

            return response()->json([
                'message' => StringHelper::toUtf8('Readequeção de proposta deletada com sucesso.'),
                'salvar' => $salvar,
            ], 200);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }   
    }

    /**
     * Faz o upload de um arquivo XLSX, sanitiza o nome e move para um diretório temporário.
     * Valida o arquivo, gera um nome único e chama `importarItens` para processá-lo.
     * Retorna erro em caso de falha no upload ou movimentação do arquivo.
     *
     * @param UploadXlsxRequest $request Requisição com o arquivo enviado.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response Resposta do processamento.
     */
    public function uploadXlsx(UploadXlsxRequest $request)
    {
        $validated = $request->validated();

        try {

            $file = $request->file('xlsx_file');
            
            $originalName = $file->getClientOriginalName();
            $sanitizedName = FileHelper::sanitizeFileName($originalName);
            
            $filename = 'uploaded_' . time() . '_' . $sanitizedName;
            
            $filename = Str::slug(pathinfo($filename, PATHINFO_FILENAME), '_') 
                        . '.' . $file->getClientOriginalExtension();
                        
            $file->move(base_path('tmp'), $filename);

            $caminhoCompletoArquivo = 'tmp/' . $filename;
            
            if (!file_exists($caminhoCompletoArquivo)) {
                throw new \Exception("Arquivo não foi movido corretamente: {$caminhoCompletoArquivo}");
            }

            return $this->importarItens($caminhoCompletoArquivo);
            
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Erro no upload: ' . $e->getMessage()
            ], 500);

        }
    }

    /**
     * Importa os dados de um arquivo XLSX e os processa.
     * Extrai informações do fornecedor, formata o CNPJ e processa os itens da planilha.
     * Divide os dados em lotes para otimizar o processamento.
     * Retorna erro específico ou inesperado, conforme necessário.
     *
     * @param string $nome_arquivo Caminho do arquivo XLSX.
     * @return \Illuminate\Http\JsonResponse Resposta do processamento.
     */
    private function importarItens($nome_arquivo)
    {
        try {

            $excelService = new ExcelService();

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

            dd($resultadoChunks, $aDadosImportar);

        } catch (JulgamentoException $e) {

            return response()->json(['error' => StringHelper::toUtf8($e->getMessage())], 400);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro inesperado.'], 500);

        }   
    }

}
