<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateCandidateRequest extends FormRequest
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
        $candidateId = $this->route('candidate')?->candidate_id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('candidates', 'name')->ignore($candidateId, 'candidate_id'),
            ],
            'position' => ['required', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:500'],
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
            'name.required' => 'The candidate name is required.',
            'name.unique' => 'A candidate with this name already exists.',
            'name.max' => 'The candidate name must not exceed 255 characters.',
            'position.required' => 'The position field is required.',
            'position.max' => 'The position must not exceed 100 characters.',
            'bio.max' => 'The biography must not exceed 500 characters.',
        ];
    }
}
