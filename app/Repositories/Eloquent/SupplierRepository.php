<?php

namespace App\Repositories\Eloquent;

use App\Models\Supplier;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function getAll(): Collection
    {
        return Supplier::orderBy('nama', 'asc')->get();
    }

    public function findById(int $id): ?Supplier
    {
        return Supplier::find($id);
    }

    public function create(array $data): Supplier
    {
        return Supplier::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $supplier = $this->findById($id);
        if (! $supplier) {
            return false;
        }

        return $supplier->update($data);
    }

    public function delete(int $id): bool
    {
        $supplier = $this->findById($id);
        if (! $supplier) {
            return false;
        }

        return $supplier->delete();
    }
}
