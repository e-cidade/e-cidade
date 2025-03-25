<?php

namespace App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LiberarMicroEmpresasRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'codigoLicitacao' => 'required|numeric',
            'codigoLicitacaoItem' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'codigoLicitacao.required' => StringHelper::toUtf8('O código da licitação é obrigatório.'),
            'codigoLicitacao.numeric' => StringHelper::toUtf8('O código da licitação deve ser um valor numérico.'),
            'codigoLicitacaoItem.required' => StringHelper::toUtf8('O código da licitação item é obrigatório.'),
            'codigoLicitacaoItem.numeric' => StringHelper::toUtf8('O código da licitação item deve ser um valor numérico.'),
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => StringHelper::toUtf8('Erro de validação do status do fornecedor.'),
            'errors' => $validator->errors(),
        ], 422));
    }
}