<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Services\SupplierService;
use Illuminate\Database\QueryException;

class SupplierController extends Controller
{
    public function __construct(protected SupplierService $supplierService) {}

    public function index()
    {
        $suppliers = $this->supplierService->getAllSuppliers();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(SupplierRequest $request)
    {
        $this->supplierService->createSupplier($request->validated());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $supplier = $this->supplierService->getSupplierById($id);
        if (! $supplier) {
            return redirect()->route('suppliers.index')->with('error', 'Supplier tidak ditemukan.');
        }

        return view('suppliers.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, $id)
    {
        $this->supplierService->updateSupplier($id, $request->validated());

        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $this->supplierService->deleteSupplier($id);
        } catch (QueryException $e) {
            report($e);

            return redirect()->route('suppliers.index')->with('error', 'Supplier tidak dapat dihapus karena masih terhubung dengan barang atau riwayat transaksi.');
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
