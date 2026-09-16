@extends('layouts.app')

@section('title', 'Edit Data User')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Edit Data User</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Perbarui informasi pengguna {{ $user->name }}.</p>
        </div>
        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Nama Lengkap *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                @error('name')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Username *</label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                @error('username')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                @error('email')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Role / Hak Akses *</label>
                <select name="role" id="role" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Full Access & Kelola Master)</option>
                    <option value="gudang" {{ old('role', $user->role) === 'gudang' ? 'selected' : '' }}>Gudang (Input Barang Masuk & Keluar)</option>
                    <option value="pimpinan" {{ old('role', $user->role) === 'pimpinan' ? 'selected' : '' }}>Pimpinan (View Only & Export Laporan)</option>
                </select>
                @error('role')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" id="password"
                       placeholder="Minimal 6 karakter"
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                @error('password')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
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
