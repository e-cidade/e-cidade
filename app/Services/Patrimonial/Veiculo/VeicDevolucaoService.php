<?php

namespace App\Services\Patrimonial\Veiculo;

use App\Models\VeicDevolucao;
use App\Models\VeicRetirada;
use App\Models\Veiculo;
use App\Repositories\Patrimonial\VeicDevolucaoRepository;
use Exception;

class VeicDevolucaoService
{
    private VeicDevolucaoRepository $veicDevolucaoRepository;

    public function __construct()
    {
        $this->veicDevolucaoRepository = new VeicDevolucaoRepository();
    }
    public function insert(array $dados): ?VeicDevolucao
    {
        $devolucao = [];

        $devolucao['ve61_veicretirada'] = $dados['codigoRetirada'];
        $devolucao['ve61_veicmotoristas'] = $dados['codigoMotorista'];
        $devolucao['ve61_datadevol'] = $dados['dataFormatoBanco'];
        $devolucao['ve61_horadevol'] = $dados['horaFormatoBanco'];
        $devolucao['ve61_medidadevol'] = $dados['kmAbs'];
        $devolucao['ve61_usuario'] = db_getsession("DB_id_usuario");
        $devolucao['ve61_data'] = $dados['data_servidor'];
        $devolucao['ve61_hora'] = $dados['hora_servidor'];
        $devolucao['ve61_importado'] = true;

        return $this->veicDevolucaoRepository->insert($devolucao);
    }

    /**
     * @param array $movimentacao
     * @param Veiculo $veiculo
     * @return void
     */
    public function validaInsercaoDevolucao(array $movimentacao, Veiculo $veiculo): void
    {
        VeicRetiradaService::validaRetiradaAnterior($movimentacao, $veiculo);
        VeicRetiradaService::validaRetiradaPosterior($movimentacao, $veiculo);

        VeicAbastService::validaAbastecimentoAnterior($movimentacao, $veiculo);
        VeicAbastService::validaAbastecimentoPosterior($movimentacao, $veiculo);
    }


    public static function validaDevolucaoVeiculos(array $movimentacao, VeicRetirada $ultimaRetirada): void
    {
        try {
            $devolucoes = $ultimaRetirada->veicDevolucao()
                ->orderBy('ve61_medidadevol', 'desc')
                ->get();

            if ($devolucoes->count() === 0) {
                throw new Exception("Veiculo {$movimentacao['placa']} com retirada sem devolução");
            }

            self::validaDevolucaoAnterior($movimentacao, $ultimaRetirada);
            self::validaDevolucaoPosterior($movimentacao, $ultimaRetirada);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        var_dump("chegou fim da devolucao");
    }

    /**
     *
     * @param array $movimentacao
     * @param VeicRetirada $ultimaRetirada
     * @return void
     */
    public static function validaDevolucaoAnterior(array $movimentacao, VeicRetirada $ultimaRetirada): void
    {
        $ultimaDevolucao = $ultimaRetirada->veicDevolucao()
            ->where('ve61_medidadevol', '<=', $movimentacao['kmAbs'])
            ->whereOr('ve61_datadevol', '<=', $movimentacao['dataFormatoBanco'])
            ->orderBy('ve61_medidadevol', 'desc')
            ->first();


        if (empty($ultimaDevolucao)) {
            var_dump("não era pra entrar aqui");
            return;
        }

        $dataUltimaDevolucao = \DateTime::createFromFormat('Y-m-d', $ultimaDevolucao->ve61_datadevol);
        $horaUltimaDevolucao = \DateTime::createFromFormat('H:i', $ultimaDevolucao->ve61_horadevol);
        $medidaUltimaDevo = (int)$ultimaDevolucao->ve61_medidadevol;

        if ($dataUltimaDevolucao  > $movimentacao['data']) {
            throw new Exception("A data  precisa ser maior que a data de devolução: {$dataUltimaDevolucao->format('d/m/Y')}");
        }

        if ($dataUltimaDevolucao === $movimentacao['data'] && $horaUltimaDevolucao >= $movimentacao['hora']) {

            throw new Exception("A hora precisa ser maior que hora de devolução: {$horaUltimaDevolucao->format('H:i')}");
        }

        if ($medidaUltimaDevo == $movimentacao['kmAbs']) {
            throw new Exception("A medida  precisa ser maior que medida de devolução: {$medidaUltimaDevo}");
        }
        var_dump("não entrou em nenhum throw da retirada verificando devolucao anterior");
    }

    /**
     *
     * @param array $movimentacao
     * @param VeicRetirada $ultimaRetirada
     * @return void
     */
    public static function validaDevolucaoPosterior(array $movimentacao, VeicRetirada $ultimaRetirada): void
    {
        $ultimaDevolucao = $ultimaRetirada->veicDevolucao()
            ->where('ve61_medidadevol', '>=', $movimentacao['kmAbs'])
            ->whereOr('ve61_datadevol', '>=', $movimentacao['dataFormatoBanco'])
            ->orderBy('ve61_medidadevol', 'asc')
            ->first();

        if (empty($ultimaDevolucao)) {
            return;
        }

        $dataUltimaDevolucao = \DateTime::createFromFormat('Y-m-d', $ultimaDevolucao->ve61_datadevol);
        $horaUltimaDevolucao = \DateTime::createFromFormat('H:i', $ultimaDevolucao->ve61_horadevol);
        $medidaUltimaDevo = (int)$ultimaDevolucao->ve61_medidadevol;

        if ($dataUltimaDevolucao  < $movimentacao['data']) {
            throw new Exception("A data  precisa ser menor que data de devolução: {$dataUltimaDevolucao->format('d/m/Y')}");
        }

        if ($dataUltimaDevolucao === $movimentacao['data'] && $horaUltimaDevolucao <= $movimentacao['hora']) {
            throw new Exception("A hora precisa ser menor que hora de devolução: {$horaUltimaDevolucao->format('H:i')}");
        }

        if ($medidaUltimaDevo == $movimentacao['kmAbs']) {
            throw new Exception("A medida  precisa ser menor que medida de devolução: {$medidaUltimaDevo}");
        }
    }
}
