<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShortRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorization handled explicitly in controller
    }

    public function rules(): array
    {
        return [
            'url_origin' => ['required', 'url', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'url_origin.required' => 'A URL de destino é obrigatória.',
            'url_origin.url' => 'Informe uma URL válida.',
            'url_origin.max' => 'A URL não pode ter mais de 2048 caracteres.',
        ];
    }
}
