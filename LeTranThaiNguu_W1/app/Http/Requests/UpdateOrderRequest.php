<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'delivery_name' => 'required'
            
        ];
    }
    public function messages(): array
    {
        return[
            
            'delivery_name.required' => 'Không được bỏ trống tên người nhận!'
        ];
    }
}
