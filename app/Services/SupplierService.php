<?php

namespace App\Services;

use App\Repositories\Contracts\SupplierRepositoryInterface;

class SupplierService
{
    public function __construct(protected SupplierRepositoryInterface $supplierRepo) {}

    public function getAllSuppliers()
    {
        return $this->supplierRepo->getAll();
    }

    public function getSupplierById(int $id)
    {
        return $this->supplierRepo->findById($id);
    }

    public function createSupplier(array $data)
    {
        return $this->supplierRepo->create($data);
    }

    public function updateSupplier(int $id, array $data)
    {
        return $this->supplierRepo->update($id, $data);
    }

    public function deleteSupplier(int $id)
    {
        return $this->supplierRepo->delete($id);
    }
}
