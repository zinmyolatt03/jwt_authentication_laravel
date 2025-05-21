<?php

namespace App\Http\Requests\Api;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    use HttpResponse;

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
            'name' => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users,email",
            "password" => "required|string|max:12"
        ];
    }

    public function failedValidation(Validator $validator){

        throw new HttpResponseException( $this->fail('fail', 400, 'validation error', $validator->errors()));
    
    }
}
