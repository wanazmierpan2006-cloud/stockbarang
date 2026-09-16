@extends('layouts.app')

@section('title', 'Buat Penyesuaian Stok Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="stockAdjustmentForm()">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Form Penyesuaian Stok (Stock Opname)</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Koreksi perbedaan jumlah stok fisik gudang dengan data di sistem.</p>
        </div>
        <a href="{{ route('adjustments.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-200 rounded-2xl text-xs font-bold transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
        
        <form action="{{ route('adjustments.store') }}" method="POST">
            @csrf

            <div class="space-y-5">
                <!-- Pilih Barang -->
                <div>
                    <label for="item_id" class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Pilih Barang *</label>
                    <select name="item_id" id="item_id" x-model="selectedItemId" @change="onItemChange()" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-[#ff8000]">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                        <option value="{{ $item->id }}" data-stok="{{ $item->stok }}" data-satuan="{{ $item->satuan }}">{{ $item->kode_barang }} - {{ $item->nama_barang }} (Stok Sistem: {{ $item->stok }} {{ $item->satuan }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Info Current Stock Card -->
                <template x-if="selectedItemId">
                    <div class="p-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 rounded-2xl flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center font-bold text-lg">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>
                            <div>
                                <p class="text-slate-500 dark:text-slate-400">Stok Saat Ini di Sistem:</p>
                                <p class="font-extrabold text-base text-slate-900 dark:text-white" x-text="currentStock + ' ' + currentSatuan"></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-slate-500 dark:text-slate-400">Selisih Fisik:</p>
                            <p class="font-extrabold text-sm" :class="difference >= 0 ? 'text-emerald-600' : 'text-rose-600'" x-text="(difference >= 0 ? '+' : '') + difference + ' ' + currentSatuan"></p>
                        </div>
                    </div>
                </template>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Stok Hasil Penyesuaian Fisik -->
                    <div>
                        <label for="stok_sesudah" class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Stok Fisik Sebenarnya *</label>
                        <input type="number" name="stok_sesudah" id="stok_sesudah" x-model.number="stokSesudah" min="0" required placeholder="Contoh: 15"
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-[#ff8000]">
                    </div>

                    <!-- Tanggal & Waktu -->
                    <div>
                        <label for="tanggal" class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Tanggal & Waktu *</label>
                        <input type="datetime-local" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d\TH:i')) }}" required
                               class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-[#ff8000]">
                    </div>
                </div>

                <!-- Alasan -->
                <div>
                    <label for="alasan" class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Alasan Penyesuaian *</label>
                    <select name="alasan" id="alasan" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-[#ff8000]">
                        <option value="Selisih Stock Opname">Selisih Stock Opname Periodic</option>
                        <option value="Barang Rusak">Barang Rusak / Cacat Pabrik</option>
                        <option value="Barang Hilang">Barang Hilang / Unaccounted</option>
                        <option value="Barang Expired">Barang Expired / Kadaluwarsa</option>
                        <option value="Koreksi Input Data">Koreksi Salah Input Data</option>
                    </select>
                </div>

                <!-- Keterangan Detail -->
                <div>
                    <label for="keterangan" class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 dark:text-slate-300 mb-2">Keterangan Tambahan (Opsional)</label>
                    <input type="text" name="keterangan" id="keterangan" placeholder="Contoh: Ditemukan pecah di rak A3 saat pembersihan"
                           class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-sm text-slate-900 dark:text-white focus:outline-none focus:border-[#ff8000]">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="submit" class="px-8 py-3.5 bg-[#ff8000] hover:bg-[#e67300] text-white rounded-2xl text-xs font-extrabold shadow-lg shadow-[#ff8000]/30 transition flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>SIMPAN PENYESUAIAN STOK</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function stockAdjustmentForm() {
    return {
        selectedItemId: '',
        currentStock: 0,
        currentSatuan: '',
        stokSesudah: 0,

        onItemChange() {
            const selectEl = document.getElementById('item_id');
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (selectedOpt && selectedOpt.dataset.stok !== undefined) {
                this.currentStock = parseInt(selectedOpt.dataset.stok);
                this.currentSatuan = selectedOpt.dataset.satuan;
                this.stokSesudah = this.currentStock;
            } else {
                this.currentStock = 0;
                this.currentSatuan = '';
                this.stokSesudah = 0;
            }
        },

        get difference() {
            return (this.stokSesudah || 0) - (this.currentStock || 0);
        }
    }
}
</script>
@endpush
@endsection
