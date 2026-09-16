<?php

namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\Contracts\ItemRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ItemRepository implements ItemRepositoryInterface
{
    public function getAll(): Collection
    {
        return Item::with(['category', 'supplier'])->orderBy('nama_barang', 'asc')->get();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Item::with(['category', 'supplier'])
            ->orderBy('nama_barang', 'asc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id): ?Item
    {
        return Item::with([
            'category',
            'supplier',
            'incomingDetails.transaction.supplier',
            'incomingDetails.transaction.user',
            'outgoingDetails.transaction.user',
        ])->find($id);
    }

    public function findByKode(string $kode): ?Item
    {
        return Item::where('kode_barang', $kode)->first();
    }

    public function create(array $data): Item
    {
        return Item::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $item = Item::find($id);
        if (! $item) {
            return false;
        }

        return $item->update($data);
    }

    public function delete(int $id): bool
    {
        $item = Item::find($id);
        if (! $item) {
            return false;
        }

        return $item->delete();
    }

    public function getLowStockItems(): Collection
    {
        return Item::with(['category', 'supplier'])
            ->whereColumn('stok', '<=', 'minimum_stok')
            ->orderBy('stok', 'asc')
            ->get();
    }

    public function getLatestItems(int $limit = 5): Collection
    {
        return Item::with(['category', 'supplier'])
            ->latest()
            ->take($limit)
            ->get();
    }

    public function updateStock(int $id, int $newStock): bool
    {
        return Item::whereKey($id)->update(['stok' => $newStock]) > 0;
    }

    public function lockById(int $id): ?Item
    {
        return Item::whereKey($id)->lockForUpdate()->first();
    }

    public function incrementStock(int $id, int $qty): bool
    {
        return Item::whereKey($id)->increment('stok', $qty) > 0;
    }

    public function decrementStock(int $id, int $qty): bool
    {
        return Item::whereKey($id)->decrement('stok', $qty) > 0;
    }
}
