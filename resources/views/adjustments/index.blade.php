@extends('layouts.app')

@section('title', 'Stock Opname & Penyesuaian Stok')

@section('content')
<div class="space-y-6" x-data="{ search: '' }">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white">Stock Opname & Penyesuaian Stok</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Riwayat penyesuaian fisik stok gudang (selisih opname, barang rusak, hilang, atau expired).</p>
        </div>
        <div>
            <a href="{{ route('adjustments.create') }}" class="inline-flex items-center px-5 py-3 bg-[#ff8000] hover:bg-[#e67300] text-white rounded-2xl text-xs font-extrabold shadow-lg shadow-[#ff8000]/30 transition">
                <i class="fa-solid fa-calculator mr-2 text-sm"></i>
                <span>+ Buat Penyesuaian Stok Baru</span>
            </a>
        </div>
    </div>

    <!-- Search Toolbar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between">
        <div class="relative w-full sm:w-80">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </div>
            <input type="text" x-model="search" placeholder="Cari kode TRX, barang, alasan..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-xs text-slate-900 dark:text-slate-200 focus:outline-none focus:border-[#ff8000]">
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 uppercase tracking-wider font-extrabold border-b border-slate-100 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Kode TRX</th>
                        <th class="px-6 py-4">Tanggal & Waktu</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4 text-center">Sebelum</th>
                        <th class="px-6 py-4 text-center">Sesudah</th>
                        <th class="px-6 py-4 text-center">Selisih</th>
                        <th class="px-6 py-4">Alasan</th>
                        <th class="px-6 py-4">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($adjustments as $index => $adj)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition"
                        x-show="search === '' || '{{ strtolower($adj->kode_penyesuaian) }}'.includes(search.toLowerCase()) || '{{ strtolower($adj->item->nama_barang ?? '') }}'.includes(search.toLowerCase()) || '{{ strtolower($adj->alasan) }}'.includes(search.toLowerCase())">
                        <td class="px-6 py-4 font-bold text-slate-400">{{ $adjustments->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ $adj->kode_penyesuaian }}</td>
                        <td class="px-6 py-4 font-mono text-slate-600 dark:text-slate-300">{{ $adj->tanggal->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">
                            {{ $adj->item->nama_barang ?? '-' }}
                            <span class="block text-[10px] text-slate-400 font-normal font-mono">{{ $adj->item->kode_barang ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-500">{{ $adj->stok_sebelumnya }}</td>
                        <td class="px-6 py-4 text-center font-bold text-slate-900 dark:text-white">{{ $adj->stok_sesudah }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($adj->tipe === 'tambah')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                +{{ $adj->selisih }}
                            </span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-500/10 text-rose-600 dark:text-rose-400">
                                -{{ $adj->selisih }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $adj->alasan }}
                            </span>
                            @if($adj->keterangan)
                            <span class="block text-[10px] text-slate-400 italic mt-0.5">{{ $adj->keterangan }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-medium">{{ $adj->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-clipboard-check text-3xl mb-2 block"></i>
                            <span>Belum ada riwayat penyesuaian stok.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($adjustments->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
            {{ $adjustments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
