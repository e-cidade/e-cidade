<?php

namespace App\Services\Patrimonial\Veiculo;

use App\Models\VeicDevolucao;
use App\Models\VeicRetirada;
use App\Models\Veiculo;
use App\Repositories\Patrimonial\VeicRetiradaRepository;
use DateTime;
use Exception;

class VeicRetiradaService
{
    /**
     *
     * @var VeicRetiradaRepository
     */
    private VeicRetiradaRepository $veicretiradaRepository;


    public function __construct()
    {
        $this->veicretiradaRepository = new VeicRetiradaRepository();
    }

    /**
     *
     * @param array $dados
     * @return VeicRetirada|null
     * @throws Exception
     */
    public function insert(array $dados): ?VeicRetirada
    {
        $retirada = [];
        $retirada['ve60_veiculo'] = $dados['codigoVeiculo'];
        $retirada['ve60_veicmotoristas'] = $dados['codigoMotorista'];
        $retirada['ve60_datasaida'] = $dados['dataFormatoBanco'];
        $retirada['ve60_horasaida']  = $dados['horaFormatoBanco'];
        $retirada['ve60_medidasaida'] = $dados['kmAbs'];
        $retirada['ve60_destino'] = "";
        $retirada['ve60_coddepto'] = $dados['codigoDepartamento'];
        $retirada['ve60_usuario'] = db_getsession("DB_id_usuario");
        $retirada['ve60_data'] = $dados['data_servidor'];
        $retirada['ve60_hora'] = $dados['hora_servidor'];
        $retirada['ve60_destinonovo'] = null;
        $retirada['ve60_importado'] = true;

        return $this->veicretiradaRepository->insert($retirada);
    }

    /**
     * @param array $movimentacao
     * @param Veiculo $veiculo
     * @return void
     */
    public function validaInsercaoRetirada(array $movimentacao, Veiculo $veiculo)
    {
        $retiradaPosterior = $veiculo->veicRetirada()
        ->where('ve60_medidasaida','>=', $movimentacao['kmAbs'])
        ->orderBy('ve60_medidasaida', 'asc')
        ->first();

        if(empty($retiradaPosterior)) {
            return;
        }

        $medidaRetiradaPosterior = (int)$retiradaPosterior->ve60_medidasaida;

        if ($medidaRetiradaPosterior === $movimentacao['kmAbs']) {
            throw new \LogicException('Existe uma retirada com medidas iguais');
        }

        VeicDevolucaoService::validaDevolucaoVeiculos($movimentacao, $retiradaPosterior);
    }

    public static function validaRetiradaAnterior(array $movimentacao, Veiculo $veiculo): void
    {
        $retiradaAnterior = $veiculo->veicRetirada()
            ->where('ve60_medidasaida', '<=', $movimentacao['kmAbs'])
            ->orderBy('ve60_medidasaida', 'desc')
            ->first();

        if (!empty($retiradaAnterior)) {
            $dataRetiradaAnterior = \DateTime::createFromFormat('Y-m-d', $retiradaAnterior->ve60_datasaida);
            $horaRetiradaAnterior = \DateTime::createFromFormat('H:i', $retiradaAnterior->ve60_horasaida);
            $medidaRetiradaAnterior = (int)$retiradaAnterior->ve60_medidasaida;

            if ($dataRetiradaAnterior > $movimentacao['data']) {
                throw new Exception("A data precisa ser maior que  a data de saída: {$dataRetiradaAnterior->format('d/m/Y')}");
            }

            if ($dataRetiradaAnterior === $movimentacao['data'] && $horaRetiradaAnterior >=  $movimentacao['hora']) {
                throw new Exception("A hora precisa ser maior que {$horaRetiradaAnterior->format('H:i')}");
            }

            if ($medidaRetiradaAnterior == $movimentacao['kmAbs']) {
                throw new Exception("A medida precisa ser maior que medida de saída: {$medidaRetiradaAnterior}");
            }
        }
    }

    public static function validaRetiradaPosterior(array $movimentacao, Veiculo $veiculo): void
    {
        $ultimaRetirada = $veiculo->veicRetirada()
            ->where('ve60_medidasaida', '>=', $movimentacao['kmAbs'])
            ->orderBy('ve60_medidasaida', 'asc')
            ->first();

        if (empty($ultimaRetirada)) {
            return;
        }

        $dataUltimaRetirada = \DateTime::createFromFormat('Y-m-d', $ultimaRetirada->ve60_datasaida);
        $horaUltimaRetirada = \DateTime::createFromFormat('H:i', $ultimaRetirada->ve60_horasaida);
        $medidaUltimaRetirada = (int)$ultimaRetirada->ve60_medidasaida;

        if ($dataUltimaRetirada  < $movimentacao['data']) {
            throw new Exception("A data precisa ser menor que  a data de saída: {$dataUltimaRetirada->format('d/m/Y')}");
        }

        if ($dataUltimaRetirada === $movimentacao['data'] && $horaUltimaRetirada <=  $movimentacao['hora']) {
            throw new Exception("A hora precisa ser menor que hora de saída: {$horaUltimaRetirada->format('H:i')}");
        }

        if ($medidaUltimaRetirada == $movimentacao['kmAbs']) {
            throw new Exception("A medida precisa ser menor que medida de saída: {$medidaUltimaRetirada}");
        }
    }
}
