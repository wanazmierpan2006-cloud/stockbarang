<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $this->categoryService->createCategory($request->validated());

        return redirect()->route('categories.index')->with('success', 'Kategori barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = $this->categoryService->getCategoryById($id);
        if (! $category) {
            return redirect()->route('categories.index')->with('error', 'Kategori tidak ditemukan.');
        }

        return view('categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, $id)
    {
        $this->categoryService->updateCategory($id, $request->validated());

        return redirect()->route('categories.index')->with('success', 'Kategori barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $this->categoryService->deleteCategory($id);
        } catch (QueryException $e) {
            report($e);

            return redirect()->route('categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh barang. Hapus atau pindahkan barangnya terlebih dahulu.');
        }

        return redirect()->route('categories.index')->with('success', 'Kategori barang berhasil dihapus.');
    }
}
