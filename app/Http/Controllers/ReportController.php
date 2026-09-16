<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService) {}

    public function incoming(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $month = $request->input('month');
        $year = $request->input('year');

        $reports = $this->reportService->getIncomingReport($startDate, $endDate, $month, $year);

        return view('reports.incoming', compact('reports', 'startDate', 'endDate', 'month', 'year'));
    }

    public function outgoing(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $month = $request->input('month');
        $year = $request->input('year');

        $reports = $this->reportService->getOutgoingReport($startDate, $endDate, $month, $year);

        return view('reports.outgoing', compact('reports', 'startDate', 'endDate', 'month', 'year'));
    }

    public function stock()
    {
        $items = $this->reportService->getStockReport();

        return view('reports.stock', compact('items'));
    }

    public function exportPdf(Request $request)
    {
        $type = $request->input('type', 'stock'); // incoming, outgoing, stock
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $month = $request->input('month');
        $year = $request->input('year');

        if ($type === 'incoming') {
            $data = $this->reportService->getIncomingReport($startDate, $endDate, $month, $year);
            $title = 'Laporan Rekapitulasi Barang Masuk';
        } elseif ($type === 'outgoing') {
            $data = $this->reportService->getOutgoingReport($startDate, $endDate, $month, $year);
            $title = 'Laporan Rekapitulasi Barang Keluar';
        } else {
            $data = $this->reportService->getStockReport();
            $title = 'Laporan Stok Barang Inventaris';
        }

        return view('reports.print_pdf', compact('data', 'type', 'title', 'startDate', 'endDate', 'month', 'year'));
    }

    public function exportExcel(Request $request)
    {
        $type = $request->input('type', 'stock');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $month = $request->input('month');
        $year = $request->input('year');

        $filename = "laporan_{$type}_".date('Ymd_His').'.csv';

        $headers = [
            'Content-type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($type, $startDate, $endDate, $month, $year) {
            $file = fopen('php://output', 'w');
            // Write BOM for UTF-8 Excel support
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            if ($type === 'incoming') {
                fputcsv($file, ['No', 'Kode TRX', 'Tanggal', 'Supplier', 'Barcode / Kode Barang', 'Nama Barang', 'Jumlah Masuk', 'Satuan', 'Staf Pemroses', 'Catatan']);
                $data = $this->reportService->getIncomingReport($startDate, $endDate, $month, $year);
                $no = 1;
                foreach ($data as $tx) {
                    foreach ($tx->details as $detail) {
                        fputcsv($file, [
                            $no++,
                            $tx->kode_transaksi,
                            $tx->tanggal->format('d/m/Y'),
                            $tx->supplier->nama ?? '-',
                            $detail->item->barcode ?? $detail->item->kode_barang ?? '-',
                            $detail->item->nama_barang ?? '-',
                            $detail->jumlah,
                            $detail->item->satuan ?? '-',
                            $tx->user->name ?? '-',
                            $tx->keterangan ?? '-',
                        ]);
                    }
                }
            } elseif ($type === 'outgoing') {
                fputcsv($file, ['No', 'Kode TRX', 'Tanggal', 'Tujuan', 'Barcode / Kode Barang', 'Nama Barang', 'Jumlah Keluar', 'Satuan', 'Staf Pemroses', 'Catatan']);
                $data = $this->reportService->getOutgoingReport($startDate, $endDate, $month, $year);
                $no = 1;
                foreach ($data as $tx) {
                    foreach ($tx->details as $detail) {
                        fputcsv($file, [
                            $no++,
                            $tx->kode_transaksi,
                            $tx->tanggal->format('d/m/Y'),
                            $tx->tujuan,
                            $detail->item->barcode ?? $detail->item->kode_barang ?? '-',
                            $detail->item->nama_barang ?? '-',
                            $detail->jumlah,
                            $detail->item->satuan ?? '-',
                            $tx->user->name ?? '-',
                            $tx->keterangan ?? '-',
                        ]);
                    }
                }
            } else {
                fputcsv($file, ['No', 'Barcode', 'Kode Barang', 'Nama Barang', 'Kategori', 'Supplier', 'Stok Saat Ini', 'Minimum Stok', 'Satuan', 'Status Stok', 'Keterangan']);
                $data = $this->reportService->getStockReport();
                foreach ($data as $index => $row) {
                    fputcsv($file, [
                        $index + 1,
                        $row->barcode ?? '-',
                        $row->kode_barang,
                        $row->nama_barang,
                        $row->category->nama ?? '-',
                        $row->supplier->nama ?? '-',
                        $row->stok,
                        $row->minimum_stok,
                        $row->satuan,
                        $row->stok <= $row->minimum_stok ? 'STOK HAMPIR HABIS' : 'NORMAL',
                        $row->keterangan ?? '-',
                    ]);
                }
            }

            fclose($file);
        };

        return Response::streamDownload($callback, $filename, $headers);
    }
}
