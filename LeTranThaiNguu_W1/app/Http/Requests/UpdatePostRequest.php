<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'required'
            
        ];
    }
    public function messages(): array
    {
        return[
            
            'title.required' => 'Không được bỏ trống tên bài viết!'
        ];
    }
}
