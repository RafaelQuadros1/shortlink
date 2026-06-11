<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreShortRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'url_origin' => ['required', 'url', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'url_origin.required' => 'O campo URL de origem é obrigatório.',
            'url_origin.url' => 'O campo URL de origem deve ser uma URL válida.',
            'url_origin.max' => 'O campo URL de origem não pode exceder 2048 caracteres.',
        ];
    }
}
