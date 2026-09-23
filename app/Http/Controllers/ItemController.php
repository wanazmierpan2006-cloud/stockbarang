<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Services\CategoryService;
use App\Services\ItemService;
use App\Services\SupplierService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct(
        protected ItemService $itemService,
        protected CategoryService $categoryService,
        protected SupplierService $supplierService
    ) {}

    public function index()
    {
        $items = $this->itemService->getItemsPaginated(15);

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        $suppliers = $this->supplierService->getAllSuppliers();

        return view('items.create', compact('categories', 'suppliers'));
    }

    public function store(ItemRequest $request)
    {
        $this->itemService->createItem($request->validated());

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = $this->itemService->getItemById($id);
        if (! $item) {
            return redirect()->route('items.index')->with('error', 'Barang tidak ditemukan.');
        }

        return view('items.show', compact('item'));
    }

    public function edit($id)
    {
        $item = $this->itemService->getItemById($id);
        if (! $item) {
            return redirect()->route('items.index')->with('error', 'Barang tidak ditemukan.');
        }
        $categories = $this->categoryService->getAllCategories();
        $suppliers = $this->supplierService->getAllSuppliers();

        return view('items.edit', compact('item', 'categories', 'suppliers'));
    }

    public function update(ItemRequest $request, $id)
    {
        $this->itemService->updateItem($id, $request->validated());

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            if (! $this->itemService->deleteItem($id)) {
                return redirect()->route('items.index')->with('error', 'Barang tidak ditemukan.');
            }
        } catch (QueryException $e) {
            report($e);

            return redirect()->route('items.index')->with('error', 'Barang gagal dihapus. Silakan coba lagi.');
        }

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus dari daftar aktif. Riwayat transaksi tetap tersimpan.');
    }

    public function printBarcodes(Request $request)
    {
        $selectedIds = $request->input('item_ids');
        if ($selectedIds && is_array($selectedIds)) {
            $items = Item::whereIn('id', $selectedIds)->get();
        } else {
            $items = $this->itemService->getAllItems();
        }

        return view('items.print-barcodes', compact('items'));
    }
}
