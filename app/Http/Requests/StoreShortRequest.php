<?php

namespace App\Http\Requests;

use App\Services\InputValidator;
use Illuminate\Foundation\Http\FormRequest;

class StoreShortRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url_origin' => ['required', 'url', 'max:2048'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $url = $this->input('url_origin');
            $url && ! InputValidator::validateUrl($url)
                ? $validator->errors()->add('url_origin', 'A URL contém um domínio não permitido.')
                : null;
        });
    }

    public function messages(): array
    {
        return [
            'url_origin.required' => 'O campo URL de origem é obrigatório.',
            'url_origin.url' => 'O campo URL de origem deve ser uma URL válida.',
            'url_origin.max' => 'O campo URL de origem não pode exceder 2048 caracteres.',
            'website.max' => 'Formulário inválido.',
        ];
    }
}
