<?php

namespace App\Http\Requests;

use App\Services\InputValidator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShortRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url_origin' => ['required', 'url', 'max:4096'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $url = $this->input('url_origin');
            if ($url && ! InputValidator::validateUrl($url)) {
                $reason = InputValidator::getUrlValidationError($url);
                $validator->errors()->add('url_origin', $reason);
            }
        });
    }

    public function messages(): array
    {
        return [
            'url_origin.required' => 'A URL de destino é obrigatória.',
            'url_origin.url' => 'Informe uma URL válida.',
            'url_origin.max' => 'A URL não pode ter mais de 4096 caracteres.',
        ];
    }
}
