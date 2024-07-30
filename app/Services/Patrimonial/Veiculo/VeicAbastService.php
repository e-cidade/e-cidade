<?php

namespace App\Services\Patrimonial\Veiculo;

use App\Models\VeicAbast;
use App\Models\Veiculo;
use App\Repositories\Patrimonial\VeicAbastRepository;
use Exception;

class VeicAbastService
{
    /**
     * @var VeicAbastRepository
     */
    private VeicAbastRepository $veicAbastRepository;


    public function __construct()
    {
        $this->veicAbastRepository = new VeicAbastRepository();
    }

    /**
     * @param array $dados
     * @return VeicAbast
     */
    public function insert(array $dados): ?VeicAbast
    {
        $abastecimento = [];

        $abastecimento['ve70_veiculos'] = $dados['codigoVeiculo'];
        $abastecimento['ve70_dtabast'] = $dados['dataFormatoBanco'];
        $abastecimento['ve70_veiculoscomb'] = $dados['codigoCombustivel'];
        $abastecimento['ve70_medida'] = $dados['kmAbs'];
        $abastecimento['ve70_litros'] = $dados['quantidadeLitros'];
        $abastecimento['ve70_valor'] = $dados['valor'];
        $abastecimento['ve70_vlrun'] = $dados['precoUnitario'];
        $abastecimento['ve70_ativo'] = true;
        $abastecimento['ve70_usuario'] = db_getsession("DB_id_usuario");
        $abastecimento['ve70_data'] = $dados['data_servidor'];
        $abastecimento['ve70_hora'] = $dados['horaFormatoBanco'];
        $abastecimento['ve70_observacao'] = "";
        $abastecimento['ve70_origemgasto'] = 2;
        $abastecimento['ve70_importado'] = true;

        return $this->veicAbastRepository->insert($abastecimento);
    }

    /**
     * @param array $movimentacao
     * @param Veiculo $veiculo
     * @return void
     */
    public function validaInsercaoAbastecimento(array $movimentacao, Veiculo $veiculo): void
    {
        $abastecimento = $veiculo->veicAbast()
            ->where('ve70_dtabast', $movimentacao['dataFormatoBanco'])
            ->where('ve70_medida', $movimentacao['kmAbs'])
            ->first();

        if (!empty($abastecimento)) {
            throw new \Exception("O abastecimento {$abastecimento->ve70_codigo} possui uma quilometragem igual a inserida");
        }

        VeicRetiradaService::validaRetiradaAnterior($movimentacao,  $veiculo);
        VeicRetiradaService::validaRetiradaPosterior($movimentacao,  $veiculo);
    }

    public static function validaAbastecimentoAnterior($movimentacao, $veiculo)
    {
        $abastecimentoAnterior = $veiculo->veicAbast()
            ->where('ve70_medida', '<=', $movimentacao['kmAbs'])
            ->orderBy('ve70_medida', 'desc')
            ->first();

        if (empty($abastecimentoAnterior)) return;

        $dataAbastecimentoAnterior = \DateTime::createFromFormat('Y-m-d', $abastecimentoAnterior->ve70_dtabast);

        if ($dataAbastecimentoAnterior >  $movimentacao['data']) {
            throw new Exception("A data de precisa ser maior que a data de abastecimento {$dataAbastecimentoAnterior->format('d/m/Y')}");
        }

        if ($abastecimentoAnterior->ve70_medida >= $movimentacao['kmAbs']) {
            throw new Exception("A medida de abastecimento  precisa ser maior que {$abastecimentoAnterior->ve70_medida}");
        }
    }

    public static function validaAbastecimentoPosterior($movimentacao, $veiculo)
    {
        $abastecimentoPosterior = $veiculo->veicAbast()
            ->where('ve70_medida', '>=', $movimentacao['kmAbs'])
            ->orderBy('ve70_medida', 'asc')
            ->first();

        if (empty($abastecimentoPosterior)) return;

        $dataAbastecimentoPosterior = \DateTime::createFromFormat('Y-m-d', $abastecimentoPosterior->ve70_dtabast);

        if ($dataAbastecimentoPosterior  < $movimentacao['data']) {
            throw new Exception("A data de saida precisa ser menor que de abastecimento: {$dataAbastecimentoPosterior->format('d/m/Y')}");
        }

        if ($abastecimentoPosterior->ve70_medida <= $movimentacao['kmAbs']) {
            throw new Exception("A medida  precisa ser menor que: {$abastecimentoPosterior->ve70_medida}");
        }
    }
}
