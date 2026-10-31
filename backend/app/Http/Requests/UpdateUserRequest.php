<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', Password::min(8)->letters()->numbers()],
            'rol' => ['sometimes', 'required', Rule::in(User::ROLES)],
            'nivel_educativo' => ['nullable', Rule::in(User::NIVELES)],
            'curso' => ['nullable', 'string', 'max:20'],
        ];
    }
}
