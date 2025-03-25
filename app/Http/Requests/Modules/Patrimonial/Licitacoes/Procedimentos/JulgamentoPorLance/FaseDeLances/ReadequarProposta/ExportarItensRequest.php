<?php

namespace App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\ReadequarProposta;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ExportarItensRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'licitacao' => 'required|string',
            'fornecedor' => 'required|string',
            'lote' => 'required|string',
            'descrforne' => 'required|string',
            'cnpj' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'licitacao.required' => StringHelper::toUtf8('O campo de licitação é obrigatório.'),
            'licitacao.string' => StringHelper::toUtf8('O campo de licitação deve ser um texto válida.'),
            
            'fornecedor.required' => StringHelper::toUtf8('O campo fornecedor é obrigatório.'),
            'fornecedor.string' => StringHelper::toUtf8('O campo fornecedor deve ser uma texto válida.'),
            
            'lote.required' => StringHelper::toUtf8('O campo lote é obrigatório.'),
            'lote.string' => StringHelper::toUtf8('O campo lote deve ser uma texto válida.'),
            
            'descrforne.required' => StringHelper::toUtf8('O campo descrforne é obrigatório.'),
            'descrforne.string' => StringHelper::toUtf8('O campo descrforne deve ser uma texto válida.'),
            
            'cnpj.required' => StringHelper::toUtf8('O campo cnpj é obrigatório.'),
            'cnpj.string' => StringHelper::toUtf8('O campo cnpj deve ser uma texto válida.'),
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