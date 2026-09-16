<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use App\Repositories\Contracts\IncomingTransactionRepositoryInterface;
use App\Repositories\Contracts\ItemRepositoryInterface;
use App\Repositories\Contracts\OutgoingTransactionRepositoryInterface;

class ReportService
{
    public function __construct(
        protected ItemRepositoryInterface $itemRepo,
        protected IncomingTransactionRepositoryInterface $incomingRepo,
        protected OutgoingTransactionRepositoryInterface $outgoingRepo
    ) {}

    public function getDashboardStats(): array
    {
        $totalItems = Item::count();
        $totalSuppliers = Supplier::count();
        $totalUsers = User::count();

        $incomingToday = $this->incomingRepo->getTodayCount();
        $outgoingToday = $this->outgoingRepo->getTodayCount();

        $lowStockItems = $this->itemRepo->getLowStockItems();
        $latestItems = $this->itemRepo->getLatestItems(5);

        $incomingChart = $this->incomingRepo->getMonthlyStats();
        $outgoingChart = $this->outgoingRepo->getMonthlyStats();

        $categories = Category::withCount('items')->orderBy('nama')->get();
        $categoryChart = [
            'labels' => $categories->pluck('nama')->toArray(),
            'data' => $categories->pluck('items_count')->toArray(),
        ];

        return [
            'totalItems' => $totalItems,
            'totalSuppliers' => $totalSuppliers,
            'totalUsers' => $totalUsers,
            'incomingToday' => $incomingToday,
            'outgoingToday' => $outgoingToday,
            'lowStockItems' => $lowStockItems,
            'latestItems' => $latestItems,
            'incomingChart' => $incomingChart,
            'outgoingChart' => $outgoingChart,
            'categoryChart' => $categoryChart,
        ];
    }

    public function getIncomingReport(?string $startDate, ?string $endDate, ?string $month, ?string $year)
    {
        return $this->incomingRepo->getFiltered($startDate, $endDate, $month, $year);
    }

    public function getOutgoingReport(?string $startDate, ?string $endDate, ?string $month, ?string $year)
    {
        return $this->outgoingRepo->getFiltered($startDate, $endDate, $month, $year);
    }

    public function getStockReport()
    {
        return $this->itemRepo->getAll();
    }
}
