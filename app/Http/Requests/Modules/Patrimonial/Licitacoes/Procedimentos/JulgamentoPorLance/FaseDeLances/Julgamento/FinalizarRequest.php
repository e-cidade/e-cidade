<?php

namespace App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FinalizarRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipoJulg' => 'required|numeric',
            'licitacaoCodigo' => 'required|numeric|min:1',
            'numeroLoteCodigo' => 'required_if:tipoJulg,3|numeric',
            'orcamentoItemCodigo' => 'required_if:tipoJulg,1|numeric',
            'licitacaoItemCodigo' => 'required_if:tipoJulg,1|numeric|min:1'
        ];
    }

    public function messages()
    {
        return [
            'tipoJulg.required' => StringHelper::toUtf8('O número do lote é obrigatório.'),
            'tipoJulg.numeric' => StringHelper::toUtf8('O número do lote deve ser numérico.'),

            'licitacaoCodigo.required' => StringHelper::toUtf8('O código da licitação é obrigatório.'),
            'licitacaoCodigo.numeric' => StringHelper::toUtf8('O código da licitação deve ser numérico.'),
            'licitacaoCodigo.min' => StringHelper::toUtf8('O código da licitação deve ser maior ou igual a um.'),

            'numeroLoteCodigo.required_if' => StringHelper::toUtf8('O número do lote é obrigatório.'),
            'numeroLoteCodigo.numeric' => StringHelper::toUtf8('O número do lote deve ser numérico.'),

            'orcamentoItemCodigo.required_if' => StringHelper::toUtf8('O código do item de orçamento é obrigatório.'),
            'orcamentoItemCodigo.numeric' => StringHelper::toUtf8('O código do item de orçamento deve ser numérico.'),

            'licitacaoItemCodigo.required_if' => StringHelper::toUtf8('O código do item da licitação é obrigatório.'),
            'licitacaoItemCodigo.numeric' => StringHelper::toUtf8('O código do item da licitação deve ser numérico.'),
            'licitacaoItemCodigo.min' => StringHelper::toUtf8('O código do item da licitação deve ser maior ou igual a um.'),
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
