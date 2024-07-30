<?php

namespace App\Services\Patrimonial\Veiculo\Abastecimento\Importacao\Adapter;

use App\Services\ExcelService;

class UploadXlsAbastecimentoAdapter
{
    private const DIRECTORY_PATH = 'tmp/';
    private ExcelService $excelService;

    public function __construct()
    {
        $this->excelService = new  ExcelService();
    }

    public function import(array $file, string $nomeArquivo): ?array
    {
        $fullPath = self::DIRECTORY_PATH . $nomeArquivo;

        if (empty($file['arquivo']) || empty($nomeArquivo)) {
            throw new \Exception('Parametro incorreto! Verifique se o arquivo foi anexado');
        }

        if (!empty($file['arquivo']['tmp_name'])) {
            $uploadArquivo  = move_uploaded_file($file['arquivo']['tmp_name'], $fullPath);

            if (!$uploadArquivo) {
                throw new \Exception('Erro ao importar arquivo ' . $nomeArquivo);
            }
        }

        $dadosImportados = $this->excelService->importFile($fullPath);

        foreach ($dadosImportados as $key => $dado) {

            if (empty($dado['A'])) {
                continue;
            }

            if ($key > 6) {
                $dadosFiltrados[$key] =  $this->hidratarDadosImportados($dado);
            }
        }
        return $dadosFiltrados;
    }

    private function hidratarDadosImportados(array $dado): array
    {
        $dadosFormatados = [];
        $arrayIndexFormatado = [
            'codigo_abastecimento', 'data', 'horario',
            'placa', 'motorista', 'cpf', 'unidade', 'subunidade', 'combustivel',
            'km_abs', 'quantidade_litros', 'preco_unitario', 'valor', 'status', 'produto'
        ];

        $arrayIndexRaw = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];

        foreach ($arrayIndexFormatado as $key => $value) {
            $dadosFormatados[$value] = trim($dado[$arrayIndexRaw[$key]]);
        }

        return $dadosFormatados;
    }
}
