<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao;

use App\Repositories\CgmRepository;
use App\Repositories\Financeiro\EmpEmpenhoRepository;
use App\Repositories\Patrimonial\VeicCadCentralRepository;
use App\Repositories\Patrimonial\VeicCentralRepository;
use App\Repositories\Patrimonial\VeicParamRepository;
use App\Repositories\Patrimonial\VeiculosRepository;
use App\Repositories\Patrimonial\VeicMotoristasRepository;
use App\Repositories\Patrimonial\VeicRetiradaRepository;
use App\Services\DTO\Patrimonial\StatusReturnDto;
use App\Services\Financeiro\EmpEmpenhoService;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command\GetBaixaVeiculo;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command\verificaEmpenhoAbastecimentoDataEmiss;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command\VerificaEmpenhoAbastecimentoElemento;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command\verificaEmpenhoInscritoemRestoaPagar;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command\VerificaRetidadaSemDevolucao;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\ResultValidatorFieldsImported;
use App\Services\Patrimonial\Veiculo\EmpVeiculosService;
use App\Services\Patrimonial\Veiculo\VeicAbastPostoService;
use App\Services\Patrimonial\Veiculo\VeicAbastService;
use App\Services\Patrimonial\Veiculo\VeicDevolucaoService;
use App\Services\Patrimonial\Veiculo\VeicRetiradaService;
use App\Repositories\Contabilidade\CondataconfRepository;
use DateTime;
use Error;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Veiculo;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Command\GetDadosUltimaMovimentacao;
use App\Services\Patrimonial\Veiculo\VeicAbastRetiradaService;

class ImportacaoAbastecimentoService
{
    private const RETIRADA = 'RETIRADA';
    private const ABASTECIMENTO = 'ABASTECIMENTO';
    private const DEVOLUCAO = 'DEVOLUCAO';
    private const MANUTENCAO = 'MANUTENCAO';

    /**
     * @var VeicRetiradaService
     */
    private VeicRetiradaService $veiRetiradaService;

    /**
     * @var VeiculosRepository
     */
    private VeiculosRepository $veiculosRepository;

    /**
     * @var CgmRepository
     */
    private CgmRepository $cgmRepository;

    /**
     * @var VeicAbastService
     */
    private VeicAbastService $veicAbastService;

    /**
     * @var EmpVeiculosService
     */
    private EmpVeiculosService $empVeiculosService;

    /**
     * @var VeicAbastPostoService
     */
    private VeicAbastPostoService $veicAbastPostoService;

    /**
     * @var VeicDevolucaoService
     */
    private VeicDevolucaoService $veicDevolucaoService;

    /**
     *
     * @var StatusReturnDto
     */
    private StatusReturnDto $statusReturnDto;

    private VeicParamRepository $veicParamRepository;

    private EmpEmpenhoService $empempenhoService;

    private VeicAbastRetiradaService $veicAbastRetiradaService;

    /**
     * @var VeicMotoristasRepository
     */
    private VeicMotoristasRepository $motoristasRepository;

    /**
     * @var VeicRetiradaRepository
     */
    private VeicRetiradaRepository $veicRetiradaRepository;

    /**
     * @var VeicCentralRepository
     */
    private VeicCentralRepository $veicCentralRepository;

    /**
     * @var VeicCadCentralRepository
     */
    private VeicCadCentralRepository $veicCadCentralRepository;

    private CondataconfRepository $conDataConfRepository;

    private EmpEmpenhoRepository $empempenhoRepository;

    public function __construct()
    {
        $this->veiRetiradaService = new VeicRetiradaService();
        $this->veiculosRepository = new VeiculosRepository();
        $this->cgmRepository = new CgmRepository();
        $this->motoristasRepository = new VeicMotoristasRepository();
        $this->veicAbastPostoService =  new VeicAbastPostoService();
        $this->veicDevolucaoService = new VeicDevolucaoService();
        $this->veicAbastService = new VeicAbastService();
        $this->empVeiculosService = new EmpVeiculosService();
        $this->statusReturnDto = new StatusReturnDto();
        $this->veicParamRepository = new veicParamRepository();
        $this->empempenhoService = new EmpEmpenhoService();
        $this->veicAbastRetiradaService = new VeicAbastRetiradaService();
        $this->veicCentralRepository = new VeicCentralRepository();
        $this->veicCadCentralRepository = new VeicCadCentralRepository();
        $this->conDataConfRepository = new CondataconfRepository();
        $this->empempenhoRepository = new EmpEmpenhoRepository();
    }

