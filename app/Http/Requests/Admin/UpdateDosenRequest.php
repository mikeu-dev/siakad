<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('dosen')?->id;

        return [
            'nidn'  => ['required', 'string', 'max:20', 'unique:dosen,nidn,'.$id],
            'nama'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:dosen,email,'.$id],
        ];
    }
}