<?php

namespace App\Http\Requests\Api;

use App\Services\InputValidator;
use Illuminate\Foundation\Http\FormRequest;

class StoreShortApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
                ? $validator->errors()->add('url_origin', 'The URL contains a blocked domain.')
                : null;
        });
    }
}
