<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPostApprovedRequest extends FormRequest
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
            // 'images' => 'required|mimes:png,jpeg,jpg,gif',
            'images' => 'sometimes|string',
            'description' => 'sometimes|string',
            'price' => 'sometimes|integer',
            'size' => 'sometimes|integer',
            'purpose' => 'sometimes|string',
            'bedrooms' => 'sometimes|numeric',
            'bathrooms' => 'sometimes|numeric',
            'region' => 'sometimes|string',
            'city' => 'sometimes|string',
            'floor' => 'sometimes|string',
            'condition' => 'sometimes|string',
            'status' => 'required|boolean',
            'ownerId' => 'sometimes|numeric|exists:owners,owner_id',
        ];
    }
}
