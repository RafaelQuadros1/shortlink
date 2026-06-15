<?php

namespace App\Http\Requests;

use App\Services\InputValidator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShortRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('short'));
    }

    public function rules(): array
    {
        return [
            'url_origin' => ['required', 'url', 'max:2048'],
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
            'url_origin.required' => 'A URL de destino é obrigatória.',
            'url_origin.url' => 'Informe uma URL válida.',
            'url_origin.max' => 'A URL não pode ter mais de 2048 caracteres.',
        ];
    }
}
