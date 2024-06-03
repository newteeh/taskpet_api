<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
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
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
        ];
    }
    public function messages()
    {
        return[
          'required' => 'Это поле обязательно для заполнения',
            'unique' => 'Аккаунт с данным email уже существует',
            'same' => 'Пароли не совпадают',
            'min' => 'Пароль должен быть не меньше 6 символов'
        ];
    }
}
