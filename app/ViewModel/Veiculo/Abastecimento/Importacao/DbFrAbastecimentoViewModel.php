<?php

namespace App\ViewModel\Veiculo\Abastecimento\Importacao;

use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Adapter\UploadXlsAbastecimentoAdapter;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\ResultValidatorFieldsImported;
use App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Validators\ValidatorFieldsImportedStrategy;
use DateTime;

class DbFrAbastecimentoViewModel
{

    /**
     *
     * @param [type] $nomeArquivo
     * @return array
     */
    public function getDadosImportados($nomeArquivo): array
    {
        $impotadorExcel = new UploadXlsAbastecimentoAdapter();
        $dadosImportados = $impotadorExcel->import($_FILES, $nomeArquivo);
        if (empty($dadosImportados)) {
            $dadosImportados = [];
        }
        return $dadosImportados;
    }

    /**
     *
     * @param array $dadosImportados
     * @return ResultValidatorFieldsImported
     */
    public function validaDadosImportados(array $dadosImportados): ResultValidatorFieldsImported
    {
        return (new ValidatorFieldsImportedStrategy())->execute($dadosImportados);

    }

    public function getMenorData(array $dadosImportados): string
    {
        $menorData = DateTime::createFromFormat('d/m/Y', '01/01/2150');

        foreach ($dadosImportados as $key => $value) {
            $dataAuxiliar = DateTime::createFromFormat('d/m/Y', $value['data']);

            if ($menorData > $dataAuxiliar) {
                $menorData = $dataAuxiliar;
            }
        }

        return $menorData->format('Y-m-d');
    }

    /**
     *
     * @param array $dadosImportados
     * @return string
     */
    public function getMaiorData(array $dadosImportados): string
    {
        $maiorData = DateTime::createFromFormat('d/m/Y', '01/01/1900');

        foreach ($dadosImportados as $key => $value) {
            $dataAuxiliar = DateTime::createFromFormat('d/m/Y', $value['data']);

            if ($maiorData < $dataAuxiliar) {
                $maiorData = $dataAuxiliar;
            }
        }

        return $maiorData->format('Y-m-d');
    }

    public function filtrarPorData($dadosImportados, $dataInicial, $dataFinal)
    {
        return array_filter($dadosImportados, function ($value) use ($dataInicial, $dataFinal) {
            $dataImportada = new DateTime(implode('/',array_reverse(explode('/',$value['data']))));
            return $dataInicial <= $dataImportada && $dataFinal
                >= $dataImportada;
        });
    }

    public function ordenaDados(array &$dadosImportados): void
    {
      array_multisort(array_column($dadosImportados, 'unidade'), SORT_ASC, $dadosImportados);
    }
}
