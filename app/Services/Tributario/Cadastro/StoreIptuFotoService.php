<?php

namespace App\Services\Tributario\Cadastro;

use App\Http\Requests\FileImageRequest;
use App\Models\Cadastro\IptuFoto;
use App\Services\FileService;
use App\Support\Storage\BucketPath;
use Exception;
use Illuminate\Http\UploadedFile;

class StoreIptuFotoService
{
    private IptuFoto $iptuFotos;
    private FileService $fileService;

    public function __construct(IptuFoto $iptuFotos, FileService $fileService)
    {
        $this->iptuFotos = $iptuFotos;
        $this->fileService = $fileService;
    }

    /**
     * @throws Exception
     */
    public function execute(FileImageRequest $request): string
    {
        $image = $request->file('image');
        $matric = $request->post('iMatric');
        $url = $this->upload($image, $matric);
        $this->save($request, $image, $url, $matric);
        return $url;
    }

    private function upload(UploadedFile $uploadFile, int $matric): string
    {
        $bucketPath = BucketPath::IPTU_FOTOS . '/' . $matric;
        return $this->fileService->upload($uploadFile, $bucketPath);
    }

    private function save(FileImageRequest $request, UploadedFile $uploadFile, string $url, int $matric): void
    {

        if($request->post('principal') === "true") {
            $this->iptuFotos->newQuery()
                ->where('j54_principal', true)
                ->update(['j54_principal' => false]);
        }

        $this->iptuFotos->create(
            [
                'j54_fotoativa' => $request->post('ativa') === "true",
                'j54_principal' => $request->post('principal') === "true",
                'j54_matric' => $matric,
                'j54_url' => $url,
                'j54_nomearquivo' => time().".".$uploadFile->getClientOriginalExtension()
            ]
        );
    }
}
