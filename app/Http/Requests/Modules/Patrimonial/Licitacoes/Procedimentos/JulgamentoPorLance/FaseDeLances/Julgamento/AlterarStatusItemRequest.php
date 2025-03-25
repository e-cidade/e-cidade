<?php

namespace App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AlterarStatusItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipoJulg' => 'required|string',
            'ids' => 'required|string',
            'categorias' => 'required|string',
            'motivo' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'tipoJulg.required' => StringHelper::toUtf8('O tipo do julgamento obrigatório.'),
            'tipoJulg.string' => StringHelper::toUtf8('O código tipo do julgamento deve ser um texto válido.'),

            'ids.required' => StringHelper::toUtf8('O código sequencial do item é obrigatório.'),
            'ids.numeric' => StringHelper::toUtf8('O código sequencial do item deve ser um valor numérico.'),

            'categorias.required' => StringHelper::toUtf8('A categoria é obrigatório para alterar o status do item.'),
            'categorias.string' => StringHelper::toUtf8('A categoria deve ser um texto válido.'),

            'motivo.required' => StringHelper::toUtf8('O motivo é obrigatório para alterar o status do item.'),
            'motivo.string' => StringHelper::toUtf8('O motivo deve ser um texto válido.'),
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