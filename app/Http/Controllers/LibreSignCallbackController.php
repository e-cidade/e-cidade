<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class LibreSignCallbackController extends Controller
{
    /**
     * Recebe o PDF assinado do middleware e o armazena de volta no e-cidade.
     *
     * O middleware envia o PDF como application/octet-stream e os metadados
     * nos headers X-Documento-* (tipo, codigo e id). A autenticacao é feita
     * por Bearer token (ECIDADE_CALLBACK_TOKEN).
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $tokenEsperado = (string) env('ECIDADE_CALLBACK_TOKEN', '');
            if ($tokenEsperado !== '') {
                $autorizacao = (string) $request->header('Authorization', '');
                if ($autorizacao !== 'Bearer ' . $tokenEsperado) {
                    return response()->json(['error' => 'Token inválido.'], 401);
                }
            }

            $tipoDocumento = (string) $request->header('X-Documento-Tipo', 'documento');
            $codDocumento  = (string) $request->header('X-Documento-Codigo', '');
            $documentoId   = (string) $request->header('X-Documento-Id', '');

            $pdf = $request->getContent();
            if ($pdf === '') {
                return response()->json(['error' => 'PDF vazio.'], 400);
            }

            // Sanitiza o nome para evitar path traversal
            $nome = preg_replace('/[^A-Za-z0-9_-]/', '_', $tipoDocumento . '-' . $codDocumento);
            $diretorio = storage_path('app/libresign/assinados');
            if (!is_dir($diretorio)) {
                mkdir($diretorio, 0755, true);
            }

            $caminho = $diretorio . '/' . $nome . '-' . $documentoId . '.pdf';
            file_put_contents($caminho, $pdf);

            return response()->json([
                'success' => true,
                'path' => $caminho,
                'tipo_documento' => $tipoDocumento,
                'cod_documento' => $codDocumento,
            ]);
        } catch (Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
    }
}
