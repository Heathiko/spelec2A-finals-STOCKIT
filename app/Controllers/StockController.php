<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Stock;
use Core\Controller;
use Core\Contracts\ReportableInterface;
use Core\Http\Request;
use Core\Http\Response;

final class StockController extends Controller{
    public function __construct(private readonly ReportableInterface $stockReport) {
    }

    public function index(Request $request): Response {
        $rows = $this->stockReport->generateReport();
        $totalUnits = array_sum(array_column($rows, 'quantity'));
        $totalValue = array_sum(array_map(
            static fn (array $row): float => (float) $row['inventory_value'],
            $rows,
        ));

        return $this->view('stock/index', [
            'title' => 'Stock Report',
            'rows' => $rows,
            'totalUnits' => $totalUnits,
            'totalValue' => $totalValue,
        ]);
    }
}
