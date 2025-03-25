<?php

namespace App\Services\Patrimonial\Licitacao\Procedimentos\JulgamentoPorLance;

use App\Exceptions\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\JulgamentoException;
use App\Models\Patrimonial\Compras\Pcorcamjulg;
use App\Models\Patrimonial\Compras\Pcorcamval;
use App\Repositories\Patrimonial\Compras\PcorcamjulgRepository;
use App\Repositories\Patrimonial\Compras\PcorcamvalRepository;
use App\Repositories\Patrimonial\Licitacao\JulgItemRepository;
use App\Repositories\Patrimonial\Licitacao\JulgItemStatusRepository;
use App\Repositories\Patrimonial\Licitacao\JulgLanceRepository;
use App\Repositories\Patrimonial\Licitacao\LicilicitemRepository;
use Illuminate\Support\Facades\DB;

class ReadequarPropostaService
{
    protected $licilicitemRepository;
    protected $julgitemRepository;
    protected $julgLanceRepository;
    protected $pcorcamjulgRepository;
    protected $pcorcamvalRepository;
    protected $julgamentoLoteService;
    protected $itemStatusRepository;

    public function __construct() {
        $this->licilicitemRepository = new LicilicitemRepository;
        $this->julgitemRepository = new JulgItemRepository;
        $this->julgLanceRepository = new JulgLanceRepository;
        $this->pcorcamjulgRepository = new PcorcamjulgRepository;
        $this->pcorcamvalRepository = new pcorcamvalRepository;
        $this->julgamentoLoteService = new JulgamentoLoteService;
        $this->itemStatusRepository = new JulgItemStatusRepository;
    }

    /**
     * Obtém os itens da readequação de proposta e calcula os valores unitários e totais.
     *
     * - Recupera os itens de proposta com base no código de licitação, código do fornecedor e número do lote.
     * - Faz a junção dos itens com os valores unitários e marcas, se existirem, e calcula os valores totais para cada item.
     * - Retorna a lista de itens com os valores calculados.
     *
     * @param int $licitacaoCodigo Código da licitação.
     * @param string $orcamforne Código do fornecedor.
     * @param int $numeroLote Número do lote.
     * 
     * @return \Illuminate\Support\Collection A lista de itens da proposta com valores unitários e totais calculados.
     */
    public function obterItensDaReadequecaoDeProposta($licitacaoCodigo, $orcamforne, $numeroLote)
    {
        $rsitensProposta = $this->licilicitemRepository->getBiddingItems($licitacaoCodigo, $orcamforne, $numeroLote);

        $itens = $rsitensProposta->pluck('pc22_orcamitem')->toArray();
        $pcorcamval = Pcorcamval::whereIn('pc23_orcamitem', $itens)->get();

        $keys = $pcorcamval->pluck('pc23_orcamitem');
        $values = $pcorcamval->map(fn($item) => [
            'pc23_vlrun' => $item['pc23_vlrun'], 
            'pc23_obs' => $item['pc23_obs']
        ]);

        $orcamitemValorUnitarioEMarca = $keys->combine($values)->toArray();

        $rsitensProposta->map(function ($item) use ($orcamitemValorUnitarioEMarca) {
            $orcamitem = $item->pc22_orcamitem;
        
            if (isset($orcamitemValorUnitarioEMarca[$orcamitem])) {
                $item->vlr_unitario = $orcamitemValorUnitarioEMarca[$orcamitem]['pc23_vlrun'];
                $item->vlr_total = "R$ " . number_format(round(($item->quantidade * $orcamitemValorUnitarioEMarca[$orcamitem]['pc23_vlrun']), 2), 2, ',', '.');
            } else {
                $item->vlr_unitario = (empty($orcamitemValorUnitarioEMarca[$orcamitem]['pc23_vlrun'])) ? '' : $orcamitemValorUnitarioEMarca[$orcamitem]['pc23_vlrun'];
                $item->vlr_total = (empty($orcamitemValorUnitarioEMarca[$orcamitem]['pc23_vlrun'])) ? 'R$ 0,00' : "R$ " . number_format(round(($item->quantidade * $orcamitemValorUnitarioEMarca[$orcamitem]['pc23_vlrun']), 2), 2, ',', '.');
            }
        
            return $item;
        });

        return $rsitensProposta;
    }

