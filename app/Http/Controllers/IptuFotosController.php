<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileImageRequest;
use App\Models\Cadastro\IptuFoto;
use App\Services\FileService;
use App\Services\Tributario\Cadastro\StoreIptuFotoService;
use App\Support\Storage\UrlPresigner;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class IptuFotosController extends Controller
{
    /**
     * @throws Exception
     */
    public function upload(FileImageRequest $request, StoreIptuFotoService $storeIptuFotoService): JsonResponse
    {
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'Arquivo não encontrado.'], 400);
        }
        try {
            $storeIptuFotoService->execute($request);
        } catch (Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }

        return response()->json(['success' => 'Imagem salva com sucesso.']);
    }

    public function delete(int $id, int $matric, FileService $fileService): JsonResponse
    {
        $iptuFoto = IptuFoto::query()->where('id', $id)->where('j54_matric', $matric)->first();

        try {

            if(!$iptuFoto->exists()) {
                throw new Exception("Imagem nao encontrada.");
            }
            if($iptuFoto->j54_principal) {
                throw new Exception('Nao e possivel excluir a imagem principal.');
            }
            $fileService->deleteFilesFromStorage($iptuFoto->j54_url);
            $iptuFoto->delete();
        } catch (Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }

        return response()->json(['success' => 'Imagem apagada com sucesso.']);
    }

    public function update(Request $request): JsonResponse
    {
        try {
            if($request->post('principal') === "true") {
                IptuFoto::where('j54_principal', true)
                    ->update(['j54_principal' => false]);
            }

            IptuFoto::find($request->post('id'))
                ->update(
                    [
                        'j54_fotoativa' => $request->post('ativa') === "true",
                        'j54_principal' => $request->post('principal') === "true",
                    ]
            );
        } catch (Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }
        return response()->json(['success' => 'Imagem alterada com sucesso.']);
    }

    public function list(int $matric): array
    {
        $images = IptuFoto::query()->where('j54_matric', $matric)->orderBy('id')->get()->toArray();

        return ['itens' => $images];
    }

    /**
     * @throws Exception
     */
    public function show(int $id): JsonResponse
    {
        try {
            $iptuFotos = IptuFoto::find($id);
            $url = $iptuFotos->j54_url;
        } catch (Throwable $throwable) {
            return response()->json(['error' => $throwable->getMessage()], 500);
        }

        return response()->json(['url' => (new UrlPresigner())->getPresignedUrl($url)]);
    }
}
