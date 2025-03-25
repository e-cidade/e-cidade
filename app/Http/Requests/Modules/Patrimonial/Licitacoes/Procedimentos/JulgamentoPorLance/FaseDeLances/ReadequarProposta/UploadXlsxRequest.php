<?php

namespace App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\ReadequarProposta;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadXlsxRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'xlsx_file' => 'required|file|mimes:xlsx|max:10240',
        ];
    }

    public function messages()
    {
        return [
            'xlsx_file.required' => StringHelper::toUtf8('O arquivo XLSX é obrigatório.'),
            'xlsx_file.file' => StringHelper::toUtf8('O campo deve ser um arquivo.'),
            'xlsx_file.mimes' => StringHelper::toUtf8('O arquivo deve ser do tipo XLSX.'),
            'xlsx_file.max' => StringHelper::toUtf8('O arquivo não pode ter mais de 10MB.'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Erro na validação do arquivo XLSX.',
            'errors' => $validator->errors(),
        ], 422));
    }
}