<?php

namespace App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance;

use App\Exceptions\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\JulgamentoException;
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

class FaseDeLancesService
{
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
    public function __construct() {
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
    }

    /**
     * Obtém os parâmetros de uma instituição específica.
     *
     * Esta função recupera os parâmetros de julgamento para a instituição definida na sessão 
     * através do método `findParamInstit` do repositório de parâmetros (`paramRepo`).
     * O código da instituição é obtido a partir da sessão com a chave `'DB_instit'`.
     *
     * @return mixed Retorna os parâmetros de julgamento encontrados para a instituição especificada 
     * ou `null` se não houver dados.
     */
    public function obterParametros()
    {
        return $this->julgParamRepository->findParamInstit(session('DB_instit'));
    }

    /**
     * Retorna o status de todos os itens.
     *
     * @return mixed Coleção com os status dos itens.
     */
    public function obterStatusDosItens()
    {
        return $this->itemStatusRepository->all();
    }

    /**
     * Retorna o status de todos os fornecedores.
     *
     * @return mixed Coleção com os status dos fornecedores.
     */
    public function obterStatusDosFornecedores()
    {
        return $this->julgForneStatusRepository->all();
    }

    /**
     * Altera os parâmetros de julgamento de uma instituição.
     *
     * Esta função verifica se já existem parâmetros de julgamento registrados para a instituição 
     * atual. Caso existam, ela tenta atualizar esses parâmetros com os dados fornecidos. Se não 
     * houver parâmetros registrados, novos parâmetros são criados. Em ambos os casos, se ocorrer 
     * algum erro, uma exceção personalizada (`JulgamentoException`) é lançada para informar o problema.
     *
     * @param array $data Dados a serem usados para atualizar ou criar os parâmetros de julgamento.
     * 
     * @throws JulgamentoException Lança uma exceção se ocorrer um erro ao tentar atualizar ou criar 
     * os parâmetros de julgamento.
     */
    public function alterarParametro($data)
    {
        try {
            
            $paramInstit = $this->julgParamRepository->findParamInstit(session('DB_instit'));

            if ($paramInstit) {
                
                $paramUpdate = $this->julgParamRepository->update($paramInstit->l13_julgparam, $data);
                
                if (!$paramUpdate) {
                    throw new JulgamentoException("#JU33 - Exceção ao atualizar os parametros de julgamentos.");
                }

            } else {

                $data['l13_instit'] = session('DB_instit');
                $paramInsert = $this->julgParamRepository->create($data);
                
                if (!$paramInsert) {
                    throw new JulgamentoException("#JU34 - Exceção ao criar os parametros de julgamentos.");
                }

            }

        } catch (\Exception $e) {

            throw new JulgamentoException("#JU35 - Exceção ao alterar status do fornecedor: " . $e->getMessage());

        }
    }

    /**
     * Altera o status de um fornecedor e registra o histórico da alteração.
     *
     * Esta função busca o registro do fornecedor, valida o novo status fornecido, 
     * atualiza o status no repositório de fornecedores e registra o histórico da alteração.
     *
     * @param int $julgForneCodigo Código do julgamento do fornecedor.
     * @param int $statusFonecedor Código do novo status do fornecedor.
     * @param string $motivoFornecedor Motivo para a alteração do status.
     *
     * @throws JulgamentoException Se o fornecedor não for encontrado, o status for inválido, 
     *                             a atualização falhar ou o histórico não for registrado.
     */
    public function alterarStatusFornecedor($julgForneCodigo, $statusFonecedor, $motivoFornecedor)
    {
        $julgForne = $this->julgForneRepository->findId($julgForneCodigo);

        if (empty($julgForne)) {
            throw new JulgamentoException("#JU48 - Exceção: Julgamento de fornecedor não encontrado para o código informado.");
        }

        $statusFornecedor = $this->julgForneStatusRepository->findId($statusFonecedor);

        if (empty($statusFornecedor)) {
            throw new JulgamentoException("#JU49 - Exceção: Novo status informado é inválido.");
        }

        $resultado = $this->julgForneRepository->update($julgForne->l34_codigo, [
            'l34_julgfornestatus' => $statusFornecedor->l35_codigo,
        ]);

        if (!$resultado) {
            throw new JulgamentoException("#JU50 - Exceção ao atualizar o status do fornecedor.");
        }

        $historico = $this->julgForneHistRepository->create([
            'l36_julgforne' => $julgForne->l34_codigo,
            'l36_julgfornestatus' => $julgForne->l34_julgfornestatus,
            'l36_motivo' => $motivoFornecedor,
        ]);

        if (!$historico) {
            throw new JulgamentoException("#JU51 - Exceção ao registrar o histórico do fornecedor.");
        }
    }
}
