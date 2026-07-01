<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'nidn'     => ['required', 'string', 'max:20', 'unique:dosen,nidn'],
            'nama'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:dosen,email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}