    /**
     * Verifica se a proposta existe no banco de dados, com base nos itens fornecidos.
     * 
     * A função verifica a existência de itens nas tabelas 'Pcorcamval' e 'Pcorcamjulg'.
     * Se houver registros em ambas, a proposta existe. Se não houver nenhum, a proposta não existe.
     * Se houver registros em uma tabela mas não na outra, é gerada uma exceção de julgamento.
     *
     * @param array $orcamItens Lista de itens com o campo 'orcamitem' que será utilizado para buscar nas tabelas.
     * 
     * @return bool Retorna true se a proposta existir, false se não existir.
     * 
     * @throws JulgamentoException Lança exceção se houver inconsistência entre as tabelas.
     */
    public function verificarPropostaExistente($orcamItens)
    {
        $itens = array_column($orcamItens, 'orcamitem');

        $pcorcamval = Pcorcamval::whereIn('pc23_orcamitem', $itens)->get();
        $pcorcamjulg = Pcorcamjulg::whereIn('pc24_orcamitem', $itens)->get();

        if ($pcorcamval->isNotEmpty() && $pcorcamjulg->isNotEmpty()) {
            return true;
        } else if ($pcorcamval->isEmpty() && $pcorcamjulg->isEmpty()) {
            return false;
        } else if (($pcorcamval->isNotEmpty() && $pcorcamjulg->isEmpty()) || ($pcorcamval->isEmpty() && $pcorcamjulg->isNotEmpty())) {
            throw new JulgamentoException("#RP01 - Ocorreu uma inconsistência na verificação das propostas, onde valores ou julgamentos estão presentes de forma isolada; verifique os dados e assegure a integridade das informações antes de prosseguir.");
        }
    }

    /**
     * Salva ou atualiza a proposta com base na existência de uma proposta já cadastrada.
     * 
     * @param string $licitacao Código da licitação.
     * @param string $lote Código do lote.
     * @param string $orcamforne Código do fornecedor.
     * @param array $itens Itens da proposta, incluindo os dados necessários para salvar a proposta.
     * 
     * @return mixed Retorna o resultado da inserção ou atualização da proposta.
     */
    public function salvarProposta($licitacao, $lote, $orcamforne, $itens)
    {
        $this->validarProposta($lote, $itens, $licitacao);

        if ($this->verificarPropostaExistente($itens)) {

            return $this->atualizarProposta($licitacao, $lote, $orcamforne, $itens);

        } else {

            return $this->inserirProposta($licitacao, $lote, $orcamforne, $itens);

        }
    }

    /**
     * Valida os itens da proposta em relação ao lance vencedor do lote e a integridade dos valores unitários.
     * 
     * Este método realiza a validação dos itens da proposta, verificando se o valor total dos itens não ultrapassa o lance
     * vencedor do lote e se todos os valores unitários dos itens são válidos (não nulos ou zero).
     * 
     * Caso algum item tenha valor unitário inválido ou caso a soma dos valores unitários ultrapasse o lance vencedor, uma exceção
     * será lançada para impedir que a proposta seja salva.
     * 
     * @param string $lote Código do lote para o qual os itens estão sendo validados.
     * @param array $itens Lista de itens da proposta contendo os valores unitários a serem validados.
     * 
     * @throws JulgamentoException Caso a soma dos valores unitários dos itens seja maior que o lance vencedor ou
     *                             se algum item tiver valor unitário nulo ou igual a zero.
     */
    private function validarProposta($lote, $itens, $licitacao)
    {
        $julgItem = $this->julgitemRepository->find('l30_numerolote', $lote);
        $lanceVencedor = $this->julgLanceRepository->findLastBidNotNull($julgItem[0]->l30_codigo);

        $vlrTotal = array_column($itens, 'vlrTotal');
        $soma = array_sum(array_filter($vlrTotal));

        if (empty($lanceVencedor)) {
            $menorProposta = $this->licilicitemRepository->getLowestBidWithoutBidding($licitacao, $lote);
            $lanceVencedor = $menorProposta->total_valor;
        } else {
            $lanceVencedor = $lanceVencedor->l32_lance;
        }

        if ($soma > floatval($lanceVencedor)) {
            throw new JulgamentoException("#RP02 - O valor total da proposta não pode exceder o último lance ou proposta vencedora. Verifique os valores unitários dos itens antes de prosseguir.");
        }

        $itensInvalidos = array_filter($itens, function($item) {
            return empty($item['vlrUnitario']) || $item['vlrUnitario'] === 0;
        });
        
        if (!empty($itensInvalidos)) {
            throw new JulgamentoException("#RP03 - Todos os itens da proposta devem possuir um valor unitário válido e maior que zero. Revise os dados e tente novamente.");
        }
    }

