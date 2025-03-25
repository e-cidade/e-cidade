<?php

namespace App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance;

use App\Exceptions\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\JulgamentoException;
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
use App\Repositories\Patrimonial\Licitacao\PcorcamforneRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JulgamentoItemService
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
     */
    public function __construct(
        FaseDeLancesService $faseDeLancesService,
        JulgItemRepository $julgItemRepository,
        JulgItemHistRepository $julgItemHistRepository,
        JulgItemStatusRepository $itemStatusRepository,
        JulgLanceRepository $julgLanceRepository,
        JulgForneRepository $julgForneRepository,
        JulgForneHistRepository $julgForneHistRepository,
        JulgForneStatusRepository $julgForneStatusRepository,
        JulgParamRepository $julgParamRepository,
        PcorcamitemRepository $pcorcamitemRepository,
        PcorcamforneRepository $pcorcamforneRepository,
        PcorcamvalRepository $pcorcamvalRepository,
        PcorcamjulgRepository $pcorcamjulgRepository,
        LicilicitemRepository $licilicitemRepository
    ) {
        $this->faseDeLancesService = $faseDeLancesService;
        $this->julgItemRepository = $julgItemRepository;
        $this->julgItemHistRepository = $julgItemHistRepository;
        $this->itemStatusRepository = $itemStatusRepository;
        $this->julgLanceRepository = $julgLanceRepository;
        $this->julgForneRepository = $julgForneRepository;
        $this->julgForneHistRepository = $julgForneHistRepository;
        $this->julgForneStatusRepository = $julgForneStatusRepository;
        $this->julgParamRepository = $julgParamRepository;
        $this->pcorcamitemRepository = $pcorcamitemRepository;
        $this->pcorcamforneRepository = $pcorcamforneRepository;
        $this->pcorcamvalRepository = $pcorcamvalRepository;
        $this->pcorcamjulgRepository = $pcorcamjulgRepository;
        $this->licilicitemRepository = $licilicitemRepository;
    }

    /**
     * Obtém o preço de referência para uma licitação específica.
     *
     * Esta função utiliza o repositório de itens de licitação (`licilicitemRepository`) para recuperar 
     * o preço de referência para a licitação com o código fornecido. O preço de referência é 
     * normalmente utilizado para comparações com as propostas apresentadas.
     *
     * @param int $liclicitaCodigo Código da licitação para a qual se deseja obter o preço de referência.
     * @param int $liclicitemCodigo Código do item da licitação para a qual se deseja obter o preço de referência.
     *
     * @return mixed Retorna o preço de referência associado à licitação, ou `null` caso não seja encontrado.
     */
    public function obterPrecoReferencia($liclicitaCodigo, $liclicitemCodigo)
    {
        return $this->licilicitemRepository->getReferencePrice($liclicitaCodigo, $liclicitemCodigo);
    }

    /**
     * Obtém a lista de fornecedores e propostas para uma licitação específica.
     * Utiliza cache para otimizar a consulta.
     *
     * @param int $liclicitaCodigo O código da licitação.
     * @param int $liclicitemCodigo O código do item da licitação.
     * @param int $valorDeReferencia O valor de referência utilizado para determinar, com base nas regras de parâmetros, quais propostas estão habilitadas a participar, considerando os limites de tolerância para valores excedentes e carência.
     * @param boolean $ignorarCache Indica se o cache deve ser ignorado, geralmente utilizado em rotinas de atualização e persistência de dados no banco.
     * 
     * @return \Illuminate\Support\Collection A coleção de resultados da consulta.
     */
    public function obterListaDeFornecedoresEProposta($liclicitaCodigo, $liclicitemCodigo, $valorDeReferencia = null, $ignorarCache = false)
    {
        try {

            if ($ignorarCache) {
                return $this->licilicitemRepository->getProposalSupplierList($liclicitaCodigo, $liclicitemCodigo, $valorDeReferencia);
            }

            $cacheKey = "fornecedores_propostas_{$liclicitaCodigo}_{$liclicitemCodigo}";

            return Cache::remember($cacheKey, 5, function () use ($liclicitaCodigo, $liclicitemCodigo, $valorDeReferencia) {
                return $this->licilicitemRepository->getProposalSupplierList($liclicitaCodigo, $liclicitemCodigo, $valorDeReferencia);
            });

        } catch (\Exception $e) {
            throw new JulgamentoException("#JU01 - Exceção ao obter fornecedores e propostas: " . $e->getMessage() . "");
        }
    }

    /**
     * Obtém o código do item de julgamento para uma licitação específica e um item de licitação.
     *
     * Esta função invoca o repositório responsável por obter o código do item de orçamento relacionado
     * à licitação e ao item de licitação fornecidos como parâmetros.
     *
     * @param int $liclicitaCodigo O código da licitação.
     * @param int $liclicitemCodigo O código do item da licitação.
     * 
     * @return mixed O código do item de orçamento correspondente ao item de julgamento.
     */
    public function obterCodigoDoItemDeJulgamento($liclicitaCodigo, $liclicitemCodigo)
    {
        try {

            return $this->licilicitemRepository->getJulgItemCode($liclicitaCodigo, $liclicitemCodigo);

        } catch (\Exception $e) {

            throw new JulgamentoException("#JU02 - Exceção ao obter código do item de julgamento: " . $e->getMessage());

        }
    }

    /**
     * Obtém os dados do item selecionado com base no código da licitação e o código do item da licitação.
     * 
     * Esta função interage com o repositório `liclicitemRepo` para buscar os dados de um item específico
     * da licitação a partir dos parâmetros fornecidos. Caso ocorra um erro durante o processo, uma exceção
     * personalizada `JulgamentoException` será lançada.
     *
     * @param int $liclicitaCodigo Código da licitação.
     * @param int $liclicitemCodigo Código do item da licitação.
     * @return mixed Dados do item selecionado, retornados pelo repositório.
     * 
     * @throws JulgamentoException Se ocorrer um erro ao obter os dados do item selecionado.
     */
    public function obterDadosDoItemSelecionado($liclicitaCodigo, $liclicitemCodigo)
    {
        try {

            return $this->licilicitemRepository->getDataItemSelected($liclicitaCodigo, $liclicitemCodigo);

        } catch (\Exception $e) {

            throw new JulgamentoException("#JU03 - Exceção ao obter dados do item selecionado: " . $e->getMessage());

        }
    }

    /**
     * Obtém o código do item de orçamento com base no código da licitação e o código do item da licitação.
     * 
     * Esta função consulta o repositório `liclicitemRepo` para recuperar o código do item de orçamento
     * relacionado à licitação e item fornecidos. Caso ocorra um erro, uma exceção personalizada
     * `JulgamentoException` será lançada para tratar a falha.
     *
     * @param int $liclicitaCodigo Código da licitação.
     * @param int $liclicitemCodigo Código do item da licitação.
     * @return mixed Código do item de orçamento.
     * 
     * @throws JulgamentoException Se ocorrer um erro ao obter o código do item de orçamento.
     */
    public function obterCodigoDoItemDeOrcamento($liclicitaCodigo, $liclicitemCodigo)
    {
        try {

            return $this->licilicitemRepository->getOrcamItemCode($liclicitaCodigo, $liclicitemCodigo);

        } catch (\Exception $e) {

            throw new JulgamentoException("#JU04 - Exceção ao obter código do item do orcamento: " . $e->getMessage());

        }
    }

    /**
     * Obtém o próximo fornecedor válido para registrar um lance em uma licitação.
     * 
     * A função percorre a lista de fornecedores e suas propostas, encontra o último lance registrado e
     * retorna o código do próximo fornecedor disponível para registrar um lance, considerando o status
     * de cada fornecedor.
     * 
     * Se o código do item de orçamento não for informado, a função tenta obter esse código
     * a partir do item de julgamento. Caso contrário, um erro é lançado se não for possível encontrar
     * um fornecedor válido.
     *
     * @param int $liclicitaCodigo O código da licitação.
     * @param int $liclicitemCodigo O código do item da licitação.
     * @param int|null $pcorcamitemCodigo O código do item de orçamento (opcional).
     * 
     * @return int O código do próximo fornecedor disponível para registrar um lance.
     * 
     * @throws JulgamentoException Se não for possível encontrar um fornecedor válido.
     */
    public function obterProximoFornecedorParaLance($liclicitaCodigo, $liclicitemCodigo, $julgItemCodigo = null, $valorDeReferencia = null, $ignorarCache = false)
    {
        try {

            $pcorcamItemCodigo = null;

            $listaDeFornecedoresEProposta = $this->obterListaDeFornecedoresEProposta($liclicitaCodigo, $liclicitemCodigo, $valorDeReferencia, $ignorarCache);

            if(empty($julgItemCodigo)) {

                $julgItemObject = $this->obterCodigoDoItemDeJulgamento($liclicitaCodigo, $liclicitemCodigo);

                if (!empty($julgItemObject)) {
                    $julgItemCodigo = $julgItemObject->l30_codigo;
                    $pcorcamItemCodigo = $julgItemObject->pc22_orcamitem;
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

                        if (empty($pcorcamItemCodigo)) {
                            $pcorcamItemObject = $this->obterCodigoDoItemDeJulgamento($liclicitaCodigo, $liclicitemCodigo);

                            if (!empty($pcorcamItemObject)) {
                                $pcorcamItemCodigo = $pcorcamItemObject->pc22_orcamitem;
                            }
                        }

                        $proximoJulgForne = $this->julgForneRepository->findItemSupplier($proximoFornecedorCode, $pcorcamItemCodigo);

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
                        throw new JulgamentoException("#JU05 - Exceção: Nenhum fornecedor válido encontrado para o item de orçamento {$julgItemCodigo}.");
                    }

                }

            }

        } catch (\Exception $e) {

            throw new JulgamentoException("#JU06 - Exceção ao encontrar o próximo fornecedor para lance: " . $e->getMessage());

        }
    }

    /**
     * Obtém o último lance registrado para um fornecedor e um item de orçamento específicos.
     *
     * Esta função busca o lance mais recente de um fornecedor para um item de orçamento fornecido,
     * verificando se tanto o fornecedor quanto o item de orçamento existem antes de realizar a consulta.
     * 
     * Se o fornecedor ou o item de orçamento não forem encontrados, a função retorna `false`.
     *
     * @param int $pcorcamforneCodigo O código do fornecedor.
     * @param int $pcorcamitemCodigo O código do item de orçamento.
     * 
     * @return mixed O último lance registrado ou `false` caso o fornecedor ou o item de orçamento não sejam encontrados.
     */
    public function obterLance($pcorcamforneCodigo, $pcorcamitemCodigo)
    {
        try {

            $listaJulgForne = $this->julgForneRepository->findItemSupplier($pcorcamforneCodigo, $pcorcamitemCodigo);
            $julgItem = $this->julgItemRepository->findItemBudget($pcorcamitemCodigo);

            if (!empty($julgItem) && !empty($listaJulgForne)) {

                return $this->julgLanceRepository->findLastBidSupplierNotNull($listaJulgForne->l34_codigo, $julgItem->l30_codigo);

            } else {

                return false;

            }

        } catch (\Exception $e) {

            throw new JulgamentoException("#JU07 - Exceção ao obter lance: " . $e->getMessage());

        }
    }

    /**
     * Obtém uma lista de fornecedores que possuem o modelo de microempresa para um item específico em uma licitação.
     *
     * Esta função utiliza o repositório `licilicitemRepository` para buscar os fornecedores associados a um item 
     * de licitação e filtra os que possuem o modelo de microempresa. Em caso de falha, lança uma exceção 
     * personalizada para lidar com erros no processo de obtenção de dados.
     *
     * @param mixed $liclicita Identificador da licitação para o qual os fornecedores serão buscados.
     * @param mixed $liclicitem Identificador do item de licitação relacionado.
     *
     * @return mixed Retorna a lista de fornecedores com modelo de microempresa.
     *
     * @throws JulgamentoException Caso ocorra um erro durante o processo, lança uma exceção contendo detalhes do erro.
     */
    public function obterFornecedoresComModeloDeMicroempresa($liclicita, $liclicitem, $valorDeReferencia)
    {
        try {
            
            return $this->licilicitemRepository->getSuppliersWithAMicroEnterprise($liclicita, $liclicitem, $valorDeReferencia);

        } catch (\Exception $e) {

            throw new JulgamentoException(
                sprintf(
                    "#JU08 - Exceção ao obter fornecedores com modelo de microempresa. Licitação: %s, Item: %s. Detalhes do erro: %s - #JU1",
                    $liclicita,
                    $liclicitem,
                    $e->getMessage()
                )
            );

        }
    }

    /**
     * Registra um lance para um fornecedor e um item de orçamento específicos.
     *
     * Esta função verifica se o item de orçamento e o fornecedor existem. Se algum deles não existir, 
     * ele cria um novo registro para o item de orçamento e/ou fornecedor com o status correspondente.
     * Depois, registra o lance no repositório de lances.
     * 
     * Caso haja algum erro ao registrar o lance, uma exceção é lançada.
     *
     * @param int $pcorcamforneCodigo O código do fornecedor.
     * @param int $pcorcamitemCodigo O código do item de orçamento.
     * @param float $valorLance O valor do lance a ser registrado.
     * @param int $liclicitaCodigo O código da licitação.
     * @param int $liclicitemCodigo O código dos itens licitação.
     * 
     * @return mixed O objeto do lance registrado.
     * 
     * @throws JulgamentoException Se ocorrer um erro ao registrar o lance.
     */
    public function registrarLance($liclicitaCodigo, $liclicitemCodigo, $pcorcamforneCodigo, $valorLance, $pcorcamitemCodigo)
    {
        DB::beginTransaction();

        try {
            
            $julgItem = $this->julgItemRepository->findItemBudget($pcorcamitemCodigo);

            if (empty($julgItem)) {
                $julgItem = $this->registrarJulgItem($pcorcamitemCodigo, 'Em aberto');
            }

            $julgForne = $this->julgForneRepository->findItemSupplier($pcorcamforneCodigo, $pcorcamitemCodigo);

            if (empty($julgForne)) {
                $julgForne = $this->registrarJulgForne($pcorcamforneCodigo, $pcorcamitemCodigo, 'Normal');
            }

            $this->validaValorDoLance($julgItem->l30_codigo, $valorLance, $liclicitaCodigo, $liclicitemCodigo);

            $julgLance = $this->julgLanceRepository->create([
                'l32_julgitem' => $julgItem->l30_codigo,
                'l32_julgforne' => $julgForne->l34_codigo,
                'l32_lance' => $valorLance
            ]);

            if (!$julgLance) {
                throw new JulgamentoException("#JU09 - Exceção ao registrar lance. Tente novamente.");
            }

            if ($valorLance === null) {
                $julgForneStatus = $this->julgForneStatusRepository->findLabel(mb_convert_encoding('Sem lance', 'UTF-8', 'ISO-8859-1'));
                $this->faseDeLancesService->alterarStatusFornecedor($julgForne->l34_codigo, $julgForneStatus->l35_codigo, mb_convert_encoding('O fornecedor foi desclassificado das próximas fases do pregão por não ter conseguido igualar ou superar o valor estipulado na sequência de lances.', 'UTF-8', 'ISO-8859-1'));
            }

            DB::commit();
            return $julgLance;

        } catch (\Exception $e) {
            
            DB::rollBack();
            throw new JulgamentoException("#JU10 - Exceção ao registrar lance: " . $e->getMessage());

        }
    }

    /**
     * Reverte o último lance registrado para um item de orçamento específico.
     *
     * Esta função busca o último lance registrado para o item de orçamento fornecido. 
     * Se o lance existir, ele é deletado do repositório de lances. 
     * Caso contrário, nenhuma ação é tomada.
     * 
     * @param int $pcorcamitemCodigo O código do item de orçamento.
     * 
     * @return void
     */
    public function reverterLance($pcorcamitemCodigo)
    {
        DB::beginTransaction();

        try {

            $julgItem = $this->julgItemRepository->findItemBudget($pcorcamitemCodigo);

            if (empty($julgItem)) {
                throw new JulgamentoException("#JU11 - Exceção: Item de orçamento não encontrado.");
            }

            $ultimoJulgLance = $this->julgLanceRepository->findLastBid($julgItem->l30_codigo);

            if (!empty($ultimoJulgLance)) {
                $this->julgLanceRepository->delete('l32_codigo', $ultimoJulgLance->l32_codigo);
            } else {
                throw new JulgamentoException("#JU12 - Exceção: Nenhum lance encontrado para o item de orçamento informado.");
            }

            if ($ultimoJulgLance->l32_lance === null) {
                $julgForneStatus = $this->julgForneStatusRepository->findLabel(mb_convert_encoding('Normal', 'UTF-8', 'ISO-8859-1'));
                $this->faseDeLancesService->alterarStatusFornecedor($ultimoJulgLance->l32_julgforne, $julgForneStatus->l35_codigo, mb_convert_encoding('Revertendo o lance que desclassificou o fornecedor das próximas fases do pregão por não ter conseguido igualar ou superar o valor estipulado na sequência de lances.', 'UTF-8', 'ISO-8859-1'));
            }

            DB::commit();

        } catch (\Exception $e) {
            
            DB::rollBack();
            throw new JulgamentoException("#JU13 - Exceção ao reverter o lance: " . $e->getMessage());

        }
    }

    /**
     * Limpa todos os lances registrados para um item de orçamento específico.
     *
     * Esta função remove todos os lances associados ao item de orçamento fornecido.
     * Primeiro, busca o item de orçamento com base no código fornecido e, em seguida,
     * exclui todos os lances registrados para esse item.
     *
     * @param int $pcorcamitemCodigo O código do item de orçamento.
     * 
     * @return void
     */
    public function limparLances($pcorcamitemCodigo)
    {
        $codigosItensLista = explode(",", $pcorcamitemCodigo);

        DB::beginTransaction();

        try {

            foreach ($codigosItensLista as $i => $value) {

                $julgItemRepository = $this->julgItemRepository->findItemBudget($value);
    
                if (empty($julgItemRepository)) {
                    throw new JulgamentoException("#JU14 - Exceção: Item de orçamento não encontrado.");
                }
    
                $lances = $this->julgLanceRepository->find('l32_julgitem', $value);

                if (!empty($lances)) {
                    $resultado = $this->julgLanceRepository->deleteBidItem($julgItemRepository->l30_codigo);
        
                    if ($resultado === false) {
                        throw new JulgamentoException("#JU15 - Exceção ao limpar os lances do item de orçamento.");
                    }
                }
                
                $pcorcamvalItens = $this->pcorcamvalRepository->find('pc23_orcamitem', $value);

                if (!empty($pcorcamvalItens)) {
                    $pcorcamval = $this->pcorcamvalRepository->delete('pc23_orcamitem', $value);

                    if ($pcorcamval === false) {
                        throw new JulgamentoException("#JU16 - Exceção ao limpar os lances do item de orçamento.");
                    }
                }

                $pcorcamjulgItens = $this->pcorcamjulgRepository->find('pc24_orcamitem', $value);

                if (!empty($pcorcamjulgItens)) {
                    $pcorcamjulg = $this->pcorcamjulgRepository->deletePcorcamitemRecords($value);

                    if ($pcorcamjulg === false) {
                        throw new JulgamentoException("#JU17 - Exceção ao limpar os lances do item de orçamento.");
                    }
                }

                $julgForne = $this->julgForneRepository->find('l34_orcamitem', $value);

                $codigoArray = [];
                foreach ($julgForne as $item) {
                    $codigoArray[] = $item->l34_codigo;
                }

                $julgForneHist = $this->julgForneHistRepository->deleteIn('l36_julgforne', $codigoArray);
                
                if ($julgForneHist === false) {
                    throw new JulgamentoException("#JU121 - Exceção ao limpar os historicos dos fornecedores do julgamento do item.");
                }

                if ($julgForne->isNotEmpty()) {

                    $julgForne = $this->julgForneRepository->delete('l34_orcamitem', $value);
                    
                    if (empty($julgForne)) {
                        throw new JulgamentoException("#JU18 - Exceção ao limpar os fornecedores do julgamento do item.");
                    }

                }

            }

            DB::commit();
            return true;

        } catch (\Exception $e) {

            DB::rollBack();
            throw new JulgamentoException("#JU19 - Exceção ao limpar lances: " . $e->getMessage());

        }
    }

    /**
     * Altera o status de um item de orçamento e registra o motivo da alteração.
     *
     * Esta função verifica se o item de orçamento existe. Caso o item não exista, ele será criado
     * com o status "Em aberto". Se o item já existir, o status é alterado para o novo status fornecido.
     * Após a alteração, o motivo da alteração é registrado no histórico de status do item.
     *
     * @param int $codigosItens O códigos dos itens.
     * @param int $novoStatus O código do novo status a ser atribuído ao item de orçamento.
     * @param string $motivo O motivo para a alteração do status do item de orçamento.
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

                // Busca a existência do item no orçamento pelo código
                $itemOrcamento = $this->pcorcamitemRepository->find($codigoItem);

                if (empty($itemOrcamento)) {
                    throw new JulgamentoException("#JU20 - Exceção: Item de orçamento com código {$codigoItem} não encontrado.");
                }
    
                // Busca o item de julgamento associado ao orçamento
                $julgItem = $this->julgItemRepository->findItemBudget($itemOrcamento->pc22_orcamitem);
    
                if (!empty($julgItem) && $julgItem->l30_julgitemstatus == 2 && $novoStatus !== 2) {

                    $pcorcamvalItens = $this->pcorcamvalRepository->find('pc23_orcamitem', $codigoItem);

                    if (!empty($pcorcamvalItens)) {
                        $pcorcamval = $this->pcorcamvalRepository->delete('pc23_orcamitem', $codigoItem);
    
                        if ($pcorcamval === false) {
                            throw new JulgamentoException(
                                sprintf(
                                    "#JU21 - Exceção ao tentar excluir os registros de pcorcamval. Código do item: %s. Verifique os logs e o estado do banco de dados.",
                                    $codigoItem
                                )
                            );
                        }
                    }
    
                    $pcorcamjulgItens = $this->pcorcamjulgRepository->find('pc24_orcamitem', $codigoItem);
    
                    if (!empty($pcorcamjulgItens)) {
                        $pcorcamjulg = $this->pcorcamjulgRepository->deletePcorcamitemRecords($codigoItem);
    
                        if ($pcorcamjulg === false) {
                            throw new JulgamentoException(
                                sprintf(
                                    "#JU22 - Exceção ao tentar excluir registros de pcorcamitem para o código do item: %s. A operação de exclusão retornou false. Verifique os dados no banco de dados e os logs para mais detalhes.",
                                    $codigoItem
                                )
                            );
                        }
                    }
                    
                    $updateSuppliers = $this->julgForneRepository->updateStatusSuppliersFromBestBidToNoBid($codigoItem, ['l34_julgfornestatus' => 3]);

                    if (empty($updateSuppliers)) {
                        throw new JulgamentoException(
                            sprintf(
                                "#JU23 - Exceção ao atualizar o status dos fornecedores para o código do item: %s. Nenhum registro foi alterado. Verifique o estado dos dados no banco de dados e os logs da aplicação para mais detalhes.",
                                $codigoItem
                            )
                        );
                    }

                }

                // Caso o item de julgamento não exista, cria um novo com o status "Em Aberto"
                if (empty($julgItem)) {

                    // Busca o status padrão "Em Aberto"
                    $statusJulgItem = $this->itemStatusRepository->findLabel(mb_convert_encoding('Em aberto', 'UTF-8', 'ISO-8859-1'));
    
                    if (empty($statusJulgItem)) {
                        throw new JulgamentoException("#JU24 - Exceção: Status Em aberto não encontrado.");
                    }
    
                    // Cria o item de julgamento no banco de dados
                    $julgItem = $this->julgItemRepository->create([
                        'l30_orcamitem' => $itemOrcamento->pc22_orcamitem,
                        'l30_julgitemstatus' => $statusJulgItem->l31_codigo,
                    ]);
    
                    if (!$julgItem) {
                        throw new JulgamentoException("#JU25 - Exceção ao criar o item de orçamento.");
                    }
    
                    // Busca o novo status para atualização
                    $novoStatusJulgItem = $this->itemStatusRepository->findId($novoStatus);
    
                    if (empty($novoStatusJulgItem)) {
                        throw new JulgamentoException("#JU26 - Exceção: Novo status informado é inválido.");
                    }
    
                    // Atualiza o status do item de julgamento
                    $resultado = $this->julgItemRepository->update($julgItem->l30_codigo, [
                        'l30_julgitemstatus' => $novoStatusJulgItem->l31_codigo,
                    ]);
    
                    if (!$resultado) {
                        throw new JulgamentoException("#JU27 - Exceção ao atualizar o status do item de orçamento.");
                    }

                } else {

                    // Caso o item de julgamento já exista, busca o novo status para atualização
                    $novoStatusJulgItem = $this->itemStatusRepository->findId($novoStatus);
    
                    if (empty($novoStatusJulgItem)) {
                        throw new JulgamentoException("#JU28 - Exceção: Novo status informado é inválido.");
                    }
    
                    // Atualiza o status do item de julgamento existente
                    $resultado = $this->julgItemRepository->update($julgItem->l30_codigo, [
                        'l30_julgitemstatus' => $novoStatusJulgItem->l31_codigo,
                    ]);
    
                    if (!$resultado) {
                        throw new JulgamentoException("#JU29 - Exceção ao atualizar o status do item de orçamento.");
                    }

                }

                // Cria um registro no histórico para o item atualizado
                $historico = $this->julgItemHistRepository->create([
                    'l33_julgitem' => $julgItem->l30_codigo,
                    'l33_julgitemstatus' => $julgItem->l30_julgitemstatus,
                    'l33_motivo' => $motivo,
                ]);
    
                if (!$historico) {
                    throw new JulgamentoException("#JU30 - Exceção ao registrar o histórico do item.");
                }
            }
    
            DB::commit();
            return true;

        } catch (\Exception $e) {

            DB::rollBack();
            throw new JulgamentoException("#JU31 - Exceção ao alterar status do item: " . $e->getMessage());

        }
    }     

    public function atualizaStatusFornecedor($pcorcamitemCodigo, $pcorcamforneCodigo, $statusFonecedor, $motivoFornecedor)
    {
        try {

            $julgForne = $this->julgForneRepository->findItemSupplier($pcorcamforneCodigo, $pcorcamitemCodigo);

            if (empty($julgForne)) {
                $julgForne = $this->registrarJulgForne($pcorcamforneCodigo, $pcorcamitemCodigo, 'Normal');
            }

            $this->faseDeLancesService->alterarStatusFornecedor($julgForne->l34_codigo, $statusFonecedor, $motivoFornecedor);

        } catch (\Exception $e) {

            throw new JulgamentoException("#JU32 - Exceção ao alterar status do fornecedor: " . $e->getMessage());

        }
    }

    /**
     * Finaliza o processo de julgamento de um item de orçamento em uma licitação.
     *
     * Este método realiza as seguintes operações:
     * - Obtém fornecedores vinculados ao item de orçamento.
     * - Recupera e organiza os detalhes dos lances, ordenando-os pelo menor valor.
     * - Gera a pontuação e armazena os dados processados nos repositórios correspondentes.
     * - Atualiza o status dos fornecedores e do item de orçamento para "Melhor Proposta" e "Julgado", respectivamente.
     * - Remove registros antigos de lances e julgamentos, substituindo-os pelos novos dados processados.
     * - Garante a integridade do processo com transações de banco de dados.
     *
     * @param mixed $liclicitaCodigo Código da licitação.
     * @param mixed $liclicitemCodigo Código do item da licitação.
     * @param mixed $pcorcamitemCodigo Código do item de orçamento.
     * 
     * @return bool Retorna `true` se o processo for concluído com sucesso.
     * 
     * @throws JulgamentoException Em caso de falhas em qualquer etapa do processo.
     */
    public function finalizar($liclicitaCodigo, $liclicitemCodigo, $pcorcamitemCodigo)
    {
        DB::beginTransaction();
    
        try {
            
            $julgFornecedores = $this->julgForneRepository->findItemSuppliers($pcorcamitemCodigo);

            if (empty($julgFornecedores)) {
                throw new JulgamentoException(
                    sprintf(
                        "#JU36 - Exceção ao buscar fornecedores para o item com código: %s. Nenhum fornecedor encontrado.",
                        $pcorcamitemCodigo
                    )
                );
            }

            $pcorcamvalData = [];

            foreach ($julgFornecedores as $i => $julgFornecedor) {
                $detalhesDaMenorOfertaDoFornecedor = $this->licilicitemRepository->getLowestBidDetailsForSupplier($liclicitaCodigo, $liclicitemCodigo, $julgFornecedor->l34_orcamforne, $pcorcamitemCodigo);

                if (empty($detalhesDaMenorOfertaDoFornecedor)) {
                    throw new JulgamentoException(
                        sprintf(
                            "#JU37 - Exceção ao buscar os detalhes da menor oferta para o fornecedor. Licitação: %s, Item: %s, Fornecedor: %s, Código do item: %s. Nenhum detalhe encontrado.",
                            $liclicitaCodigo,
                            $liclicitemCodigo,
                            $julgFornecedor->l34_orcamforne,
                            $pcorcamitemCodigo
                        )
                    );
                }

                if ($detalhesDaMenorOfertaDoFornecedor->l32_lance === null) {
                    $proposalSupplier = $this->licilicitemRepository->getProposalSupplier($liclicitaCodigo, $liclicitemCodigo, $julgFornecedor->l34_orcamforne);
                    
                    if (empty($proposalSupplier)) {
                        throw new JulgamentoException(
                            sprintf(
                                "#JU58 - Exceção ao buscar os detalhes da menor oferta para o fornecedor. Licitação: %s, Item: %s, Fornecedor: %s.",
                                $liclicitaCodigo,
                                $liclicitemCodigo,
                                $julgFornecedor->l34_orcamforne,
                            )
                        );
                    }

                    $detalhesDaMenorOfertaDoFornecedor->l32_lance = $proposalSupplier->l224_vlrun;
                }

                $pcorcamvalData[] = [
                    'pc23_orcamitem' => $detalhesDaMenorOfertaDoFornecedor->pc22_orcamitem,
                    'pc23_orcamforne' => $detalhesDaMenorOfertaDoFornecedor->pc21_orcamforne,
                    'pc23_valor' =>  round((floatval($detalhesDaMenorOfertaDoFornecedor->l32_lance) * intval($detalhesDaMenorOfertaDoFornecedor->l224_quant)), 2),
                    'pc23_quant' => $detalhesDaMenorOfertaDoFornecedor->l224_quant,
                    'pc23_vlrun' => $detalhesDaMenorOfertaDoFornecedor->l32_lance
                ];
            }

            usort($pcorcamvalData, function ($a, $b) {
                $valorA = is_numeric($a['pc23_valor']) ? (float) $a['pc23_valor'] : 0;
                $valorB = is_numeric($b['pc23_valor']) ? (float) $b['pc23_valor'] : 0;
            
                return $valorA <=> $valorB;
            });

            $pcorcamjulgData = [];

            foreach ($pcorcamvalData as $i => $pcorcamval) {
                $pcorcamjulgData[] = [
                    'pc24_orcamitem' => $pcorcamval['pc23_orcamitem'],
                    'pc24_pontuacao' => ($i + 1),
                    'pc24_orcamforne' => $pcorcamval['pc23_orcamforne']
                ];
            }

            $pcorcamvalItens = $this->pcorcamvalRepository->find('pc23_orcamitem', $pcorcamitemCodigo);

            if (!empty($pcorcamvalItens)) {
                $pcorcamval = $this->pcorcamvalRepository->delete('pc23_orcamitem', $pcorcamitemCodigo);

                if ($pcorcamval === false) {
                    throw new JulgamentoException("#JU38 - Exceção ao limpar os registros da pcorcamval.");
                }
            }

            $pcorcamvalInsert = $this->pcorcamvalRepository->insertData($pcorcamvalData);

            if (empty($pcorcamvalInsert)) {
                throw new JulgamentoException("#JU39 - Exceção ao inserir os registros na pcorcamval.");
            }

            foreach ($julgFornecedores as $k => $julgFornecedor) {
                $statusFornecedor = $this->julgForneStatusRepository->findLabel(mb_convert_encoding('Melhor Proposta', 'UTF-8', 'ISO-8859-1'));
                $this->faseDeLancesService->alterarStatusFornecedor($julgFornecedor->l34_codigo, $statusFornecedor->l35_codigo, mb_convert_encoding('O status dos fornecedores foram atualizados para melhor lance após a conclusão da rotina de julgamento.', 'UTF-8', 'ISO-8859-1'));
            }

            $statusItem = $this->itemStatusRepository->findLabel(mb_convert_encoding('Julgado', 'UTF-8', 'ISO-8859-1'));
            $statusItemUpdate = $this->alterarStatusItem($pcorcamitemCodigo, $statusItem->l31_codigo, mb_convert_encoding('O status do item foi atualizado para julgado após a conclusão da rotina de julgamento.', 'UTF-8', 'ISO-8859-1'));

            if (empty($statusItemUpdate)) {
                throw new JulgamentoException("#JU40 - Exceção ao atualizar o status do item após a conclusão da rotina de julgamento.");
            }

            $pcorcamjulgItens = $this->pcorcamjulgRepository->find('pc24_orcamitem', $pcorcamitemCodigo);

            if (!empty($pcorcamjulgItens)) {
                $pcorcamjulg = $this->pcorcamjulgRepository->deletePcorcamitemRecords($pcorcamitemCodigo);

                if ($pcorcamjulg === false) {
                    throw new JulgamentoException("#JU41 - Exceção ao limpar os lances do item de orçamento.");
                }
            }

            $pcorcamjulgInsert = $this->pcorcamjulgRepository->insert($pcorcamjulgData);

            if (empty($pcorcamjulgInsert)) {
                throw new JulgamentoException("#JU42 - Exceção ao registrar os melhores lances na ordem dos fornecedores vencedores em pcorcamjulg após a conclusão da rotina de julgamento.");
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {

            DB::rollBack();
            throw new JulgamentoException("#JU43 - Exceção na rotina de finalização de julgamento: " . $e->getMessage());

        }
    }

    /**
     * Libera fornecedores com modelo de microempresa para um item de licitação.
     *
     * - Obtém fornecedores utilizando `obterFornecedoresComModeloDeMicroempresa`.
     * - Valida se há fornecedores e se o status "Normal" existe no repositório.
     * - Atualiza o status de cada fornecedor encontrado para "Normal".
     *
     * @param mixed $liclicita ID da licitação.
     * @param mixed $liclicitem ID do item de licitação.
     * 
     * @throws JulgamentoException Em caso de erro durante o processo.
     */
    public function liberarMicroEmpresas($liclicita, $liclicitem, $valorDeReferencia=null)
    {
        try {

            $fornecedoresComModeloDeMicroempresa = $this->obterFornecedoresComModeloDeMicroempresa($liclicita, $liclicitem, $valorDeReferencia);
            
            if (empty($fornecedoresComModeloDeMicroempresa)) {
                throw new JulgamentoException(
                    sprintf(
                        "#JU44 - Exceção ao obter fornecedores com modelo de microempresa. Licitação: %s, Item: %s. Nenhum fornecedor encontrado.",
                        $liclicita,
                        $liclicitem
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

                $julgForne = $this->julgForneRepository->findItemSupplier($fornecedorMicroempresa->pc21_orcamforne, $fornecedorMicroempresa->pc22_orcamitem);

                if (empty($julgForne)) {
                    throw new JulgamentoException(
                        sprintf(
                            "#JU46 - Exceção ao buscar fornecedor para o item com código de fornecedor: %s e código de item: %s. Nenhum fornecedor encontrado.",
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
                    "#JU47 - Exceção ao liberar microempresas para a licitação %s e item %s. Detalhes do erro: %s",
                    $liclicita,
                    $liclicitem,
                    $e->getMessage()
                )
            );
            
        }
    }

    /**
     * Registra o julgamento de um item de orçamento.
     *
     * Esta função cria um registro no repositório de itens com os detalhes do julgamento,
     * incluindo o código do item e o status associado.
     *
     * @param int $pcorcamitemCodigo Código do item no orçamento.
     * @param string $statusLabel Rótulo do status do item.
     *
     * @return mixed Retorna o registro criado do item de orçamento.
     *
     * @throws JulgamentoException Se o status do item não for encontrado ou ocorrer um erro na criação.
     */
    private function registrarJulgItem($pcorcamitemCodigo, $statusLabel)
    {
        $statusItem = $this->itemStatusRepository->findLabel(mb_convert_encoding($statusLabel, 'UTF-8', 'ISO-8859-1'));

        if (empty($statusItem)) {
            throw new JulgamentoException("#JU52 - Exceção: Status do item de orçamento não encontrado.");
        }

        $julgItem = $this->julgItemRepository->create([
            'l30_orcamitem' => $pcorcamitemCodigo,
            'l30_julgitemstatus' => $statusItem->l31_codigo,
        ]);

        if (!$julgItem) {

            throw new JulgamentoException("#JU53 - Exceção ao criar o item de orçamento.");

        } else {

            return $julgItem;

        }
    }

    /**
     * Registra o julgamento de um fornecedor para um item de orçamento.
     *
     * Esta função cria um registro no repositório de fornecedores com os detalhes do 
     * julgamento, incluindo o código do fornecedor, o código do item e o status associado.
     *
     * @param int $pcorcamforneCodigo Código do fornecedor no orçamento.
     * @param int $pcorcamitemCodigo Código do item no orçamento.
     * @param string $statusLabel (Opcional) Rótulo do status do fornecedor, padrão é "Normal".
     *
     * @return mixed Retorna o registro criado do fornecedor.
     *
     * @throws JulgamentoException Se o status do fornecedor não for encontrado ou ocorrer um erro na criação.
     */
    private function registrarJulgForne($pcorcamforneCodigo, $pcorcamitemCodigo, $statusLabel="Normal")
    {
        $statusFornecedor = $this->julgForneStatusRepository->findLabel(mb_convert_encoding($statusLabel, 'UTF-8', 'ISO-8859-1'));

        if (empty($statusFornecedor)) {
            throw new JulgamentoException("#JU54 - Exceção: Status do fornecedor não encontrado.");
        }

        $julgForne = $this->julgForneRepository->create([
            'l34_orcamforne' => $pcorcamforneCodigo,
            'l34_orcamitem' => $pcorcamitemCodigo,
            'l34_julgfornestatus' => $statusFornecedor->l35_codigo,
        ]);

        if (!$julgForne) {

            throw new JulgamentoException("#JU55 - Exceção ao criar o fornecedor.");

        } else {

            return $julgForne;

        }
    }

    /**
     * Valida o valor de um lance fornecido para um item em uma licitação.
     *
     * Esta função verifica se o valor do lance é válido, seguindo as regras:
     * - O valor deve ser menor que a menor proposta inicial ou lance anterior, 
     *   considerando a diferença mínima permitida (`l13_difminlance`).
     *
     * @param int $julgItemCodigo Código do item em julgamento.
     * @param float $valorLance Valor do lance fornecido.
     * @param int $liclicitaCodigo Código da licitação.
     * @param int $liclicitemCodigo Código do item na licitação.
     *
     * @throws JulgamentoException Se o valor do lance não for válido de acordo com as regras.
     */
    private function validaValorDoLance($julgItemCodigo, $valorLance, $liclicitaCodigo, $liclicitemCodigo)
    {
        $param = $this->faseDeLancesService->obterParametros();

        $ultimoLance = $this->julgLanceRepository->findLastBidNotNull($julgItemCodigo);

        if (empty($ultimoLance)) {

            $ultimaProposta = $this->licilicitemRepository->getTheLowestBid($liclicitaCodigo, $liclicitemCodigo, $param->l13_clapercent);

            if($valorLance > (floatval($ultimaProposta->l224_vlrun) - floatval($param->l13_difminlance))) {
                throw new JulgamentoException("#JU56 - Exceção: O fornecedor deve apresentar um lance inferior ao último registrado, respeitando o valor mínimo estipulado para redução entre lances.");
            }

        } else {
            
            if ($valorLance !== null && $valorLance > ($ultimoLance->l32_lance - $param->l13_difminlance)) {
                throw new JulgamentoException("#JU57 - Exceção: O fornecedor não pode enviar um lance maior que o menor lance.");
            }

        }
    }
}
