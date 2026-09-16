@extends('layouts.app')

@section('title', 'Dashboard Monitoring')

@section('content')
<div class="space-y-6">

    <!-- Top Hero Banner (White with #ff8000 accent) -->
    <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-900 text-slate-800 dark:text-white border-2 border-[#ff8000]/20 shadow-sm relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-10 -translate-y-10 w-72 h-72 bg-[#ff8000]/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#ff8000]/10 text-[#ff8000] text-xs font-extrabold border border-[#ff8000]/20 mb-3">
                    <i class="fa-solid fa-clock"></i>
                    <span>{{ date('l, d F Y') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">Selamat Datang, {{ auth()->user()->name }}!</h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Anda login sebagai <span class="font-extrabold text-[#ff8000] uppercase tracking-wider">{{ auth()->user()->role }}</span> di Sistem Informasi Stok Barang PT STH Network.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                @if(auth()->user()->isAdmin() || auth()->user()->isGudang())
                    <a href="{{ route('incoming.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-xs font-extrabold shadow-md shadow-emerald-600/20 transition flex items-center space-x-2">
                        <i class="fa-solid fa-plus"></i>
                        <span>+ Barang Masuk</span>
                    </a>
                    <a href="{{ route('outgoing.create') }}" class="px-4 py-2.5 bg-[#ff8000] hover:bg-[#e67300] text-white rounded-2xl text-xs font-extrabold shadow-md shadow-[#ff8000]/20 transition flex items-center space-x-2">
                        <i class="fa-solid fa-minus"></i>
                        <span>- Barang Keluar</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Barang -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Total Barang</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($totalItems) }}</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Item Terdaftar</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#ff8000]/10 text-[#ff8000] flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- Total Supplier -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Supplier</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($totalSuppliers) }}</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Mitra Pemasok</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-truck-field"></i>
            </div>
        </div>

        <!-- Barang Masuk Hari Ini -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Masuk Hari Ini</p>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">+{{ number_format($incomingToday) }}</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Jumlah Item</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-download"></i>
            </div>
        </div>

        <!-- Barang Keluar Hari Ini -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Keluar Hari Ini</p>
                <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">-{{ number_format($outgoingToday) }}</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Jumlah Item</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-arrow-up-from-bracket"></i>
            </div>
        </div>

        <!-- Total User -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center justify-between transition-transform hover:-translate-y-1">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Total User</p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($totalUsers) }}</h3>
                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Pengguna Pengawas</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-[#ff8000]/10 text-[#ff8000] flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert Banner (If Any) -->
    @if($lowStockItems->count() > 0)
    <div class="bg-orange-50 dark:bg-slate-900 border border-[#ff8000]/30 rounded-3xl p-5 text-[#ff8000] flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-2xl bg-[#ff8000]/20 text-[#ff8000] flex items-center justify-center text-lg font-bold">
                <i class="fa-solid fa-triangle-exclamation animate-bounce"></i>
            </div>
            <div>
                <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">Peringatan: terdapat {{ $lowStockItems->count() }} barang dengan stok hampir habis!</h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">Segera lakukan pengadaan atau barang masuk untuk mencegah kehabisan stok inventaris STH Network.</p>
            </div>
        </div>
        <a href="{{ route('reports.stock') }}" class="px-4 py-2 bg-[#ff8000] hover:bg-[#e67300] text-white text-xs font-extrabold rounded-2xl transition whitespace-nowrap shadow-sm shadow-[#ff8000]/20">
            Lihat Detail Stok <i class="fa-solid fa-arrow-right ml-1"></i>
        </a>
    </div>
    @endif

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Chart Barang Masuk -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Grafik Barang Masuk</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tren penerimaan barang 6 bulan terakhir</p>
                </div>
                <span class="p-2.5 rounded-2xl bg-[#ff8000]/10 text-[#ff8000] text-sm"><i class="fa-solid fa-chart-column"></i></span>
            </div>
            <div class="h-64">
                <canvas id="incomingChartCanvas"></canvas>
            </div>
        </div>

        <!-- Chart Barang Keluar -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Grafik Barang Keluar</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tren pengeluaran barang 6 bulan terakhir</p>
                </div>
                <span class="p-2.5 rounded-2xl bg-rose-500/10 text-rose-500 text-sm"><i class="fa-solid fa-chart-line"></i></span>
            </div>
            <div class="h-64">
                <canvas id="outgoingChartCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Tables Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Table: 5 Barang Terbaru -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">5 Barang Terbaru</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Penambahan barang master terakhir</p>
                </div>
                <a href="{{ route('items.index') }}" class="text-xs font-bold text-[#ff8000] hover:underline">
                    Semua Barang &rarr;
                </a>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Nama Barang</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3 text-right">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @forelse($latestItems as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                            <td class="px-5 py-3 font-mono font-bold text-[#ff8000]">{{ $item->kode_barang }}</td>
                            <td class="px-5 py-3 font-bold text-slate-900 dark:text-white">{{ $item->nama_barang }}</td>
                            <td class="px-5 py-3">{{ $item->category->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-right font-bold">
                                <span class="px-2.5 py-1 rounded-full text-[11px] {{ $item->isLowStock() ? 'bg-rose-500/20 text-rose-600 dark:text-rose-400' : 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200' }}">
                                    {{ $item->stok }} {{ $item->satuan }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada data barang terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table: Low Stock Warning -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base">Stok Hampir Habis</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Item yang perlu penambahan persediaan</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-[#ff8000]/10 text-[#ff8000]">
                    {{ $lowStockItems->count() }} Item
                </span>
            </div>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <tr>
                            <th class="px-5 py-3">Nama Barang</th>
                            <th class="px-5 py-3">Min Stok</th>
                            <th class="px-5 py-3 text-right">Stok Sisa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @forelse($lowStockItems as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                            <td class="px-5 py-3 font-bold text-slate-900 dark:text-white">
                                {{ $item->nama_barang }}
                                <p class="text-[10px] text-slate-400 font-mono">{{ $item->kode_barang }}</p>
                            </td>
                            <td class="px-5 py-3 text-slate-500">{{ $item->minimum_stok }} {{ $item->satuan }}</td>
                            <td class="px-5 py-3 text-right font-bold text-rose-600 dark:text-rose-400">
                                <span class="px-2.5 py-1 rounded-full bg-rose-500/10 text-rose-600 dark:text-rose-400 font-extrabold border border-rose-500/20">
                                    {{ $item->stok }} {{ $item->satuan }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-circle-check text-emerald-500 text-2xl mb-1 block"></i>
                                Semua stok barang dalam kondisi aman.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Barang Masuk (#ff8000 Theme)
        const incomingCtx = document.getElementById('incomingChartCanvas').getContext('2d');
        new Chart(incomingCtx, {
            type: 'bar',
            data: {
                labels: @json($incomingChart['labels']),
                datasets: [{
                    label: 'Jumlah Barang Masuk',
                    data: @json($incomingChart['data']),
                    backgroundColor: '#ff8000',
                    borderColor: '#e67300',
                    borderWidth: 1,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(156, 163, 175, 0.1)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Chart Barang Keluar
        const outgoingCtx = document.getElementById('outgoingChartCanvas').getContext('2d');
        new Chart(outgoingCtx, {
            type: 'line',
            data: {
                labels: @json($outgoingChart['labels']),
                datasets: [{
                    label: 'Jumlah Barang Keluar',
                    data: @json($outgoingChart['data']),
                    backgroundColor: 'rgba(244, 63, 94, 0.12)',
                    borderColor: '#f43f5e',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f43f5e',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(156, 163, 175, 0.1)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endpush
@endsection
