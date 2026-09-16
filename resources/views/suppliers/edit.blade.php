@extends('layouts.app')

@section('title', 'Edit Data Supplier')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Edit Data Supplier</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Perbarui informasi supplier {{ $supplier->nama }}.</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Supplier -->
            <div>
                <label for="nama" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Nama Supplier / Perusahaan *</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $supplier->nama) }}" required
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                @error('nama')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat -->
            <div>
                <label for="alamat" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Alamat Lengkap *</label>
                <textarea name="alamat" id="alamat" rows="3" required
                          class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">{{ old('alamat', $supplier->alamat) }}</textarea>
                @error('alamat')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- No HP / Telepon -->
            <div>
                <label for="telepon" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">No HP / Telepon *</label>
                <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $supplier->telepon) }}" required
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                @error('telepon')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}" required
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                @error('email')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('suppliers.index') }}" class="px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-cyan-600/30 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
