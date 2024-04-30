<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ownerUpdateRequest extends FormRequest
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
            'username'=>'sometimes|string|max:255|unique:owners',
            'owner_name'=>'sometimes|string|max:200',
            'password'=>'sometimes|string|',
            'phone'=>'sometimes|string|unique:owners',
            'national_id'=>'sometimes',
            'status'=>'sometimes|boolean',
            'image' => 'sometimes|image|mimes:jpg,bmp,png',
        ];
    }
}




