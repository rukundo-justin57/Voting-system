<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CastVoteRequest extends FormRequest
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
     * Accepts an array of candidate IDs, one per position.
     * Each candidate must exist and be valid.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'candidate_ids' => ['required', 'array', 'min:1'],
            'candidate_ids.*' => ['required', 'integer', 'exists:candidates,candidate_id'],
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
            'candidate_ids.required' => 'Please select at least one candidate to vote for.',
            'candidate_ids.array' => 'Invalid vote format.',
            'candidate_ids.*.required' => 'Please select a candidate for each position.',
            'candidate_ids.*.exists' => 'One or more selected candidates are not valid.',
        ];
    }
}
