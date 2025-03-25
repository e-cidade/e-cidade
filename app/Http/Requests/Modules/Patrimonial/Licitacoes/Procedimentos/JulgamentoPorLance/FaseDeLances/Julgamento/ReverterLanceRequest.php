<?php

namespace App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReverterLanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipoJulg' => 'required|numeric',
            'numeroLoteCodigo' => 'required_if:tipoJulg,3|numeric',
            'orcamentoItemCodigo' => 'required_if:tipoJulg,1|numeric',
        ];
    }

    public function messages()
    {
        return [
            'tipoJulg.required' => StringHelper::toUtf8('O número do lote é obrigatório.'),
            'tipoJulg.numeric' => StringHelper::toUtf8('O número do lote deve ser numérico.'),
            
            'numeroLoteCodigo.required_if' => StringHelper::toUtf8('O número do lote é obrigatório.'),
            'numeroLoteCodigo.numeric' => StringHelper::toUtf8('O número do lote deve ser numérico.'),

            'orcamentoItemCodigo.required_if' => StringHelper::toUtf8('O código do item de orçamento é obrigatório.'),
            'orcamentoItemCodigo.numeric' => StringHelper::toUtf8('O código do item de orçamento deve ser numérico.')
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => StringHelper::toUtf8('Erro de validação para registrar lance.'),
            'errors' => $validator->errors(),
        ], 422));
    }
}
