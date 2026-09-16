<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $supplierId = $this->route('supplier') ? $this->route('supplier') : null;

        return [
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'telepon' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', Rule::unique('suppliers', 'email')->ignore($supplierId)],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama supplier wajib diisi.',
            'alamat.required' => 'Alamat supplier wajib diisi.',
            'telepon.required' => 'No. HP/Telepon supplier wajib diisi.',
            'email.required' => 'Email supplier wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email supplier sudah terdaftar.',
        ];
    }
}
