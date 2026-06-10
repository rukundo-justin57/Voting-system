<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoterLoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'national_id' => ['required', 'string', 'max:20', 'exists:voters,national_id'],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'national_id.required' => 'Please enter your Rwanda National ID Numbers.',
            'national_id.exists' => 'No voter found with this Rwanda National ID Numbers. Please contact the election commission.',
        ];
    }
}
