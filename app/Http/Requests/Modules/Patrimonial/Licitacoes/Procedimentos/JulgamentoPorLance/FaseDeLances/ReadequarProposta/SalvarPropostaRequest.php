<?php

namespace App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\ReadequarProposta;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SalvarPropostaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'licitacao' => 'required',
            'lote' => 'required',
            'orcamforne' => 'required',
            'itens' => 'required|array',
            'itens.*.ordem' => 'required',
            'itens.*.vlrUnitario' => 'required|numeric',
            'itens.*.vlrTotal' => 'required|numeric',
            'itens.*.orcamitem' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'licitacao.required' => StringHelper::toUtf8('O campo licitação é obrigatório.'),

            'lote.required' => StringHelper::toUtf8('O campo lote é obrigatório.'),

            'orcamforne.required' => StringHelper::toUtf8('O campo orcamforne é obrigatório.'),

            'itens.required' => StringHelper::toUtf8('O campo itens é obrigatório.'),
            'itens.array' => StringHelper::toUtf8('O campo itens deve ser um array.'),

            'itens.*.ordem.required' => StringHelper::toUtf8('O campo "ordem" é obrigatório e não foi enviado na operação de salvar a proposta.'),

            'itens.*.vlrUnitario.required' => StringHelper::toUtf8('O valor unitário do item é obrigatório e não foi informado.'),
            'itens.*.vlrUnitario.numeric' => StringHelper::toUtf8('O valor unitário do item deve ser um número válido.'),

            'itens.*.vlrTotal.required' => StringHelper::toUtf8('O valor total do item é obrigatório e não foi informado.'),
            'itens.*.vlrTotal.numeric' => StringHelper::toUtf8('O valor total do item deve ser um número válido.'),

            'itens.*.orcamitem.required' => StringHelper::toUtf8('O campo "orcamitem" é obrigatório e não foi enviado na operação de salvar a proposta.'),
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