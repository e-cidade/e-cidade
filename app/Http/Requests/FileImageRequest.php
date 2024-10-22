<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileImageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => [
                'mimes:jpeg,png,jpg',
                'max:2000',
            ],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'file.mimes' => 'Deve ser enviado um arquivo do tipo jpg, png, ou jpeg.',
            'file.max' => 'Não são permitidas imagens com mais de 2MB.',
        ];
    }
}
