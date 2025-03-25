<?php

namespace App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LimparLancesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipoJulg' => 'required|numeric',
            'numeroLoteCodigo' => 'required_if:tipoJulg,3|string',
            'orcamentoItemCodigo' => 'required_if:tipoJulg,1|string',
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
            'orcamentoItemCodigo.string' => StringHelper::toUtf8('O código do item de orçamento deve ser numérico ou texto numerico válido.'),
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => StringHelper::toUtf8('Erro de validação ao limpar lance.'),
            'errors' => $validator->errors(),
        ], 422));
    }
}
