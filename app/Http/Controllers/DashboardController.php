<?php

namespace App\Http\Controllers;

use App\Services\ReportService;

class DashboardController extends Controller
{
    public function __construct(protected ReportService $reportService) {}

    public function index()
    {
        $stats = $this->reportService->getDashboardStats();

        return view('dashboard.index', $stats);
    }
}
