<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\Incomes;
use App\Services\PatrimonyService;

class SummaryController
{
    protected PatrimonyService $incomesService;
    protected PatrimonyService $expensesService;

    public function __construct()
    {
        $this->incomesService = new PatrimonyService(app(Incomes::class));
        $this->expensesService = new PatrimonyService(app(Expenses::class));
    }

    public function getByDate(int $year, int $month)
    {
        $incomes = $this->incomesService->getByDate($year, $month);
        $expenses = $this->expensesService->getByDate($year, $month);

        $totalIncomes = $incomes->sum('valor');
        $totalExpenses = $expenses->sum('valor');

        $balance = $totalIncomes - $totalExpenses;

        return response()->json([
            'error' => false,
            'data' => [
                'receitas' => $totalIncomes,
                'despesas' => $totalExpenses,
                'saldo' => $balance,
                'categorias' => array_map(function ($category) use ($expenses) {
                    return $expenses->where('categoria', $category)->sum('valor');
                }, Expenses::CATEGORIES)
            ]
        ]);
    }
}
