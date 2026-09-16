<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryService
{
    public function __construct(protected CategoryRepositoryInterface $categoryRepo) {}

    public function getAllCategories()
    {
        return $this->categoryRepo->getAll();
    }

    public function getCategoryById(int $id)
    {
        return $this->categoryRepo->findById($id);
    }

    public function createCategory(array $data)
    {
        return $this->categoryRepo->create($data);
    }

    public function updateCategory(int $id, array $data)
    {
        return $this->categoryRepo->update($id, $data);
    }

    public function deleteCategory(int $id)
    {
        return $this->categoryRepo->delete($id);
    }
}
