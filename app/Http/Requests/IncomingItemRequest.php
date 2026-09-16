<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomingItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isGudang());
    }

    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'item_id' => ['required', 'exists:items,id'],
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal barang masuk wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'item_id.required' => 'Barang wajib dipilih.',
            'item_id.exists' => 'Barang tidak ditemukan.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'supplier_id.exists' => 'Supplier tidak ditemukan.',
            'jumlah.required' => 'Jumlah barang masuk wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah barang masuk harus lebih dari 0.',
        ];
    }
}
