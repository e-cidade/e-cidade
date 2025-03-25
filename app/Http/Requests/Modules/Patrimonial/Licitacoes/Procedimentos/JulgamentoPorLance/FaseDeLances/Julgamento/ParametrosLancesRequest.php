<?php

namespace App\Http\Requests\Modules\Patrimonial\Licitacoes\Procedimentos\JulgamentoPorLance\FaseDeLances\Julgamento;

use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ParametrosLancesRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'l13_precoref' => 'required|numeric',
            'l13_difminlance' => 'required|numeric',
            'l13_clapercent' => 'nullable',
            'l13_avisodeacoestabela' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'l13_precoref.required' => StringHelper::toUtf8('O precoref é obrigatório.'),
            'l13_precoref.numeric' => StringHelper::toUtf8('O precoref deve ser numérico.'),

            'l13_difminlance.required' => StringHelper::toUtf8('O difminlance é obrigatório.'),
            'l13_difminlance.numeric' => StringHelper::toUtf8('O difminlance deve ser numérico.'),

            'l13_avisodeacoestabela.required' => StringHelper::toUtf8('O avisos é obrigatório.'),
            'l13_avisodeacoestabela.numeric' => StringHelper::toUtf8('O avisos deve ser numérico.'),
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
