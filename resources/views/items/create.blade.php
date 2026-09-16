@extends('layouts.app')

@section('title', 'Tambah Barang Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Tambah Barang Baru</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftarkan item barang master ke dalam sistem stok.</p>
        </div>
        <a href="{{ route('items.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm">
        <form action="{{ route('items.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Kode Barang -->
                <div>
                    <label for="kode_barang" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Kode Barang *</label>
                    <input type="text" name="kode_barang" id="kode_barang" value="{{ old('kode_barang', 'BRG-' . rand(100, 999)) }}" required
                           placeholder="Contoh: BRG-001"
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-mono text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                    @error('kode_barang')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Barang -->
                <div>
                    <label for="nama_barang" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Nama Barang *</label>
                    <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang') }}" required
                           placeholder="Contoh: Laptop Asus ExpertBook B1"
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                    @error('nama_barang')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Kategori *</label>
                    <select name="category_id" id="category_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->nama }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Supplier -->
                <div>
                    <label for="supplier_id" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Supplier Pemasok *</label>
                    <select name="supplier_id" id="supplier_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->nama }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Satuan -->
                <div>
                    <label for="satuan" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Satuan Barang *</label>
                    <input type="text" name="satuan" id="satuan" value="{{ old('satuan', 'Pcs') }}" required
                           placeholder="Pcs, Unit, Box, Rim, Kg, Botol, Roll"
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                    @error('satuan')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Minimum Stok -->
                <div>
                    <label for="minimum_stok" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Minimum Stok Alert *</label>
                    <input type="number" name="minimum_stok" id="minimum_stok" value="{{ old('minimum_stok', 5) }}" min="0" required
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                    @error('minimum_stok')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Stok Awal -->
            <div>
                <label for="stok" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Stok Awal Saat Ini *</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok', 0) }}" min="0" required
                       class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">
                @error('stok')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan -->
            <div>
                <label for="keterangan" class="block text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Keterangan / Spesifikasi (Opsional)</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                          placeholder="Catatan tambahan mengenai spesifikasi barang ataulokasi penyimpanan..."
                          class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-800 dark:text-white focus:outline-none focus:border-cyan-500">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 flex justify-end space-x-3">
                <a href="{{ route('items.index') }}" class="px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-cyan-600/30 transition">
                    Simpan Barang Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
