<?php

namespace App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance;

use App\Exceptions\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\JulgamentoException;
use App\Models\Patrimonial\Licitacao\Liclicitemlote;
use App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance\FaseDeLancesService;
use App\Repositories\Patrimonial\Compras\PcorcamitemRepository;
use App\Repositories\Patrimonial\Compras\PcorcamjulgRepository;
use App\Repositories\Patrimonial\Compras\PcorcamvalRepository;
use App\Repositories\Patrimonial\Licitacao\JulgForneHistRepository;
use App\Repositories\Patrimonial\Licitacao\JulgForneRepository;
use App\Repositories\Patrimonial\Licitacao\JulgForneStatusRepository;
use App\Repositories\Patrimonial\Licitacao\JulgItemHistRepository;
use App\Repositories\Patrimonial\Licitacao\JulgItemRepository;
use App\Repositories\Patrimonial\Licitacao\JulgItemStatusRepository;
use App\Repositories\Patrimonial\Licitacao\JulgLanceRepository;
use App\Repositories\Patrimonial\Licitacao\JulgParamRepository;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use App\Repositories\Patrimonial\Licitacao\LiclicitemLoteRepository;
use App\Repositories\Patrimonial\Licitacao\PcorcamforneRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JulgamentoLoteService
{
    protected $faseDeLancesService;
    protected $julgItemRepository;
    protected $julgItemHistRepository;
    protected $itemStatusRepository;
    protected $julgLanceRepository;
    protected $julgForneRepository;
    protected $julgForneHistRepository;
    protected $julgForneStatusRepository;
    protected $julgParamRepository;
    protected $pcorcamitemRepository;
    protected $pcorcamforneRepository;
    protected $pcorcamvalRepository;
    protected $pcorcamjulgRepository;
    protected $licilicitemRepository;
    protected $liclicitemLoteRepository;

    /**
     * Construtor para injeção de dependências dos repositórios necessários.
     *
     * @param FaseDeLancesService $faseDeLancesService
     * @param JulgItemRepository $julgItemRepository
     * @param JulgItemHistRepository $julgItemHistRepository
     * @param JulgItemStatusRepository $itemStatusRepository
     * @param JulgLanceRepository $julgLanceRepository
     * @param JulgForneRepository $julgForneRepository
     * @param JulgForneHistRepository $julgForneHistRepository
     * @param JulgForneStatusRepository $julgForneStatusRepository
     * @param JulgParamRepository $julgParamRepository
     * @param PcorcamitemRepository $pcorcamitemRepository
     * @param PcorcamforneRepository $pcorcamforneRepository
     * @param PcorcamvalRepository $pcorcamvalRepository,
     * @param PcorcamjulgRepository $pcorcamjulgRepository,
     * @param LicilicitemRepository $licilicitemRepository
     * @param LiclicitemLoteRepository $liclicitemLoteRepository
     */
    public function __construct() {
        $this->faseDeLancesService = new FaseDeLancesService;
        $this->julgItemRepository = new JulgItemRepository;
        $this->julgItemHistRepository = new JulgItemHistRepository;
        $this->itemStatusRepository = new JulgItemStatusRepository;
        $this->julgLanceRepository = new JulgLanceRepository;
        $this->julgForneRepository = new JulgForneRepository;
        $this->julgForneHistRepository = new JulgForneHistRepository;
        $this->julgForneStatusRepository = new JulgForneStatusRepository;
        $this->julgParamRepository = new JulgParamRepository;
        $this->pcorcamitemRepository = new PcorcamitemRepository;
        $this->pcorcamforneRepository = new PcorcamforneRepository;
        $this->pcorcamvalRepository = new PcorcamvalRepository;
        $this->pcorcamjulgRepository = new PcorcamjulgRepository;
        $this->licilicitemRepository = new LicilicitemRepository;
        $this->liclicitemLoteRepository = new LiclicitemLoteRepository;
    }

    /**
     * Obtém o preço de referência para uma licitação específica.
     *
     * Esta função utiliza o repositório de itens de licitação (`licilicitemRepository`) para recuperar 
     * o preço de referência para a licitação com o código fornecido. O preço de referência é 
     * normalmente utilizado para comparações com as propostas apresentadas.
     *
     * @param int $liclicitaCodigo Código da licitação para a qual se deseja obter o preço de referência.
     * @param int $numeroLoteCodigo Código do lote da licitação para a qual se deseja obter o preço de referência.
     *
     * @return mixed Retorna o preço de referência associado à licitação, ou `null` caso não seja encontrado.
     */
    public function obterPrecoReferencia($liclicitaCodigo, $numeroLoteCodigo)
    {
        return $this->licilicitemRepository->getReferencePriceLot($liclicitaCodigo, $numeroLoteCodigo);
    }

    /**
     * Obtém a lista de fornecedores e propostas para uma licitação específica.
     * Utiliza cache para otimizar a consulta.
     *
     * @param int $liclicitaCodigo O código da licitação.
     * @param int $numeroLoteCodigo O código do lote da licitação.
     * @param int $valorDeReferencia O valor de referência utilizado para determinar, com base nas regras de parâmetros, quais propostas estão habilitadas a participar, considerando os limites de tolerância para valores excedentes e carência.
     * @param boolean $ignorarCache Indica se o cache deve ser ignorado, geralmente utilizado em rotinas de atualização e persistência de dados no banco.
     * 
     * @return \Illuminate\Support\Collection A coleção de resultados da consulta.
     */
    public function obterListaDeFornecedoresEProposta($liclicitaCodigo, $numeroLoteCodigo, $valorDeReferencia = null, $ignorarCache = false)
    {
        try {

            if ($ignorarCache) {
                return $this->licilicitemRepository->getProposalSupplierListLots($liclicitaCodigo, $numeroLoteCodigo, $valorDeReferencia);
            }

            $cacheKey = "fornecedores_propostas_lotes_{$liclicitaCodigo}_{$numeroLoteCodigo}";

            return Cache::remember($cacheKey, 5, function () use ($liclicitaCodigo, $numeroLoteCodigo, $valorDeReferencia) {
                return $this->licilicitemRepository->getProposalSupplierListLots($liclicitaCodigo, $numeroLoteCodigo, $valorDeReferencia);
            });

        } catch (\Exception $e) {
            throw new JulgamentoException("#JUL01 - Exceção ao obter fornecedores e propostas: " . $e->getMessage() . "");
        }
    }

    /**
     * Obtém o código do lote de julgamento para uma licitação específica e um lote de licitação.
     *
     * Esta função invoca o repositório responsável por obter o código do lote de orçamento relacionado
     * à licitação e ao lote de licitação fornecidos como parâmetros.
     *
     * @param int $numeroloteCodigo O código do lote da licitação.
     * 
     * @return mixed O código do lote de orçamento correspondente ao lote de julgamento.
     */
    public function obterCodigoDoItemDeJulgamento($numeroloteCodigo)
    {
        try {

            return $this->julgItemRepository->findLotBudget($numeroloteCodigo);

        } catch (\Exception $e) {

            throw new JulgamentoException("#JUL02 - Exceção ao obter código do lote de julgamento: " . $e->getMessage());

        }
    }

    /**
     * Obtém os dados do lote selecionado com base no código do lote da licitação.
     * 
     * Esta função interage com o repositório `liclicitemLoteRepository` para buscar os dados de um lote específico
     * da licitação a partir dos parâmetros fornecidos. Caso ocorra um erro durante o processo, uma exceção
     * personalizada `JulgamentoException` será lançada.
     *
     * @param int $numeroloteCodigo Código do lote.
     * @return mixed Dados do lote selecionado, retornados pelo repositório.
     * 
     * @throws JulgamentoException Se ocorrer um erro ao obter os dados do lote selecionado.
     */
    public function obterDadosDoLotesSelecionado($numeroloteCodigo)
    {
        try {

            return $this->liclicitemLoteRepository->getDataLotsSelected($numeroloteCodigo);

        } catch (\Exception $e) {

            throw new JulgamentoException("#JUL213 - Exceção ao obter dados do lote selecionado: " . $e->getMessage());

        }
    }

    /**
     * Obtém o próximo fornecedor válido para registrar um lance em uma licitação.
     * 
     * A função percorre a lista de fornecedores e suas propostas, encontra o último lance registrado e
     * retorna o código do próximo fornecedor disponível para registrar um lance, considerando o status
     * de cada fornecedor.
     * 
     * Se o código do lote de orçamento não for informado, a função tenta obter esse código
     * a partir do lote de julgamento. Caso contrário, um erro é lançado se não for possível encontrar
     * um fornecedor válido.
     *
     * @param int $liclicitaCodigo O código da licitação.
     * @param int $liclicitemCodigo O código do lote da licitação.
     * @param int|null $pcorcamitemCodigo O código do lote de orçamento (opcional).
     * 
     * @return int O código do próximo fornecedor disponível para registrar um lance.
     * 
     * @throws JulgamentoException Se não for possível encontrar um fornecedor válido.
     */
    public function obterProximoFornecedorParaLance($liclicitaCodigo, $numeroloteCodigo, $julgItemCodigo = null, $valorDeReferencia = null, $ignorarCache = false)
    {
        try {

            $listaDeFornecedoresEProposta = $this->obterListaDeFornecedoresEProposta($liclicitaCodigo, $numeroloteCodigo, $valorDeReferencia, $ignorarCache);

            if(empty($julgItemCodigo)) {

                $julgItemObject = $this->obterCodigoDoItemDeJulgamento($numeroloteCodigo);

                if (!empty($julgItemObject)) {
                    $julgItemCodigo = $julgItemObject->l30_codigo;
                }

            }

            if(empty($julgItemCodigo)) {

                return $listaDeFornecedoresEProposta[0]->pc21_orcamforne;

            } else {

                $ultimoLance = $this->julgLanceRepository->findLastBid($julgItemCodigo);

                if (empty($ultimoLance)) {

                    return $listaDeFornecedoresEProposta[0]->pc21_orcamforne;
                    
                } else {

                    $listaDeFornecedores = collect($listaDeFornecedoresEProposta)->map(function ($item) {
                        return $item->pc21_orcamforne;
                    })->toArray();
    
                    $ultimoFornecedor = $this->julgForneRepository->findId($ultimoLance->l32_julgforne);
                    $indiceAtual = array_search($ultimoFornecedor->l34_orcamforne, $listaDeFornecedores);
            
                    $totalFornecedores = count($listaDeFornecedores);
                    $totalFornecedoresInaptos = 0;
                    $tentativas = 0;
            
                    do {
    
                        $indiceAtual = ($indiceAtual + 1) % $totalFornecedores;
                        $proximoFornecedorCode = $listaDeFornecedores[$indiceAtual];

                        $proximoFornecedor = $this->pcorcamforneRepository->findId($proximoFornecedorCode);

                        if (empty($numeroloteCodigo)) {
                            $julgItemObject = $this->obterCodigoDoItemDeJulgamento($numeroloteCodigo);

                            // numerolote alterar
                            if (!empty($julgItemObject)) {
                                $numeroloteCodigo = $julgItemObject->l30_numerolote;
                            }
                        }

                        $proximoJulgForne = $this->julgForneRepository->findLotSupplier($proximoFornecedorCode, $numeroloteCodigo);

                        if (!empty($proximoFornecedor)) {
                            if (!empty($proximoJulgForne)) {
                                if ($proximoJulgForne->l34_julgfornestatus == 1) {
                                    return $proximoJulgForne->l34_orcamforne;
                                } else { 
                                    $totalFornecedoresInaptos++;
                                }
                            } else {
                                return $proximoFornecedor->pc21_orcamforne;
                            }
                        }
                        
                        $tentativas++;
            
                    } while ($tentativas < $totalFornecedores);
            
                    if ($totalFornecedoresInaptos !== $totalFornecedores) {
                        throw new JulgamentoException("#JUL05 - Exceção: Nenhum fornecedor válido encontrado para o lote de orçamento {$julgItemCodigo}.");
                    }

                }

            }

        } catch (\Exception $e) {

            throw new JulgamentoException("#JUL06 - Exceção ao encontrar o próximo fornecedor para lance: " . $e->getMessage());

        }
    }

    /**
     * Obtém o último lance registrado para um fornecedor e um lote de orçamento específicos.
     *
     * Esta função busca o lance mais recente de um fornecedor para um lote de orçamento fornecido,
     * verificando se tanto o fornecedor quanto o lote de orçamento existem antes de realizar a consulta.
     * 
     * Se o fornecedor ou o lote de orçamento não forem encontrados, a função retorna `false`.
     *
     * @param int $pcorcamforneCodigo O código do fornecedor.
     * @param int $pcorcamitemCodigo O código do lote de orçamento.
     * 
     * @return mixed O último lance registrado ou `false` caso o fornecedor ou o lote de orçamento não sejam encontrados.
     */
    public function obterLance($pcorcamforneCodigo, $numeroloteCodigo)
    {
        try {

            $listaJulgForne = $this->julgForneRepository->findLotSupplier($pcorcamforneCodigo, $numeroloteCodigo);
            $julgItem = $this->julgItemRepository->findLotBudget($numeroloteCodigo);

            if (!empty($julgItem) && !empty($listaJulgForne)) {

                return $this->julgLanceRepository->findLastBidSupplierNotNull($listaJulgForne->l34_codigo, $julgItem->l30_codigo);

            } else {

                return false;

            }

        } catch (\Exception $e) {

            throw new JulgamentoException("#JUL07 - Exceção ao obter lance: " . $e->getMessage());

        }
    }

    /**
     * Obtém uma lista de fornecedores que possuem o modelo de microempresa para um lote específico em uma licitação.
     *
     * Esta função utiliza o repositório `licilicitemRepository` para buscar os fornecedores associados a um lote 
     * de licitação e filtra os que possuem o modelo de microempresa. Em caso de falha, lança uma exceção 
     * personalizada para lidar com erros no processo de obtenção de dados.
     *
     * @param mixed $liclicita Identificador da licitação para o qual os fornecedores serão buscados.
     * @param mixed $liclicitem Identificador do lote de licitação relacionado.
     *
     * @return mixed Retorna a lista de fornecedores com modelo de microempresa.
     *
     * @throws JulgamentoException Caso ocorra um erro durante o processo, lança uma exceção contendo detalhes do erro.
     */
    public function obterFornecedoresComModeloDeMicroempresa($liclicita, $numeroloteCodigo, $valorDeReferencia)
    {
        try {
            
            return $this->licilicitemRepository->getSuppliersWithAMicroEnterpriseLot($liclicita, $numeroloteCodigo, $valorDeReferencia);

        } catch (\Exception $e) {

            throw new JulgamentoException(
                sprintf(
                    "#JU08 - Exceção ao obter fornecedores com modelo de microempresa. Licitação: %s, Item: %s. Detalhes do erro: %s - #JU1",
                    $liclicita,
                    $numeroloteCodigo,
                    $e->getMessage()
                )
            );

        }
    }

    /**
     * Registra um lance para um fornecedor e um lote de orçamento específicos.
     *
     * Esta função verifica se o lote de orçamento e o fornecedor existem. Se algum deles não existir, 
     * ele cria um novo registro para o lote de orçamento e/ou fornecedor com o status correspondente.
     * Depois, registra o lance no repositório de lances.
     * 
     * Caso haja algum erro ao registrar o lance, uma exceção é lançada.
     *
     * @param int $pcorcamforneCodigo O código do fornecedor.
     * @param int $numeroLote O código do lote de orçamento.
     * @param float $valorLance O valor do lance a ser registrado.
     * @param int $liclicitaCodigo O código da licitação.
     * @param int $liclicitemCodigo O código dos itens licitação.
     * 
     * @return mixed O objeto do lance registrado.
     * 
     * @throws JulgamentoException Se ocorrer um erro ao registrar o lance.
     */
    public function registrarLance($liclicitaCodigo, $pcorcamforneCodigo, $valorLance, $numeroLote)
    {
        DB::beginTransaction();

        try {
            
            $julgItem = $this->julgItemRepository->findLotBudget($numeroLote);

            if (empty($julgItem)) {
                $julgItem = $this->registrarJulgItem($numeroLote, 'Em aberto');
            }

            $julgForne = $this->julgForneRepository->findLotSupplier($pcorcamforneCodigo, $numeroLote);

            if (empty($julgForne)) {
                $julgForne = $this->registrarJulgForne($pcorcamforneCodigo, $numeroLote, 'Normal');
            }

            $this->validaValorDoLance($julgItem->l30_codigo, $valorLance, $liclicitaCodigo, $numeroLote);

            $julgLance = $this->julgLanceRepository->create([
                'l32_julgitem' => $julgItem->l30_codigo,
                'l32_julgforne' => $julgForne->l34_codigo,
                'l32_lance' => $valorLance
            ]);

            if (!$julgLance) {
                throw new JulgamentoException("#JUL09 - Exceção ao registrar lance. Tente novamente.");
            }

            if ($valorLance === null) {
                $julgForneStatus = $this->julgForneStatusRepository->findLabel(mb_convert_encoding('Sem lance', 'UTF-8', 'ISO-8859-1'));
                $this->faseDeLancesService->alterarStatusFornecedor($julgForne->l34_codigo, $julgForneStatus->l35_codigo, mb_convert_encoding('O fornecedor foi desclassificado das próximas fases do pregão por não ter conseguido igualar ou superar o valor estipulado na sequência de lances.', 'UTF-8', 'ISO-8859-1'));
            }

            DB::commit();
            return $julgLance;

        } catch (\Exception $e) {
            
            DB::rollBack();
            throw new JulgamentoException("#JUL10 - Exceção ao registrar lance: " . $e->getMessage());

        }
    }

    /**
     * Reverte o último lance registrado para um lote de orçamento específico.
     *
     * Esta função busca o último lance registrado para o lote de orçamento fornecido. 
     * Se o lance existir, ele é deletado do repositório de lances. 
     * Caso contrário, nenhuma ação é tomada.
     * 
     * @param int $numeroLote O código do lote de orçamento.
     * 
     * @return void
     */
    public function reverterLance($numeroLote)
    {
        DB::beginTransaction();

        try {

            $julgItem = $this->julgItemRepository->findLotBudget($numeroLote);

            if (empty($julgItem)) {
                throw new JulgamentoException("#JUL11 - Exceção: Lote de orçamento não encontrado.");
            }

            $ultimoJulgLance = $this->julgLanceRepository->findLastBid($julgItem->l30_codigo);

            if (!empty($ultimoJulgLance)) {
                $this->julgLanceRepository->delete('l32_codigo', $ultimoJulgLance->l32_codigo);
            } else {
                throw new JulgamentoException("#JUL12 - Exceção: Nenhum lance encontrado para o lote de orçamento informado.");
            }

            if ($ultimoJulgLance->l32_lance === null) {
                $julgForneStatus = $this->julgForneStatusRepository->findLabel(mb_convert_encoding('Normal', 'UTF-8', 'ISO-8859-1'));
                $this->faseDeLancesService->alterarStatusFornecedor($ultimoJulgLance->l32_julgforne, $julgForneStatus->l35_codigo, mb_convert_encoding('Revertendo o lance que desclassificou o fornecedor das próximas fases do pregão por não ter conseguido igualar ou superar o valor estipulado na sequência de lances.', 'UTF-8', 'ISO-8859-1'));
            }

            DB::commit();

        } catch (\Exception $e) {
            
            DB::rollBack();
            throw new JulgamentoException("#JUL13 - Exceção ao reverter o lance: " . $e->getMessage());

        }
    }

    /**
     * Limpa todos os lances registrados para um lote de orçamento específico.
     *
     * Esta função remove todos os lances associados ao lote de orçamento fornecido.
     * Primeiro, busca o lote de orçamento com base no código fornecido e, em seguida,
     * exclui todos os lances registrados para esse lote.
     *
     * @param int $numeroLote O código do lote de orçamento.
     * 
     * @return void
     */
    public function limparLances($numeroLote)
    {
        $codigosItensLista = explode(",", $numeroLote);

        DB::beginTransaction();

        try {

            foreach ($codigosItensLista as $i => $value) {

                $julgItemRepository = $this->julgItemRepository->findLotBudget($value);
    
                if (empty($julgItemRepository)) {
                    throw new JulgamentoException("#JUL14 - Exceção: Lote de orçamento não encontrado.");
                }
    
                $lances = $this->julgLanceRepository->find('l32_julgitem', $value);

                if (!empty($lances)) {
                    $resultado = $this->julgLanceRepository->deleteBidItem($julgItemRepository->l30_codigo);
        
                    if ($resultado === false) {
                        throw new JulgamentoException("#JUL15 - Exceção ao limpar os lances do lote de orçamento.");
                    }
                }
                
                $julgForne = $this->julgForneRepository->find('l34_numerolote', $value);

                $codigoArray = [];
                foreach ($julgForne as $item) {
                    $codigoArray[] = $item->l34_codigo;
                }

                $julgForneHist = $this->julgForneHistRepository->deleteIn('l36_julgforne', $codigoArray);
                
                if ($julgForneHist === false) {
                    throw new JulgamentoException("#JUL121 - Exceção ao limpar os historicos dos fornecedores do julgamento do item.");
                }

                if ($julgForne->isNotEmpty()) {
                    
                    $julgForne = $this->julgForneRepository->delete('l34_numerolote', $value);
                    
                    if (empty($julgForne)) {
                        throw new JulgamentoException("#JUL18 - Exceção ao limpar os fornecedores do julgamento do item.");
                    }

                }

            }

            DB::commit();
            return true;

        } catch (\Exception $e) {

            DB::rollBack();
            throw new JulgamentoException("#JUL19 - Exceção ao limpar lances: " . $e->getMessage());

        }
    }

    /**
     * Altera o status de um lote de orçamento e registra o motivo da alteração.
     *
     * Esta função verifica se o lote de orçamento existe. Caso o lote não exista, ele será criado
     * com o status "Em aberto". Se o lote já existir, o status é alterado para o novo status fornecido.
     * Após a alteração, o motivo da alteração é registrado no histórico de status do lote.
     *
     * @param int $codigosItens O códigos dos itens.
     * @param int $novoStatus O código do novo status a ser atribuído ao lote de orçamento.
     * @param string $motivo O motivo para a alteração do status do lote de orçamento.
     * 
     * @return void
     */
    public function alterarStatusItem($codigosItens, $novoStatus, $motivo)
    {
        // Pega os ids dos itens do orçamento e os transforma em um array
        $codigosItensLista = explode(",", $codigosItens);
    
        // Inicia uma transação no banco de dados
        DB::beginTransaction();
    
        try {

            foreach ($codigosItensLista as $i => $codigoItem) {

                // Busca a existência do lote no orçamento pelo código
                $liclicitemLote = Liclicitemlote::where('l04_numerolote', $codigoItem)->first();

                if (empty($liclicitemLote)) {
                    throw new JulgamentoException("#JUL20 - Exceção: Lote de orçamento com código {$codigoItem} não encontrado.");
                }

                $pcorcamitens = $this->liclicitemLoteRepository->getOrcamItemByLoteNumber($codigoItem);

                if (empty($pcorcamitens)) {
                    throw new JulgamentoException("#JUL20 - Exceção: Lote de orçamento com código {$codigoItem} não encontrado.");
                }

                // Busca o lote de julgamento associado ao orçamento
                $julgItem = $this->julgItemRepository->findLotBudget($liclicitemLote->l04_numerolote);
    
                if (!empty($julgItem) && $julgItem->l30_julgitemstatus == 8 && $novoStatus !== 8) {

                    foreach ($pcorcamitens as $i => $pcorcamitem) {

                        $pcorcamvalItens = $this->pcorcamvalRepository->find('pc23_orcamitem', $pcorcamitem->pc22_orcamitem);
    
                        if (!empty($pcorcamvalItens)) {
                            $pcorcamval = $this->pcorcamvalRepository->delete('pc23_orcamitem', $pcorcamitem->pc22_orcamitem);
        
                            if ($pcorcamval === false) {
                                throw new JulgamentoException(
                                    sprintf(
                                        "#JU21 - Exceção ao tentar excluir os registros de pcorcamval. Código do lote: %s. Verifique os logs e o estado do banco de dados.",
                                        $pcorcamitem->pc22_orcamitem
                                    )
                                );
                            }
                        }
        
                        $pcorcamjulgItens = $this->pcorcamjulgRepository->find('pc24_orcamitem', $pcorcamitem->pc22_orcamitem);
        
                        if (!empty($pcorcamjulgItens)) {
                            $pcorcamjulg = $this->pcorcamjulgRepository->deletePcorcamitemRecords($pcorcamitem->pc22_orcamitem);
        
                            if ($pcorcamjulg === false) {
                                throw new JulgamentoException(
                                    sprintf(
                                        "#JU22 - Exceção ao tentar excluir registros de pcorcamitem para o código do lote: %s. A operação de exclusão retornou false. Verifique os dados no banco de dados e os logs para mais detalhes.",
                                        $pcorcamitem->pc22_orcamitem
                                    )
                                );
                            }
                        }
                        
                    }
                }

                if (!empty($julgItem) && $julgItem->l30_julgitemstatus == 7 && $novoStatus !== 2) {

                    if (!empty($this->julgForneRepository->find('l34_numerolote', $codigoItem))) {
        
                        $updateSuppliers = $this->julgForneRepository->updateStatusSuppliersFromBestBidToNoBidLot($codigoItem, ['l34_julgfornestatus' => 3]);
        
                        if (empty($updateSuppliers)) {
                            throw new JulgamentoException(
                                sprintf(
                                    "#JU23 - Exceção ao atualizar o status dos fornecedores para o código do lote: %s. Nenhum registro foi alterado. Verifique o estado dos dados no banco de dados e os logs da aplicação para mais detalhes.",
                                    $codigoItem
                                )
                            );
                        }
                    }
                }

                // Caso o lote de julgamento não exista, cria um novo com o status "Em Aberto"
                if (empty($julgItem)) {

                    // Busca o status padrão "Em Aberto"
                    $statusJulgItem = $this->itemStatusRepository->findLabel(mb_convert_encoding('Em aberto', 'UTF-8', 'ISO-8859-1'));
    
                    if (empty($statusJulgItem)) {
                        throw new JulgamentoException("#JUL24 - Exceção: Status Em aberto não encontrado.");
                    }
    
                    // Cria o lote de julgamento no banco de dados
                    $julgItem = $this->julgItemRepository->create([
                        'l30_numerolote' => $liclicitemLote->l04_numerolote,
                        'l30_julgitemstatus' => $statusJulgItem->l31_codigo,
                    ]);
    
                    if (!$julgItem) {
                        throw new JulgamentoException("#JUL25 - Exceção ao criar o lote de orçamento.");
                    }
    
                    // Busca o novo status para atualização
                    $novoStatusJulgItem = $this->itemStatusRepository->findId($novoStatus);
    
                    if (empty($novoStatusJulgItem)) {
                        throw new JulgamentoException("#JUL26 - Exceção: Novo status informado é inválido.");
                    }
    
                    // Atualiza o status do lote de julgamento
                    $resultado = $this->julgItemRepository->update($julgItem->l30_codigo, [
                        'l30_julgitemstatus' => $novoStatusJulgItem->l31_codigo,
                    ]);
    
                    if (!$resultado) {
                        throw new JulgamentoException("#JUL27 - Exceção ao atualizar o status do lote de orçamento.");
                    }

                } else {

                    // Caso o lote de julgamento já exista, busca o novo status para atualização
                    $novoStatusJulgItem = $this->itemStatusRepository->findId($novoStatus);
    
                    if (empty($novoStatusJulgItem)) {
                        throw new JulgamentoException("#JUL28 - Exceção: Novo status informado é inválido.");
                    }
    
                    // Atualiza o status do lote de julgamento existente
                    $resultado = $this->julgItemRepository->update($julgItem->l30_codigo, [
                        'l30_julgitemstatus' => $novoStatusJulgItem->l31_codigo,
                    ]);
    
                    if (!$resultado) {
                        throw new JulgamentoException("#JUL29 - Exceção ao atualizar o status do lote de orçamento.");
                    }

                }

                // Cria um registro no histórico para o lote atualizado
                $historico = $this->julgItemHistRepository->create([
                    'l33_julgitem' => $julgItem->l30_codigo,
                    'l33_julgitemstatus' => $julgItem->l30_julgitemstatus,
                    'l33_motivo' => $motivo,
                ]);
    
                if (!$historico) {
                    throw new JulgamentoException("#JUL30 - Exceção ao registrar o histórico do lote.");
                }
            }
    
            DB::commit();
            return true;

        } catch (\Exception $e) {

            DB::rollBack();
            throw new JulgamentoException("#JUL31 - Exceção ao alterar status do lote: " . $e->getMessage());

        }
    }     

    public function atualizaStatusFornecedor($numeroLote, $pcorcamforneCodigo, $statusFonecedor, $motivoFornecedor)
    {
        try {

            $julgForne = $this->julgForneRepository->findLotSupplier($pcorcamforneCodigo, $numeroLote);

            if (empty($julgForne)) {
                $julgForne = $this->registrarJulgForne($pcorcamforneCodigo, $numeroLote, 'Normal');
            }

            $this->faseDeLancesService->alterarStatusFornecedor($julgForne->l34_codigo, $statusFonecedor, $motivoFornecedor);

        } catch (\Exception $e) {

            throw new JulgamentoException("#JUL32 - Exceção ao alterar status do fornecedor: " . $e->getMessage());

        }
    }

    /**
     * Finaliza o processo de julgamento de um lote de orçamento em uma licitação.
     *
     * Este método realiza as seguintes operações:
     * - Obtém fornecedores vinculados ao lote de orçamento.
     * - Recupera e organiza os detalhes dos lances, ordenando-os pelo menor valor.
     * - Gera a pontuação e armazena os dados processados nos repositórios correspondentes.
     * - Atualiza o status dos fornecedores e do lote de orçamento para "Melhor Proposta" e "Julgado", respectivamente.
     * - Remove registros antigos de lances e julgamentos, substituindo-os pelos novos dados processados.
     * - Garante a integridade do processo com transações de banco de dados.
     *
     * @param mixed $liclicitaCodigo Código da licitação.
     * @param mixed $liclicitemCodigo Código do lote da licitação.
     * @param mixed $numeroLote Código do lote de orçamento.
     * 
     * @return bool Retorna `true` se o processo for concluído com sucesso.
     * 
     * @throws JulgamentoException Em caso de falhas em qualquer etapa do processo.
     */
    public function finalizar($numeroLote)
    {
        DB::beginTransaction();
    
        try {
            
            $julgFornecedores = $this->julgForneRepository->findLotSuppliers($numeroLote);

            if (empty($julgFornecedores)) {
                throw new JulgamentoException(
                    sprintf(
                        "#JU36 - Exceção ao buscar fornecedores para o lote com código: %s. Nenhum fornecedor encontrado.",
                        $numeroLote
                    )
                );
            }

            foreach ($julgFornecedores as $k => $julgFornecedor) {
                $statusFornecedor = $this->julgForneStatusRepository->findLabel(mb_convert_encoding('Melhor Proposta', 'UTF-8', 'ISO-8859-1'));
                $this->faseDeLancesService->alterarStatusFornecedor($julgFornecedor->l34_codigo, $statusFornecedor->l35_codigo, mb_convert_encoding('O status dos fornecedores foram atualizados para melhor lance após a conclusão da rotina de julgamento.', 'UTF-8', 'ISO-8859-1'));
            }

            $statusItem = $this->itemStatusRepository->findLabel(mb_convert_encoding('Aguardando Readequação', 'UTF-8', 'ISO-8859-1'));
            $statusItemUpdate = $this->alterarStatusItem($numeroLote, $statusItem->l31_codigo, mb_convert_encoding('O status do lote foi atualizado para Aguardando Readequação após a conclusão da rotina de julgamento.', 'UTF-8', 'ISO-8859-1'));

            if (empty($statusItemUpdate)) {
                throw new JulgamentoException("#JUL40 - Exceção ao atualizar o status do lote após a conclusão da rotina de julgamento.");
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {

            DB::rollBack();
            throw new JulgamentoException("#JUL43 - Exceção na rotina de finalização de julgamento: " . $e->getMessage());

        }
    }

    /**
     * Libera fornecedores com modelo de microempresa para um lote de licitação.
     *
     * - Obtém fornecedores utilizando `obterFornecedoresComModeloDeMicroempresa`.
     * - Valida se há fornecedores e se o status "Normal" existe no repositório.
     * - Atualiza o status de cada fornecedor encontrado para "Normal".
     *
     * @param mixed $liclicita ID da licitação.
     * @param mixed $liclicitem ID do lote de licitação.
     * 
     * @throws JulgamentoException Em caso de erro durante o processo.
     */
    public function liberarMicroEmpresas($liclicita, $numeroLote, $valorDeReferencia=null)
    {
        try {

            $fornecedoresComModeloDeMicroempresa = $this->obterFornecedoresComModeloDeMicroempresa($liclicita, $numeroLote, $valorDeReferencia);
            
            if (empty($fornecedoresComModeloDeMicroempresa)) {
                throw new JulgamentoException(
                    sprintf(
                        "#JU44 - Exceção ao obter fornecedores com modelo de microempresa. Licitação: %s, Item: %s. Nenhum fornecedor encontrado.",
                        $liclicita,
                        $numeroLote
                    )
                );
            }

            $statusFornecedor = $this->julgForneStatusRepository->findLabel(mb_convert_encoding('Normal', 'UTF-8', 'ISO-8859-1'));

            if (empty($statusFornecedor)) {
                throw new JulgamentoException(
                    "#JU45 - Exceção ao buscar o status do fornecedor com o valor 'Normal'. Nenhum status encontrado. Verifique os dados no banco de dados.",
                );
            }

            foreach ($fornecedoresComModeloDeMicroempresa as $i => $fornecedorMicroempresa) {

                $julgForne = $this->julgForneRepository->findLotSupplier($fornecedorMicroempresa->pc21_orcamforne, $fornecedorMicroempresa->l04_numerolote);

                if (empty($julgForne)) {
                    throw new JulgamentoException(
                        sprintf(
                            "#JU46 - Exceção ao buscar fornecedor para o lote com código de fornecedor: %s e código de item: %s. Nenhum fornecedor encontrado.",
                            $fornecedorMicroempresa->pc21_orcamforne,
                            $fornecedorMicroempresa->pc22_orcamitem
                        )
                    );
                }

                $this->faseDeLancesService->alterarStatusFornecedor($julgForne->l34_codigo, $statusFornecedor->l35_codigo, mb_convert_encoding('A alteração de status foi efetuada em razão da liberação de novos lances, conforme estabelecido pela Lei 123/2006.', 'UTF-8', 'ISO-8859-1'));
            }

        } catch (\Exception $e) {

            throw new JulgamentoException(
                sprintf(
                    "#JU47 - Exceção ao liberar microempresas para a licitação %s e lote %s. Detalhes do erro: %s",
                    $liclicita,
                    $numeroLote,
                    $e->getMessage()
                )
            );
            
        }
    }

    /**
     * Registra o julgamento de um lote de orçamento.
     *
     * Esta função cria um registro no repositório de itens com os detalhes do julgamento,
     * incluindo o código do lote e o status associado.
     *
     * @param int $numeroLote Código do lote no orçamento.
     * @param string $statusLabel Rótulo do status do lote.
     *
     * @return mixed Retorna o registro criado do lote de orçamento.
     *
     * @throws JulgamentoException Se o status do lote não for encontrado ou ocorrer um erro na criação.
     */
    private function registrarJulgItem($numeroLote, $statusLabel)
    {
        $statusItem = $this->itemStatusRepository->findLabel(mb_convert_encoding($statusLabel, 'UTF-8', 'ISO-8859-1'));

        if (empty($statusItem)) {
            throw new JulgamentoException("#JUL52 - Exceção: Status do lote de orçamento não encontrado.");
        }

        $julgItem = $this->julgItemRepository->create([
            'l30_numerolote' => $numeroLote,
            'l30_julgitemstatus' => $statusItem->l31_codigo,
        ]);

        if (!$julgItem) {

            throw new JulgamentoException("#JUL53 - Exceção ao criar o lote de orçamento.");

        } else {

            return $julgItem;

        }
    }

    /**
     * Registra o julgamento de um fornecedor para um lote de orçamento.
     *
     * Esta função cria um registro no repositório de fornecedores com os detalhes do 
     * julgamento, incluindo o código do fornecedor, o código do lote e o status associado.
     *
     * @param int $pcorcamforneCodigo Código do fornecedor no orçamento.
     * @param int $numeroLote Código do lote no orçamento.
     * @param string $statusLabel (Opcional) Rótulo do status do fornecedor, padrão é "Normal".
     *
     * @return mixed Retorna o registro criado do fornecedor.
     *
     * @throws JulgamentoException Se o status do fornecedor não for encontrado ou ocorrer um erro na criação.
     */
    private function registrarJulgForne($pcorcamforneCodigo, $numeroLote, $statusLabel="Normal")
    {
        $statusFornecedor = $this->julgForneStatusRepository->findLabel(mb_convert_encoding($statusLabel, 'UTF-8', 'ISO-8859-1'));

        if (empty($statusFornecedor)) {
            throw new JulgamentoException("#JUL54 - Exceção: Status do fornecedor não encontrado.");
        }

        $julgForne = $this->julgForneRepository->create([
            'l34_orcamforne' => $pcorcamforneCodigo,
            'l34_numerolote' => $numeroLote,
            'l34_julgfornestatus' => $statusFornecedor->l35_codigo,
        ]);

        if (!$julgForne) {

            throw new JulgamentoException("#JUL55 - Exceção ao criar o fornecedor.");

        } else {

            return $julgForne;

        }
    }

    /**
     * Valida o valor de um lance fornecido para um lote em uma licitação.
     *
     * Esta função verifica se o valor do lance é válido, seguindo as regras:
     * - O valor deve ser menor que a menor proposta inicial ou lance anterior, 
     *   considerando a diferença mínima permitida (`l13_difminlance`).
     *
     * @param int $julgItemCodigo Código do lote em julgamento.
     * @param float $valorLance Valor do lance fornecido.
     * @param int $liclicitaCodigo Código da licitação.
     * @param int $liclicitemCodigo Código do lote na licitação.
     *
     * @throws JulgamentoException Se o valor do lance não for válido de acordo com as regras.
     */
    private function validaValorDoLance($julgItemCodigo, $valorLance, $liclicitaCodigo, $numeroLote)
    {
        $param = $this->faseDeLancesService->obterParametros();

        $ultimoLance = $this->julgLanceRepository->findLastBidNotNull($julgItemCodigo);

        if (empty($ultimoLance)) {

            $ultimaProposta = $this->licilicitemRepository->getTheLowestBidLot($liclicitaCodigo, $numeroLote, $param->l13_clapercent);

            if ($valorLance > (floatval($ultimaProposta->l224_vlrun) - floatval($param->l13_difminlance))) {
                throw new JulgamentoException("#JUL56 - Exceção: O fornecedor deve apresentar um lance inferior ao último registrado, respeitando o valor mínimo estipulado para redução entre lances.");
            }

        } else {
            
            if ($valorLance !== null && $valorLance > ($ultimoLance->l32_lance - $param->l13_difminlance)) {
                throw new JulgamentoException("#JUL57 - Exceção: O fornecedor não pode enviar um lance maior que o menor lance.");
            }

        }
    }
}
