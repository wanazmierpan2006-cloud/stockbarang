<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAll(): Collection
    {
        return Category::orderBy('nama', 'asc')->get();
    }

    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $category = $this->findById($id);
        if (! $category) {
            return false;
        }

        return $category->update($data);
    }

    public function delete(int $id): bool
    {
        $category = $this->findById($id);
        if (! $category) {
            return false;
        }

        return $category->delete();
    }
}