    /**
     * Insere os itens da proposta nas tabelas de julgamento e valores do orçamento.
     * 
     * Este método processa os itens fornecidos na proposta, calcula os valores necessários e insere as informações nas tabelas
     * `Pcorcamjulg` (julgamento de orçamento) e `Pcorcamval` (valores de orçamento). Para cada item da proposta, são calculados
     * os valores unitários e totais com base nas quantidades do item, além de associar as marcas e outras informações relevantes.
     * 
     * Caso ocorra algum erro durante a inserção dos dados nas tabelas, uma exceção `JulgamentoException` é lançada.
     * 
     * @param string $licitacao Código da licitação para a qual os itens são inseridos.
     * @param string $lote Código do lote para o qual os itens são inseridos.
     * @param string $orcamforne Código do fornecedor relacionado à proposta.
     * @param array $itens Lista de itens da proposta a serem inseridos, cada um contendo dados como `orcamitem`, `vlrUnitario`, `marca`, etc.
     * 
     * @return array Retorna um array com as informações inseridas nas tabelas `Pcorcamjulg` e `Pcorcamval`.
     * 
     * @throws JulgamentoException Caso ocorra um erro na inserção dos itens nas tabelas de orçamento.
     */
    private function inserirProposta($licitacao, $lote, $orcamforne, $itens)
    {
        $quantidadesDosItens = $this->licilicitemRepository->getBidItemQuantities($licitacao, $lote, $orcamforne);
        $mapaQuantidade = $quantidadesDosItens->pluck('pc11_quant', 'l21_ordem');

        $pcorcamjulgData = array();
        $pcorcamvalData = array();

        foreach ($itens as $i => &$item) {
            $pcorcamjulgData[$i]['pc24_orcamitem'] = $item['orcamitem'];
            $pcorcamjulgData[$i]['pc24_pontuacao'] = 1;
            $pcorcamjulgData[$i]['pc24_orcamforne'] = $orcamforne;

            $pcorcamvalData[$i]['pc23_orcamforne'] = $orcamforne;
            $pcorcamvalData[$i]['pc23_orcamitem'] = $item['orcamitem'];

            $valorCalculado = $mapaQuantidade[$item['ordem']] * $item['vlrUnitario'];
            $pcorcamvalData[$i]['pc23_valor'] = round($valorCalculado, 2);

            $pcorcamvalData[$i]['pc23_quant'] = $mapaQuantidade[$item['ordem']] ?? null;
            $pcorcamvalData[$i]['pc23_obs'] = $item['marca'];
            $pcorcamvalData[$i]['pc23_vlrun'] = floatval($item['vlrUnitario']);
        }

        $pcorcamjulg = Pcorcamjulg::insert($pcorcamjulgData);

        if (!$pcorcamjulg) {
            throw new JulgamentoException("#RP04 - Falha ao inserir os dados de julgamento da proposta. Verifique as informações e tente novamente.");
        }
        
        $pcorcamval = Pcorcamval::insert($pcorcamvalData);

        if (!$pcorcamval) {
            throw new JulgamentoException("#RP05 - Erro ao registrar os valores da proposta. Certifique-se de que os dados estão corretos e tente novamente.");
        }

        $statusItem = $this->itemStatusRepository->findLabel(mb_convert_encoding('Julgado', 'UTF-8', 'ISO-8859-1'));
        $statusItemUpdate = $this->julgamentoLoteService->alterarStatusItem($lote, $statusItem->l31_codigo, mb_convert_encoding('O status do item foi atualizado para julgado após a conclusão da rotina de julgamento. em readequar propostas.', 'UTF-8', 'ISO-8859-1'));
        
        if (empty($statusItemUpdate)) {
            throw new JulgamentoException("#RP06 - O status do item não pôde ser atualizado para Julgado. Verifique a integridade dos dados e reexecute o processo.");
        }

        return [
            'pcorcamjulg' => $pcorcamjulg,
            'pcorcamval' => $pcorcamval
        ];
    }

