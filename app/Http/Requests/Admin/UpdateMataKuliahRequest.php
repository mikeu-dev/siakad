<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMataKuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('mata_kuliah')?->id;

        return [
            'kode'     => ['required', 'string', 'max:20', 'unique:mata_kuliah,kode,'.$id],
            'nama'     => ['required', 'string', 'max:255'],
            'sks'      => ['required', 'integer', 'min:1', 'max:6'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
        ];
    }
}