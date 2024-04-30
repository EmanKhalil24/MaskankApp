<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RenterUpdateRequest extends FormRequest
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
            'username'=>'sometimes|string|max:255|unique:renters',
            'renter_name'=>'sometimes|string|max:200',
            'email'=>'sometimes|email|unique:renters',
            'password'=>'sometimes|string|',
            'phone'=>'sometimes|string|unique:renters',
            'photo' => 'sometimes|image|mimes:jpg,bmp,png',
        ];
    }
}
