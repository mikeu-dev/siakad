<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeuanganRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id' => ['required', 'exists:mahasiswa,id'],
            'keterangan'   => ['required', 'string', 'max:255'],
            'nominal'      => ['required', 'numeric', 'min:0'],
            'status'       => ['required', 'in:belum_bayar,sudah_bayar'],
        ];
    }
}