    /**
     * Atualiza os itens da proposta nas tabelas de julgamento e valores de orçamento.
     * 
     * Este método atualiza os dados existentes de uma proposta nas tabelas `Pcorcamjulg` (julgamento de orçamento) e `Pcorcamval` (valores de orçamento).
     * Para cada item fornecido, ele atualiza as informações de pontuação, quantidade, valores e outras propriedades. O processo de atualização é feito
     * por meio de uma transação para garantir a consistência dos dados. Caso ocorra um erro durante a atualização, a transação é revertida e uma exceção é lançada.
     * 
     * @param string $licitacao Código da licitação que está sendo atualizada.
     * @param string $lote Código do lote relacionado à proposta.
     * @param string $orcamforne Código do fornecedor para o qual os itens estão sendo atualizados.
     * @param array $orcamItens Lista de itens da proposta que precisam ser atualizados, contendo informações como `orcamitem`, `vlrUnitario`, `marca`, etc.
     * 
     * @return array Retorna um array indicando o sucesso da atualização nas tabelas `Pcorcamjulg` e `Pcorcamval`. Ex: ['pcorcamjulg' => true, 'pcorcamval' => true].
     * 
     * @throws JulgamentoException Caso ocorra um erro ao tentar atualizar os itens nas tabelas de orçamento. A transação será revertida.
     */
    private function atualizarProposta($licitacao, $lote, $orcamforne, $orcamItens)
    {
        $itens = array_column($orcamItens, 'orcamitem');

        $quantidadesDosItens = $this->licilicitemRepository->getBidItemQuantities($licitacao, $lote, $orcamforne);
        $mapaQuantidade = $quantidadesDosItens->pluck('pc11_quant', 'l21_ordem');

        $pcorcamjulgData = [];
        $pcorcamvalData = [];

        foreach ($orcamItens as $i => &$item) {
            $pcorcamjulgData[$item['orcamitem']] = [
                'pc24_orcamitem' => $item['orcamitem'],
                'pc24_pontuacao' => 1,
                'pc24_orcamforne' => $orcamforne,
            ];
    
            $pcorcamvalData[$item['orcamitem']] = [
                'pc23_orcamforne' => $orcamforne,
                'pc23_orcamitem' => $item['orcamitem'],
                'pc23_valor' => round($mapaQuantidade[$item['ordem']] * $item['vlrUnitario'], 2),
                'pc23_quant' => $mapaQuantidade[$item['ordem']] ?? null,
                'pc23_obs' => $item['marca'],
                'pc23_vlrun' => floatval($item['vlrUnitario']),
            ];
        }

        DB::beginTransaction();

        try {
            foreach ($itens as $i => $item) {
                Pcorcamjulg::where('pc24_orcamitem', $item)->update($pcorcamjulgData[$item]);
            }

            foreach ($itens as $i => $item) {
                Pcorcamval::where('pc23_orcamitem', $item)->update($pcorcamvalData[$item]);
            }

            DB::commit();

            return [
                'pcorcamjulg' => true,
                'pcorcamval' => true
            ];

        } catch (\Exception $e) {
            DB::rollback();
            throw new JulgamentoException("#RP07 - Ocorreu um erro durante a atualização da proposta. A transação foi revertida para evitar inconsistências. Verifique os dados e tente novamente: " . $e->getMessage());
        }
    }

    /**
     * Deleta a proposta relacionada aos itens de orçamento fornecidos.
     * 
     * Este método verifica se a proposta existe, e, se for o caso, exclui os registros correspondentes nas tabelas `Pcorcamjulg` (julgamento de orçamento)
     * e `Pcorcamval` (valores de orçamento) para os itens fornecidos. O processo de deleção é realizado em duas etapas, e caso alguma operação de deleção
     * falhe, uma exceção será lançada, garantindo que a integridade dos dados seja mantida. Caso a proposta não exista, uma exceção diferente será gerada.
     * 
     * @param array $orcamItens Lista de itens de orçamento que precisam ser excluídos. Cada item contém dados como `orcamitem`, entre outros.
     * 
     * @return array Retorna um array indicando o sucesso da deleção nas tabelas `Pcorcamjulg` e `Pcorcamval`. Ex: ['pcorcamjulg' => true, 'pcorcamval' => true].
     * 
     * @throws JulgamentoException Caso a proposta não exista ou ocorra um erro ao tentar deletar os itens nas tabelas de orçamento.
     */
    public function deletarProposta($orcamItens, $lote)
    {
        if ($this->verificarPropostaExistente($orcamItens)) {

            $itens = array_column($orcamItens, 'orcamitem');

            $pcorcamjulg = Pcorcamjulg::whereIn('pc24_orcamitem', $itens)->delete();
            
            if (!$pcorcamjulg) {
                throw new JulgamentoException("#RP08 - Erro ao excluir os dados de julgamento da proposta. Verifique e tente novamente.");
            }

            $pcorcamval = Pcorcamval::whereIn('pc23_orcamitem', $itens)->delete();
    
            if (!$pcorcamval) {
                throw new JulgamentoException("#RP09 - Falha ao remover os valores da proposta. Confirme os dados e tente novamente.");
            }

            $statusItem = $this->itemStatusRepository->findLabel(mb_convert_encoding('Aguardando Readequação', 'UTF-8', 'ISO-8859-1'));
            $statusItemUpdate = $this->julgamentoLoteService->alterarStatusItem($lote, $statusItem->l31_codigo, mb_convert_encoding('O status do item foi atualizado para Aguardando Readequação após a remover a conclusão da rotina de julgamento em readequar propostas.', 'UTF-8', 'ISO-8859-1'));
    
            if (empty($statusItemUpdate)) {
                throw new JulgamentoException("#RP10 - O status do item não pôde ser atualizado para Aguardando Readequação. Verifique a integridade dos dados e reexecute o processo.");
            }   

            return [
                'pcorcamjulg' => $pcorcamjulg,
                'pcorcamval' => $pcorcamval
            ];

        } else {
            throw new JulgamentoException("#RP11 - Não há proposta existente para ser excluída. Verifique os dados antes de prosseguir.");
        }
    }

    public function importarItens()
    {}
}