    public function import(array $movimentacoes): StatusReturnDto
    {
        try {
            $resultValidator =  new ResultValidatorFieldsImported();
            DB::beginTransaction();
            //data do empenho for igual ou posterior a data do parametro
            $validaAbastecimentoPorEmpenho = $this->veicParamRepository->validarAbastecimentoPorEmpenho();
            if ($validaAbastecimentoPorEmpenho) {
                $empenhosSaldoInsuficiente =  $this->getEmpenhoSaldosInsuficiente($movimentacoes,$validaAbastecimentoPorEmpenho->ve50_datacorte);
                if (count($empenhosSaldoInsuficiente) > 0) {
                    $message = implode($empenhosSaldoInsuficiente);
                    throw new Exception($message);
                }
            }

            foreach ($movimentacoes as $key => $movimentacao) {

                try {
                    $movimentacaoValida = [];
                    $movimentacaoValida['id'] = $movimentacao->id;
                    $movimentacaoValida['codigoAbastecimentoPlanilha'] = $movimentacao->codigo_abastecimento;
                    $movimentacaoValida['data'] = DateTime::createFromFormat('d/m/Y', $movimentacao->data);
                    $movimentacaoValida['dataFormatoBanco'] = ($movimentacaoValida['data'])->format('Y-m-d');
                    $movimentacaoValida['hora'] = DateTime::createFromFormat('H:i:s', $movimentacao->hora) ?: DateTime::createFromFormat('H:i', $movimentacao->hora);
                    $movimentacaoValida['horaFormatoBanco'] = ($movimentacaoValida['hora'])->format('H:i');
                    $movimentacaoValida['data_servidor'] = date("Y-m-d", db_getsession('DB_uol_hora'));
                    $movimentacaoValida['hora_servidor'] = date("H:i", db_getsession('DB_uol_hora'));

                    $placa = str_replace("-", '', $movimentacao->placa);
                    $veiculos = $this->veiculosRepository->getVeiculosByPlacaAndInstit($placa,db_getsession('DB_instit') ,['ve01_codigo', 've01_placa']);

                    if(empty($veiculos)){
                        throw new Exception('Veículo não encontrado');
                    }

                    $veiculosCodigo = array_column($veiculos, 've01_codigo');
                    $veiculo = $this->veiculosRepository->getVeiculoNotBaixa($veiculosCodigo, $movimentacao->data);

                    if(empty($veiculo)){
                        throw new Exception('Veículo baixado');
                    }

                    $verificaRetiradaSemDevolucaoCommand = new VerificaRetidadaSemDevolucao();
                    $retiradaSemDevolucao = $verificaRetiradaSemDevolucaoCommand->execute($veiculo->ve01_codigo);

                    if($retiradaSemDevolucao[0]->ve60_codigo){
                        throw new Exception('Veículo possui retida sem devolução codigo : '.$retiradaSemDevolucao[0]->ve60_codigo);
                    }

                    $movimentacaoValida['codigoVeiculo'] = $veiculo->ve01_codigo;
                    $seqCentral = $this->veicCentralRepository->getCentralByVeiculo($veiculo->ve01_codigo);
                    if(!$seqCentral){
                        throw new Exception('Veículo não esta vinculado a nenhuma central.');
                    }
                    $codDepartamento = $this->veicCadCentralRepository->getCodDepartamentobyCentral($seqCentral->ve40_veiccadcentral);
                    $movimentacaoValida['codigoDepartamento'] = $codDepartamento->ve36_coddepto;
                    $movimentacaoValida['codigoCombustivel'] = $this->getCodigoCombustivel($veiculo, $movimentacao->combustivel);

                    $movimentacaoValida['cpf'] = $movimentacao->cpf;

                    $movimentacaoValida['motorista'] = $movimentacao->motorista;

                    $motoristaCgm = $this->cgmRepository->getMotoristaByCpf($movimentacaoValida['cpf']);

                    if (empty($motoristaCgm->ve05_codigo)) {
                        throw new Exception('Motorista não encontrado');
                    }

                    $movimentacaoValida['codigoMotorista'] = $motoristaCgm->ve05_codigo;

                    $movimentacaoValida['unidade'] = $movimentacao->unidade;
                    $movimentacaoValida['subunidade'] = $movimentacao->subunidade;
                    $movimentacaoValida['kmAbs'] = (int)$movimentacao->km_abs;
                    $movimentacaoValida['quantidadeLitros'] = $movimentacao->quantidade_litros;
                    $movimentacaoValida['precoUnitario'] = $movimentacao->preco_unitario;
                    $movimentacaoValida['valor'] = (float)$movimentacao->valor;
                    $movimentacaoValida['produto'] = $movimentacao->produto;
                    $movimentacaoValida['estado'] = $movimentacao->estado;
                    $movimentacaoValida['empenho'] = $movimentacao->empenho;
                    $movimentacaoValida['datacorte'] = \DateTime::createFromFormat('Y-m-d', $validaAbastecimentoPorEmpenho->ve50_datacorte);
                    $empenho = explode("/", $movimentacaoValida['empenho']);
                    $empenhoData = $this->empempenhoRepository->getEmpenho($empenho[0], $empenho[1]);
                    $movimentacaoValida['dataempenho'] = \DateTime::createFromFormat('Y-m-d', $empenhoData->e60_emiss);

                    $this->validaEncerramentoPatrimonial($movimentacaoValida);
                    $this->validaEntradas($movimentacaoValida);

                    if ($validaAbastecimentoPorEmpenho->ve50_abastempenho == 1) {
                        if($movimentacaoValida['dataempenho'] >= $movimentacaoValida['datacorte']){
                            $this->empempenhoService->alterarSaldoDisponivel($movimentacaoValida['valor'], $movimentacaoValida['empenho']);
                        }
                    }

                    $retirada = $this->veiRetiradaService->insert($movimentacaoValida);
                    if (empty($retirada->ve60_codigo)) {
                        throw new Exception('Não foi possivel inserir na tabela veiretirada ');
                    }

                    $movimentacaoValida['codigoRetirada'] = $retirada->ve60_codigo;

                    $abastecimento = $this->veicAbastService->insert($movimentacaoValida);
                    if (empty($abastecimento->ve70_codigo)) {
                        throw new Exception('Não foi possivel inserir na tabela veicabastecimento');
                    }
                    $movimentacaoValida['codigoAbastecimento'] = $abastecimento->ve70_codigo;

                    $abastecimentoRetirada = $this->veicAbastRetiradaService->insert($movimentacaoValida['codigoAbastecimento'], $movimentacaoValida['codigoRetirada']);
                    if (empty($abastecimentoRetirada->ve73_codigo)) {
                        throw new Exception('Não foi possível inserir na tabela veicabastretirada');
                    }


                    $empenhoVeiculo = $this->empVeiculosService->insert($movimentacaoValida);
                    if (empty($empenhoVeiculo)) {
                        throw new Exception('Não foi possivel inserir na tabela empveiculo');
                    }

                    $veicAbastPosto = $this->veicAbastPostoService->insert($movimentacaoValida);
                    if (empty($veicAbastPosto)) {
                        throw new Exception('Não foi possivel inserir na tabela veicaabastPosto');
                    }

                    $veicDevolucao = $this->veicDevolucaoService->insert($movimentacaoValida);
                    if (empty($veicDevolucao->ve61_codigo)) {
                        throw new Exception('Não foi possivel inserir na tabela veicadevolucao');
                    }
                } catch (\Exception $e) {
                    $resultValidator->setError($movimentacao->placa, utf8_encode($e->getMessage()), $movimentacao->codigo_abastecimento);
                    continue;
                }
            }

            if ($resultValidator->hasErrors()) {
                $this->statusReturnDto->errors = $resultValidator->getErrors();
                throw new \Exception("Existem erros ao tentar atualizar");
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            $this->statusReturnDto->status = false;
            $this->statusReturnDto->message = $e->getMessage();
        } catch (Error $e) {
            DB::rollBack();
            $this->statusReturnDto->status = false;
            $this->statusReturnDto->message =  utf8_encode($e->getMessage()) . ' arquivo:' . $e->getFile() . ' linha:' . $e->getLine();
        }

        return $this->statusReturnDto;
    }

    private function validaEntradas(array $movimentacaoValida)
    {
        $movimentacaoCommand = new GetDadosUltimaMovimentacao();
        $movimentacoesAnteriores = $movimentacaoCommand->execute($movimentacaoValida['codigoVeiculo'], $movimentacaoValida['dataFormatoBanco'], $movimentacaoValida['horaFormatoBanco']);

        $this->validaempenhoRestoaPagar($movimentacaoValida);
        $this->validaempenhoElementoValido($movimentacaoValida);
        $this->validaempenhoDataEmissvalido($movimentacaoValida);

        if (!empty($movimentacoesAnteriores)) {
            $this->validaMovimentacaoAnterior(self::RETIRADA, $movimentacoesAnteriores, $movimentacaoValida);
            $this->validaMovimentacaoAnterior(self::ABASTECIMENTO, $movimentacoesAnteriores, $movimentacaoValida);
            $this->validaMovimentacaoAnterior(self::DEVOLUCAO, $movimentacoesAnteriores, $movimentacaoValida);
            $this->validaMovimentacaoAnterior(self::MANUTENCAO, $movimentacoesAnteriores, $movimentacaoValida);
        }

        $movimentacoesPosteriores = $movimentacaoCommand->execute($movimentacaoValida['codigoVeiculo'], $movimentacaoValida['dataFormatoBanco'], $movimentacaoValida['horaFormatoBanco'], false);
        $this->validaMovimentacaoPosterior(self::RETIRADA, $movimentacoesPosteriores, $movimentacaoValida);
        $this->validaMovimentacaoPosterior(self::ABASTECIMENTO, $movimentacoesPosteriores, $movimentacaoValida);
        $this->validaMovimentacaoPosterior(self::DEVOLUCAO, $movimentacoesPosteriores, $movimentacaoValida);
        $this->validaMovimentacaoPosterior(self::MANUTENCAO, $movimentacoesPosteriores, $movimentacaoValida);
    }

    private function validaEncerramentoPatrimonial(array $movimentacaoValida)
    {
        $dataEncerramentoPartrimonial = $this->conDataConfRepository->getEncerramentoPatrimonial(db_getsession('DB_anousu'),db_getsession('DB_instit'));
        $dataEncerramentoPartrimonial = \DateTime::createFromFormat('Y-m-d', $dataEncerramentoPartrimonial->c99_datapat);

        if($dataEncerramentoPartrimonial >= $movimentacaoValida['data']){
            throw new Exception("O período já foi encerrado para envio do SICOM. Verifique os dados do lançamento e entre em contato com o suporte. ABASTECIMENTO ".$movimentacaoValida['codigoAbastecimentoPlanilha']);
        }
    }

    private function validaMovimentacaoAnterior(string $tipoMovimentacao, array $movimentacoes, array $movimentacaoValida)
    {
        $movimentacaoAnterior = array_filter($movimentacoes, function ($objeto) use ($tipoMovimentacao) {
            return $objeto->tipo ===  $tipoMovimentacao;
        });

        if (empty($movimentacaoAnterior)) {
            return;
        }

        $movimentacaoAnterior = reset($movimentacaoAnterior);

        $dataMovimentacaoAnterior = new DateTime($movimentacaoAnterior->data);
        $horaRetiradaAnterior = \DateTime::createFromFormat('H:i', $movimentacaoAnterior->hora);
        $dataMovimentacaoValida     = new DateTime($movimentacaoValida['data']->format('Y/m/d'));

        if ($dataMovimentacaoAnterior == $dataMovimentacaoValida && $horaRetiradaAnterior <  $movimentacaoValida['hora'] && intval($movimentacaoValida['kmAbs']) < $movimentacaoAnterior->ultimamedida) {
            throw new Exception("1 - A medida de ABASTECIMENTO precisa ser maior ou igual a ultima medida: {$movimentacaoAnterior->ultimamedida} - {$tipoMovimentacao} - ANTERIOR");
        }

        if ($dataMovimentacaoAnterior == $dataMovimentacaoValida && $horaRetiradaAnterior >  $movimentacaoValida['hora'] && intval($movimentacaoValida['kmAbs']) > $movimentacaoAnterior->ultimamedida) {
            throw new Exception("2 - A medida de ABASTECIMENTO precisa ser maior ou igual a ultima medida: {$movimentacaoAnterior->ultimamedida} - {$tipoMovimentacao} - ANTERIOR");
        }

        if ($dataMovimentacaoAnterior < $dataMovimentacaoValida && intval($movimentacaoValida['kmAbs']) < $movimentacaoAnterior->ultimamedida) {
            throw new Exception("3 - A medida de ABASTECIMENTO precisa ser maior ou igual a ultima medida: {$movimentacaoAnterior->ultimamedida} - {$tipoMovimentacao} - ANTERIOR");
        }

        $dataValida = implode('/',array_reverse(explode('-',$movimentacaoValida['dataFormatoBanco'])));

        if ($dataMovimentacaoAnterior == $dataMovimentacaoValida && $movimentacaoAnterior->ultimamedida == intval($movimentacaoValida['kmAbs']) && $horaRetiradaAnterior ==  $movimentacaoValida['hora'] && $tipoMovimentacao == 'ABASTECIMENTO') {
            throw new Exception("4 - Já existe ABASTECIMENTO para esta data e hora. Data: {$dataValida} Hora: {$movimentacaoValida['horaFormatoBanco']} Km: {$movimentacaoValida['kmAbs']} - {$tipoMovimentacao} - DUPLICADO");
        }

    }

    /**
     *
     * @param string $tipoMovimentacao
     * @param array $movimentacoes
     * @param array $movimentacaoValida
     * @return void
     */
    private function validaMovimentacaoPosterior(string $tipoMovimentacao, array $movimentacoes, array $movimentacaoValida)
    {
        $movimentacaoPosterior = array_filter($movimentacoes, function ($objeto) use ($tipoMovimentacao) {
            return $objeto->tipo ===  $tipoMovimentacao;
        });

        if (empty($movimentacaoPosterior)) {
            return;
        }

        $movimentacaoPosterior = reset($movimentacaoPosterior);

        $dataMovimentacao           = new DateTime($movimentacaoPosterior->data);
        $horaMovimentacaoPosterior  = \DateTime::createFromFormat('H:i', $movimentacaoPosterior->hora);
        $dataMovimentacaoValida     = new DateTime($movimentacaoValida['data']->format('Y/m/d'));

        if ($dataMovimentacao == $dataMovimentacaoValida && $horaMovimentacaoPosterior <  $movimentacaoValida['hora'] && intval($movimentacaoValida['kmAbs']) < $movimentacaoPosterior->ultimamedida) {
            throw new Exception("10 - A medida de ABASTECIMENTO precisa ser menor ou igual a proxima medida: {$movimentacaoPosterior->ultimamedida} - {$tipoMovimentacao} - POSTERIOR");
        }

        if ($dataMovimentacao == $dataMovimentacaoValida && $horaMovimentacaoPosterior >  $movimentacaoValida['hora'] && intval($movimentacaoValida['kmAbs']) > $movimentacaoPosterior->ultimamedida) {
            throw new Exception("20 - A medida de ABASTECIMENTO precisa ser menor ou igual a proxima medida: {$movimentacaoPosterior->ultimamedida} - {$tipoMovimentacao} - POSTERIOR");
        }

        if ($dataMovimentacaoValida < $dataMovimentacao  && intval($movimentacaoValida['kmAbs']) > intval($movimentacaoPosterior->ultimamedida)) {
            throw new Exception("30 - A medida de ABASTECIMENTO precisa ser menor ou igual a proxima medida: {$movimentacaoPosterior->ultimamedida} - {$tipoMovimentacao} - POSTERIOR");
        }

        $dataValida = implode('/',array_reverse(explode('-',$movimentacaoValida['dataFormatoBanco'])));

        if ($dataMovimentacao == $dataMovimentacaoValida && intval($movimentacaoPosterior->ultimamedida) == intval($movimentacaoValida['kmAbs']) && $horaMovimentacaoPosterior ==  $movimentacaoValida['hora'] && $tipoMovimentacao == 'ABASTECIMENTO') {
            throw new Exception("40 - Já existe ABASTECIMENTO para esta data e hora. Data: {$dataValida}. Hora: {$movimentacaoValida['horaFormatoBanco']} Km: {$movimentacaoValida['kmAbs']} - {$tipoMovimentacao} - POSTERIOR");
        }
    }

    /**
     * Undocumented function
     *
     * @param array $movimentacoes
     * @return array
     */
    private function validaempenhoElementoValido(array $movimentacoes)
    {
        $comandVerificaEmpenhoAbastecimento = new VerificaEmpenhoAbastecimentoElemento();
        $empvalido = $comandVerificaEmpenhoAbastecimento->execute($movimentacoes['empenho']);

        if(!$empvalido){
            throw new Exception('Subelemento do(os) empenho(os) não permitido(os) para abastecimento. Empenhos: '.$movimentacoes['empenho']);
        }
    }

    /**
     * Undocumented function
     *
     * @param array $movimentacoes
     * @return array
     */
    private function validaempenhoDataEmissvalido(array $movimentacoes)
    {

        $comandVerificaEmpenhoDataEmissValido = new verificaEmpenhoAbastecimentoDataEmiss();
        $empvalido = $comandVerificaEmpenhoDataEmissValido->execute($movimentacoes['empenho'],$movimentacoes['dataFormatoBanco']);

        if(!$empvalido){
            throw new Exception('A data do empenho deve ser igual ou anterior à data do abastecimento. Empenhos com data posterior: '.$movimentacoes['empenho']);
        }
    }

    /**
     * Undocumented function
     *
     * @param array $movimentacoes
     * @return array
     */
    private function validaempenhoRestoaPagar(array $movimentacoes)
    {

        $comandVerificaEmpenhoRestoaPagar = new verificaEmpenhoInscritoemRestoaPagar();
        $empvalido = $comandVerificaEmpenhoRestoaPagar->execute($movimentacoes['empenho']);

        if(!$empvalido){
            throw new Exception('O(os) empenho(os) selecionado(os) não está(ão) inscrito(os) em restos a pagar. Empenhos: '.$movimentacoes['empenho']);
        }
    }

    /**
     * Undocumented function
     *
     * @param array $movimentacoes
     * @return array
     */
    private function getEmpenhoSaldosInsuficiente(array $movimentacoes, string $dataCorte): array
    {
        $empenhosSemSaldo = [];
        $empenhosPorValor = array_reduce($movimentacoes, function ($acumulador, $item) {
            $grupo = $item->empenho;
            if (!isset($acumulador[$grupo])) {
                $acumulador[$grupo] = 0;
            }
            $acumulador[$grupo] = $acumulador[$grupo] + (float)$item->valor;
            return $acumulador;
        }, array());

        $empempenhoRepository = new EmpEmpenhoRepository();

        foreach ($empenhosPorValor as $key => $valor) {
            //verificar se a data do empenho e < do que a data do parametro se for nao validar o saldo.
            $empenho = explode("/", $key);
            $empenhoData = $empempenhoRepository->getEmpenho($empenho[0], $empenho[1]);
            $empdata = \DateTime::createFromFormat('Y-m-d', $empenhoData->e60_emiss);
            $cortedata = \DateTime::createFromFormat('Y-m-d', $dataCorte);

            if ($empdata >= $cortedata){
                $saldoDisponivel = (float) $empenhoData->e60_vlremp - (float)$empenhoData->e60_vlrutilizado;
                if ($saldoDisponivel < (float)$valor) {
                    $empenhosSemSaldo[$key] = "Empenho {$key} não tem saldo suficiente. <br />";
                }
            }
        }

        return $empenhosSemSaldo;
    }

    /**
     *
     * @param Veiculo $veiculo
     * @param string $combustivelBusca
     * @return void
     */
    private function getCodigoCombustivel(Veiculo $veiculo, string  $combustivelBusca)
    {
        $listaCombustivel = $veiculo->veiculoComb()->get();

        $combustivelValido = $listaCombustivel->filter(function ($value, $key) use ($combustivelBusca) {
            return $value->veicCadComb->ve26_descr === $combustivelBusca;
        });

        if ($combustivelValido->count() !== 1) {
            throw new Exception("Combustível inválido para este veículo");
        }

        $keys = $combustivelValido->keys();

        return $combustivelValido[$keys[0]]->ve06_veiccadcomb;
    }

}
