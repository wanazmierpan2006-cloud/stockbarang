<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutgoingItemRequest extends FormRequest
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
            'tujuan' => ['required', 'string', 'max:255'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.required' => 'Tanggal barang keluar wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'item_id.required' => 'Barang wajib dipilih.',
            'item_id.exists' => 'Barang tidak ditemukan.',
            'tujuan.required' => 'Tujuan pengiriman/penggunaan wajib diisi.',
            'jumlah.required' => 'Jumlah barang keluar wajib diisi.',
            'jumlah.integer' => 'Jumlah harus berupa angka bulat.',
            'jumlah.min' => 'Jumlah barang keluar harus lebih dari 0.',
        ];
    }
}
