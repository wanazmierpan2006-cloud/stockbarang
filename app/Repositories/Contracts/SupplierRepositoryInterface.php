<?php

namespace App\Repositories\Contracts;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Collection;

interface SupplierRepositoryInterface
{
    public function getAll(): Collection;

    public function findById(int $id): ?Supplier;

    public function create(array $data): Supplier;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;
}
