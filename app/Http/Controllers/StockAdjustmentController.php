<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Services\InventoryService;
use App\Services\ItemService;
use Exception;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
        protected ItemService $itemService
    ) {}

    public function index()
    {
        $adjustments = StockAdjustment::with(['item', 'user'])->latest()->paginate(20);

        return view('adjustments.index', compact('adjustments'));
    }

    public function create()
    {
        $items = $this->itemService->getAllItems();

        return view('adjustments.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'stok_sesudah' => ['required', 'integer', 'min:0'],
            'alasan' => ['required', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
        ], [
            'item_id.required' => 'Barang wajib dipilih.',
            'stok_sesudah.required' => 'Stok hasil penyesuaian fisik wajib diisi.',
            'alasan.required' => 'Alasan penyesuaian wajib dipilih / diisi.',
            'tanggal.required' => 'Tanggal penyesuaian wajib diisi.',
        ]);

        try {
            $this->inventoryService->processStockAdjustment($request->all(), auth()->id());

            return redirect()->route('adjustments.index')->with('success', 'Penyesuaian stok (Stock Opname) berhasil disimpan.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
