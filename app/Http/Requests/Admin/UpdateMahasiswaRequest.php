<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('mahasiswa')?->id;

        return [
            'nim'           => ['required', 'string', 'max:20', 'unique:mahasiswa,nim,'.$id],
            'nama'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:mahasiswa,email,'.$id],
            'program_studi' => ['required', 'string', 'max:100'],
            'angkatan'      => ['required', 'integer', 'min:2000', 'max:2099'],
        ];
    }
}