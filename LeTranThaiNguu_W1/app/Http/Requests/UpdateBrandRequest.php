<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'required'
            
        ];
    }
    public function messages(): array
    {
        return[
            
            'name.required' => 'Không được bỏ trống tên thương hiệu!'
        ];
    }
}
