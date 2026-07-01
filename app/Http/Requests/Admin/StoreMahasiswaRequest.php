<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'nim'           => ['required', 'string', 'max:20', 'unique:mahasiswa,nim'],
            'nama'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:mahasiswa,email', 'unique:users,email'],
            'program_studi' => ['required', 'string', 'max:100'],
            'angkatan'      => ['required', 'integer', 'min:2000', 'max:2099'],
            'password'      => ['required', 'string', 'min:8'],
        ];
    }
}