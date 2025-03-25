<?php

namespace App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AlterarStatusFornecedorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipoJulg' => 'required|numeric',
            'itemCodigo' => 'required|numeric',
            'fornecedorCodigo' => 'required|numeric',
            'fornecedorCategoria' => 'required|numeric',
            'fornecedorMotivo' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'tipoJulg.required' => StringHelper::toUtf8('O número do lote é obrigatório.'),
            'tipoJulg.numeric' => StringHelper::toUtf8('O número do lote deve ser numérico.'),

            'itemCodigo.required' => StringHelper::toUtf8('O código do item é obrigatório.'),
            'itemCodigo.numeric' => StringHelper::toUtf8('O código do item deve ser um valor numérico.'),

            'fornecedorCodigo.required' => StringHelper::toUtf8('O código do fornecedor é obrigatório.'),
            'fornecedorCodigo.numeric' => StringHelper::toUtf8('O código do fornecedor deve ser um valor numérico.'),

            'fornecedorCategoria.required' => StringHelper::toUtf8('A categoria do fornecedor é obrigatório.'),
            'fornecedorCategoria.numeric' => StringHelper::toUtf8('A categoria do fornecedor deve ser um valor numérico.'),

            'fornecedorMotivo.required' => StringHelper::toUtf8('O motivo é obrigatório para alterar o status do fornecedor.'),
            'fornecedorMotivo.string' => StringHelper::toUtf8('O motivo deve ser um texto válido.'),
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