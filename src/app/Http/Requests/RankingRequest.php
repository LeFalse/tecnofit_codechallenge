<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RankingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'movementId' => $this->route('movementId'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'movementId' => 'required|integer',
        ];
    }

    /**
     * Customize the error messages for validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'movementId.required' => 'O campo é obrigatório.',
            'movementId.integer' => 'O campo deve ser um número inteiro.',
        ];
    }
}
